-- ============================================================
-- SkillBridge: Update Course Lesson Video URLs
-- Replace placeholder dQw4w9WgXcQ with real educational videos
-- Sources: freeCodeCamp, Traversy Media, Programming with Mosh,
--          Net Ninja, Bro Code, Dave Gray, Academind, Fireship,
--          Kevin Powell, Web Dev Simplified, Tech With Tim, etc.
-- ============================================================

-- ============================================================
-- CS-101: Mastering Pure PHP 8 Development
-- Source: freeCodeCamp PHP full course + Traversy Media PHP
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. PHP 8 Introduction & Environment Setup',
  `description` = 'Install PHP 8, configure your development environment, and write your first PHP script.',
  `video_url` = 'https://www.youtube.com/embed/OK_JCtrrv-c'
WHERE `id` = 1;

UPDATE `lessons` SET
  `title` = '2. PHP Functions, Arrays & OOP Basics',
  `description` = 'Master PHP functions, arrays, loops, and an introduction to object-oriented programming concepts.',
  `video_url` = 'https://www.youtube.com/embed/pWG7ajC_OVo'
WHERE `id` = 2;

UPDATE `lessons` SET
  `title` = '3. PHP & MySQL — Building a Full Web App',
  `description` = 'Build a complete PHP 8 CRUD application connected to MySQL with sessions, forms, and validation.',
  `video_url` = 'https://www.youtube.com/embed/3DMMPA3uxBo'
WHERE `id` = 3;

-- ============================================================
-- CS-102: Relational Database Masterclass (MySQL)
-- Source: Programming with Mosh MySQL + freeCodeCamp SQL
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. SQL & MySQL Fundamentals — Queries & Tables',
  `description` = 'Learn SQL syntax, how to create databases, tables, and run basic SELECT queries in MySQL.',
  `video_url` = 'https://www.youtube.com/embed/7S_tz1z_5bA'
WHERE `id` = 4;

UPDATE `lessons` SET
  `title` = '2. Joins, Subqueries & Aggregate Functions',
  `description` = 'Master INNER JOIN, LEFT JOIN, GROUP BY, HAVING, and complex subqueries in MySQL.',
  `video_url` = 'https://www.youtube.com/embed/p3qvj9hO_Bo'
WHERE `id` = 5;

UPDATE `lessons` SET
  `title` = '3. Database Design, Indexes & Stored Procedures',
  `description` = 'Design normalized relational schemas, use indexes for performance, and write stored procedures and triggers.',
  `video_url` = 'https://www.youtube.com/embed/ER8oKX5myE0'
WHERE `id` = 6;

-- ============================================================
-- CS-103: Modern JavaScript ES6+ Mastery
-- Source: Net Ninja JS ES6 + freeCodeCamp JS
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Modern JavaScript ES6 — Let, Const, Arrow Functions & Destructuring',
  `description` = 'Understand ES6+ fundamentals: let/const, template literals, arrow functions, and destructuring.',
  `video_url` = 'https://www.youtube.com/embed/NCwa_xi0Uuc'
WHERE `id` = 7;

UPDATE `lessons` SET
  `title` = '2. Promises, Async/Await & the Fetch API',
  `description` = 'Handle asynchronous JavaScript with Promises, async/await, and fetch data from REST APIs.',
  `video_url` = 'https://www.youtube.com/embed/DHvZLI7Db8E'
WHERE `id` = 8;

UPDATE `lessons` SET
  `title` = '3. JavaScript Modules, Classes & Modern Tooling',
  `description` = 'Work with ES6 modules, classes, iterators, generators, and modern bundling with Webpack/Vite.',
  `video_url` = 'https://www.youtube.com/embed/lI1ae4REbFM'
WHERE `id` = 9;

-- ============================================================
-- CS-104: Responsive Design with Bootstrap 5
-- Source: Traversy Media Bootstrap 5 Crash Course
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Bootstrap 5 — Grid System & Utility Classes',
  `description` = 'Set up Bootstrap 5, learn the 12-column grid system, breakpoints, and core utility classes.',
  `video_url` = 'https://www.youtube.com/embed/4sosXZsdy-s'
WHERE `id` = 10;

UPDATE `lessons` SET
  `title` = '2. Bootstrap 5 Components — Navbar, Cards & Forms',
  `description` = 'Build responsive navbars, cards, modals, forms, and interactive components with Bootstrap 5.',
  `video_url` = 'https://www.youtube.com/embed/rQryOSyfXmI'
