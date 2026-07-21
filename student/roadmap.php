<?php
/**
 * SkillBridge - Dynamic Student Learning Roadmap & Career Pathways
 * Merged 100% with roadmap.html UI/UX design, interactive YouTube embeds, 
 * milestone task trackers, notes editor, and real-time database skill analytics.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_role('student');

$studentId = $_SESSION['profile_id'];
$userId = $_SESSION['user_id'];
$db = Database::getInstance();

// Fetch student info
$student = $db->fetch(
    "SELECT s.*, u.username, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?",
    [$studentId]
);

$studentName = htmlspecialchars($student['first_name'] . ' ' . $student['last_name']);
$studentDept = htmlspecialchars($student['department'] ?? 'Computer Science');
$defaultRoleKey = 'fullstack';
if (stripos($studentDept, 'front') !== false) $defaultRoleKey = 'frontend';
if (stripos($studentDept, 'back') !== false) $defaultRoleKey = 'backend';
if (stripos($studentDept, 'data') !== false) $defaultRoleKey = 'datascientist';
if (stripos($studentDept, 'sec') !== false) $defaultRoleKey = 'cybersecurity';

// Fetch all active skills from DB with weighted calculation for current student
$skillsRaw = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");
$studentSkills = [];

foreach ($skillsRaw as $s) {
    $weighted = calculate_weighted_skill_percentage($studentId, (int)$s['id']);
    $score = (float)$weighted['overall_percentage'];
    
    $studentSkills[$s['id']] = [
        'id' => (int)$s['id'],
        'name' => $s['name'],
        'category' => $s['category'],
        'score' => $score,
        'status' => $weighted['status'],
        'attempted_levels' => $weighted['attempted_levels']
    ];
}

$pageTitle = "Learning Roadmap - SkillBridge";
include __DIR__ . '/../includes/header.php';
?>

<style>
/* ── Roadmap Custom Styles (Theme-Aware) ── */
.role-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-top: 1.5rem;
}

.role-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  display: flex;
  flex-direction: column;
  height: 100%;
  position: relative;
  overflow: hidden;
  box-shadow: var(--shadow-card);
}

.role-card:hover {
  transform: translateY(-6px);
  border-color: var(--primary);
  box-shadow: var(--shadow-card-hover);
  background: var(--bg-hover);
}

.role-icon-box {
  width: 54px;
  height: 54px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
  margin-bottom: 1rem;
}
.role-icon-box.primary { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-light); }
.role-icon-box.accent  { background: var(--accent-light); color: var(--accent); border: 1px solid var(--accent-light); }
.role-icon-box.warning { background: var(--warning-light); color: var(--warning-text); border: 1px solid var(--warning-light); }
.role-icon-box.success { background: var(--success-light); color: var(--success-text); border: 1px solid var(--success-light); }

.role-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-heading); }
.role-desc { font-size: 0.825rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1rem; flex-grow: 1; }

.role-skills { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 1.25rem; }
.role-skill-tag { background: var(--primary-light); border: 1px solid var(--border); padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; color: var(--primary); font-weight: 600; }

.btn-select-role {
  background: var(--primary); color: #fff; border: none; border-radius: 10px; padding: 0.75rem 1rem; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; width: 100%; cursor: pointer;
}
.role-card:hover .btn-select-role { background: var(--primary-hover); }

.top-path-selector-card {
  background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 0.85rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: var(--shadow-card);
}

.top-path-select-wrapper { display: flex; align-items: center; gap: 12px; }
.top-path-dropdown {
  background: var(--bg-input); color: var(--text-heading); border: 1px solid var(--border-input); padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.875rem; min-width: 240px; cursor: pointer; outline: none; transition: border-color 0.2s;
}
.top-path-dropdown:focus { border-color: var(--border-focus); }

/* Custom Checkboxes */
.milestone-custom-checkbox {
  width: 24px; height: 24px; border: 2px solid var(--border-input); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; transition: all 0.2s; background: var(--bg-input); flex-shrink: 0; margin-top: 2px;
}
.milestone-custom-checkbox.checked { background: var(--success); border-color: var(--success); }

