import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Medical Dashboard Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard animations
    initializeDashboardAnimations();
    
    // Initialize real-time updates
    initializeRealTimeUpdates();
    
    // Initialize interactive elements
    initializeInteractiveElements();
});

function initializeDashboardAnimations() {
    // Animate statistics cards on load
    const statCards = document.querySelectorAll('.dashboard-card');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add hover effects to module cards
    const moduleCards = document.querySelectorAll('a[href*="route"]');
    moduleCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) translateY(0)';
        });
    });
}

function initializeRealTimeUpdates() {
    // Update time every minute
    setInterval(updateTime, 60000);
    
    // Update statistics every 5 minutes
    setInterval(updateStatistics, 300000);
}

function updateTime() {
    const timeElements = document.querySelectorAll('.time-display');
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
    
    timeElements.forEach(element => {
        element.textContent = timeString;
    });
}

function updateStatistics() {
    // Fetch updated statistics from the server
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatCards(data.data);
            }
        })
        .catch(error => {
            console.error('Error updating statistics:', error);
        });
}

function updateStatCards(stats) {
    Object.keys(stats).forEach(key => {
        const statElement = document.querySelector(`[data-stat="${key}"]`);
        if (statElement) {
            const currentValue = parseInt(statElement.textContent);
            const newValue = stats[key];
            
            if (currentValue !== newValue) {
                animateNumberChange(statElement, currentValue, newValue);
            }
        }
    });
}

function animateNumberChange(element, from, to) {
    const duration = 1000;
    const startTime = performance.now();
    
    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = Math.round(from + (to - from) * progress);
        element.textContent = current.toLocaleString();
        
        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        }
    }
    
    requestAnimationFrame(updateNumber);
}

function initializeInteractiveElements() {
    // Add click handlers for quick actions
    const quickActionButtons = document.querySelectorAll('.quick-action');
    quickActionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.dataset.action;
            handleQuickAction(action);
        });
    });
    
    // Add search functionality
    const searchInput = document.querySelector('#dashboard-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            filterDashboardCards(query);
        });
    }
    
    // Add notification system
    initializeNotifications();
}

function handleQuickAction(action) {
    switch(action) {
        case 'new-appointment':
            window.location.href = '/appointments/create';
            break;
        case 'new-record':
            window.location.href = '/medical-records/create';
            break;
        case 'expert-system':
            window.location.href = '/expert-system';
            break;
        case 'reports':
            window.location.href = '/reports';
            break;
        default:
            console.log('Unknown action:', action);
    }
}

function filterDashboardCards(query) {
    const cards = document.querySelectorAll('.dashboard-card');
    cards.forEach(card => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        const description = card.querySelector('p').textContent.toLowerCase();
        
        if (title.includes(query) || description.includes(query)) {
            card.style.display = 'block';
            card.style.opacity = '1';
        } else {
            card.style.opacity = '0.5';
        }
    });
}

function initializeNotifications() {
    // Check for new notifications every 30 seconds
    setInterval(checkNotifications, 30000);
}

function checkNotifications() {
    fetch('/api/notifications/unread')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.count > 0) {
                showNotificationBadge(data.count);
            }
        })
        .catch(error => {
            console.error('Error checking notifications:', error);
        });
}

function showNotificationBadge(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = 'block';
        badge.classList.add('medical-bounce');
    }
}

// Alpine.js components for dashboard
Alpine.data('dashboard', () => ({
    searchQuery: '',
    isLoading: false,
    notifications: [],
    
    init() {
        this.loadNotifications();
    },
    
    async loadNotifications() {
        this.isLoading = true;
        try {
            const response = await fetch('/api/notifications');
            const data = await response.json();
            this.notifications = data.notifications || [];
        } catch (error) {
            console.error('Error loading notifications:', error);
        } finally {
            this.isLoading = false;
        }
    },
    
    filterCards() {
        const cards = document.querySelectorAll('.dashboard-card');
        cards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            const query = this.searchQuery.toLowerCase();
            
            if (title.includes(query) || description.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
}));

Alpine.start();