WHERE `id` = 11;

UPDATE `lessons` SET
  `title` = '3. Bootstrap 5 — Building a Complete Responsive Website',
  `description` = 'Apply Bootstrap 5 to build a full responsive landing page with custom CSS overrides.',
  `video_url` = 'https://www.youtube.com/embed/Jyvffr3aCp0'
WHERE `id` = 12;

-- ============================================================
-- CS-105: Web Security Essentials & OWASP
-- Source: freeCodeCamp Web Security + Hussein Nasser
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. OWASP Top 10 — Understanding Web Vulnerabilities',
  `description` = 'Explore the OWASP Top 10 most critical web application security risks and how attackers exploit them.',
  `video_url` = 'https://www.youtube.com/embed/t0IT914i3TU'
WHERE `id` = 13;

UPDATE `lessons` SET
  `title` = '2. SQL Injection, XSS & CSRF Attacks & Defenses',
  `description` = 'Deep dive into SQL Injection, Cross-Site Scripting (XSS), and CSRF with hands-on defense techniques.',
  `video_url` = 'https://www.youtube.com/embed/WtHnT73NaaQ'
WHERE `id` = 14;

UPDATE `lessons` SET
  `title` = '3. HTTPS, Authentication Security & Security Headers',
  `description` = 'Implement HTTPS, secure password hashing, JWT authentication, and critical HTTP security headers.',
  `video_url` = 'https://www.youtube.com/embed/F5KJVuii0Yw'
WHERE `id` = 15;

-- ============================================================
-- CS-106: RESTful API Engineering in PHP
-- Source: Traversy Media PHP REST API
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. REST API Fundamentals & HTTP Methods in PHP',
  `description` = 'Understand REST principles, HTTP verbs (GET, POST, PUT, DELETE), and build your first PHP API endpoint.',
  `video_url` = 'https://www.youtube.com/embed/OEWXbpUMODk'
WHERE `id` = 16;

UPDATE `lessons` SET
  `title` = '2. PHP REST API — CRUD Operations & JSON Responses',
  `description` = 'Implement full CRUD operations in a PHP REST API with proper JSON responses and status codes.',
  `video_url` = 'https://www.youtube.com/embed/eyvRc9XSqMw'
WHERE `id` = 17;

UPDATE `lessons` SET
  `title` = '3. PHP REST API — Authentication, JWT & Security',
  `description` = 'Secure your PHP REST API with JWT tokens, API keys, rate limiting, and CORS configuration.',
  `video_url` = 'https://www.youtube.com/embed/T-Pum2TraX4'
WHERE `id` = 18;

-- ============================================================
-- CS-107: Data Structures & Algorithms
-- Source: freeCodeCamp DSA + CS Dojo
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Arrays, Linked Lists & Big O Notation',
  `description` = 'Master arrays, linked lists, stacks, queues, and understand time/space complexity with Big O notation.',
  `video_url` = 'https://www.youtube.com/embed/BBpAmxU_NQo'
WHERE `id` = 19;

UPDATE `lessons` SET
  `title` = '2. Trees, Graphs & Sorting Algorithms',
  `description` = 'Explore binary trees, BSTs, graphs, BFS/DFS traversal, and implement sorting algorithms from scratch.',
  `video_url` = 'https://www.youtube.com/embed/pkYVOmU3MgA'
WHERE `id` = 20;

UPDATE `lessons` SET
  `title` = '3. Dynamic Programming & Algorithm Design Patterns',
  `description` = 'Solve complex problems using dynamic programming, memoization, greedy algorithms, and divide-and-conquer.',
  `video_url` = 'https://www.youtube.com/embed/oBt53YbR9Kk'
WHERE `id` = 21;

-- ============================================================
-- CS-108: Object-Oriented Software Architecture
-- Source: Programming with Mosh OOP + freeCodeCamp Design Patterns
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. OOP Principles — Classes, Encapsulation & Inheritance',
  `description` = 'Master the four OOP pillars: encapsulation, abstraction, inheritance, and polymorphism with real examples.',
  `video_url` = 'https://www.youtube.com/embed/pTB0EiLXUC8'
WHERE `id` = 22;

UPDATE `lessons` SET
  `title` = '2. SOLID Principles & Clean Architecture',
  `description` = 'Apply SOLID design principles to write maintainable, extensible, and testable object-oriented code.',
  `video_url` = 'https://www.youtube.com/embed/_jDNAkmINF0'