/* Video Player Styles */
.milestone-video-panel { background: var(--bg-alt); border: 1px solid var(--border); border-radius: 12px; margin-top: 1rem; overflow: hidden; width: 100%; }
.video-panel-header { display: flex; justify-content: space-between; align-items: center; padding: 8px 14px; background: var(--bg-card); border-bottom: 1px solid var(--border); }
.video-duration { font-size: 0.75rem; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; }
.youtube-external-link { font-size: 0.75rem; color: var(--primary); display: flex; align-items: center; gap: 4px; text-decoration: none; font-weight: 600; }
.youtube-external-link:hover { color: var(--accent); text-decoration: underline; }

.video-placeholder { padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; cursor: pointer; background: var(--bg-alt); transition: background 0.2s; }
.video-placeholder:hover { background: var(--bg-hover); }

.play-btn-circle {
  width: 44px; height: 44px; border-radius: 50%; background: var(--danger-light); color: var(--danger-text); border: 1px solid var(--danger-light); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; transition: all 0.2s;
}
.video-placeholder:hover .play-btn-circle { transform: scale(1.1); background: var(--danger); color: #fff; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); }

.video-container { position: relative; width: 100%; padding-bottom: 56.25%; height: 0; background: #000; }
.video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }

/* Timeline Styles */
.roadmap-timeline { position: relative; padding-left: 2rem; border-left: 2px solid var(--border); margin-left: 12px; }
.roadmap-phase { position: relative; margin-bottom: 2.5rem; }

.roadmap-dot {
  position: absolute; left: -43px; top: 2px; width: 24px; height: 24px; border-radius: 50%; background: var(--bg-card); border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; z-index: 2;
}
.roadmap-dot.completed { background: var(--success); border-color: var(--success); color: white; box-shadow: 0 0 10px rgba(16, 185, 129, 0.3); }
.roadmap-dot.current { background: var(--primary); border-color: var(--accent); color: white; box-shadow: 0 0 0 4px rgba(38, 101, 140, 0.18); }
.roadmap-dot.locked { background: var(--bg-muted); color: var(--text-muted); border-color: var(--border); }

.roadmap-phase-title { font-size: 1.15rem; font-weight: 700; margin: 0 0 1rem 0; color: var(--text-heading); }
.roadmap-milestones { display: flex; flex-direction: column; gap: 1rem; }

.roadmap-milestone {
  background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 1.25rem; transition: all 0.3s ease; display: flex; gap: 1rem; align-items: flex-start; box-shadow: var(--shadow-card);
}
.roadmap-milestone:hover { transform: translateY(-4px); border-color: var(--primary); box-shadow: var(--shadow-card-hover); }
.roadmap-milestone.completed { border-color: var(--success); background: var(--success-light); }

.milestone-info { flex-grow: 1; }
.milestone-title-wrapper { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.5rem; }
.milestone-title { font-size: 1.05rem; font-weight: 700; color: var(--text-heading); }
.milestone-desc { font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 0.75rem; }

.diff-badge { font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; }
.diff-beginner { background: var(--success-light); color: var(--success-text); border: 1px solid var(--success-light); }
.diff-intermediate { background: var(--warning-light); color: var(--warning-text); border: 1px solid var(--warning-light); }
.diff-advanced { background: var(--accent-light); color: var(--accent); border: 1px solid var(--accent-light); }

.practice-project-container { background: var(--bg-alt); border: 1px dashed var(--border-input); border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1rem; }
.practice-project-header { font-size: 0.7rem; font-weight: 700; color: var(--warning-text); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 4px; }
.practice-project-body { font-size: 0.8rem; color: var(--text-secondary); line-height: 1.5; }

.milestone-links-bar { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.75rem; }
.milestone-link-btn {
  display: inline-flex; align-items: center; gap: 6px; font-size: 0.75rem; color: var(--text-secondary); text-decoration: none; background: var(--bg-muted); border: 1px solid var(--border); padding: 6px 12px; border-radius: 6px; transition: all 0.2s; font-weight: 600;
}
.milestone-link-btn:hover { background: var(--bg-hover); color: var(--text-heading); }

.notes-textarea {
  width: 100%; height: 80px; background: var(--bg-input); border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; color: var(--text-body); font-size: 0.8rem; outline: none; transition: border-color 0.2s;
}
.notes-textarea:focus { border-color: var(--border-focus); box-shadow: var(--shadow-focus); }

.btn-switch-role {
  background: var(--primary-light); border: 1px solid var(--border); color: var(--primary); border-radius: 20px; padding: 6px 16px; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.2s;
}
.btn-switch-role:hover { background: var(--primary); color: #FFF; }
</style>

<!-- Main Roadmap Workspace Layout -->
<div class="dash-content">
  
  <!-- Role Selection Screen -->
  <div id="role-selection-screen" class="animate-slideUp" style="display: none;">
    <div style="text-align: center; margin-bottom: 2rem; max-width: 700px; margin-left: auto; margin-right: auto;">
      <h1 class="fw-bold" style="font-size: 2.2rem; line-height: 1.3">Select Your <span class="gradient-text">Career Path</span></h1>
      <p class="text-muted small">Choose your professional target to generate your personalized learning roadmap, curated video playlists, and track real-time DB skill achievements.</p>
    </div>
    <div class="role-grid" id="role-grid-container">
      <!-- Injected by JS -->
    </div>
  </div>

  <!-- Active Roadmap Screen -->
  <div id="roadmap-screen" style="display: none;">
    <!-- Top Active Path Selector Bar -->
    <div class="top-path-selector-card">
      <div class="top-path-select-wrapper">
        <i class="fa-solid fa-graduation-cap text-primary fs-5"></i>
        <span class="fw-bold text-dark small">Active Learning Path:</span>
      </div>
      <select id="role-selector-top" onchange="selectRole(this.value)" class="top-path-dropdown">
        <option value="frontend">Frontend Developer</option>
        <option value="backend">Backend Developer</option>
        <option value="fullstack">Full Stack Developer</option>
        <option value="uiux">UI/UX Designer</option>
        <option value="datascientist">Data Scientist / Analyst</option>
        <option value="devops">DevOps & Cloud Engineer</option>
        <option value="cybersecurity">Cybersecurity Specialist</option>
      </select>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <div>
        <h2 class="fw-bold text-dark mb-1">Learning Roadmap <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 ms-2" style="font-size: 0.75rem;">DB Integrated</span></h2>
        <p class="text-muted small mb-0">Step-by-step career path to becoming a <strong id="roadmap-role-name" class="text-primary">Role Name</strong> &bull; Estimated Duration: <strong id="roadmap-role-duration" class="text-warning">6 Months</strong></p>
      </div>
      <div>
        <button onclick="resetRoleSelection()" class="btn-switch-role">
          <i class="fa-solid fa-arrows-rotate me-1"></i> Switch Path
        </button>
      </div>
    </div>

    <!-- Overall Roadmap Progress Widget -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #FFFFFF; border: 1px solid #E2E8F0;">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <div>
          <h5 class="fw-bold text-dark mb-1">Overall Roadmap Progress</h5>
          <p class="text-muted small mb-0">Dynamic real-time progress computed from your database assessment performance</p>
        </div>
        <div class="text-end">
          <span id="dashboard-progress-percent" class="fw-extrabold text-success fs-2" style="line-height: 1;">0%</span>
          <div class="text-muted small fw-bold text-uppercase" style="font-size: 10px;">Completed</div>
        </div>
      </div>

      <div class="progress rounded-pill mb-4" style="height: 14px; background: #F1F5F9;">
        <div id="dashboard-progress-fill" class="progress-bar bg-success rounded-pill" style="width: 0%; transition: width 0.5s ease;"></div>
      </div>

      <div class="row g-3">
        <div class="col-md-4">
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
            <div class="p-2.5 rounded-circle bg-success-subtle text-success fs-4"><i class="fa-solid fa-circle-check"></i></div>
            <div>
              <div id="dashboard-completed-steps" class="fw-bold text-dark fs-5">0</div>
              <div class="text-muted small">Completed Steps</div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
            <div class="p-2.5 rounded-circle bg-primary-subtle text-primary fs-4"><i class="fa-solid fa-hourglass-half"></i></div>
            <div>
              <div id="dashboard-remaining-steps" class="fw-bold text-dark fs-5">0</div>
              <div class="text-muted small">Remaining Steps</div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
            <div class="p-2.5 rounded-circle bg-warning-subtle text-warning fs-4"><i class="fa-solid fa-clock"></i></div>
            <div>
              <div id="dashboard-total-hours" class="fw-bold text-dark fs-5">0 / 0 hrs</div>
              <div class="text-muted small">Study Hours Logged</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Timeline Grid Layout -->
    <div class="row g-4">
      <div class="col-lg-8">
        <div id="roadmap-timeline-container" class="roadmap-timeline">
          <!-- Populated dynamically via JS -->
        </div>
      </div>

      <!-- Right Column Stats & Recommended Action -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-center">
          <h6 class="fw-bold text-dark mb-3">Target Career Readiness</h6>
          <div class="mx-auto mb-3" style="width: 140px; height: 140px; border-radius: 50%; background: conic-gradient(#10B981 0deg, #F1F5F9 0deg); display: flex; align-items: center; justify-content: center;" id="career-match-gauge">
            <div style="width: 112px; height: 112px; background: #FFF; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
              <span id="career-match-percent" class="fw-extrabold text-success fs-3">0%</span>
              <span id="career-match-label" class="text-muted" style="font-size: 10px;">Role Match</span>
            </div>
          </div>
          <p id="career-match-status-text" class="text-muted small mb-0"></p>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
          <h6 class="fw-bold text-dark mb-3">Roadmap Summary</h6>
          <div class="d-flex flex-column gap-2">
            <div class="d-flex justify-content-between align-items-center p-2.5 bg-light rounded-3 border">
              <span class="small text-dark fw-semibold"><i class="fa-solid fa-check text-success me-2"></i>Completed</span>
              <strong id="stat-completed" class="text-success small">0 tasks</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center p-2.5 bg-light rounded-3 border">
              <span class="small text-dark fw-semibold"><i class="fa-solid fa-spinner text-primary me-2"></i>In Progress</span>
              <strong id="stat-in-progress" class="text-primary small">0 tasks</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center p-2.5 bg-light rounded-3 border">
              <span class="small text-dark fw-semibold"><i class="fa-solid fa-lock text-muted me-2"></i>Remaining</span>
              <strong id="stat-remaining" class="text-muted small">0 tasks</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center p-2.5 bg-light rounded-3 border">
              <span class="small text-dark fw-semibold"><i class="fa-solid fa-clock text-warning me-2"></i>Hours Spent</span>
              <strong id="stat-hours" class="text-warning small">0 hrs</strong>
            </div>
          </div>
          
          <a href="<?= BASE_URL ?>student/assessments.php" class="btn btn-primary bg-gradient-primary border-0 rounded-pill w-100 py-2.5 mt-3 fw-semibold">
            <i class="fa-solid fa-clipboard-check me-1"></i> Take Skill Assessment
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
// JSON Student DB Skill Performance Array passed from PHP
const studentSkillsData = <?php echo json_encode($studentSkills); ?>;
const userDefaultRole = <?php echo json_encode($defaultRoleKey); ?>;

// Master Multi-Role Roadmap Definition
const roadmaps = {
  frontend: {
    title: "Frontend Developer",
    duration: "5 Months",
    matchPercentage: 75,
    phases: [
      {
        name: "Phase 1 — Web Fundamentals",
        duration: "6 Weeks",
        milestones: [
          { id: "fe_m1", skillId: 4, title: "HTML5 & Semantic Structure", desc: "Master web semantics, document structure, forms, and accessibility guidelines.", hours: 10, playlistId: "PL4cUxeGkcC9ivBXXWbFiFiA7aKdi88yRL", difficulty: "Beginner", docLink: "https://developer.mozilla.org/en-US/docs/Web/HTML", practiceProject: "Build a responsive personal portfolio site from scratch." },
          { id: "fe_m2", skillId: 5, title: "CSS3 & Bootstrap Layouts", desc: "Master Box Model, Flexbox, Grid system, Bootstrap classes, and fluid design.", hours: 20, playlistId: "PL4cUxeGkcC9itC4o504sKxpW-Z5e60p1C", difficulty: "Beginner", docLink: "https://developer.mozilla.org/en-US/docs/Web/CSS", practiceProject: "Design a fully responsive landing page optimized across viewports." },
          { id: "fe_m3", skillId: 3, title: "JavaScript ES6+ & DOM Manipulation", desc: "Learn variables, arrow functions, DOM events, promises, and dynamic rendering.", hours: 30, playlistId: "PL4cUxeGkcC9haFPT7J25Q9GRB_Z5AlJuV", difficulty: "Beginner", docLink: "https://developer.mozilla.org/en-US/docs/Web/JavaScript", practiceProject: "Build an interactive task manager with search and local storage." }
        ]
      },
      {
        name: "Phase 2 — Modern Frameworks",
        duration: "8 Weeks",
        milestones: [
          { id: "fe_m4", skillId: 10, title: "Git & Version Control", desc: "Master commits, branching, merging, and collaborative GitHub workflows.", hours: 10, playlistId: "PL4cUxeGkcC9goXbgTDQ0n_4TBzOO0ocPR", difficulty: "Beginner", docLink: "https://git-scm.com/doc", practiceProject: "Create a public repository with automated versioning." },
          { id: "fe_m5", skillId: 14, title: "React.js Framework Core", desc: "Learn JSX syntax, component lifecycle, hooks, props, and client state.", hours: 35, playlistId: "PL4cUxeGkcC9gZD-Tvwfod2gaISzfRiP9d", difficulty: "Intermediate", docLink: "https://react.dev", practiceProject: "Develop a live weather API dashboard in React." }
        ]
      },
      {
        name: "Phase 3 — State & Architecture",
        duration: "6 Weeks",
        milestones: [
          { id: "fe_m6", skillId: 11, title: "UI/UX & Design Systems", desc: "Create component design tokens, accessibility contrast, and user flows.", hours: 15, playlistId: "PL4cUxeGkcC9h6OAGy8Sy1x7dbVOMQGjX8", difficulty: "Intermediate", docLink: "https://www.w3.org/WAI/", practiceProject: "Implement a complete accessible theme switch component." }
        ]
      }
    ]
  },
  backend: {
    title: "Backend Developer",
    duration: "6 Months",
    matchPercentage: 70,
    phases: [
      {
        name: "Phase 1 — Core Server Programming",
        duration: "6 Weeks",
        milestones: [
          { id: "be_m1", skillId: 1, title: "PHP 8 & Backend Logic", desc: "Understand OOP PHP, session management, forms processing, and error handling.", hours: 25, playlistId: "PLr3d3Ku5PSPw_A_cnBscs5vU0A7a1S9mS", difficulty: "Beginner", docLink: "https://www.php.net/docs.php", practiceProject: "Build an authenticated user portal with session control." },
          { id: "be_m2", skillId: 2, title: "MySQL Database Design", desc: "Master relational schemas, SQL queries, indexes, and PDO prepared statements.", hours: 25, playlistId: "PL0b6OzIxLPbyrzCMJofzLnf_-_5E_brvs", difficulty: "Intermediate", docLink: "https://dev.mysql.com/doc/", practiceProject: "Design a relational database for an e-commerce platform." }
        ]
      },
      {
        name: "Phase 2 — API Architecture & Security",
        duration: "8 Weeks",
        milestones: [
          { id: "be_m3", skillId: 7, title: "RESTful API Development", desc: "Design JSON API endpoints, authentication tokens, and standard response codes.", hours: 20, playlistId: "PLillGF-RfqbZ2ybcoVmjCQmVJ23MfmzpB", difficulty: "Intermediate", docLink: "https://restfulapi.net/", practiceProject: "Create a RESTful API service supporting CRUD operations." },
          { id: "be_m4", skillId: 6, title: "Web Security & OWASP Top 10", desc: "Protect against SQL injection, XSS, CSRF, and manage password hashing.", hours: 15, playlistId: "PL10u0b3N6Lw4UfW75pXW8w216fA", difficulty: "Advanced", docLink: "https://owasp.org/", practiceProject: "Perform a security audit on a PHP form application." }
        ]
      }
    ]
  },
  fullstack: {
    title: "Full Stack Developer",
    duration: "6 Months",
    matchPercentage: 80,
    phases: [
      {
        name: "Phase 1 — Web Fundamentals & DB Architecture",
        duration: "6 Weeks",
        milestones: [
          { id: "fs_m1", skillId: 4, title: "Frontend Foundation (HTML/CSS)", desc: "Build responsive semantic user interfaces.", hours: 20, playlistId: "PL4cUxeGkcC9ivBXXWbFiFiA7aKdi88yRL", difficulty: "Beginner", docLink: "https://developer.mozilla.org", practiceProject: "Build a responsive web application layout." },
          { id: "fs_m2", skillId: 1, title: "PHP Backend & MySQL Integration", desc: "Connect PHP server logic to database tables safely.", hours: 30, playlistId: "PLr3d3Ku5PSPw_A_cnBscs5vU0A7a1S9mS", difficulty: "Intermediate", docLink: "https://www.php.net", practiceProject: "Build an end-to-end full stack CRUD application." }
        ]
      },
      {
        name: "Phase 2 — Modern Full Stack Frameworks",
        duration: "8 Weeks",
        milestones: [
          { id: "fs_m3", skillId: 14, title: "React & RESTful Integration", desc: "Build decoupled SPA frontends powered by REST APIs.", hours: 35, playlistId: "PL4cUxeGkcC9gZD-Tvwfod2gaISzfRiP9d", difficulty: "Intermediate", docLink: "https://react.dev", practiceProject: "Deploy a full stack dashboard with live API requests." },
          { id: "fs_m4", skillId: 13, title: "Docker & Container Deployment", desc: "Package full stack apps into Docker containers.", hours: 15, playlistId: "PL4cUxeGkcC9g_69kOfXICzT_hZxRN16", difficulty: "Advanced", docLink: "https://docs.docker.com", practiceProject: "Containerize a PHP/MySQL application using Docker Compose." }
        ]
      }
    ]
  },
  datascientist: {
    title: "Data Scientist / Analyst",
    duration: "6 Months",
    matchPercentage: 65,
    phases: [
      {
        name: "Phase 1 — Python & Data Structures",
        duration: "6 Weeks",
        milestones: [
          { id: "ds_m1", skillId: 12, title: "Python Programming for Analytics", desc: "Master Python data types, functions, and data manipulation.", hours: 25, playlistId: "PL-osiE80TeTskrapNbzXhwoFZuGYkmo8", difficulty: "Beginner", docLink: "https://docs.python.org/3/", practiceProject: "Analyze and clean a public CSV dataset using Python." },
          { id: "ds_m2", skillId: 8, title: "Data Structures & Algorithmic Logic", desc: "Understand arrays, trees, hashing, and complexity.", hours: 25, playlistId: "PL2_aWCzGMAwI3W_JlcBbtYTwiQSsOTa6P", difficulty: "Intermediate", docLink: "https://en.wikipedia.org/wiki/Data_structure", practiceProject: "Implement algorithmic search routines." }
        ]
      }
    ]
  }
};

let currentRoleKey = userDefaultRole || 'fullstack';
if (!roadmaps[currentRoleKey]) currentRoleKey = 'fullstack';

document.addEventListener('DOMContentLoaded', function() {
    initRoadmapPage();
});

function initRoadmapPage() {
    renderRoleCards();
    selectRole(currentRoleKey, false);
}

function renderRoleCards() {
    const container = document.getElementById('role-grid-container');
    if (!container) return;
    
    let html = '';
    for (const key in roadmaps) {
        const role = roadmaps[key];
        html += `
            <div class="role-card" onclick="selectRole('${key}')">
                <div class="role-icon-box primary"><i class="fa-solid fa-briefcase"></i></div>
                <div class="role-title">${role.title}</div>
                <div class="role-desc">Personalized pathway to master ${role.title} competencies and industry tools.</div>
                <div class="role-skills">
                    <span class="role-skill-tag">${role.duration}</span>
                    <span class="role-skill-tag">${role.phases.length} Phases</span>
                </div>
                <button class="btn-select-role"><i class="fa-solid fa-arrow-right"></i> Launch Pathway</button>
            </div>
        `;
    }
    container.innerHTML = html;
}

function selectRole(roleKey, animate = true) {
    if (!roadmaps[roleKey]) roleKey = 'fullstack';
    currentRoleKey = roleKey;

    document.getElementById('role-selection-screen').style.display = 'none';
    document.getElementById('roadmap-screen').style.display = 'block';

    const selectTop = document.getElementById('role-selector-top');
    if (selectTop) selectTop.value = roleKey;

    renderRoadmap(roleKey);
}

function resetRoleSelection() {
    document.getElementById('roadmap-screen').style.display = 'none';
    document.getElementById('role-selection-screen').style.display = 'block';
}

function renderRoadmap(roleKey) {
    const role = roadmaps[roleKey];
    document.getElementById('roadmap-role-name').textContent = role.title;
    document.getElementById('roadmap-role-duration').textContent = role.duration;

    let totalMilestones = 0;
    let completedMilestones = 0;
    let totalHours = 0;
    let completedHours = 0;

    let timelineHtml = '';

    role.phases.forEach((phase, phaseIndex) => {
        timelineHtml += `
            <div class="roadmap-phase">
                <h4 class="roadmap-phase-title">${phase.name} <span class="text-muted small fw-normal">(${phase.duration})</span></h4>
                <div class="roadmap-milestones">
        `;

        phase.milestones.forEach((m, mIndex) => {
            totalMilestones++;
            totalHours += m.hours;

            // DYNAMIC STATUS FROM DB PERFORMANCE
            let status = 'todo';
            const dbSkill = studentSkillsData[m.skillId];
            
            if (dbSkill) {
                if (dbSkill.score >= 60) {
                    status = 'completed';
                } else if (dbSkill.score > 0 || dbSkill.attempted_levels > 0) {
                    status = 'active';
                }
            }

            // Check override in localStorage
            const localSaved = localStorage.getItem('m_status_' + m.id);
            if (localSaved) status = localSaved;

            if (status === 'completed') {
                completedMilestones++;
                completedHours += m.hours;
            }

            const isChecked = status === 'completed' ? 'checked' : '';
            const statusBadge = status === 'completed' 
                ? '<span class="badge bg-success-subtle text-success border border-success-subtle">Completed</span>'
                : (status === 'active' 
                    ? '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">In Progress</span>'
                    : '<span class="badge bg-light text-muted border">To Do</span>');

            const diffClass = m.difficulty === 'Beginner' ? 'diff-beginner' : (m.difficulty === 'Intermediate' ? 'diff-intermediate' : 'diff-advanced');

            const savedNote = localStorage.getItem('m_note_' + m.id) || '';

            timelineHtml += `
                <div class="roadmap-milestone ${status}" id="milestone-card-${m.id}">
                    <div class="roadmap-dot ${status}">${status === 'completed' ? '✓' : mIndex + 1}</div>
                    
                    <div class="milestone-custom-checkbox ${isChecked}" onclick="toggleMilestoneStatus('${m.id}')">
                        ${isChecked ? '<i class="fa-solid fa-check fs-6"></i>' : ''}
                    </div>

                    <div class="milestone-info">
                        <div class="milestone-title-wrapper">
                            <div class="milestone-title">${m.title}</div>
                            ${statusBadge}
                        </div>
                        <p class="milestone-desc">${m.desc}</p>
                        
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                            <span class="diff-badge ${diffClass}">${m.difficulty}</span>
                            <span class="badge bg-light text-muted border"><i class="fa-solid fa-clock me-1"></i>${m.hours} hrs</span>
                            ${dbSkill ? `<span class="badge bg-info-subtle text-info border">DB Score: ${Math.round(dbSkill.score)}%</span>` : ''}
                        </div>

                        ${m.practiceProject ? `
                            <div class="practice-project-container">
                                <span class="practice-project-header"><i class="fa-solid fa-laptop-code me-1"></i> Practice Project</span>
                                <div class="practice-project-body">${m.practiceProject}</div>
                            </div>
                        ` : ''}

                        <div class="milestone-links-bar">
                            ${m.playlistId ? `
                                <button class="milestone-link-btn" onclick="toggleVideo('${m.id}', '${m.playlistId}')">
                                    <i class="fa-brands fa-youtube text-danger"></i> Watch Video Playlist
                                </button>
                            ` : ''}
                            ${m.docLink ? `
                                <a href="${m.docLink}" target="_blank" class="milestone-link-btn">
                                    <i class="fa-solid fa-book-open text-primary"></i> Documentation
                                </a>
                            ` : ''}
                        </div>

                        <div id="video-container-${m.id}" style="display:none;" class="milestone-video-panel"></div>

                        <!-- Notes Section -->
                        <div class="mt-2 border-top pt-2">
                            <button class="btn btn-link p-0 text-muted small text-decoration-none" onclick="toggleNotes('${m.id}')">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Personal Notes ${savedNote ? '✓' : ''}
                            </button>
                            <div id="notes-panel-${m.id}" style="display:${savedNote ? 'block' : 'none'};" class="mt-2">
                                <textarea class="notes-textarea" placeholder="Write personal study notes here..." onblur="saveNotes('${m.id}', this.value)">${savedNote}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
            `;
        });

        timelineHtml += `
                </div>
            </div>
        `;
    });

    document.getElementById('roadmap-timeline-container').innerHTML = timelineHtml;

    // Update Metrics
    const pct = totalMilestones > 0 ? Math.round((completedMilestones / totalMilestones) * 100) : 0;
    document.getElementById('dashboard-progress-percent').textContent = pct + '%';
    document.getElementById('dashboard-progress-fill').style.width = pct + '%';

    document.getElementById('dashboard-completed-steps').textContent = completedMilestones;
    document.getElementById('dashboard-remaining-steps').textContent = (totalMilestones - completedMilestones);
    document.getElementById('dashboard-total-hours').textContent = `${completedHours} / ${totalHours} hrs`;

    const careerMatch = Math.min(98, Math.max(20, Math.round(pct * 0.85 + 15)));
    document.getElementById('career-match-percent').textContent = careerMatch + '%';
    document.getElementById('career-match-gauge').style.background = `conic-gradient(#10B981 ${careerMatch * 3.6}deg, #F1F5F9 0deg)`;

    document.getElementById('stat-completed').textContent = `${completedMilestones} tasks`;
    document.getElementById('stat-in-progress').textContent = `${totalMilestones - completedMilestones} tasks`;
    document.getElementById('stat-remaining').textContent = `${totalMilestones - completedMilestones} tasks`;
    document.getElementById('stat-hours').textContent = `${completedHours} hrs`;
}

function toggleMilestoneStatus(id) {
    const current = localStorage.getItem('m_status_' + id);
    const newStatus = current === 'completed' ? 'todo' : 'completed';
    localStorage.setItem('m_status_' + id, newStatus);
    renderRoadmap(currentRoleKey);
}

function toggleVideo(id, playlistId) {
    const container = document.getElementById('video-container-' + id);
    if (!container) return;

    if (container.style.display === 'none') {
        container.style.display = 'block';
        container.innerHTML = `
            <div class="video-panel-header">
                <span class="video-duration"><i class="fa-brands fa-youtube text-danger"></i> YouTube Playlist</span>
                <a href="https://www.youtube.com/playlist?list=${playlistId}" target="_blank" class="youtube-external-link">Open in YouTube <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/videoseries?list=${playlistId}" title="Video Tutorial" allowfullscreen></iframe>
            </div>
        `;
    } else {
        container.style.display = 'none';
        container.innerHTML = '';
    }
}

function toggleNotes(id) {
    const panel = document.getElementById('notes-panel-' + id);
    if (panel) {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
}

function saveNotes(id, text) {
    localStorage.setItem('m_note_' + id, text);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
