document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const slider = document.querySelector('.slider');
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');
    
    // Set login as active by default
    loginForm.classList.add('active');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Move slider
            const tabType = this.getAttribute('data-tab');
            if (tabType === 'login') {
                slider.style.transform = 'translateX(0)';
                loginForm.classList.add('active');
                registerForm.classList.remove('active');
            } else {
                slider.style.transform = 'translateX(100%)';
                loginForm.classList.remove('active');
                registerForm.classList.add('active');
            }
        });
    });
});