WHERE `id` = 23;

UPDATE `lessons` SET
  `title` = '3. Design Patterns — Creational, Structural & Behavioral',
  `description` = 'Implement the most important GoF design patterns including Singleton, Factory, Observer, and Strategy.',
  `video_url` = 'https://www.youtube.com/embed/tv-_1er1mWI'
WHERE `id` = 24;

-- ============================================================
-- CS-109: Git & GitHub Collaboration Workflow
-- Source: Traversy Media Git Crash Course + freeCodeCamp Git
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Git Fundamentals — Init, Commit, Branch & Merge',
  `description` = 'Install Git, initialize repositories, make commits, create branches, and perform merges.',
  `video_url` = 'https://www.youtube.com/embed/RGOj5yH7evk'
WHERE `id` = 25;

UPDATE `lessons` SET
  `title` = '2. GitHub — Remote Repos, Pull Requests & Code Reviews',
  `description` = 'Push to GitHub, work with remote repositories, fork projects, and collaborate via Pull Requests.',
  `video_url` = 'https://www.youtube.com/embed/SWYqp7iY_Tc'
WHERE `id` = 26;

UPDATE `lessons` SET
  `title` = '3. Git Workflows — Rebasing, Cherry-pick & CI/CD Integration',
  `description` = 'Master advanced Git workflows: rebase, cherry-pick, Git Flow, and integrate with GitHub Actions CI/CD.',
  `video_url` = 'https://www.youtube.com/embed/Uszj_k0DGsg'
WHERE `id` = 27;

-- ============================================================
-- CS-110: UI/UX Fundamentals
-- Source: Figma Tutorial + UX Design Crash Course
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. UX Design Principles — Research, Wireframing & Prototyping',
  `description` = 'Learn UX research methods, create wireframes, and build interactive prototypes using Figma.',
  `video_url` = 'https://www.youtube.com/embed/c9Wg6RyOxjU'
WHERE `id` = 28;

UPDATE `lessons` SET
  `title` = '2. Figma Masterclass — Components, Auto Layout & Design Systems',
  `description` = 'Build reusable Figma components, master Auto Layout, and create a consistent design system.',
  `video_url` = 'https://www.youtube.com/embed/FTFaQWZBqQ8'
WHERE `id` = 29;

UPDATE `lessons` SET
  `title` = '3. UI Design — Color Theory, Typography & Accessibility',
  `description` = 'Apply color theory, typography best practices, and WCAG accessibility guidelines to UI design.',
  `video_url` = 'https://www.youtube.com/embed/yNDgFK2Jj1E'
WHERE `id` = 30;

-- ============================================================
-- CS-111: Python for Software Automation
-- Source: freeCodeCamp Python + Tech With Tim
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Python Fundamentals — Variables, Loops & Functions',
  `description` = 'Learn Python syntax, data types, control flow, functions, and working with modules.',
  `video_url` = 'https://www.youtube.com/embed/rfscVS0vtbw'
WHERE `id` = 31;

UPDATE `lessons` SET
  `title` = '2. Python — File Automation, OS Module & Scripting',
  `description` = 'Automate file system tasks, work with the os module, write scripts, and use regular expressions.',
  `video_url` = 'https://www.youtube.com/embed/s3lrgez5pls'
WHERE `id` = 32;

UPDATE `lessons` SET
  `title` = '3. Python — Web Scraping, APIs & Task Automation',
  `description` = 'Use Requests, BeautifulSoup, and schedule libraries to build powerful automation pipelines.',
  `video_url` = 'https://www.youtube.com/embed/ycdptosWgFc'
WHERE `id` = 33;

-- ============================================================
-- CS-112: Docker Container Essentials
-- Source: TechWorld with Nana Docker + freeCodeCamp Docker
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Docker Introduction — Containers, Images & Dockerfile',
  `description` = 'Understand containerization, install Docker, build images with Dockerfile, and run containers.',
  `video_url` = 'https://www.youtube.com/embed/pg19Z8LL06w'
WHERE `id` = 34;

UPDATE `lessons` SET
  `title` = '2. Docker Compose — Multi-Container Applications',
  `description` = 'Define and run multi-container applications with Docker Compose, volumes, and networking.',
  `video_url` = 'https://www.youtube.com/embed/DM65_JyGxCo'
WHERE `id` = 35;

