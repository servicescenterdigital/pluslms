// Documentation Page Functionality with AI Assistant

document.addEventListener('DOMContentLoaded', function() {
    initDocumentation();
    initAIAssistant();
});

// Documentation functionality
function initDocumentation() {
    initSidebarNavigation();
    initSearch();
    initCodeTabs();
    initScrollSpy();
    initCopyButtons();
}

// Sidebar navigation
function initSidebarNavigation() {
    const sidebar = document.getElementById('docs-sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const navLinks = document.querySelectorAll('.nav-link');
    
    // Toggle sidebar on mobile
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    }
    
    // Handle navigation clicks
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                // Update active state
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                
                // Scroll to section
                const offsetTop = targetElement.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
                
                // Close sidebar on mobile
                if (window.innerWidth <= 1024) {
                    sidebar.classList.remove('active');
                }
            }
        });
    });
}

// Search functionality
function initSearch() {
    const searchInput = document.getElementById('docs-search');
    const navLinks = document.querySelectorAll('.nav-link');
    
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            
            navLinks.forEach(link => {
                const text = link.textContent.toLowerCase();
                const parentSection = link.closest('.nav-section');
                
                if (query === '' || text.includes(query)) {
                    link.style.display = 'flex';
                    if (parentSection) {
                        parentSection.style.display = 'block';
                    }
                } else {
                    link.style.display = 'none';
                    
                    // Hide section if all links are hidden
                    if (parentSection) {
                        const visibleLinks = parentSection.querySelectorAll('.nav-link[style*="flex"]');
                        if (visibleLinks.length === 0) {
                            parentSection.style.display = 'none';
                        }
                    }
                }
            });
        });
        
        // Clear search on escape
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.blur();
            }
        });
    }
}

// Code tabs functionality
function initCodeTabs() {
    const codeTabs = document.querySelectorAll('.code-tab');
    const codeBlocks = document.querySelectorAll('.code-block-wrapper');
    
    codeTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetLang = tab.getAttribute('data-lang');
            
            // Remove active class from all tabs
            codeTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            // Show corresponding code block (simplified - in real implementation you'd have multiple blocks)
            codeBlocks.forEach(block => {
                const language = block.querySelector('.code-language').textContent.toLowerCase();
                if (language.includes(targetLang)) {
                    block.style.display = 'block';
                } else {
                    block.style.display = 'none';
                }
            });
        });
    });
}

// Scroll spy for navigation
function initScrollSpy() {
    const sections = document.querySelectorAll('.docs-section');
    const navLinks = document.querySelectorAll('.nav-link');
    
    const observerOptions = {
        rootMargin: '-20% 0px -80% 0px',
        threshold: 0
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                
                // Update active nav link
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }, observerOptions);
    
    sections.forEach(section => {
        observer.observe(section);
    });
}

// Copy to clipboard functionality
function initCopyButtons() {
    window.copyCode = function(button) {
        const codeBlock = button.closest('.code-block-wrapper').querySelector('pre code');
        const code = codeBlock.textContent;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                showCopyFeedback(button);
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = code;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showCopyFeedback(button);
        }
    };
}

function showCopyFeedback(button) {
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.style.background = 'var(--success-color)';
    button.style.color = 'white';
    button.style.borderColor = 'var(--success-color)';
    
    setTimeout(() => {
        button.textContent = originalText;
        button.style.background = '';
        button.style.color = '';
        button.style.borderColor = '';
    }, 2000);
}

