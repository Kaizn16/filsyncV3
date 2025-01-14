document.getElementById('togglePassword-signin').addEventListener('click', function () {
    const passwordField = document.getElementById('signin-password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    this.innerText = type === 'password' ? 'visibility' : 'visibility_off';
});
