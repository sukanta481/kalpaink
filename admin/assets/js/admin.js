/**
 * Kalpoink Admin CRM JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const mainContent = document.getElementById('mainContent');
    
    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.add('show');
            overlay.classList.add('show');
        });
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }
    
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
    
    // Initialize DataTables
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries"
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });
    }
    
    // Initialize TinyMCE
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '.tinymce-editor',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter ' +
                     'alignright alignjustify | bullist numlist outdent indent | ' +
                     'removeformat | link image | code | help',
            content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }',
            branding: false
        });
    }
    
    // Delete Confirmation
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.dataset.edited) {
                slugInput.value = generateSlug(this.value);
            }
        });
        
        slugInput.addEventListener('input', function() {
            this.dataset.edited = 'true';
        });
    }
    
    // Image Preview
    document.querySelectorAll('.image-upload').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const preview = document.getElementById(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-dismiss alerts
    setTimeout(function() {
        document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Helper function to generate slug
function generateSlug(text) {
    return text
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

// AJAX helper
async function ajaxRequest(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    if (data) {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        console.error('Ajax request failed:', error);
        return null;
    }
}

// Toast notification
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    document.body.appendChild(container);
    return container;
}

/**
 * File Upload Progress System
 * Shows upload progress for single and multiple file uploads
 */
class FileUploadProgress {
    constructor() {
        this.createProgressModal();
        this.initFileInputs();
    }

