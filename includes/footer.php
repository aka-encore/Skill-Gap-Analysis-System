<?php
/**
 * SkillBridge - Reusable Footer Component
 */
?>
<?php if (is_logged_in()): ?>
        </main>
        
        <!-- App Footer -->
        <footer class="app-footer-saas d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="footer-saas-copyright text-center text-md-start">
                &copy; <?= date('Y') ?> <strong>SkillBridge</strong> – AI-Based Skill Gap Analysis & Learning Management System. All rights reserved.
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-center">
                <a href="<?= BASE_URL ?>privacy-policy.php" class="footer-saas-link"><i class="bi bi-shield-lock me-1"></i> Privacy Policy</a>
                <span class="text-muted opacity-50">&bull;</span>
                <a href="<?= BASE_URL ?>terms-of-service.php" class="footer-saas-link"><i class="bi bi-file-text me-1"></i> Terms of Service</a>
                <span class="text-muted opacity-50">&bull;</span>
                <a href="<?= BASE_URL ?>student/help.php" class="footer-saas-link"><i class="bi bi-question-circle me-1"></i> Help</a>
                <span class="text-muted opacity-50">&bull;</span>
                <a href="<?= BASE_URL ?>about.php" class="footer-saas-link"><i class="bi bi-info-circle me-1"></i> About</a>
            </div>
        </footer>
    </div> <!-- /.main-wrapper -->
</div> <!-- /.app-layout -->
<?php endif; ?>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Global App JS -->
<script src="<?= BASE_URL ?>assets/js/app.js"></script>

<!-- SkillBridge Theme Engine (must load after DOM) -->
<script src="<?= BASE_URL ?>assets/js/theme-toggle.js"></script>

</body>
</html>
