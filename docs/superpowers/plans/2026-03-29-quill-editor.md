# Quill.js Rich Text Editor Implementation Plan

> **For agentic workers:** REQUIRED: Use superpowers:subagent-driven-development (if subagents available) or superpowers:executing-plans to implement this plan. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the broken TinyMCE CDN integration with Quill.js so all admin rich-text fields (case studies, blogs, services, FAQs, pages) show a working formatting toolbar.

**Architecture:** Load Quill from CDN (no API key). For each `.tinymce-editor` textarea, hide it, inject a Quill div above it, pre-fill with existing content, and sync back to the textarea on form submit so PHP handlers are untouched.

**Tech Stack:** Quill.js 2.x (CDN), vanilla JS, PHP 8/Apache (XAMPP)

**Spec:** `docs/superpowers/specs/2026-03-29-quill-editor-design.md`

---

## Chunk 1: Load Quill assets

### Task 1: Add Quill CSS to admin header

**Files:**
- Modify: `admin/includes/header.php:31-35`

- [ ] **Step 1: Add Quill Snow CSS link after the DataTables CSS line**

In `admin/includes/header.php`, after the DataTables CSS link (line 32) and before the Custom Admin CSS link (line 35), add:

```html
    <!-- Quill Rich Text Editor CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
```

The section should look like:
```html
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Quill Rich Text Editor CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?php echo getAdminUrl('assets/css/admin.css'); ?>">
```

- [ ] **Step 2: Verify in browser**

Open `http://localhost/kalpoink/admin/projects.php?action=add` in a browser. Open DevTools → Network tab. Confirm `quill.snow.css` loads with status 200. No visual change expected yet (Quill JS not loaded).

---

### Task 2: Replace TinyMCE script with Quill JS in admin footer

**Files:**
- Modify: `admin/includes/footer.php:30-36`

- [ ] **Step 1: Replace the TinyMCE block with Quill JS**

In `admin/includes/footer.php`, replace the entire TinyMCE block (lines 30–37, including the trailing blank line):

```html
    <!-- TinyMCE Editor -->
    <?php
    $tinymceKey = ($_SERVER['SERVER_NAME'] ?? 'localhost') === 'localhost' || ($_SERVER['SERVER_NAME'] ?? '127.0.0.1') === '127.0.0.1'
        ? '5xym3iqrlk70fxgju7h3vpelkys6gvx16nvxfe38i4n9mi8j'
        : 'c6dnzoialg8zo3sb0ymi2pq3fwr09mpe8pqy4vtef212k4gf';
    ?>
    <script src="https://cdn.tiny.cloud/1/<?php echo $tinymceKey; ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
```

with:

```html
    <!-- Quill Rich Text Editor -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
```

- [ ] **Step 2: Verify in browser**

Reload `http://localhost/kalpoink/admin/projects.php?action=add`. In DevTools → Network, confirm `quill.js` loads with status 200. In DevTools → Console (on the `projects.php?action=add` tab, not a blank tab), type `typeof Quill` — it should return `"function"`, not `"undefined"`.

- [ ] **Step 3: Commit assets changes**

```bash
cd c:/xampp/htdocs/kalpoink
git add admin/includes/header.php admin/includes/footer.php
git commit -m "feat: load Quill.js editor assets, remove TinyMCE CDN"
```

---

## Chunk 2: Quill initialization in admin.js

### Task 3: Replace TinyMCE init with Quill init

**Files:**
- Modify: `admin/assets/js/admin.js:52-124`

- [ ] **Step 1: Replace the TinyMCE init block**

In `admin/assets/js/admin.js`, replace the entire TinyMCE block from line 52 through line 124:

```javascript
    // Initialize TinyMCE
    if (typeof tinymce !== 'undefined') {
        // ... (everything up to and including the closing `}` at line 124)
    }
```

with the following Quill initialization code:

