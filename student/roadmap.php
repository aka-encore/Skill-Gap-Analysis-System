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
check_suspended_status();

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

// Fetch all active skills from DB with weighted calculation for current student
$skillsRaw = $db->fetchAll("SELECT * FROM skills ORDER BY name ASC");
$studentSkills = [];
$skillsMap = [];

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
    $skillsMap[strtolower($s['name'])] = (int)$s['id'];
}

// Fetch all active courses with their skills mappings, progress, and lesson count for the current student
$coursesRaw = $db->fetchAll(
    "SELECT 
        c.id, 
        c.title, 
        c.instructor, 
        c.difficulty_level, 
        c.duration_hours, 
        cs.skill_id,
        COALESCE(sp.progress_percentage, 0) AS progress_percentage,
        COALESCE(sp.status, 'not_started') AS enrollment_status,
        (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS total_lessons,
        COALESCE((SELECT SUM(duration_minutes) FROM lessons l WHERE l.course_id = c.id), 0) AS total_duration_minutes
     FROM courses c
     JOIN course_skills cs ON c.id = cs.course_id
     LEFT JOIN student_progress sp ON c.id = sp.course_id AND sp.student_id = ?
     WHERE c.status = 'active'",
    [$studentId]
);

$coursesBySkill = [];
foreach ($coursesRaw as $c) {
    $skillId = (int)$c['skill_id'];
    if (!isset($coursesBySkill[$skillId])) {
        $coursesBySkill[$skillId] = [];
    }
    $coursesBySkill[$skillId][] = [
        'id' => (int)$c['id'],
        'title' => $c['title'],
        'instructor' => $c['instructor'],
        'difficulty' => $c['difficulty_level'],
        'duration' => $c['duration_hours'],
        'total_duration_minutes' => (int)$c['total_duration_minutes'],
        'lessons_count' => (int)$c['total_lessons'],
        'progress' => (int)$c['progress_percentage'],
        'status' => $c['enrollment_status']
    ];
}

$pageTitle = "Learning Roadmap - SkillBridge";
$careerMatch = calculate_student_career_match($studentId);
include __DIR__ . '/../includes/header.php';
?>

<style>
/* ── Roadmap Custom Styles (Theme-Aware) ── */
.role-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.25rem;
  margin-top: 1rem;
}

.role-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 1.25rem;
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

.role-card.selected {
  border: 2px solid var(--primary, #26658C) !important;
  background-color: var(--primary-light, rgba(38, 101, 140, 0.05)) !important;
  box-shadow: 0 10px 25px -5px rgba(38, 101, 140, 0.15), 0 8px 10px -6px rgba(38, 101, 140, 0.15) !important;
  transform: translateY(-4px);
}

.role-icon-box {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
  margin-bottom: 0.85rem;
}
.role-icon-box.primary { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-light); }
.role-icon-box.accent  { background: var(--accent-light); color: var(--accent); border: 1px solid var(--accent-light); }
.role-icon-box.warning { background: var(--warning-light); color: var(--warning-text); border: 1px solid var(--warning-light); }
.role-icon-box.success { background: var(--success-light); color: var(--success-text); border: 1px solid var(--success-light); }

.role-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.4rem; color: var(--text-heading); }
.role-desc { font-size: 0.8rem; color: var(--text-secondary); line-height: 1.45; margin-bottom: 0.85rem; flex-grow: 1; }

.role-skills { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 1rem; }
.role-skill-tag { background: var(--primary-light); border: 1px solid var(--border); padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; color: var(--primary); font-weight: 600; }

