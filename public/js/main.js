// SchoolDream+ Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navbarMenu = document.querySelector('.navbar-menu');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navbarMenu.classList.toggle('active');
        });
    }
    
    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Quiz timer
    const quizTimer = document.querySelector('.quiz-timer');
    if (quizTimer) {
        const timeLimit = parseInt(quizTimer.getAttribute('data-time-limit'));
        let timeRemaining = timeLimit * 60;
        
        const timerInterval = setInterval(() => {
            timeRemaining--;
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            quizTimer.textContent = `Time Remaining: ${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                document.querySelector('.quiz-form').submit();
            }
        }, 1000);
    }
    
    // Progress tracking
    const lessonContent = document.querySelector('.lesson-content');
    if (lessonContent) {
        const lessonId = lessonContent.getAttribute('data-lesson-id');
        let timeSpent = 0;
        
        setInterval(() => {
            timeSpent += 5;
            // Could send to server to track time spent
        }, 5000);
    }
});

// API helper functions
const API = {
    async request(url, method = 'GET', data = null) {
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
            },
        };
        
        if (data) {
            options.body = JSON.stringify(data);
        }
        
        const response = await fetch(url, options);
        return await response.json();
    },
    
    async enroll(courseId) {
        return await this.request('/api/enroll', 'POST', { course_id: courseId });
    },
    
    async markLessonComplete(lessonId) {
        return await this.request('/api/lesson-progress', 'POST', {
            lesson_id: lessonId,
            action: 'complete'
        });
    },
    
    async submitQuiz(quizId, answers) {
        return await this.request(`/api/quiz/${quizId}/submit`, 'POST', { answers });
    }
};

// Make API available globally
window.API = API;
