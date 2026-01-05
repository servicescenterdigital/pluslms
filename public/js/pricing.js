// Pricing Page Functionality

document.addEventListener('DOMContentLoaded', function() {
    initPricingToggle();
    initAccordion();
    initComparisonTable();
    initPricingAnimations();
});

// Pricing Toggle (Monthly/Annual)
function initPricingToggle() {
    const toggle = document.getElementById('pricing-toggle');
    const pricingAmounts = document.querySelectorAll('.pricing-amount');
    
    if (toggle) {
        toggle.addEventListener('change', function() {
            const isAnnual = this.checked;
            
            pricingAmounts.forEach(amount => {
                const currentPrice = amount.textContent;
                
                if (isAnnual) {
                    // Apply 20% discount for annual
                    const numericPrice = parseFloat(currentPrice.replace('%', ''));
                    const discountedPrice = (numericPrice * 0.8).toFixed(1);
                    amount.textContent = discountedPrice + '%';
                    
                    // Update the period text
                    const period = amount.nextElementSibling;
                    if (period && period.classList.contains('pricing-period')) {
                        period.innerHTML = '<span class="annual-price">+ $0.25 per transaction</span><span class="monthly-price" style="display: none;">+ $0.30 per transaction</span>';
                    }
                } else {
                    // Reset to monthly pricing
                    const numericPrice = parseFloat(currentPrice.replace('%', ''));
                    const originalPrice = (numericPrice / 0.8).toFixed(1);
                    amount.textContent = originalPrice + '%';
                    
                    const period = amount.nextElementSibling;
                    if (period && period.classList.contains('pricing-period')) {
                        period.innerHTML = '<span class="monthly-price">+ $0.30 per transaction</span><span class="annual-price" style="display: none;">+ $0.25 per transaction</span>';
                    }
                }
                
                // Add animation effect
                amount.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    amount.style.transform = 'scale(1)';
                }, 200);
            });
        });
    }
}

// Accordion functionality for FAQ
function initAccordion() {
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        
        header.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            
            // Close all other items
            accordionItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            if (isActive) {
                item.classList.remove('active');
            } else {
                item.classList.add('active');
            }
        });
    });
}

// Comparison table interactivity
function initComparisonTable() {
    const comparisonRows = document.querySelectorAll('.comparison-row');
    
    comparisonRows.forEach(row => {
        const values = row.querySelectorAll('.comparison-value');
        const features = row.querySelector('.comparison-feature');
        
        // Highlight best values
        const numericValues = Array.from(values).map(value => {
            const text = value.textContent;
            if (text.includes('%')) {
                return parseFloat(text.replace('%', ''));
            } else if (text.includes('$')) {
                return parseFloat(text.replace('$', ''));
            } else if (text.includes('.')) {
                return parseFloat(text);
            }
            return text === 'Yes' ? 1 : text === 'No' ? 0 : text;
        });
        
        // Find the best value (lowest for prices, highest for percentages/uptime)
        const isPrice = values[0].textContent.includes('$');
        const isUptime = values[0].textContent.includes('%') && features.textContent.includes('Uptime');
        
        let bestIndex = 0;
        if (isPrice) {
            bestIndex = numericValues.indexOf(Math.min(...numericValues));
        } else if (isUptime) {
            bestIndex = numericValues.indexOf(Math.max(...numericValues));
        }
        
        // Highlight best values
        values.forEach((value, index) => {
            if (index === bestIndex && (isPrice || isUptime)) {
                value.classList.add('best-value');
            }
        });
    });
}

// Pricing animations
function initPricingAnimations() {
    const pricingCards = document.querySelectorAll('.pricing-card');
    
    // Stagger card animations
    pricingCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.2}s`;
        card.classList.add('page-enter');
        
        // Add hover effects
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', () => {
            if (!card.classList.contains('featured')) {
                card.style.transform = 'translateY(0) scale(1)';
            } else {
                card.style.transform = 'scale(1.05)';
            }
        });
    });
    
    // Animate comparison table
    const comparisonTable = document.querySelector('.comparison-table');
    if (comparisonTable) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const rows = entry.target.querySelectorAll('.comparison-row');
                    rows.forEach((row, index) => {
                        setTimeout(() => {
                            row.style.opacity = '1';
                            row.style.transform = 'translateX(0)';
                        }, index * 100);
                    });
                }
            });
        });
        
        observer.observe(comparisonTable);
        
        // Initially hide rows for animation
        const rows = comparisonTable.querySelectorAll('.comparison-row');
        rows.forEach(row => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            row.style.transition = 'all 0.5s ease';
        });
    }
}

// Add pricing-specific styles
const pricingStyles = `
    <style>
        .hero-compact {
            min-height: 60vh;
            padding-top: calc(var(--navbar-height) + 2rem);
        }
        
        .pricing-toggle {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .toggle-label {
            font-weight: 600;
            color: var(--text-secondary);
        }
        
        .discount-badge {
            background: var(--success-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius-md);
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        .pricing-section {
            padding: 4rem 0;
        }
        
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .pricing-period {
            font-size: 0.875rem;
            color: var(--text-tertiary);
            margin-top: 0.5rem;
        }
        
        .annual-price, .monthly-price {
            display: inline;
        }
        
        .fee-comparison {
            padding: 6rem 0;
            background: var(--bg-secondary);
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
        
        .best-value {
            background: var(--success-color) !important;
            color: white !important;
            position: relative;
        }
        
        .best-value::after {
            content: '✓';
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            font-size: 0.875rem;
        }
        
        .faq-section {
            padding: 6rem 0;
        }
        
        @media (max-width: 768px) {
            .pricing-grid {
                grid-template-columns: 1fr;
            }
            
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
    </style>
`;

// Inject pricing styles
document.head.insertAdjacentHTML('beforeend', pricingStyles);