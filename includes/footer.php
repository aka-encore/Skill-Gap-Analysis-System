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
                &copy; 2026 <strong>SkillBridge</strong> – Skill Gap Analysis & LMS. All rights reserved.
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-center">
                <a href="<?= BASE_URL ?>privacy-policy.php" class="footer-saas-link"><i class="bi bi-shield-lock me-1"></i> Privacy Policy</a>
                <span class="text-muted opacity-50">&bull;</span>
                <a href="<?= BASE_URL ?>terms-of-service.php" class="footer-saas-link"><i class="bi bi-file-text me-1"></i> Terms & Conditions</a>
                <span class="text-muted opacity-50">&bull;</span>
                <a href="mailto:skill.profile.project1@gmail.com" class="footer-saas-link"><i class="bi bi-envelope me-1"></i> Support</a>
            </div>
        </footer>
    </div> <!-- /.main-wrapper -->
</div> <!-- /.app-layout -->

<!-- Global Announcement Details Modal -->
<div class="modal fade" id="globalAnnouncementModal" tabindex="-1" aria-labelledby="globalAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <span class="badge bg-danger rounded-pill px-3 py-1.5 fw-semibold small" id="announcementModalPriority" style="display: none;">Important</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 px-md-5 pt-3 pb-4">
                <h3 class="fw-bold text-dark mb-2" id="announcementModalTitle">Announcement Title</h3>
                <div class="d-flex align-items-center gap-2 mb-4 text-muted small flex-wrap" style="font-size: 12px;">
                    <span><i class="fa-solid fa-user me-1"></i> <strong id="announcementModalAuthor">Author</strong></span>
                    <span>&bull;</span>
                    <span><i class="fa-solid fa-calendar-days me-1"></i> <span id="announcementModalDate">Date</span></span>
                    <span id="announcementModalDeptSpan" style="display: none;">
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-building me-1"></i> <span id="announcementModalDept">Department</span></span>
                    </span>
                </div>
                <hr class="my-3 opacity-10">
                <div class="text-secondary lh-lg mb-4" id="announcementModalContent" style="white-space: pre-wrap; font-size: 14px;">
                    Announcement content body...
                </div>

            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4 px-md-5 justify-content-end">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart Helpers: loaded globally so PJAX pages can always call renderScoreBarChart(), etc. -->
<script src="<?= BASE_URL ?>assets/js/charts-config.js"></script>

<!-- Global App JS -->
<script src="<?= BASE_URL ?>assets/js/app.js"></script>

<!-- SkillBridge Theme Engine (must load after DOM) -->
<script src="<?= BASE_URL ?>assets/js/theme-toggle.js"></script>

</body>
</html>