```javascript
    // Initialize Quill Rich Text Editor
    if (typeof Quill !== 'undefined') {
        // Detect base URL for upload endpoint
        // Uses the admin.css href (absolute URL) so this works correctly for
        // pages in subdirectories like admin/content/*.php
        var adminBase = document.querySelector('link[href*="admin.css"]');
        var uploadUrl = 'api/upload.php';
        if (adminBase) {
            var cssHref = adminBase.getAttribute('href');
            var adminPath = cssHref.substring(0, cssHref.indexOf('assets/'));
            uploadUrl = adminPath + 'api/upload.php';
        }

        // Custom image upload handler — POSTs to existing api/upload.php endpoint
        function quillImageHandler(quillInstance) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/jpeg,image/png,image/gif,image/webp');
            input.click();
            input.addEventListener('change', function () {
                var file = this.files[0];
                if (!file) return;
                var formData = new FormData();
                formData.append('file', file);
                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.location) {
                        var range = quillInstance.getSelection(true);
                        quillInstance.insertEmbed(range ? range.index : 0, 'image', data.location);
                        quillInstance.setSelection((range ? range.index : 0) + 1);
                    } else {
                        alert(data.error || 'Image upload failed.');
                    }
                })
                .catch(function () {
                    alert('Image upload failed. Please try again.');
                });
            });
        }

        var quillToolbar = [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            ['link', 'image'],
            ['clean']
        ];

        document.querySelectorAll('.tinymce-editor').forEach(function (textarea) {
            // Hide the original textarea — it still receives the value on submit
            textarea.style.display = 'none';

            // Insert Quill container immediately before the hidden textarea
            var container = document.createElement('div');
            container.className = 'quill-editor-container';
            textarea.parentNode.insertBefore(container, textarea);

            // Init Quill with Snow theme and custom image handler
            var quill = new Quill(container, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: quillToolbar,
                        handlers: {
                            image: function () { quillImageHandler(quill); }
                        }
                    }
                }
            });

            // Pre-fill with existing content from the database
            // textarea.value is decoded by the browser (htmlspecialchars entities → real HTML)
            // dangerouslyPasteHTML correctly parses the HTML for display in Quill
            if (textarea.value.trim()) {
                quill.clipboard.dangerouslyPasteHTML(textarea.value);
            }

            // Sync Quill HTML back to textarea before form submits
            // Registered here (inside DOMContentLoaded block) so it fires
            // before FileUploadProgress submit handlers registered later
            var form = textarea.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    textarea.value = quill.root.innerHTML;
                });
            }
        });
    }
```

- [ ] **Step 2: Verify editor renders**

Open `http://localhost/kalpoink/admin/projects.php?action=add` in a browser.

Expected:
- Under "Full Case Study" label: a Quill Snow toolbar (bold, italic, H1/H2/H3, lists, link, image buttons)
- A white editor area below the toolbar, ready for input
- No plain textarea visible

If you see a plain textarea instead: open DevTools → Console and check for JS errors.

- [ ] **Step 3: Verify editor renders on edit page**

Open an existing project edit page, e.g. `http://localhost/kalpoink/admin/projects.php?action=edit&id=1`

Expected:
- Quill editor loads with existing content already populated (text visible in editor area)
- Formatted HTML content (if any) renders with proper headings/paragraphs

- [ ] **Step 4: Verify all other editor pages**

Check each page below — each should show the Quill editor in place of a plain textarea:
- `http://localhost/kalpoink/admin/blogs.php?action=add`
- `http://localhost/kalpoink/admin/services.php?action=add`
- `http://localhost/kalpoink/admin/content/faqs.php?action=add`
- `http://localhost/kalpoink/admin/content/pages.php?action=add`

For the `content/` pages (one subdirectory deeper), also check DevTools → Network to confirm the `quill.js` and `quill.snow.css` assets load without 404.

- [ ] **Step 5: Test save and frontend display**

1. Open an existing case study in edit mode
2. In the Quill editor: make "Our Approach" a **Heading 2**, wrap body text in paragraphs
3. Click Save
4. Open the case study on the frontend, e.g. `http://localhost/kalpoink/case-study/<slug>`
5. Expected: "About This Project" section shows formatted content with visible headings and paragraphs, not a wall of text

- [ ] **Step 6: Test image upload**

1. In the Quill editor, click the image button (📷) in the toolbar
2. Select a JPG or PNG file
3. Expected: image appears inline in the editor
4. Save the post, reload the frontend page
5. Expected: image renders at the correct URL (not a base64 blob)

- [ ] **Step 7: Commit**

```bash
cd c:/xampp/htdocs/kalpoink
git add admin/assets/js/admin.js
git commit -m "feat: replace TinyMCE with Quill.js rich text editor"
```

---

## Chunk 3: Editor styling

### Task 4: Style Quill editor to match admin UI

**Files:**
- Modify: `admin/assets/css/admin.css` (append at end)

- [ ] **Step 1: Add Quill container styles**

Append the following to the end of `admin/assets/css/admin.css`:

```css
/* ── Quill Rich Text Editor ─────────────────────────────────────── */
.quill-editor-container {
    background: #fff;
    border-radius: 0 0 6px 6px;
    min-height: 400px;
}

.ql-toolbar.ql-snow {
    border: 1px solid #dee2e6;
    border-radius: 6px 6px 0 0;
    background: #f8f9fa;
}

.ql-container.ql-snow {
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 6px 6px;
    font-family: Inter, sans-serif;
    font-size: 14px;
    min-height: 380px;
}

.ql-editor {
    min-height: 380px;
    padding: 16px;
    line-height: 1.7;
}

.ql-editor img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 16px 0;
}
```

- [ ] **Step 2: Verify styling**

Reload any admin editor page. Expected:
- Toolbar has a light grey background matching the Bootstrap card style
- Editor area has matching border, consistent with other form inputs
- No layout breaking or overlapping elements

- [ ] **Step 3: Commit**

```bash
cd c:/xampp/htdocs/kalpoink
git add admin/assets/css/admin.css
git commit -m "style: match Quill editor to admin UI"
```
