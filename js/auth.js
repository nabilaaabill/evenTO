// tab Selection Logic
const authOptions = document.querySelectorAll('.auth-option');

authOptions.forEach(option => {
    option.addEventListener('click', function() {
        // Remove active class from all options
        authOptions.forEach(opt => opt.classList.remove('active'));
        
        // Add active class to clicked option
        this.classList.add('active');
        
        // Get tab type
        const tabType = this.dataset.tab;
        
        // Hide all forms
        document.querySelectorAll('.auth-form').forEach(form => {
            form.classList.remove('active');
        });
        
        // Show corresponding form
        document.querySelector(`.${tabType}-form`).classList.add('active');
    });
});

//  Form Submission
const forms = document.querySelectorAll('.auth-form');

forms.forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formType = this.classList.contains('login-form') ? 'login' : 'register';
        console.log(`${formType} form submitted`);
        
        // Add your form submission logic here
        // Contoh: Validasi, AJAX request, dll
    });
});
    const tabButtons = document.querySelectorAll('.tab-btn');
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-tab');
            
            // Toggle tab active class
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Toggle form display
            if (target === 'login') {
                loginForm.classList.add('active');
                registerForm.classList.remove('active');
            } else {
                loginForm.classList.remove('active');
                registerForm.classList.add('active');
            }
        });
    });
