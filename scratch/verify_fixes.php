<?php
$app     = file_get_contents(__DIR__ . '/../assets/js/app.js');
$footer  = file_get_contents(__DIR__ . '/../includes/footer.php');
$charts  = file_get_contents(__DIR__ . '/../assets/js/charts-config.js');
$facDash = file_get_contents(__DIR__ . '/../faculty/dashboard.php');
$admDash = file_get_contents(__DIR__ . '/../admin/dashboard.php');
$admAna  = file_get_contents(__DIR__ . '/../admin/analytics.php');
$stuSg   = file_get_contents(__DIR__ . '/../student/skill-gap.php');
$facSg   = file_get_contents(__DIR__ . '/../faculty/skill-gap.php');

function check($label, $condition) {
    echo ($condition ? "  ✓ " : "  ✗ ") . $label . "\n";
}

echo "=== FINAL VERIFICATION ===\n\n";

echo "[app.js]\n";
check("'assessments.php' REMOVED from exclude list", strpos($app, "'assessments.php', 'take-assessment.php'") === false);
check("'take-assessment.php' still excluded (correct)", strpos($app, 'take-assessment.php') !== false);
check("runPageSpecificInitializer uses switch dispatch", strpos($app, 'switch (fn)') !== false);
check("runPageSpecificInitializer called in DOMContentLoaded", substr_count($app, 'runPageSpecificInitializer()') >= 2);
check("executePageScripts skips src scripts", strpos($app, 'oldScript.src && oldScript.src.length') !== false);
check("initFacultyDashboard in dispatch", strpos($app, 'initFacultyDashboard') !== false);
check("initAdminDashboard in dispatch",   strpos($app, 'initAdminDashboard') !== false);
check("initFacultySkillGap in dispatch",  strpos($app, 'initFacultySkillGap') !== false);
check("initAdminAnalytics in dispatch",   strpos($app, 'initAdminAnalytics') !== false);
check("initSkillGap in dispatch",         strpos($app, 'initSkillGap') !== false);
check("initDashboard in dispatch",        strpos($app, 'initDashboard') !== false);

echo "\n[includes/footer.php]\n";
check("charts-config.js loaded globally", strpos($footer, 'charts-config.js') !== false);

echo "\n[assets/js/charts-config.js]\n";
check("_chartReady() guard defined", strpos($charts, '_chartReady') !== false);
check("_destroyExistingChart() helper defined", strpos($charts, '_destroyExistingChart') !== false);
check("Empty-data guard in renderScoreBarChart", strpos($charts, 'No assessment data yet') !== false);
check("Empty-data guard in renderPassFailDoughnutChart", strpos($charts, 'No submission data yet') !== false);

echo "\n[faculty/dashboard.php]\n";
check("initFacultyDashboard registered", strpos($facDash, 'window.initFacultyDashboard') !== false);
check("No self-call (DOMContentLoaded pattern removed)", strpos($facDash, 'document.addEventListener(\'DOMContentLoaded\', window.initFacultyDashboard)') === false);
check("No inline charts-config.js src tag", strpos($facDash, 'charts-config.js') === false);

echo "\n[admin/dashboard.php]\n";
check("initAdminDashboard registered", strpos($admDash, 'window.initAdminDashboard') !== false);
check("No self-call removed", strpos($admDash, "addEventListener('DOMContentLoaded', window.initAdminDashboard)") === false);

echo "\n[admin/analytics.php]\n";
check("initAdminAnalytics registered", strpos($admAna, 'window.initAdminAnalytics') !== false);
check("No self-call removed", strpos($admAna, "addEventListener('DOMContentLoaded', window.initAdminAnalytics)") === false);

echo "\n[faculty/skill-gap.php]\n";
check("initFacultySkillGap registered", strpos($facSg, 'window.initFacultySkillGap') !== false);
check("No self-call removed", strpos($facSg, "addEventListener('DOMContentLoaded', window.initFacultySkillGap)") === false);

echo "\n[student/skill-gap.php]\n";
check("initSkillGap registered", strpos($stuSg, 'window.initSkillGap') !== false);
check("No self-call removed", strpos($stuSg, "addEventListener('DOMContentLoaded', window.initSkillGap)") === false);

echo "\n=== DONE ===\n";