.btn-select-role {
  background: var(--bg-muted, #F1F5F9);
  color: var(--text-secondary, #475569);
  border: 1px solid var(--border, #E2E8F0);
  border-radius: 10px;
  padding: 0.65rem 1rem;
  font-weight: 600;
  font-size: 0.825rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.25s ease;
  width: 100%;
  cursor: pointer;
}
.role-card:hover .btn-select-role {
  background: var(--primary-light, rgba(38, 101, 140, 0.1));
  color: var(--primary, #26658C);
  border-color: var(--primary-light, rgba(38, 101, 140, 0.1));
}
.role-card.selected .btn-select-role {
  background: var(--primary, #26658C);
  color: #FFF;
  border-color: var(--primary, #26658C);
}

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

/* SkillBridge Courses Recommendation Styling */
.course-rec-section-title {
  font-size: 0.85rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--primary);
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
  gap: 6px;
}
.course-rec-card {
  border: 1px solid var(--border) !important;
  background: var(--bg-card) !important;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  display: flex;
  flex-direction: column;
  height: 100%;
}
.course-rec-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-card-hover) !important;
  border-color: var(--primary) !important;
}
.course-rec-thumb {
  height: 90px;
  background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
}
[data-theme="dark"] .course-rec-thumb {
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
}

/* YouTube Recommendation Styling */
.youtube-section-title {
  font-size: 0.85rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #ef4444;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
  gap: 6px;
}
.youtube-playlist-card {
  background: var(--bg-card) !important;
  border: 1px solid var(--border) !important;
  border-left: 4px solid #ef4444 !important;
  transition: all 0.3s ease;
}
.youtube-playlist-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-card-hover) !important;
}
</style>

<!-- Main Roadmap Workspace Layout -->
<div class="dash-content">
  
  <!-- Role Selection Screen -->
  <div id="role-selection-screen" class="animate-slideUp">
    <div style="text-align: center; margin-bottom: 2rem; max-width: 700px; margin-left: auto; margin-right: auto;">
      <h1 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; line-height: 1.3">Select Your <span class="gradient-text">Career Path</span></h1>
      <p class="text-muted small">Choose your professional target to generate your personalized learning roadmap, curated video playlists, and track real-time DB skill achievements.</p>
    </div>
    <div class="role-grid" id="role-grid-container">
      <!-- Injected dynamically by JS -->
    </div>
  </div>

  <!-- Active Roadmap Screen -->
  <div id="roadmap-screen" style="display: none;">

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
    
    <!-- Roadmap Empty State (📚 Courses Coming Soon) -->
    <div id="roadmap-empty-state" class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white mb-4" style="display: none; border: 1px solid #E2E8F0 !important;">
      <div class="mb-3">
        <span style="font-size: 3.5rem;"><i class="fa-solid fa-graduation-cap text-primary opacity-50"></i></span>
      </div>
      <h4 class="fw-bold text-dark mb-2">📚 Courses Coming Soon</h4>
      <p class="text-muted mb-4 mx-auto" style="max-width: 500px; font-size: 0.85rem;">
        Courses for the selected learning pathway are not yet available. Please check back later as new learning content is regularly added.
      </p>
      <div>
        <button class="btn btn-primary rounded-pill px-4 btn-sm fw-semibold" onclick="selectRole('fullstack')">View Full Stack Pathway</button>
      </div>
    </div>

    <!-- Active Roadmap Content Container -->
    <div id="roadmap-content-container">

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
    </div> <!-- Close roadmap-content-container -->

  </div>
</div>

<script>
// JSON Student DB Skill Performance Array passed from PHP
const studentSkillsData = <?php echo json_encode($studentSkills); ?>;
const skillBridgeCourses = <?php echo json_encode($coursesBySkill); ?>;
const userDefaultRole = <?php echo json_encode($defaultRoleKey); ?>;
const dbCareerMatch = <?php echo json_encode($careerMatch); ?>;
const BASE_URL = <?php echo json_encode(BASE_URL); ?>;
const skillIds = <?php echo json_encode($skillsMap); ?>;

function escapeHtml(str) {
  return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

const youtubePlaylistsMetadata = {
  "PL4cUxeGkcC9ivBXXWbFiFiA7aKdi88yRL": {
    title: "HTML5 Tutorial for Beginners",
    channel: "Net Ninja",
    videoCount: 38,
    duration: "5.5 hours",
    thumbnail: "https://img.youtube.com/vi/pQN-pnXPaVg/mqdefault.jpg"
  },
  "PL4cUxeGkcC9itC4o504sKxpW-Z5e60p1C": {
    title: "CSS Tutorial for Beginners",
    channel: "Net Ninja",
    videoCount: 29,
    duration: "4 hours",
    thumbnail: "https://img.youtube.com/vi/yfoY53QXEnI/mqdefault.jpg"
  },
  "PL4cUxeGkcC9haFPT7J25Q9GRB_Z5AlJuV": {
    title: "JavaScript ES6+ Tutorial",
    channel: "Net Ninja",
    videoCount: 42,
    duration: "7.5 hours",
    thumbnail: "https://img.youtube.com/vi/W6NZfCO5SIk/mqdefault.jpg"
  },
  "PL4cUxeGkcC9goXbgTDQ0n_4TBzOO0ocPR": {
    title: "Git & GitHub Tutorial for Beginners",
    channel: "Net Ninja",
    videoCount: 12,
    duration: "2 hours",
    thumbnail: "https://img.youtube.com/vi/3R8dxOnDXWs/mqdefault.jpg"
  },
  "PL4cUxeGkcC9gZD-Tvwfod2gaISzfRiP9d": {
    title: "React.js Tutorial for Beginners",
    channel: "Net Ninja",
    videoCount: 32,
    duration: "6 hours",
    thumbnail: "https://img.youtube.com/vi/j942wKiXFu8/mqdefault.jpg"
  },
  "PL4cUxeGkcC9h6OAGy8Sy1x7dbVOMQGjX8": {
    title: "UI/UX Design Tutorials",
    channel: "DesignCourse",
    videoCount: 24,
    duration: "5 hours",
    thumbnail: "https://img.youtube.com/vi/c9Wg6RyOxjU/mqdefault.jpg"
  },
  "PLr3d3Ku5PSPw_A_cnBscs5vU0A7a1S9mS": {
    title: "PHP 8 Tutorial for Beginners",
    channel: "Net Ninja",
    videoCount: 30,
    duration: "5 hours",
    thumbnail: "https://img.youtube.com/vi/a7_WFUlFS9c/mqdefault.jpg"
  },
  "PL0b6OzIxLPbyrzCMJofzLnf_-_5E_brvs": {
    title: "MySQL Database Tutorial",
    channel: "Programming with Mosh",
    videoCount: 15,
    duration: "3 hours",
    thumbnail: "https://img.youtube.com/vi/7S_tz1z_5bA/mqdefault.jpg"
  },
  "PLillGF-RfqbZ2ybcoVmjCQmVJ23MfmzpB": {
    title: "RESTful API Design & Dev",
    channel: "Traversy Media",
    videoCount: 18,
    duration: "4.5 hours",
    thumbnail: "https://img.youtube.com/vi/g1c_x3_X4sA/mqdefault.jpg"
  },
  "PL10u0b3N6Lw4UfW75pXW8w216fA": {
    title: "Web Security & OWASP Standards",
    channel: "Hussein Nasser",
    videoCount: 14,
    duration: "3.5 hours",
    thumbnail: "https://img.youtube.com/vi/mI1v5V5vR24/mqdefault.jpg"
  },
  "PL-osiE80TeTskrapNbzXhwoFZuGYkmo8": {
    title: "Python Programming Tutorial",
    channel: "Corey Schafer",
    videoCount: 26,
    duration: "8 hours",
    thumbnail: "https://img.youtube.com/vi/YYXdXT2l-Gg/mqdefault.jpg"
  },
  "PL2_aWCzGMAwI3W_JlcBbtYTwiQSsOTa6P": {
    title: "Data Structures & Algorithms",
    channel: "mycodeschool",
    videoCount: 38,
    duration: "9 hours",
    thumbnail: "https://img.youtube.com/vi/B31LgI4Y4DQ/mqdefault.jpg"
  }
};

// Master Multi-Role Roadmap Definition
const roadmaps = {
  frontend: {
    title: "Frontend Developer",
    duration: "5 Months",
    matchPercentage: 75,
    phases: [
      {
        name: "Phase 1 — Web Foundations",
        duration: "6 Weeks",
        milestones: [
          { id: "fe_m1", skillId: skillIds['html'], title: "HTML5 Structure", desc: "Master semantic tags, form layouts, and accessibility guidelines.", hours: 10, playlistId: "PL4cUxeGkcC9ivBXXWbFiFiA7aKdi88yRL", difficulty: "Beginner", docLink: "https://developer.mozilla.org", practiceProject: "Build a responsive static landing page." },
          { id: "fe_m2", skillId: skillIds['css'], title: "CSS Styles & Positioning", desc: "Understand grids, Flexbox, transitions, and responsive styling techniques.", hours: 15, playlistId: "PL4cUxeGkcC9itC4o504sKxpW-Z5e60p1C", difficulty: "Beginner", docLink: "https://developer.mozilla.org", practiceProject: "Re-create a pixel-perfect design layout." },
          { id: "fe_m3", skillId: skillIds['javascript'], title: "Modern JavaScript (ES6+)", desc: "Learn arrows, destructuring, promises, and dynamic DOM injection.", hours: 25, playlistId: "PL4cUxeGkcC9haFPT7J25Q9GRB_Z5AlJuV", difficulty: "Beginner", docLink: "https://developer.mozilla.org", practiceProject: "Build an interactive dynamic todo application." }
        ]
      },
      {
        name: "Phase 2 — Responsive & Styling",
        duration: "6 Weeks",
        milestones: [
          { id: "fe_m4", skillId: skillIds['bootstrap'], title: "Bootstrap Framework", desc: "Utilize utility classes, container grids, and standard themes.", hours: 10, playlistId: "PL4cUxeGkcC9itC4o504sKxpW-Z5e60p1C", difficulty: "Beginner", docLink: "https://getbootstrap.com", practiceProject: "Implement a dashboard page layout using Bootstrap." },
          { id: "fe_m5", skillId: skillIds['tailwind css'], title: "Tailwind CSS", desc: "Design customized interfaces quickly using utility-first configuration.", hours: 10, playlistId: "PL4cUxeGkcC9itC4o504sKxpW-Z5e60p1C", difficulty: "Beginner", docLink: "https://tailwindcss.com", practiceProject: "Create a glassmorphism card component." }
        ]
      },
      {
        name: "Phase 3 — JavaScript Frameworks",
        duration: "8 Weeks",
        milestones: [
          { id: "fe_m6", skillId: skillIds['react'], title: "React Component Architecture", desc: "Master custom hooks, components state, and local routing.", hours: 30, playlistId: "PL4cUxeGkcC9gZD-Tvwfod2gaISzfRiP9d", difficulty: "Intermediate", docLink: "https://react.dev", practiceProject: "Build a single-page weather dashboard app." },
          { id: "fe_m7", skillId: skillIds['typescript'], title: "TypeScript Core Concepts", desc: "Implement interfaces, strict typing, and compile workflows.", hours: 20, playlistId: "PL4cUxeGkcC9haFPT7J25Q9GRB_Z5AlJuV", difficulty: "Intermediate", docLink: "https://typescriptlang.org", practiceProject: "Refactor a JS application to TypeScript." }
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
        duration: "8 Weeks",
        milestones: [
          { id: "be_m1", skillId: skillIds['python'], title: "Python Programming", desc: "Understand functions, libraries, and basic file processing.", hours: 20, playlistId: "PL-osiE80TeTskrapNbzXhwoFZuGYkmo8", difficulty: "Beginner", docLink: "https://python.org", practiceProject: "Build an automation script for CSV processing." },
          { id: "be_m2", skillId: skillIds['php'], title: "PHP Object-Oriented Backend", desc: "Learn classes, PDO database access, session management, and routing.", hours: 25, playlistId: "PLr3d3Ku5PSPw_A_cnBscs5vU0A7a1S9mS", difficulty: "Intermediate", docLink: "https://php.net", practiceProject: "Build a secure user authentication portal." }
        ]
      },
      {
        name: "Phase 2 — Relational & NoSQL Databases",
        duration: "8 Weeks",
        milestones: [
          { id: "be_m3", skillId: skillIds['mysql'], title: "MySQL Database Administration", desc: "Write joins, trigger procedures, index optimizations, and normalization.", hours: 25, playlistId: "PL0b6OzIxLPbyrzCMJofzLnf_-_5E_brvs", difficulty: "Intermediate", docLink: "https://mysql.com", practiceProject: "Design an optimized schema for an ordering catalog." },
          { id: "be_m4", skillId: skillIds['mongodb'], title: "MongoDB NoSQL Store", desc: "Work with document collections, aggregation filters, and BSON formats.", hours: 15, playlistId: "PL0b6OzIxLPbyrzCMJofzLnf_-_5E_brvs", difficulty: "Intermediate", docLink: "https://mongodb.com", practiceProject: "Integrate a document store into an application backend." }
        ]
      },
      {
        name: "Phase 3 — Server Frameworks",
        duration: "8 Weeks",
        milestones: [
          { id: "be_m5", skillId: skillIds['node.js'], title: "Node.js Core Runtime", desc: "Handle filesystem tasks, streams, child processes, and cluster forks.", hours: 25, playlistId: "PL4cUxeGkcC9haFPT7J25Q9GRB_Z5AlJuV", difficulty: "Advanced", docLink: "https://nodejs.org", practiceProject: "Create a raw HTTP network proxy gateway." }
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
        name: "Phase 1 — MVC Frameworks",
        duration: "8 Weeks",
        milestones: [
          { id: "fs_m1", skillId: skillIds['laravel'], title: "Laravel PHP Framework", desc: "Build applications with Eloquent ORM, Blade views, and migrations.", hours: 30, playlistId: "PLr3d3Ku5PSPw_A_cnBscs5vU0A7a1S9mS", difficulty: "Intermediate", docLink: "https://laravel.com", practiceProject: "Build an inventory management system." },
          { id: "fs_m2", skillId: skillIds['django'], title: "Django Python Framework", desc: "Develop secure backends with built-in admin panel interfaces.", hours: 30, playlistId: "PL-osiE80TeTskrapNbzXhwoFZuGYkmo8", difficulty: "Intermediate", docLink: "https://djangoproject.com", practiceProject: "Build a community posting board application." }
        ]
      },
      {
        name: "Phase 2 — Javascript Stack & APIs",
        duration: "8 Weeks",
        milestones: [
          { id: "fs_m3", skillId: skillIds['express.js'], title: "Express.js REST APIs", desc: "Implement middleware pipelines, routing, and JSON endpoint structures.", hours: 20, playlistId: "PLillGF-RfqbZ2ybcoVmjCQmVJ23MfmzpB", difficulty: "Intermediate", docLink: "https://expressjs.com", practiceProject: "Expose a standard CRUD API for items catalog." },
          { id: "fs_m4", skillId: skillIds['next.js'], title: "Next.js Full Stack SPA", desc: "Implement Server Components, Server Actions, and SSR pathways.", hours: 25, playlistId: "PL4cUxeGkcC9gZD-Tvwfod2gaISzfRiP9d", difficulty: "Advanced", docLink: "https://nextjs.org", practiceProject: "Deploy an SSR ecommerce client linked to backend APIs." }
        ]
      }
    ]
  }
};

let currentRoleKey = '';

window.initRoadmap = function() {
    initRoadmapPage();
};

function initRoadmapPage() {
    renderRoleCards();
    document.getElementById('roadmap-screen').style.display = 'none';
}

function renderRoleCards() {
    const container = document.getElementById('role-grid-container');
    if (!container) return;
    
    let html = '';
    for (const key in roadmaps) {
        const role = roadmaps[key];
        const isSelected = (key === currentRoleKey);
        
        let icon = 'fa-briefcase';
        if (key === 'frontend') icon = 'fa-code';
        else if (key === 'backend') icon = 'fa-server';
        else if (key === 'fullstack') icon = 'fa-layer-group';
        else if (key === 'uiux') icon = 'fa-pen-nib';
        else if (key === 'datascientist') icon = 'fa-chart-pie';
        else if (key === 'devops') icon = 'fa-network-wired';
        else if (key === 'cybersecurity') icon = 'fa-shield-halved';
        else if (key === 'mobile') icon = 'fa-mobile-screen-button';

        html += `
            <div class="role-card ${isSelected ? 'selected' : ''}" onclick="selectRole('${key}')">
                <div class="role-icon-box primary"><i class="fa-solid ${icon}"></i></div>
                <div class="role-title">${role.title}</div>
                <div class="role-desc">Personalized pathway to master ${role.title} competencies and industry tools.</div>
                <div class="role-skills">
                    <span class="role-skill-tag">${role.duration}</span>
                    <span class="role-skill-tag">${role.phases.length} Phases</span>
                </div>
                <button type="button" class="btn-select-role" onclick="selectRole('${key}'); event.stopPropagation();">
                    ${isSelected ? '<i class="fa-solid fa-circle-check"></i> Active Pathway' : '<i class="fa-solid fa-arrow-right"></i> Launch Pathway'}
                </button>
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

    renderRoleCards();
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

    // Verify whether SkillBridge currently contains courses for the selected learning pathway
    let availableCoursesCount = 0;
    role.phases.forEach(phase => {
        phase.milestones.forEach(m => {
            const mCourses = skillBridgeCourses[m.skillId] || [];
            if (mCourses.length > 0) {
                availableCoursesCount++;
            }
        });
    });

    const emptyStateEl = document.getElementById('roadmap-empty-state');
    const contentEl = document.getElementById('roadmap-content-container');

    if (availableCoursesCount === 0) {
        if (emptyStateEl) emptyStateEl.style.display = 'block';
        if (contentEl) contentEl.style.display = 'none';
        return;
    } else {
        if (emptyStateEl) emptyStateEl.style.display = 'none';
        if (contentEl) contentEl.style.display = 'block';
    }

    role.phases.forEach((phase, phaseIndex) => {
        timelineHtml += `
            <div class="roadmap-phase">
                <h4 class="roadmap-phase-title">${phase.name} <span class="text-muted small fw-normal">(${phase.duration})</span></h4>
                <div class="roadmap-milestones">
        `;

        phase.milestones.forEach((m, mIndex) => {
            totalMilestones++;
            totalHours += m.hours;

            // MATCHED SKILLBRIDGE COURSE STATUS & PROGRESS
            const mCourses = skillBridgeCourses[m.skillId] || [];
            let progress = 0;
            let status = 'todo';
            let courseHtml = '';

            const dbSkill = studentSkillsData[m.skillId];

            if (mCourses.length > 0) {
                const primaryCourse = mCourses[0];
                progress = parseInt(primaryCourse.progress || 0, 10);
                if (primaryCourse.status === 'completed' || progress >= 100) {
                    status = 'completed';
                } else if (primaryCourse.status === 'in_progress' || progress > 0) {
                    status = 'active';
                }

                // Compute dynamic duration from lesson minutes
                let durationStr = '';
                if (primaryCourse.total_duration_minutes > 0) {
                    const h = Math.floor(primaryCourse.total_duration_minutes / 60);
                    const mn = primaryCourse.total_duration_minutes % 60;
                    durationStr = h > 0 ? `${h}h ${mn}m` : `${mn}m`;
                } else {
                    durationStr = `${primaryCourse.duration}h`;
                }

                let progressFillHtml = '';
                if (status === 'active' || status === 'completed') {
                    progressFillHtml = `
                        <div class="mt-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Progress</span>
                                <span class="fw-bold text-primary">${progress}%</span>
                            </div>
                            <div class="progress" style="height: 8px; background-color: var(--border-input);">
                                <div class="progress-bar bg-primary" style="width: ${progress}%"></div>
                            </div>
                        </div>
                    `;
                }

                let badgeHtml = '';
                if (status === 'completed') {
                    badgeHtml = `<span class="badge bg-success text-white rounded-pill"><i class="fa-solid fa-circle-check me-1"></i>Completed</span>`;
                } else if (status === 'active') {
                    badgeHtml = `<span class="badge bg-primary text-white rounded-pill">Enrolled</span>`;
                }

                let buttonHtml = '';
                if (status === 'completed') {
                    buttonHtml = `
                        <a href="${BASE_URL}student/courses.php?course_id=${primaryCourse.id}" class="btn btn-outline-success btn-sm rounded-pill w-100 fw-bold py-2 text-center">
                            <i class="fa-solid fa-circle-check me-1"></i> Review Course
                        </a>
                    `;
                } else if (status === 'active') {
                    buttonHtml = `
                        <a href="${BASE_URL}student/courses.php?course_id=${primaryCourse.id}" class="btn btn-success btn-sm rounded-pill w-100 fw-bold py-2 text-center">
                            <i class="fa-solid fa-circle-play me-1"></i> Continue Learning
                        </a>
                    `;
                } else {
                    buttonHtml = `
                        <a href="${BASE_URL}student/courses.php?enroll_course_id=${primaryCourse.id}" class="btn btn-primary btn-sm rounded-pill w-100 fw-bold py-2 text-center">
                            <i class="fa-solid fa-circle-play me-1"></i> Enroll Now
                        </a>
                    `;
                }

                courseHtml = `
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden course-rec-card mt-3">
                        <div class="course-rec-thumb d-flex flex-column justify-content-between p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-white-subtle text-white border border-white-subtle rounded-pill small">SkillBridge Course</span>
                                ${badgeHtml}
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-white">
                                <span class="badge bg-primary text-white rounded-pill small text-capitalize">${primaryCourse.difficulty}</span>
                                <span class="small"><i class="fa-regular fa-clock me-1"></i>${durationStr} • ${primaryCourse.lessons_count} Lessons</span>
                            </div>
                        </div>
                        <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">${escapeHtml(primaryCourse.title)}</h6>
                                <div class="text-muted small mb-1"><i class="fa-solid fa-user-tie me-1"></i>${escapeHtml(primaryCourse.instructor)}</div>
                                ${progressFillHtml}
                            </div>
                            <div class="pt-3 border-top mt-3">
                                ${buttonHtml}
                            </div>
                        </div>
                    </div>
                `;
            } else {
                courseHtml = `
                    <div class="alert alert-light border small text-muted rounded-3 p-3 d-flex align-items-center gap-2 mt-3 mb-0">
                        <i class="fa-solid fa-circle-info text-secondary fs-6"></i> Course not available.
                    </div>
                `;
            }

            if (status === 'completed') {
                completedMilestones++;
                completedHours += m.hours;
            }

            const statusBadge = status === 'completed' 
                ? '<span class="badge bg-success-subtle text-success border border-success-subtle">Completed</span>'
                : (status === 'active' 
                    ? '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">In Progress</span>'
                    : '<span class="badge bg-light text-muted border">To Do</span>');

            const diffClass = m.difficulty === 'Beginner' ? 'diff-beginner' : (m.difficulty === 'Intermediate' ? 'diff-intermediate' : 'diff-advanced');
            const savedNote = localStorage.getItem('m_note_' + m.id) || '';

            timelineHtml += `
                <div class="roadmap-milestone ${status}" id="milestone-card-${m.id}">
                    <div class="roadmap-dot ${status}">${status === 'completed' ? '<i class="fa-solid fa-check"></i>' : mIndex + 1}</div>
                    
                    <div class="milestone-info">
                        <div class="milestone-title-wrapper">
                            <div class="milestone-title">${m.title}</div>
                            ${statusBadge}
                        </div>
                        <p class="milestone-desc">${m.desc}</p>
                        
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                            <span class="diff-badge ${diffClass}">${m.difficulty}</span>
                            ${dbSkill ? `<span class="badge bg-info-subtle text-info border">DB Score: ${Math.round(dbSkill.score)}%</span>` : ''}
                        </div>

                        ${m.practiceProject ? `
                            <div class="practice-project-container">
                                <span class="practice-project-header"><i class="fa-solid fa-laptop-code me-1"></i> Practice Project</span>
                                <div class="practice-project-body">${m.practiceProject}</div>
                            </div>
                        ` : ''}

                        ${courseHtml}

                        <!-- Notes Section -->
                        <div class="mt-2 border-top pt-2">
                            <button class="btn btn-link p-0 text-muted small text-decoration-none" onclick="toggleNotes('${m.id}')">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Personal Notes ${savedNote ? '<i class="fa-solid fa-check text-success ms-1"></i>' : ''}
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

    const careerMatch = dbCareerMatch;
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
