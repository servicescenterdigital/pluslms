// Animation Functions for DGS-Pay Website

// Scroll Reveal Animation
function initScrollReveal() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                // Unobserve after animation to improve performance
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all elements with scroll reveal classes
    const scrollElements = document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale');
    scrollElements.forEach(el => {
        observer.observe(el);
    });
}

// Staggered Animation for Grid Items
function initStaggeredAnimations() {
    const staggerContainers = document.querySelectorAll('.features-grid, .testimonials-grid, .stats-grid');
    
    staggerContainers.forEach(container => {
        const items = container.children;
        Array.from(items).forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('page-enter');
        });
    });
}

// Smooth Scroll for Anchor Links
function initSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const navHeight = document.querySelector('.navbar').offsetHeight;
                const targetPosition = targetElement.offsetTop - navHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Parallax Effect
function initParallax() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        
        parallaxElements.forEach(element => {
            element.style.transform = `translateY(${rate}px)`;
        });
    });
}

// Counter Animation
function initCounterAnimation() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 2000; // Animation duration in milliseconds
    
    const animateCounter = (counter) => {
        const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
        const increment = target / (speed / 16); // 60 FPS
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Format the number based on original text
            const originalText = counter.getAttribute('data-original') || counter.textContent;
            if (originalText.includes('%')) {
                counter.textContent = Math.floor(current) + '%';
            } else if (originalText.includes('<')) {
                counter.textContent = '<' + Math.floor(current) + 'ms';
            } else if (originalText.includes('+')) {
                counter.textContent = Math.floor(current) + '+';
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    };
    
    // Store original text content
    counters.forEach(counter => {
        counter.setAttribute('data-original', counter.textContent);
    });
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
}

// Payment Cards Animation
function initPaymentCards() {
    const cards = document.querySelectorAll('.payment-card');
    
    cards.forEach((card, index) => {
        // Add initial animation delay
        card.style.animationDelay = `${index * 0.5}s`;
        
        // Add hover effect enhancement
        card.addEventListener('mouseenter', () => {
            cards.forEach((otherCard, otherIndex) => {
                if (otherIndex !== index) {
                    otherCard.style.transform = 'translateY(10px) scale(0.95)';
                    otherCard.style.opacity = '0.7';
                }
            });
        });
        
        card.addEventListener('mouseleave', () => {
            cards.forEach(otherCard => {
                otherCard.style.transform = '';
                otherCard.style.opacity = '';
            });
        });
    });
}

// Floating Elements Animation
function initFloatingElements() {
    const floatingElements = document.querySelectorAll('.floating-element');
    
    floatingElements.forEach((element, index) => {
        // Add random floating animation
        const randomDelay = Math.random() * 2;
        const randomDuration = 4 + Math.random() * 2;
        
        element.style.animationDelay = `${randomDelay}s`;
        element.style.animationDuration = `${randomDuration}s`;
        
        // Add interactive hover effect
        element.addEventListener('mouseenter', () => {
            element.style.transform = 'translateY(-10px) scale(1.1)';
            element.style.zIndex = '10';
        });
        
        element.addEventListener('mouseleave', () => {
            element.style.transform = '';
            element.style.zIndex = '';
        });
    });
}

// Code Tabs Animation
function initCodeTabs() {
    const tabs = document.querySelectorAll('.code-tab');
    const codeBlocks = document.querySelectorAll('.code-block');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetLang = tab.getAttribute('data-lang');
            
            // Remove active class from all tabs and code blocks
            tabs.forEach(t => t.classList.remove('active'));
            codeBlocks.forEach(block => block.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding code block
            tab.classList.add('active');
            const targetBlock = document.querySelector(`[data-lang="${targetLang}"]`);
            if (targetBlock) {
                targetBlock.classList.add('active');
            }
        });
    });
}

// Typing Animation
function initTypingAnimation() {
    const typingElements = document.querySelectorAll('.typing-text');
    
    typingElements.forEach(element => {
        const text = element.textContent;
        element.textContent = '';
        element.style.borderRight = '2px solid var(--primary-color)';
        
        let index = 0;
        const typeTimer = setInterval(() => {
            element.textContent += text[index];
            index++;
            
            if (index >= text.length) {
                clearInterval(typeTimer);
                // Remove cursor after typing is complete
                setTimeout(() => {
                    element.style.borderRight = 'none';
                }, 1000);
            }
        }, 100);
    });
}

// Particle System
function createParticles() {
    const particleContainer = document.querySelector('.particles');
    if (!particleContainer) return;
    
    const particleCount = 50;
    
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = (8 + Math.random() * 4) + 's';
        particleContainer.appendChild(particle);
    }
}

// Morphing Shapes
function initMorphingShapes() {
    const morphElements = document.querySelectorAll('.morph');
    
    morphElements.forEach(element => {
        // Add random morphing effect
        element.addEventListener('mouseenter', () => {
            element.style.animationPlayState = 'paused';
            element.style.borderRadius = '20px 80px 40px 60px / 40px 20px 60px 80px';
        });
        
        element.addEventListener('mouseleave', () => {
            element.style.animationPlayState = 'running';
        });
    });
}

// Loading Screen Animation
function initLoadingScreen() {
    const loadingScreen = document.getElementById('loading-screen');
    if (!loadingScreen) return;
    
    // Hide loading screen after page load
    window.addEventListener('load', () => {
        setTimeout(() => {
            loadingScreen.classList.add('hidden');
            // Remove from DOM after animation
            setTimeout(() => {
                loadingScreen.remove();
            }, 500);
        }, 1000);
    });
}

// Intersection Observer for Performance
function createIntersectionObserver(callback, options = {}) {
    const defaultOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    return new IntersectionObserver(callback, { ...defaultOptions, ...options });
}

// Lazy Loading Images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = createIntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Performance Monitoring
function initPerformanceMonitoring() {
    // Monitor scroll performance
    let ticking = false;
    
    function updateOnScroll() {
        // Add scroll-based animations here
        ticking = false;
    }
    
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateOnScroll);
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', requestTick);
}

// Initialize all animations when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initStaggeredAnimations();
    initSmoothScroll();
    initParallax();
    initCounterAnimation();
    initPaymentCards();
    initFloatingElements();
    initCodeTabs();
    initTypingAnimation();
    createParticles();
    initMorphingShapes();
    initLoadingScreen();
    initLazyLoading();
    initPerformanceMonitoring();
});

// Export functions for external use
window.DGSAnimations = {
    initScrollReveal,
    initCounterAnimation,
    initCodeTabs,
    createParticles
};