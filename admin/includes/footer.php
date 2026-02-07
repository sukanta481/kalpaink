        </div>
        <!-- End Content Wrapper -->
        
        <!-- Footer -->
        <footer class="admin-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">&copy; <?php echo date('Y'); ?> Kalpoink. All rights reserved.</span>
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
    
    <!-- TinyMCE Editor -->
    <?php
    $tinymceKey = ($_SERVER['SERVER_NAME'] ?? 'localhost') === 'localhost' || ($_SERVER['SERVER_NAME'] ?? '127.0.0.1') === '127.0.0.1'
        ? '5xym3iqrlk70fxgju7h3vpelkys6gvx16nvxfe38i4n9mi8j'
        : 'c6dnzoialg8zo3sb0ymi2pq3fwr09mpe8pqy4vtef212k4gf';
    ?>
    <script src="https://cdn.tiny.cloud/1/<?php echo $tinymceKey; ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <!-- Admin JS -->
    <script src="<?php echo getAdminUrl('assets/js/admin.js'); ?>?v=<?php echo filemtime(__DIR__ . '/../assets/js/admin.js'); ?>"></script>
</body>
</html>
