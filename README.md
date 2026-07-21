# SkillBridge – Skill Gap Analysis and Learning Management System

SkillBridge is a web-based **Skill Gap Analysis and Learning Management System (LMS)** developed for educational institutions. The system enables students to evaluate their technical and professional skills through online assessments with live timers. Based on assessment performance, the system automatically identifies skill gaps, recommends suitable learning resources and courses, tracks student progress, and provides faculty and administrators with real-time analytical reports and radar charts.

---

## Technology Stack

- **Backend Logic:** PHP 8.x (Pure PHP, OOP, PDO Database Singleton)
- **Database:** MySQL 8.x / MariaDB ( central `skillbridge_db` with 19 normalized tables, foreign key constraints, and 100+ seed records)
- **Frontend Architecture:** HTML5, CSS3, Bootstrap 5, JavaScript (ES6), AJAX
- **Analytics & Data Visualizations:** Chart.js 4.4 (Radar, Bar, Doughnut charts)
- **Web Server:** Apache (XAMPP / WAMP / LAMP)

---

## Directory Architecture

```text
SkillBridge/
├── config/
│   ├── config.php               # Base path, session config, app constants
│   └── database.php             # Singleton PDO database wrapper class
├── includes/
│   ├── header.php               # Boilerplate, CSS/JS CDNs, notification alerts
│   ├── footer.php               # Layout footer & script bundles
│   ├── navbar.php               # Top navigation bar & notifications dropdown
│   ├── sidebar.php              # Role-aware dynamic navigation sidebar
│   ├── auth.php                 # Authentication & role authorization guards
│   ├── functions.php            # Helper functions, skill gap formula, recommendation engine
│   └── validators.php           # CSRF token protection & input validators
├── assets/
│   ├── css/
│   │   └── style.css            # Custom CSS token design system & stats cards
│   ├── js/
│   │   ├── app.js               # Global UI interactions, sidebar toggle & notifications
│   │   ├── assessment-timer.js  # Live quiz countdown timer & periodic auto-save
│   │   └── charts-config.js     # Chart.js renderers (Radar, Bar, Doughnut)
│   └── images/
├── uploads/
│   └── avatars/                 # Profile avatars storage directory
├── student/
│   ├── dashboard.php            # Student main dashboard (charts, metrics, recommendations)
│   ├── assessments.php          # Available online assessments list
│   ├── take-assessment.php     # Live quiz taking interface with countdown timer
│   ├── assessment-result.php    # Detailed result breakdown & answer review
│   ├── history.php              # Historical assessment log
│   ├── skill-gap.php            # Detailed skill gap breakdown & radar chart
│   ├── recommendations.php      # Personalized course suggestions based on weak skills
│   ├── progress.php             # Learning progress tracker per course
│   └── profile.php              # Profile management & avatar upload
├── faculty/
│   ├── dashboard.php            # Faculty dashboard (class averages, submissions)
│   ├── assessments.php          # Assessment management (List & status toggle)
│   ├── assessment-create.php    # Create new assessment form
│   ├── assessment-edit.php      # Edit assessment details
│   ├── question-bank.php        # Manage questions per assessment (CRUD 4-choice questions)
│   ├── students.php             # Enrolled student roster
│   ├── evaluate.php             # Detailed score breakdown & student performance viewer
│   ├── skill-gap.php            # Class-wide skill gap analytics & deficit heatmap
│   ├── recommend-courses.php    # Assign course recommendations manually
│   └── profile.php              # Faculty profile settings
├── admin/
│   ├── dashboard.php            # Admin global stats, entity counts, audit trail
│   ├── students.php             # Full CRUD for Student accounts
│   ├── faculty.php              # Full CRUD for Faculty accounts
│   ├── courses.php              # Full CRUD for Courses & Skill tags
│   ├── skills.php               # Full CRUD for Technical Skills catalog
│   ├── assessments.php          # System-wide assessment oversight
│   ├── notifications.php        # Broadcast announcement system
│   ├── reports.php              # Institutional reports exporter
│   ├── analytics.php            # Advanced system-wide skill metrics
│   ├── settings.php             # System settings editor
│   ├── activity-logs.php        # Audit trail log viewer
│   ├── backup.php               # One-click SQL database exporter
│   └── profile.php              # Admin profile settings
├── api/
│   ├── notifications.php        # AJAX mark as read / delete notifications
│   ├── auto-save.php            # Mid-assessment auto-save endpoint
│   ├── search.php               # Dynamic live search API
│   └── analytics.php            # Real-time Chart.js data endpoint
├── reports/
│   └── export.php               # Exporter engine (Printable view, CSV stream, PDF layout)
├── sql/
│   └── skillbridge_db.sql       # Full schema & 100+ seed records
├── index.php                    # Landing page / dynamic role router
├── login.php                    # Multi-role secure login form with Remember Me
├── register.php                 # Student registration page
├── logout.php                   # Session teardown
├── forgot-password.php          # Password reset request
├── reset-password.php           # Token-verified password reset
└── README.md                    # Setup documentation
```

