document.getElementById('phoneForm').addEventListener('submit', function(event) {
    var phoneInput = document.getElementById('phone');
    var phonePattern = /^\+?\d{0,10}$/;
    if (!phonePattern.test(phoneInput.value)) {
        phoneInput.classList.add('is-invalid');
        event.preventDefault();
    } else {
        phoneInput.classList.remove('is-invalid');
    }
});