UPDATE `lessons` SET
  `title` = '3. Docker in Production — Registry, CI/CD & Best Practices',
  `description` = 'Push images to Docker Hub, integrate with CI/CD pipelines, and apply production-grade best practices.',
  `video_url` = 'https://www.youtube.com/embed/3c-iBn73dDE'
WHERE `id` = 36;

-- ============================================================
-- CS-113: React Frontend Foundations
-- Source: Dave Gray React Course + freeCodeCamp React
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. React Fundamentals — Components, Props & JSX',
  `description` = 'Set up a React project with Vite, create components, pass props, and understand JSX syntax.',
  `video_url` = 'https://www.youtube.com/embed/RVFAyFWO4go'
WHERE `id` = 37;

UPDATE `lessons` SET
  `title` = '2. React Hooks — useState, useEffect & Custom Hooks',
  `description` = 'Master React Hooks: useState for state management, useEffect for side effects, and build custom hooks.',
  `video_url` = 'https://www.youtube.com/embed/O6P86uwfdR0'
WHERE `id` = 38;

UPDATE `lessons` SET
  `title` = '3. React Router, Context API & Building a Full App',
  `description` = 'Implement React Router v6, global state with Context API, and build a complete React application.',
  `video_url` = 'https://www.youtube.com/embed/w7ejDZ8SWv8'
WHERE `id` = 39;

-- ============================================================
-- CS-114: Cloud Infrastructure Fundamentals (AWS)
-- Source: freeCodeCamp AWS + TechWorld with Nana Cloud
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Cloud Computing & AWS Core Services Overview',
  `description` = 'Understand cloud computing models (IaaS, PaaS, SaaS), AWS global infrastructure, and core services.',
  `video_url` = 'https://www.youtube.com/embed/NhDYbskXRgc'
WHERE `id` = 40;

UPDATE `lessons` SET
  `title` = '2. AWS EC2, S3, RDS & IAM Hands-On',
  `description` = 'Launch EC2 instances, store files in S3, configure RDS databases, and set up IAM roles and policies.',
  `video_url` = 'https://www.youtube.com/embed/ulprqHHWlng'
WHERE `id` = 41;

UPDATE `lessons` SET
  `title` = '3. AWS Deployment — Elastic Beanstalk, Lambda & CloudFormation',
  `description` = 'Deploy apps with Elastic Beanstalk, run serverless functions with Lambda, and use CloudFormation IaC.',
  `video_url` = 'https://www.youtube.com/embed/SOTamWNgDKc'
WHERE `id` = 42;

-- ============================================================
-- CS-115: Automated Software Testing & TDD
-- Source: Traversy Media Testing + freeCodeCamp TDD
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Software Testing Fundamentals — Unit, Integration & E2E',
  `description` = 'Understand different testing types, the testing pyramid, and write your first unit tests.',
  `video_url` = 'https://www.youtube.com/embed/r9HdJ8P6GQI'
WHERE `id` = 43;

UPDATE `lessons` SET
  `title` = '2. Test-Driven Development (TDD) — Red, Green, Refactor',
  `description` = 'Practice the TDD cycle: write failing tests first, make them pass, then refactor with confidence.',
  `video_url` = 'https://www.youtube.com/embed/Jv2uxzhPFl4'
WHERE `id` = 44;

UPDATE `lessons` SET
  `title` = '3. PHPUnit & Jest — Testing in CI/CD Pipelines',
  `description` = 'Write tests with PHPUnit for PHP and Jest for JavaScript, then integrate testing into CI/CD pipelines.',
  `video_url` = 'https://www.youtube.com/embed/ajiAl5UNsZQ'
WHERE `id` = 45;

-- ============================================================
-- CS-116: Asynchronous Node.js & Express
-- Source: Traversy Media Node.js Crash Course + Dave Gray
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Node.js Fundamentals — Event Loop, Modules & npm',
  `description` = 'Understand the Node.js event loop, CommonJS modules, npm ecosystem, and working with the file system.',
  `video_url` = 'https://www.youtube.com/embed/fBNz5xF-Kx4'
WHERE `id` = 46;

UPDATE `lessons` SET
  `title` = '2. Express.js — Routing, Middleware & REST APIs',
  `description` = 'Build a REST API with Express.js, use middleware, handle routing, and work with request/response objects.',
  `video_url` = 'https://www.youtube.com/embed/L72fhGm1tfE'
WHERE `id` = 47;

