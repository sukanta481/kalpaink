        </div>
        <!-- End Content Wrapper -->
        
        <!-- Footer -->
        <footer class="admin-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">&copy; <?php echo date('Y'); ?> Kalpanik. All rights reserved.</span>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="text-muted">Version 1.0.0</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- End Main Content -->
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Quill Rich Text Editor -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>

    <!-- Admin JS -->
    <script src="<?php echo getAdminUrl('assets/js/admin.js'); ?>?v=<?php echo filemtime(__DIR__ . '/../assets/js/admin.js'); ?>"></script>
</body>
</html>
