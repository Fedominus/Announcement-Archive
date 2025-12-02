function validatePassword(password) {
    return {
        length: password.length >= 8,
        upper: /[A-Z]/.test(password),
        lower: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[^A-Za-z0-9]/.test(password)
    };
}

function updateRequirements(password) {
    const checks = validatePassword(password);
    document.getElementById('length').className = checks.length ? 'valid' : 'invalid';
    document.getElementById('upper').className = checks.upper ? 'valid' : 'invalid';
    document.getElementById('lower').className = checks.lower ? 'valid' : 'invalid';
    document.getElementById('number').className = checks.number ? 'valid' : 'invalid';
    document.getElementById('special').className = checks.special ? 'valid' : 'invalid';
}

document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const reqDiv = document.getElementById('requirements');
    const checks = validatePassword(password);

    if (password.length > 0 && !(checks.length && checks.upper && checks.lower && checks.number && checks.special)) {
        reqDiv.style.display = 'block';
        updateRequirements(password);
    } else {
        reqDiv.style.display = 'none';
    }
});

const password = document.getElementById("password");
const toggle = document.getElementById("toggle");
const cpass = document.getElementById("c-password")

toggle.addEventListener("click", () => {
    if (password.type == "password") {
        password.type = "text";
        cpass.type = "text";
        toggle.textContent = "Hide";
    }
    else {
        password.type = "password";
        cpass.type = "password";
        toggle.textContent = "Show";

    }
})


document.getElementById('SchlID').addEventListener('input', function (e) {
    let value = e.target.value;
    value = value.replace(/[^0-9-]/g, '');
    let parts = value.split('-');

    if (parts[0]) {
        parts[0] = parts[0].slice(0, 4);
    }

    if (parts.length > 1) {
        parts[1] = parts.slice(1).join('').replace(/[^0-9]/g, '').slice(0, 5);
        parts = [parts[0], parts[1]];
    }

    value = parts[0];
    if (parts[0].length === 4 && parts[1] !== undefined) {
        value += '-' + parts[1];
    } else if (parts[0].length > 4) {

        value = parts[0].slice(0, 4) + '-' + parts[0].slice(4) + (parts[1] || '');
    }

    value = value.slice(0, 10);

    e.target.value = value;
});