// AI Assistant functionality
function initAIAssistant() {
    const aiToggle = document.getElementById('ai-assistant-toggle');
    const aiAssistant = document.getElementById('ai-assistant');
    const aiClose = document.getElementById('ai-toggle');
    const aiInput = document.getElementById('ai-input');
    const aiSend = document.getElementById('ai-send');
    const aiChat = document.getElementById('ai-chat');
    
    // Toggle AI assistant
    if (aiToggle) {
        aiToggle.addEventListener('click', () => {
            aiAssistant.classList.add('active');
            aiToggle.style.display = 'none';
            aiInput.focus();
        });
    }
    
    if (aiClose) {
        aiClose.addEventListener('click', () => {
            aiAssistant.classList.remove('active');
            aiToggle.style.display = 'flex';
        });
    }
    
    // Send message
    if (aiSend) {
        aiSend.addEventListener('click', sendAIMessage);
    }
    
    if (aiInput) {
        aiInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendAIMessage();
            }
        });
    }
    
    // Handle suggested questions
    const suggestedQuestions = document.querySelectorAll('.suggested-question');
    suggestedQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const questionText = question.getAttribute('data-question');
            aiInput.value = questionText;
            sendAIMessage();
        });
    });
}

function sendAIMessage() {
    const aiInput = document.getElementById('ai-input');
    const aiChat = document.getElementById('ai-chat');
    const message = aiInput.value.trim();
    
    if (!message) return;
    
    // Add user message
    addAIMessage(message, 'user');
    aiInput.value = '';
    
    // Show typing indicator
    showAITyping();
    
    // Simulate AI response
    setTimeout(() => {
        hideAITyping();
        const response = generateAIResponse(message);
        addAIMessage(response, 'bot');
    }, 1500);
}

function addAIMessage(message, sender) {
    const aiChat = document.getElementById('ai-chat');
    const messageDiv = document.createElement('div');
    messageDiv.className = `ai-message ai-${sender}`;
    
    messageDiv.innerHTML = `
        <div class="message-content">
            ${formatAIMessage(message)}
        </div>
    `;
    
    aiChat.appendChild(messageDiv);
    aiChat.scrollTop = aiChat.scrollHeight;
}

function showAITyping() {
    const aiChat = document.getElementById('ai-chat');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'ai-message ai-bot';
    typingDiv.id = 'ai-typing';
    
    typingDiv.innerHTML = `
        <div class="message-content">
            <div class="typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    `;
    
    aiChat.appendChild(typingDiv);
    aiChat.scrollTop = aiChat.scrollHeight;
}

function hideAITyping() {
    const typingDiv = document.getElementById('ai-typing');
    if (typingDiv) {
        typingDiv.remove();
    }
}

function formatAIMessage(message) {
    // Convert markdown-like formatting to HTML
    return message
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`(.*?)`/g, '<code>$1</code>')
        .replace(/\n/g, '<br>');
}