    createProgressModal() {
        // Create the progress overlay/modal
        const modal = document.createElement('div');
        modal.id = 'uploadProgressModal';
        modal.className = 'upload-progress-modal';
        modal.innerHTML = `
            <div class="upload-progress-content">
                <div class="upload-progress-header">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h5>Uploading Files...</h5>
                    <span class="upload-status-text">Please wait while your files are being uploaded</span>
                </div>
                <div class="upload-progress-body" id="uploadProgressBody">
                    <!-- Progress items will be added here -->
                </div>
                <div class="upload-progress-footer">
                    <div class="overall-progress">
                        <span class="overall-text">Overall Progress</span>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="overallProgress" style="width: 0%">0%</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        this.modal = modal;
        this.progressBody = document.getElementById('uploadProgressBody');
        this.overallProgress = document.getElementById('overallProgress');
    }

    initFileInputs() {
        // Find all forms with file inputs
        document.querySelectorAll('form').forEach(form => {
            const fileInputs = form.querySelectorAll('input[type="file"]');
            if (fileInputs.length > 0) {
                // Track which submit button was clicked
                form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        form._clickedSubmit = btn;
                    });
                });
                form.addEventListener('submit', (e) => this.handleFormSubmit(e, form, fileInputs));
            }
        });
    }

    handleFormSubmit(e, form, fileInputs) {
        // Check if any files are selected
        let hasFiles = false;
        let totalFiles = 0;
        
        fileInputs.forEach(input => {
            if (input.files && input.files.length > 0) {
                hasFiles = true;
                totalFiles += input.files.length;
            }
        });

        if (!hasFiles) return; // No files, proceed with normal submit

        // Show upload progress overlay but let the form submit normally
        // (Don't use XHR — it breaks POST-redirect-GET flow and flash messages)
        this.showProgress(fileInputs, totalFiles);
        
        // Animate progress bar to 90% over a few seconds for visual feedback
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            this.updateProgress(Math.round(progress));
        }, 300);
        
        // Store interval so it clears when page unloads
        window.addEventListener('beforeunload', () => clearInterval(interval));
        
        // Let the form submit normally (don't call e.preventDefault())
    }

    showProgress(fileInputs, totalFiles) {
        this.progressBody.innerHTML = '';
        let fileIndex = 0;

        fileInputs.forEach(input => {
            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const fileId = `file-${fileIndex}`;
                    const fileSize = this.formatFileSize(file.size);
                    const fileIcon = this.getFileIcon(file.type);
                    
                    const progressItem = document.createElement('div');
                    progressItem.className = 'upload-file-item';
                    progressItem.id = fileId;
                    progressItem.innerHTML = `
                        <div class="file-icon">
                            <i class="fas ${fileIcon}"></i>
                        </div>
                        <div class="file-details">
                            <div class="file-name">${this.truncateFileName(file.name, 30)}</div>
                            <div class="file-meta">
                                <span class="file-size">${fileSize}</span>
                                <span class="file-status" id="${fileId}-status">Waiting...</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" id="${fileId}-bar" style="width: 0%"></div>
                            </div>
                            <div class="file-percent" id="${fileId}-percent">0%</div>
                        </div>
                    `;
                    this.progressBody.appendChild(progressItem);
                    fileIndex++;
                }
            }
        });

        this.modal.classList.add('show');
        this.totalFiles = totalFiles;
        this.completedFiles = 0;
    }

    uploadWithProgress(form) {
        const formData = new FormData(form);
        
        // Include the clicked submit button's name/value (FormData doesn't include it by default)
        if (form._clickedSubmit && form._clickedSubmit.name) {
            formData.append(form._clickedSubmit.name, form._clickedSubmit.value || '');
        }
        
        const xhr = new XMLHttpRequest();

        // Track upload progress
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                this.updateProgress(percentComplete);
            }
        });

        // Handle completion
        xhr.addEventListener('load', () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                this.completeUpload(true);
                // Redirect or handle response
                setTimeout(() => {
                    // Check if response contains redirect URL or just reload
                    if (xhr.responseURL) {
                        window.location.href = xhr.responseURL;
                    } else {
                        window.location.reload();
                    }
                }, 1000);
            } else {
                this.completeUpload(false);
                showToast('Upload failed. Please try again.', 'danger');
                setTimeout(() => this.hideProgress(), 2000);
            }
        });

        xhr.addEventListener('error', () => {
            this.completeUpload(false);
            showToast('Upload failed. Please try again.', 'danger');
            setTimeout(() => this.hideProgress(), 2000);
        });

        // Open and send
        xhr.open(form.method || 'POST', form.action || window.location.href, true);
        xhr.send(formData);
    }

    updateProgress(percent) {
        // Update overall progress
        this.overallProgress.style.width = `${percent}%`;
        this.overallProgress.textContent = `${percent}%`;

        // Update individual file progress (simulate distribution)
        const fileItems = this.progressBody.querySelectorAll('.upload-file-item');
        const progressPerFile = 100 / fileItems.length;
        
        fileItems.forEach((item, index) => {
            const fileProgress = Math.min(100, Math.round((percent / 100) * ((index + 1) * progressPerFile) * (100 / progressPerFile)));
            const bar = item.querySelector('.progress-bar');
            const percentText = item.querySelector('.file-percent');
            const status = item.querySelector('.file-status');
            
            if (bar) {
                bar.style.width = `${fileProgress}%`;
                bar.classList.add('progress-bar-animated', 'progress-bar-striped');
            }
            if (percentText) {
                percentText.textContent = `${fileProgress}%`;
            }
            if (status) {
                if (fileProgress >= 100) {
                    status.textContent = 'Complete';
                    status.className = 'file-status text-success';
                    bar.classList.remove('progress-bar-animated', 'progress-bar-striped');
                    bar.classList.add('bg-success');
                } else if (fileProgress > 0) {
                    status.textContent = 'Uploading...';
                    status.className = 'file-status text-primary';
                }
            }
        });
    }

    completeUpload(success) {
        if (success) {
            this.overallProgress.style.width = '100%';
            this.overallProgress.textContent = '100%';
            this.overallProgress.classList.remove('progress-bar-animated');
            this.overallProgress.classList.add('bg-success');
            
            document.querySelector('.upload-status-text').textContent = 'Upload complete! Redirecting...';
            document.querySelector('.upload-progress-header i').className = 'fas fa-check-circle text-success';
        } else {
            this.overallProgress.classList.remove('progress-bar-animated');
            this.overallProgress.classList.add('bg-danger');
            document.querySelector('.upload-status-text').textContent = 'Upload failed!';
            document.querySelector('.upload-progress-header i').className = 'fas fa-times-circle text-danger';
        }
    }

    hideProgress() {
        this.modal.classList.remove('show');
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    getFileIcon(mimeType) {
        if (mimeType.startsWith('image/')) return 'fa-file-image';
        if (mimeType.startsWith('video/')) return 'fa-file-video';
        if (mimeType.startsWith('audio/')) return 'fa-file-audio';
        if (mimeType.includes('pdf')) return 'fa-file-pdf';
        if (mimeType.includes('word') || mimeType.includes('document')) return 'fa-file-word';
        if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'fa-file-excel';
        return 'fa-file';
    }

    truncateFileName(name, maxLength) {
        if (name.length <= maxLength) return name;
        const ext = name.split('.').pop();
        const nameWithoutExt = name.substring(0, name.length - ext.length - 1);
        const truncatedName = nameWithoutExt.substring(0, maxLength - ext.length - 4) + '...';
        return truncatedName + '.' + ext;
    }
}

// Initialize upload progress on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    new FileUploadProgress();
});

