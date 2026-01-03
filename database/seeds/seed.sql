-- SchoolDream+ Seed Data

-- Insert Admin User (password: admin123)
INSERT INTO users (name, email, password, role, created_at) VALUES
('Admin User', 'admin@schooldream.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW());

-- Insert Instructors (password: instructor123)
INSERT INTO users (name, email, password, role, instructor_status, bio, created_at) VALUES
('Dr. Jean-Paul Munyaneza', 'jpmunyaneza@schooldream.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'approved', 'Computer Science PhD with 15 years of teaching experience in ICT and software development.', NOW()),
('Prof. Grace Uwase', 'guwase@schooldream.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'approved', 'Business Administration expert specializing in entrepreneurship and digital marketing.', NOW()),
('Eng. Patrick Nkubito', 'pnkubito@schooldream.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'approved', 'Software engineer with expertise in web development, mobile apps, and cloud computing.', NOW()),
('Sarah Mukamana', 'smukamana@schooldream.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'pending', 'Data scientist passionate about AI and machine learning applications.', NOW());

-- Insert Students (password: student123)
INSERT INTO users (name, email, password, role, created_at) VALUES
('Alice Kabera', 'alice@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW()),
('Bob Mugisha', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW()),
('Claire Uwimana', 'claire@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW()),
('David Habimana', 'david@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW()),
('Emma Ingabire', 'emma@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW());

-- Insert Courses
INSERT INTO courses (instructor_id, title, description, category, price, duration, level, status, learning_outcomes, created_at) VALUES
(2, 'Introduction to ICT and Internet Technologies', 'Comprehensive course covering fundamental ICT concepts, internet technologies, computer hardware and software, networking basics, and digital literacy. Perfect for beginners looking to understand modern technology.', 'Information Technology', 12000.00, '4 weeks', 'beginner', 'published', 'Understand computer hardware and software components\nNavigate the internet safely and effectively\nUse productivity software (Word, Excel, PowerPoint)\nUnderstand networking and internet protocols\nApply digital security best practices', NOW()),
(2, 'Web Development Fundamentals', 'Learn to build modern, responsive websites from scratch using HTML5, CSS3, and JavaScript. This course includes hands-on projects and real-world examples.', 'Web Development', 25000.00, '8 weeks', 'beginner', 'published', 'Build responsive websites with HTML5 and CSS3\nCreate interactive web pages with JavaScript\nUnderstand web standards and best practices\nDeploy websites to the internet\nUse version control with Git', NOW()),
(3, 'Mobile App Development with Flutter', 'Master mobile app development using Flutter framework. Build beautiful, native applications for iOS and Android from a single codebase.', 'Mobile Development', 35000.00, '10 weeks', 'intermediate', 'published', 'Build cross-platform mobile apps with Flutter\nUnderstand Dart programming language\nImplement complex UI designs\nIntegrate APIs and databases\nPublish apps to App Store and Play Store', NOW()),
(3, 'Cloud Computing with AWS', 'Learn to deploy and manage applications on Amazon Web Services. Cover EC2, S3, RDS, Lambda, and other essential AWS services.', 'Cloud Computing', 45000.00, '12 weeks', 'intermediate', 'published', 'Deploy applications to AWS cloud\nManage cloud infrastructure\nImplement auto-scaling and load balancing\nSecure cloud applications\nOptimize cloud costs', NOW()),
(3, 'Digital Marketing Mastery', 'Complete guide to digital marketing including SEO, social media marketing, content marketing, email campaigns, and analytics.', 'Marketing', 18000.00, '6 weeks', 'beginner', 'published', 'Create effective digital marketing strategies\nOptimize websites for search engines\nManage social media campaigns\nAnalyze marketing data and metrics\nBuild email marketing funnels', NOW()),
(4, 'Data Science with Python', 'Introduction to data science using Python, covering data analysis, visualization, statistics, and machine learning basics.', 'Data Science', 40000.00, '12 weeks', 'intermediate', 'pending', 'Analyze data with Python and Pandas\nCreate data visualizations\nApply statistical methods\nBuild machine learning models\nWork with real-world datasets', NOW()),
(2, 'Introduction to Programming', 'Learn programming fundamentals with Python. Perfect for absolute beginners with no prior coding experience.', 'Programming', 0.00, '4 weeks', 'beginner', 'published', 'Understand programming concepts\nWrite Python programs\nSolve problems with code\nDebug and test programs\nBuild simple applications', NOW());

-- Insert Lessons for Course 1 (Introduction to ICT)
INSERT INTO lessons (course_id, title, description, content_type, content_text, duration, order_index, is_free, created_at) VALUES
(1, 'Welcome to ICT', 'Introduction to the course and overview of topics we will cover', 'text', '<h2>Welcome to Introduction to ICT and Internet Technologies!</h2><p>This course will take you on a journey through the fascinating world of information and communication technology.</p><h3>What you will learn:</h3><ul><li>Computer hardware and software fundamentals</li><li>Internet and networking basics</li><li>Digital literacy and productivity tools</li><li>Online safety and security</li></ul><p>Let us get started!</p>', 15, 1, TRUE, NOW()),
(1, 'Understanding Computer Hardware', 'Learn about the physical components of computers', 'text', '<h2>Computer Hardware Components</h2><p>A computer system consists of various hardware components working together:</p><h3>Main Components:</h3><ul><li><strong>CPU (Central Processing Unit):</strong> The brain of the computer</li><li><strong>RAM (Random Access Memory):</strong> Temporary storage for running programs</li><li><strong>Hard Drive/SSD:</strong> Permanent data storage</li><li><strong>Motherboard:</strong> Connects all components together</li><li><strong>Power Supply:</strong> Provides electricity to components</li></ul><h3>Peripheral Devices:</h3><ul><li>Input devices: Keyboard, Mouse, Scanner</li><li>Output devices: Monitor, Printer, Speakers</li></ul>', 30, 2, TRUE, NOW()),
(1, 'Computer Software Essentials', 'Understanding different types of software', 'text', '<h2>Types of Software</h2><h3>System Software:</h3><ul><li>Operating Systems (Windows, macOS, Linux)</li><li>Device Drivers</li><li>Utilities</li></ul><h3>Application Software:</h3><ul><li>Productivity: Microsoft Office, Google Workspace</li><li>Web Browsers: Chrome, Firefox, Safari</li><li>Communication: Email clients, messaging apps</li><li>Multimedia: Photo editors, video players</li></ul><h3>Software Licensing:</h3><ul><li>Proprietary software</li><li>Open-source software</li><li>Freeware and shareware</li></ul>', 25, 3, FALSE, NOW()),
(1, 'Introduction to the Internet', 'How the internet works and its history', 'text', '<h2>The Internet</h2><p>The internet is a global network of interconnected computers that communicate using standardized protocols.</p><h3>Key Concepts:</h3><ul><li><strong>IP Address:</strong> Unique identifier for devices</li><li><strong>DNS:</strong> Translates domain names to IP addresses</li><li><strong>HTTP/HTTPS:</strong> Protocols for web communication</li><li><strong>ISP:</strong> Internet Service Provider</li></ul><h3>Internet Services:</h3><ul><li>World Wide Web (WWW)</li><li>Email</li><li>File Transfer (FTP)</li><li>Instant Messaging</li><li>Video Conferencing</li></ul>', 35, 4, FALSE, NOW()),
(1, 'Web Browsers and Search Engines', 'Navigating the web effectively', 'text', '<h2>Web Browsers</h2><p>Web browsers are applications that allow you to access and view websites.</p><h3>Popular Browsers:</h3><ul><li>Google Chrome</li><li>Mozilla Firefox</li><li>Safari</li><li>Microsoft Edge</li></ul><h3>Search Engines:</h3><ul><li>Google</li><li>Bing</li><li>DuckDuckGo</li></ul><h3>Effective Search Techniques:</h3><ul><li>Use specific keywords</li><li>Use quotes for exact phrases</li><li>Use minus sign to exclude terms</li><li>Use site: to search specific websites</li></ul>', 20, 5, FALSE, NOW()),
(1, 'Email and Communication', 'Professional email communication', 'text', '<h2>Email Communication</h2><h3>Email Basics:</h3><ul><li>Email address format: username@domain.com</li><li>Subject lines</li><li>CC and BCC</li><li>Attachments</li></ul><h3>Email Etiquette:</h3><ul><li>Use clear subject lines</li><li>Professional greetings</li><li>Proofread before sending</li><li>Reply in a timely manner</li><li>Be concise and clear</li></ul><h3>Email Security:</h3><ul><li>Recognize phishing emails</li><li>Do not click suspicious links</li><li>Verify sender identity</li><li>Use strong passwords</li></ul>', 25, 6, FALSE, NOW()),
(1, 'Online Safety and Security', 'Protecting yourself online', 'text', '<h2>Cybersecurity Basics</h2><h3>Password Security:</h3><ul><li>Use strong, unique passwords</li><li>Enable two-factor authentication</li><li>Use password managers</li></ul><h3>Common Threats:</h3><ul><li>Phishing</li><li>Malware and viruses</li><li>Identity theft</li><li>Social engineering</li></ul><h3>Best Practices:</h3><ul><li>Keep software updated</li><li>Use antivirus software</li><li>Be cautious with public Wi-Fi</li><li>Back up important data</li><li>Think before you click</li></ul>', 30, 7, FALSE, NOW()),
(1, 'Productivity Software', 'Using Microsoft Office and alternatives', 'text', '<h2>Productivity Tools</h2><h3>Word Processing:</h3><ul><li>Microsoft Word / Google Docs</li><li>Creating documents</li><li>Formatting text</li><li>Inserting images and tables</li></ul><h3>Spreadsheets:</h3><ul><li>Microsoft Excel / Google Sheets</li><li>Creating spreadsheets</li><li>Formulas and functions</li><li>Charts and graphs</li></ul><h3>Presentations:</h3><ul><li>Microsoft PowerPoint / Google Slides</li><li>Creating slides</li><li>Adding multimedia</li><li>Presenting effectively</li></ul>', 40, 8, FALSE, NOW());

-- Insert Lessons for Course 7 (Free Programming Course)
INSERT INTO lessons (course_id, title, description, content_type, content_text, duration, order_index, is_free, created_at) VALUES
(7, 'Why Learn Programming?', 'Introduction to programming and its applications', 'text', '<h2>Welcome to Programming!</h2><p>Programming is the skill of the 21st century. It empowers you to:</p><ul><li>Build websites and mobile apps</li><li>Automate repetitive tasks</li><li>Analyze data and solve problems</li><li>Create games and interactive experiences</li><li>Launch tech startups</li></ul><p>This course will teach you programming fundamentals using Python, one of the most beginner-friendly languages.</p>', 20, 1, TRUE, NOW()),
(7, 'Setting Up Python', 'Install Python and set up your development environment', 'text', '<h2>Installing Python</h2><h3>Steps:</h3><ol><li>Visit python.org</li><li>Download Python 3.x for your operating system</li><li>Run the installer</li><li>Check "Add Python to PATH"</li><li>Complete installation</li></ol><h3>Testing Your Installation:</h3><p>Open terminal/command prompt and type:</p><pre>python --version</pre><p>You should see the Python version number.</p>', 15, 2, TRUE, NOW()),
(7, 'Your First Python Program', 'Write and run your first program', 'text', '<h2>Hello, World!</h2><p>The traditional first program:</p><pre>print("Hello, World!")</pre><p>This simple program displays text on the screen.</p><h3>Try it yourself:</h3><ol><li>Open a text editor</li><li>Type the code above</li><li>Save as hello.py</li><li>Run with: python hello.py</li></ol>', 25, 3, TRUE, NOW());

-- Insert Quiz for Course 1
INSERT INTO quizzes (course_id, title, description, passing_score, time_limit, created_at) VALUES
(1, 'ICT Fundamentals Quiz', 'Test your understanding of basic ICT concepts', 70, 30, NOW());

-- Insert Quiz Questions
INSERT INTO quiz_questions (quiz_id, question_text, question_type, options, correct_answer, points, order_index, created_at) VALUES
(1, 'What does CPU stand for?', 'multiple_choice', '["Central Processing Unit", "Computer Personal Unit", "Central Program Utility", "Computer Processing Utility"]', 'Central Processing Unit', 1, 1, NOW()),
(1, 'Which of the following is an input device?', 'multiple_choice', '["Monitor", "Printer", "Keyboard", "Speaker"]', 'Keyboard', 1, 2, NOW()),
(1, 'RAM is a type of permanent storage.', 'true_false', '["True", "False"]', 'False', 1, 3, NOW()),
(1, 'What does WWW stand for?', 'short_answer', '[]', 'World Wide Web', 1, 4, NOW()),
(1, 'Which protocol is used for secure web communication?', 'multiple_choice', '["HTTP", "HTTPS", "FTP", "SMTP"]', 'HTTPS', 1, 5, NOW());

-- Insert Enrollments
INSERT INTO enrollments (user_id, course_id, enrolled_at, payment_status, status) VALUES
(6, 1, NOW(), 'paid', 'active'),
(6, 7, NOW(), 'free', 'active'),
(7, 1, NOW(), 'paid', 'active'),
(7, 2, NOW(), 'paid', 'active'),
(8, 1, NOW(), 'paid', 'completed'),
(8, 7, NOW(), 'free', 'active'),
(9, 2, NOW(), 'paid', 'active'),
(9, 5, NOW(), 'paid', 'active'),
(10, 7, NOW(), 'free', 'active'),
(10, 1, NOW(), 'paid', 'active');

-- Insert Lesson Progress for some students
INSERT INTO lesson_progress (user_id, lesson_id, status, started_at, completed_at, time_spent) VALUES
(6, 1, 'completed', NOW(), NOW(), 900),
(6, 2, 'completed', NOW(), NOW(), 1800),
(6, 3, 'in_progress', NOW(), NULL, 600),
(8, 1, 'completed', NOW(), NOW(), 850),
(8, 2, 'completed', NOW(), NOW(), 1750),
(8, 3, 'completed', NOW(), NOW(), 1500),
(8, 4, 'completed', NOW(), NOW(), 2100),
(8, 5, 'completed', NOW(), NOW(), 1200),
(8, 6, 'completed', NOW(), NOW(), 1450),
(8, 7, 'completed', NOW(), NOW(), 1700),
(8, 8, 'completed', NOW(), NOW(), 2300);

-- Insert Quiz Attempts
INSERT INTO quiz_attempts (user_id, quiz_id, score, total_questions, answers, started_at, completed_at, status) VALUES
(8, 1, 80, 5, '{"1":"Central Processing Unit","2":"Keyboard","3":"False","4":"World Wide Web","5":"HTTPS"}', NOW(), NOW(), 'completed'),
(6, 1, 60, 5, '{"1":"Central Processing Unit","2":"Monitor","3":"False","4":"World Wide Web","5":"HTTP"}', NOW(), NOW(), 'completed');

-- Insert Certificate for completed course
INSERT INTO certificates (user_id, course_id, certificate_number, issued_at) VALUES
(8, 1, 'SD-2024-A1B2C3D4', NOW());

-- Insert Reviews
INSERT INTO reviews (user_id, course_id, rating, comment, created_at) VALUES
(8, 1, 5, 'Excellent course! Very comprehensive and well-structured. Dr. Munyaneza is an amazing instructor.', NOW()),
(6, 1, 4, 'Good content, but could use more practical examples. Overall satisfied with the course.', NOW()),
(7, 2, 5, 'Best web development course I have taken. Hands-on projects really helped me learn.', NOW()),
(9, 5, 5, 'Digital marketing strategies taught here are immediately applicable. Highly recommended!', NOW());

-- Insert Announcements
INSERT INTO announcements (course_id, instructor_id, title, content, created_at) VALUES
(1, 2, 'Welcome to the Course!', 'Hello everyone! Welcome to Introduction to ICT and Internet Technologies. I am excited to have you here. Please check the course schedule and do not hesitate to ask questions.', NOW()),
(1, 2, 'Quiz Available', 'The ICT Fundamentals Quiz is now available. You have 30 minutes to complete it. Good luck!', NOW()),
(2, 2, 'New Project Assignment', 'A new project has been added to Week 4. Please build a portfolio website using HTML, CSS, and JavaScript. Due date: End of week 5.', NOW());