function generateAIResponse(question) {
    const lowerQuestion = question.toLowerCase();
    
    // Knowledge base for DGS-Pay documentation
    const responses = {
        'payment': {
            keywords: ['payment', 'pay', 'charge', 'transaction'],
            response: `To create a payment with DGS-Pay, you can use our Payments API:

**Create Payment Example:**
\`\`\`javascript
const payment = await dgsPay.payments.create({
  amount: 2500, // $25.00 in cents
  currency: 'usd',
  source: 'tok_visa',
  description: 'Product purchase'
});
\`\`\`

**Required Parameters:**
- **amount**: Amount in cents (integer)
- **currency**: Three-letter ISO currency code
- **source**: Payment source token

Would you like to see more examples or learn about specific payment methods?`
        },
        
        'authentication': {
            keywords: ['auth', 'key', 'api key', 'secret', 'token'],
            response: `Authentication is handled using API keys in the Authorization header:

**Get Your API Keys:**
1. Sign up at [DGS-Pay Dashboard](get-started.html)
2. Navigate to API Keys section
3. Copy your test/live secret key

**Authentication Header:**
\`\`\`
Authorization: Bearer sk_test_your_secret_key_here
\`\`\`

**Important:** Keep your secret keys secure and never expose them in client-side code!`
        },
        
        'webhook': {
            keywords: ['webhook', 'callback', 'event', 'notification'],
            response: `Webhooks allow you to receive real-time notifications about payment events:

**Setup Webhooks:**
1. Go to Dashboard → Webhooks
2. Add endpoint URL
3. Select events to listen for

**Supported Events:**
- \`payment.succeeded\`
- \`payment.failed\`
- \`charge.dispute.created\`
- \`customer.created\`

**Example Webhook Handler:**
\`\`\`javascript
app.post('/webhook', (req, res) => {
  const event = req.body;
  
  if (event.type === 'payment.succeeded') {
    // Handle successful payment
    console.log('Payment succeeded:', event.data.object);
  }
  
  res.json({received: true});
});
\`\`\``
        },
        
        'method': {
            keywords: ['method', 'card', 'paypal', 'apple pay', 'google pay'],
            response: `DGS-Pay supports multiple payment methods:

**Card Payments:**
- Visa, Mastercard, American Express, Discover
- Credit and debit cards

**Digital Wallets:**
- Apple Pay
- Google Pay
- Samsung Pay

**Bank Transfers:**
- ACH (US)
- SEPA (Europe)
- Local bank transfers

**Buy Now, Pay Later:**
- Klarna
- Afterpay

To use a specific payment method, include it in your payment request:
\`\`\`javascript
{
  "amount": 2500,
  "currency": "usd",
  "payment_method_types": ["card", "apple_pay"]
}
\`\`\``
        },
        
        'error': {
            keywords: ['error', 'failed', 'exception', 'troubleshoot'],
            response: `Common errors and how to handle them:

**Card Errors:**
- \`card_declined\`: Card was declined by the bank
- \`insufficient_funds\`: Card has insufficient funds
- \`expired_card\`: Card has expired

**Request Errors:**
- \`invalid_request_error\`: Missing or invalid parameters
- \`authentication_error\`: Invalid API key
- \`api_error\`: Temporary server error

**Error Handling Example:**
\`\`\`javascript
try {
  const payment = await dgsPay.payments.create({...});
} catch (error) {
  if (error.type === 'card_error') {
    console.log('Card issue:', error.message);
  } else {
    console.log('Other error:', error.message);
  }
}
\`\`\``

        },
        
        'subscription': {
            keywords: ['subscription', 'recurring', 'billing', 'plan'],
            response: `For recurring payments, use our Subscriptions API:

**Create Subscription:**
\`\`\`javascript
const subscription = await dgsPay.subscriptions.create({
  customer: 'cus_1234567890',
  items: [{
    price: 'price_1234567890',
    quantity: 1
  }]
});
\`\`\`

**Key Concepts:**
- **Customer**: Customer object with payment method
- **Price**: Recurring pricing configuration
- **Subscription**: Links customer to pricing

Would you like me to show you how to set up your first subscription?`
        }
    };
    
    // Find matching response
    for (const [key, data] of Object.entries(responses)) {
        if (data.keywords.some(keyword => lowerQuestion.includes(keyword))) {
            return data.response;
        }
    }
    
    // Default response
    return `I'm here to help with DGS-Pay documentation! I can assist you with:

• **Payments**: Creating and managing payments
• **Authentication**: API keys and security
• **Webhooks**: Real-time event notifications  
• **Payment Methods**: Cards, wallets, bank transfers
• **Error Handling**: Common issues and solutions
• **Subscriptions**: Recurring billing setup

Could you be more specific about what you'd like to know? For example:
- "How do I create my first payment?"
- "What payment methods are supported?"
- "How do I handle webhooks?"`;
}

// Add typing indicator styles
const typingStyles = `
    <style>
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .typing-indicator span {
            width: 8px;
            height: 8px;
            background: var(--text-tertiary);
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        
        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.5;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }
        
        .message-content code {
            background: var(--bg-tertiary);
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius-sm);
            font-family: var(--font-mono);
            font-size: 0.875rem;
        }
        
        .message-content pre {
            background: #1e293b;
            padding: 1rem;
            border-radius: var(--border-radius-md);
            overflow-x: auto;
            margin: 1rem 0;
        }
        
        .message-content pre code {
            background: none;
            padding: 0;
            color: #e2e8f0;
        }
    </style>
`;

// Inject typing indicator styles
document.head.insertAdjacentHTML('beforeend', typingStyles);