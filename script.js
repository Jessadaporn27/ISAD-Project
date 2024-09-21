document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'toggle-form') {
        e.preventDefault(); // ป้องกันการทำงานลิงก์แบบปกติ

        const formTitle = document.getElementById('form-title');
        const submitBtn = document.getElementById('submit-btn');
        const toggleText = document.getElementById('toggle-text');
        const authForm = document.getElementById('auth-form');

        if (formTitle.textContent === 'Login') {
            // เปลี่ยนเป็นฟอร์ม Sign Up
            formTitle.textContent = 'Sign Up';
            submitBtn.textContent = 'Sign Up';
            toggleText.innerHTML = 'Already have an account? <a href="#" id="toggle-form">LOGIN</a>';
            authForm.action = 'register.php'; // เปลี่ยน action เป็น register.php
        } else {
            // เปลี่ยนกลับเป็นฟอร์ม Login
            formTitle.textContent = 'Login';
            submitBtn.textContent = 'Login';
            toggleText.innerHTML = 'Or Sign Up Using <a href="#" id="toggle-form">SIGN UP</a>';
            authForm.action = 'login.php'; // เปลี่ยน action เป็น login.php
        }
    }
});
document.getElementById('auth-form').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === "" || password === "") {
        e.preventDefault(); // ป้องกันการส่งฟอร์มเปล่า
        alert("Please fill in both fields.");
    }
});