UPDATE `lessons` SET
  `title` = '3. Node.js — Authentication, MongoDB & Deployment',
  `description` = 'Add JWT authentication, connect to MongoDB with Mongoose, and deploy your Node.js app to production.',
  `video_url` = 'https://www.youtube.com/embed/ENrzD9HAZK4'
WHERE `id` = 48;

-- ============================================================
-- CS-117: Linux Command Line Administration
-- Source: freeCodeCamp Linux + NetworkChuck Linux
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Linux Command Line Basics — Navigation, Files & Permissions',
  `description` = 'Navigate the Linux file system, manage files and directories, and understand user permissions and chmod.',
  `video_url` = 'https://www.youtube.com/embed/ZtqBQ68cfJc'
WHERE `id` = 49;

UPDATE `lessons` SET
  `title` = '2. Linux Shell Scripting — Bash Automation & Cron Jobs',
  `description` = 'Write Bash shell scripts, use variables, loops, and conditionals to automate repetitive system tasks.',
  `video_url` = 'https://www.youtube.com/embed/tK9Oc6AEnR4'
WHERE `id` = 50;

UPDATE `lessons` SET
  `title` = '3. Linux System Administration — Processes, Networking & Services',
  `description` = 'Manage Linux processes, configure networking, work with systemd services, and monitor system health.',
  `video_url` = 'https://www.youtube.com/embed/wBp0Rb-ZJak'
WHERE `id` = 51;

-- ============================================================
-- CS-118: Agile Product Delivery & Scrum
-- Source: freeCodeCamp Agile + Scrum.org resources
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Agile Fundamentals — Manifesto, Principles & Mindset',
  `description` = 'Understand the Agile Manifesto, its 12 principles, and how Agile thinking transforms software delivery.',
  `video_url` = 'https://www.youtube.com/embed/8eVXTyIZ1Hs'
WHERE `id` = 52;

UPDATE `lessons` SET
  `title` = '2. Scrum Framework — Roles, Events & Artifacts',
  `description` = 'Learn Scrum roles (Product Owner, Scrum Master, Dev Team), ceremonies, and artifacts like the Sprint Backlog.',
  `video_url` = 'https://www.youtube.com/embed/2Vt7Ik8Ublw'
WHERE `id` = 53;

UPDATE `lessons` SET
  `title` = '3. Agile Estimation, Kanban & Scaling Frameworks',
  `description` = 'Use story points, Kanban boards, velocity tracking, and explore scaling frameworks like SAFe and LeSS.',
  `video_url` = 'https://www.youtube.com/embed/iVaFVa7HYj4'
WHERE `id` = 54;

-- ============================================================
-- CS-119: Practical Cyber Security Defenses
-- Source: freeCodeCamp Ethical Hacking + NetworkChuck
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Ethical Hacking & Penetration Testing Fundamentals',
  `description` = 'Learn ethical hacking methodology, set up Kali Linux, and understand the penetration testing lifecycle.',
  `video_url` = 'https://www.youtube.com/embed/3Kq1MIfTWCE'
WHERE `id` = 55;

UPDATE `lessons` SET
  `title` = '2. Network Security — Firewalls, VPNs & Intrusion Detection',
  `description` = 'Configure firewalls, understand VPN protocols, analyze network traffic with Wireshark, and set up IDS.',
  `video_url` = 'https://www.youtube.com/embed/qiQR5rTSshw'
WHERE `id` = 56;

UPDATE `lessons` SET
  `title` = '3. Incident Response, Cryptography & Security Operations',
  `description` = 'Build a security operations workflow, implement cryptography, and establish incident response procedures.',
  `video_url` = 'https://www.youtube.com/embed/AQDCe585Lnc'
WHERE `id` = 57;

-- ============================================================
-- CS-120: Full Stack Web Architecture Capstone
-- Source: Traversy Media MERN Stack + freeCodeCamp Full Stack
-- ============================================================
UPDATE `lessons` SET
  `title` = '1. Full Stack Architecture — Planning, Tech Stack & Project Setup',
  `description` = 'Design a full stack web application, choose the right tech stack, and set up the complete project structure.',
  `video_url` = 'https://www.youtube.com/embed/7CqJlxBYj-M'
WHERE `id` = 58;

UPDATE `lessons` SET
  `title` = '2. Full Stack Development — Backend API, Database & Frontend Integration',
  `description` = 'Build the backend API, connect to the database, and integrate the frontend with the REST API.',
  `video_url` = 'https://www.youtube.com/embed/ngc9gnGgUdA'
WHERE `id` = 59;
