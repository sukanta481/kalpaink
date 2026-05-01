# Quill.js Rich Text Editor — Design Spec
Date: 2026-03-29

## Problem
TinyMCE CDN fails to load (API key / network issue), leaving all admin rich-text fields as plain textareas. Content saved as plain text renders as a wall of text on the frontend.

## Solution
Replace TinyMCE with Quill.js — free, no API key, reliable CDN.

## Scope
All existing `.tinymce-editor` textareas across admin:
- `admin/projects.php` — Full Case Study
- `admin/blogs.php` — Blog Content
- `admin/services.php` — Full Description
- `admin/content/faqs.php` — Answer
- `admin/content/pages.php` — Content Body

## Architecture

### 1. Load Quill assets
- **`admin/includes/header.php`**: Add `<link>` for Quill Snow CSS from CDN
- **`admin/includes/footer.php`**: Replace TinyMCE `<script>` block with Quill JS `<script>` from CDN

### 2. Editor initialization (`admin/assets/js/admin.js`)
Replace the `tinymce.init()` block (lines 52–123) with a Quill init loop inside the **existing first `DOMContentLoaded` block** (the block that opens at line 5). This is critical — the Quill form-submit sync listeners must be registered inside this block so they fire before the `FileUploadProgress` submit handlers registered later (line ~562).

For each `.tinymce-editor` textarea:
- Compute the upload URL using the same CSS-href detection already in the file:
  ```js
  var adminBase = document.querySelector('link[href*="admin.css"]');
  var uploadUrl = 'api/upload.php';
  if (adminBase) {
      var cssHref = adminBase.getAttribute('href');
      var adminPath = cssHref.substring(0, cssHref.indexOf('assets/'));
      uploadUrl = adminPath + 'api/upload.php';
  }
  ```
  This must use the **absolute CSS href** — not a hardcoded relative string — so that `admin/content/*.php` pages (one subdirectory deeper) resolve the upload path correctly.
- Hide the textarea (`display:none`)
- Insert a `<div class="quill-editor-container">` immediately before it
- Initialize `new Quill(container, { theme: 'snow', modules: { toolbar: [...] } })`
- Pre-fill Quill using **`quill.clipboard.dangerouslyPasteHTML(textarea.value)`** — this correctly handles HTML stored in the database (the browser decodes `htmlspecialchars`-encoded entities in the textarea `.value`, producing real HTML for Quill to parse)
- Attach a `submit` listener to the parent `<form>` that sets `textarea.value = quill.root.innerHTML` before the form posts

### 3. Toolbar
```
[ H1 H2 H3 | Bold Italic Underline Strike | Blockquote Code ]
[ Ordered Unordered | Link Image | Clean ]
```
**Indent/Outdent are intentionally omitted.** Quill adds `ql-indent-*` CSS classes for indentation which have no effect on the frontend unless Quill's stylesheet is loaded there. Removing these buttons avoids saving inert markup.

### 4. Image upload
Override Quill's default image handler (which converts to base64 inline) with a custom handler that POSTs to the `uploadUrl` computed above (the `admin/api/upload.php` endpoint). On success, insert the returned `location` URL as an `<img>` in the editor. The endpoint already validates MIME type, checks auth, and returns `{ location: "..." }` — fully compatible with this approach.

### 5. Form submission
Quill's `submit` listener is registered inside the main `DOMContentLoaded` block, ensuring it runs before `FileUploadProgress` handlers. On submit: `textarea.value = quill.root.innerHTML`. No changes to PHP form handlers needed.

## Files Changed
| File | Change |
|------|--------|
| `admin/includes/header.php` | Add Quill Snow CSS `<link>` |
| `admin/includes/footer.php` | Replace TinyMCE `<script>` block with Quill `<script>` |
| `admin/assets/js/admin.js` | Replace `tinymce.init()` block with Quill init |

## Files NOT Changed
- All admin PHP form files
- All frontend templates
- Database schema
- `admin/api/upload.php`

## Subdirectory note
`admin/content/faqs.php` and `admin/content/pages.php` are served from `admin/content/` (one level deeper). The upload URL detection via the absolute CSS href handles this correctly — must be verified during testing.

## Frontend rendering
No changes needed — frontend templates already do `<?php echo $p_full; ?>` (raw HTML output). Quill produces semantic HTML (`<h1>`, `<p>`, `<ul>`, `<strong>` etc.). Once content is re-saved through Quill, it renders with proper formatting.

## Existing content migration
Existing plain-text content will display in Quill as unstyled text. The admin user must open each project/blog/etc., apply formatting through the Quill editor, and re-save. No automated migration needed.
