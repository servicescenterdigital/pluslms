// Why DGS-Pay Page Functionality

document.addEventListener('DOMContentLoaded', function() {
    initWhyDGSAnimations();
    initComparisonTable();
    initStoryCards();
});

// Animation for competitive advantages
function initWhyDGSAnimations() {
    const advantageCards = document.querySelectorAll('.advantage-card');
    const techItems = document.querySelectorAll('.tech-item');
    const awardItems = document.querySelectorAll('.award-item');
    
    // Advantage cards animation
    const advantageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('advantage-revealed');
            }
        });
    }, { threshold: 0.3 });
    
    advantageCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        advantageObserver.observe(card);
    });
    
    // Tech items animation
    const techObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('tech-revealed');
            }
        });
    }, { threshold: 0.3 });
    
    techItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.15}s`;
        techObserver.observe(item);
    });
    
    // Award items animation
    const awardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('award-revealed');
            }
        });
    }, { threshold: 0.3 });
    
    awardItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        awardObserver.observe(item);
    });
}

// Enhanced comparison table
function initComparisonTable() {
    const comparisonRows = document.querySelectorAll('.comparison-row');
    
    comparisonRows.forEach(row => {
        const values = row.querySelectorAll('.comparison-value');
        const feature = row.querySelector('.comparison-feature').textContent;
        
        // Highlight DGS-Pay advantages
        if (feature.includes('Fee') || feature.includes('Response Time')) {
            values.forEach((value, index) => {
                if (index === 0) { // DGS-Pay column
                    value.classList.add('dgs-advantage');
                }
            });
        }
        
        // Add checkmarks/ crosses for boolean features
        values.forEach((value, index) => {
            if (value.textContent === '✅') {
                value.classList.add('feature-supported');
            } else if (value.textContent === '❌') {
                value.classList.add('feature-not-supported');
            }
        });
    });
}

// Customer story cards interaction
function initStoryCards() {
    const storyCards = document.querySelectorAll('.story-card');
    
    storyCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Add Why DGS-Pay specific styles
const whyDGSStyles = `
    <style>
        .hero-compact {
            min-height: 60vh;
            padding-top: calc(var(--navbar-height) + 2rem);
        }
        
        .competitive-advantages {
            padding: 6rem 0;
            background: var(--bg-secondary);
        }
        
        .advantages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .advantage-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .advantage-card.advantage-revealed {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.6s ease;
        }
        
        .advantage-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-color);
        }
        
        .advantage-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .advantage-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .advantage-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .advantage-card p {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .advantage-highlight {
            background: var(--bg-tertiary);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-md);
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .comparison-section {
            padding: 6rem 0;
        }
        
        .comparison-table {
            background: var(--bg-card);
            border-radius: var(--border-radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-top: 3rem;
        }
        
        .comparison-header {
            display: grid;
            grid-template-columns: 2fr repeat(4, 1fr);
            gap: 1px;
            background: var(--border-color);
        }
        
        .comparison-feature,
        .comparison-provider {
            background: var(--bg-card);
            padding: 1.5rem;
            font-weight: 600;
            text-align: center;
            color: var(--text-primary);
        }
        
        .comparison-feature {
            text-align: left;
        }
        
        .comparison-row {
            display: grid;
            grid-template-columns: 2fr repeat(4, 1fr);
            gap: 1px;
            background: var(--border-color);
        }
        
        .comparison-feature,
        .comparison-value {
            background: var(--bg-card);
            padding: 1rem 1.5rem;
            color: var(--text-secondary);
        }
        
        .comparison-value {
            text-align: center;
            font-weight: 600;
        }
        
        .dgs-advantage {
            background: var(--success-color) !important;
            color: white !important;
            position: relative;
        }
        
        .dgs-advantage::after {
            content: '✓';
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            font-size: 0.875rem;
        }
        
        .feature-supported {
            color: var(--success-color);
            font-size: 1.2rem;
        }
        
        .feature-not-supported {
            color: var(--error-color);
            font-size: 1.2rem;
        }
        
        .tech-stack {
            padding: 6rem 0;
            background: var(--bg-secondary);
        }
        
        .tech-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .tech-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .tech-item.tech-revealed {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.6s ease;
        }
        
        .tech-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .tech-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .tech-icon i {
            font-size: 1.5rem;
            color: white;
        }
        
        .tech-item h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .tech-item p {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        .customer-stories {
            padding: 6rem 0;
        }
        
        .stories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .story-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            transition: all 0.3s ease;
        }
        
        .story-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }
        
        .story-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .story-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .story-info h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .story-info p {
            color: var(--text-tertiary);
            font-size: 0.875rem;
            margin: 0;
        }
        
        .story-rating {
            display: flex;
            gap: 0.25rem;
            margin-bottom: 1.5rem;
        }
        
        .story-rating i {
            color: #fbbf24;
        }
        
        .story-text {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 2rem;
            font-style: italic;
        }
        
        .story-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .metric {
            text-align: center;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: var(--border-radius-md);
        }
        
        .metric-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .metric-label {
            font-size: 0.75rem;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .awards-section {
            padding: 6rem 0;
            background: var(--bg-secondary);
        }
        
        .awards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .award-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .award-item.award-revealed {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.6s ease;
        }
        
        .award-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .award-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .award-icon i {
            font-size: 1.5rem;
            color: white;
        }
        
        .award-item h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .award-item p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        @media (max-width: 1024px) {
            .comparison-header,
            .comparison-row {
                grid-template-columns: 1fr;
            }
            
            .comparison-feature,
            .comparison-provider,
            .comparison-value {
                text-align: left;
                border-bottom: 1px solid var(--border-color);
            }
            
            .comparison-provider,
            .comparison-value {
                position: relative;
                padding-left: 2rem;
            }
            
            .comparison-provider::before,
            .comparison-value::before {
                content: attr(data-label);
                position: absolute;
                left: 0.5rem;
                font-size: 0.875rem;
                color: var(--text-tertiary);
            }
        }
        
        @media (max-width: 768px) {
            .advantages-grid,
            .tech-grid,
            .stories-grid,
            .awards-grid {
                grid-template-columns: 1fr;
            }
            
            .story-metrics {
                grid-template-columns: 1fr;
            }
        }
    </style>
`;

// Inject Why DGS-Pay styles
document.head.insertAdjacentHTML('beforeend', whyDGSStyles);