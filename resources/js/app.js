import './bootstrap';
import 'chart.js';

// Listen for user registration events
Echo.private('admin-dashboard')
    .listen('UserRegistered', (e) => {
        window.dispatchEvent(new CustomEvent('userRegistered'));
    });