---

## Setup & Installation Guide (XAMPP)

1. **Copy Repository to `htdocs`**:
   Place the project folder into your XAMPP web root directory:
   `C:\xampp\htdocs\Skill Gap Analysis\Skill-Gap-Analysis-System\` (or `C:\xampp\htdocs\SkillBridge\`).

2. **Start Apache & MySQL in XAMPP**:
   Open the XAMPP Control Panel and start **Apache** and **MySQL**.

3. **Import MySQL Database**:
   - Open phpMyAdmin in browser: `http://localhost/phpmyadmin/`
   - Click **Import** tab.
   - Choose file `sql/skillbridge_db.sql` from the project repository.
   - Click **Go** to create the `skillbridge_db` database, 19 normalized tables, foreign key constraints, and seed data.

4. **Launch Application in Browser**:
   Navigate to:
   `http://localhost/Skill Gap Analysis/Skill-Gap-Analysis-System/` (or your configured local URL).

---

## Pre-Configured Seed Login Credentials

All seed accounts are initialized with the default password: **`Password123!`**

| User Role | Username / Email | Default Password | Initial Dashboard |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` / `admin@skillbridge.edu` | `Password123!` | `/admin/dashboard.php` |
| **Faculty** | `f_turing` / `faculty1@skillbridge.edu` | `Password123!` | `/faculty/dashboard.php` |
| **Student** | `s_john` / `student1@skillbridge.edu` | `Password123!` | `/student/dashboard.php` |

---

## Skill Gap Calculation Formula

When a student completes an assessment tied to a technical skill:
1. **Score Percentage ($P$):**
   $$P = \left( \frac{\text{Obtained Marks}}{\text{Total Marks}} \right) \times 100$$
2. **Achieved Skill Level ($L_{\text{actual}}$):**
   $$L_{\text{actual}} = \min\left(5, \max\left(1, \left\lceil \frac{P}{20} \right\rceil\right)\right)$$
3. **Target Skill Benchmark ($L_{\text{target}}$):** Benchmark level 4 (Proficient).
4. **Skill Gap Percentage ($G$):**
   $$G = \max(0, 100 - P)$$
5. **Automated Action:** If $P < 60\%$, the system automatically identifies matching courses in `course_skills` covering that skill deficit and inserts a high-priority recommendation record.

---

## Verification & Features List

- **Multi-Role Authentication:** Student Registration, Login, Logout, Forgot Password token reset, Remember Me cookie.
- **Student Portal:** Interactive quiz taking with countdown timer, score breakdown, radar chart visualization, course recommendations, progress tracking.
- **Faculty Portal:** Assessment creation, 4-choice Question Bank builder, student roster evaluation, class-wide skill gap heatmaps, course recommendation assignment.
- **Administrator Portal:** Account management (Students/Faculty), Course catalog, Skills registry, Announcements, Reports, Settings, Activity audit trail, One-click SQL backup exporter.

---

## License & Support
Developed for educational institutional deployment. Built using standard pure PHP 8.x and MySQL.
