document.getElementById(`last-name`).addEventListener(`input`, function (e) {
    this.value = this.value.replace(/[^a-zA-Z\s]/g, ``);
});

document.getElementById(`last-name`).addEventListener(`keypress`, function (e) {
    const allowed = /[a-zA-Z\s]/;
    if (!allowed.test(e.key)) {
        e.preventDefault();
    }
})

document.getElementById(`first-name`).addEventListener(`input`, function (e) {
    this.value = this.value.replace(/[^a-zA-Z\s]/g, ``);
});

document.getElementById(`first-name`).addEventListener(`keypress`, function (e) {
    const allowed = /[a-zA-Z\s]/;
    if (!allowed.test(e.key)) {
        e.preventDefault();
    }
})

document.getElementById(`middle-ini`).addEventListener(`input`, function (e) {
    this.value = this.value.replace(/[^a-zA-Z\s]/g, ``);
});

document.getElementById(`middle-ini`).addEventListener(`keypress`, function (e) {
    const allowed = /[a-zA-Z\s]/;
    if (!allowed.test(e.key)) {
        e.preventDefault();
    }
})

document.getElementById(`parent`).addEventListener(`input`, function (e) {
    this.value = this.value.replace(/[^a-zA-Z\s]/g, ``);
});

document.getElementById(`parent`).addEventListener(`keypress`, function (e) {
    const allowed = /[a-zA-Z\s]/;
    if (!allowed.test(e.key)) {
        e.preventDefault();
    }
})

const editBtn = document.getElementById('edit-but');
const firstnameInput = document.getElementById('first-name');
const lastnameInput = document.getElementById('last-name');
const middleInput = document.getElementById(`middle-ini`);
const genderInput = document.getElementById(`gender`);
const ageInput = document.getElementById(`age`);
const birthInput = document.getElementById(`birth-date`);
const programInput = document.getElementById(`program`);
const yearInput = document.getElementById(`year-level`);
const parentInput = document.getElementById(`parent`);
const parentNumInput = document.getElementById(`phone-no`);
const addressInput = document.getElementById(`address`);
const CpassInput = document.getElementById(`c-password`);
const NpassInput = document.getElementById(`n-password`);
const CFpassInput = document.getElementById(`cf-password`);

editBtn.addEventListener('click', function () {
    if (firstnameInput.disabled) {
        
        firstnameInput.disabled = false;
        lastnameInput.disabled = false;
        middleInput.disabled = false;
        genderInput.disabled = false;
        ageInput.disabled = false;
        birthInput.disabled = false;
        programInput.disabled = false;
        yearInput.disabled = false;
        parentInput.disabled = false;
        parentNumInput.disabled = false;
        addressInput.disabled = false;
        CpassInput.disabled = false;
        NpassInput.disabled = false;
        CFpassInput.disabled = false;

        editBtn.textContent = 'Save';


        firstnameInput.classList.remove('disabled');
        lastnameInput.classList.remove('disabled');
        middleInput.classList.remove(`disabled`);
        genderInput.classList.remove(`disabled`);
        ageInput.classList.remove(`disabled`);
        birthInput.classList.remove(`disabled`);
        programInput.classList.remove(`disabled`);
        yearInput.classList.remove(`disabled`);
        parentInput.classList.remove(`disabled`);
        parentNumInput.classList.remove(`disabled`);
        addressInput.classList.remove(`disabled`);
        CpassInput.classList.remove(`disabled`);
        NpassInput.classList.remove(`disabled`);
        CFpassInput.classList.remove(`disabled`);
    } 
    else {
        
        firstnameInput.disabled = true;
        lastnameInput.disabled = true;
        middleInput.disabled = true;
        genderInput.disabled = true;
        ageInput.disabled = true;
        birthInput.disabled = true;
        programInput.disabled = true;
        yearInput.disabled = true;
        parentInput.disabled = true;
        parentNumInput.disabled = true;
        addressInput.disabled = true;
        CpassInput.disabled = true;
        NpassInput.disabled = true;
        CFpassInput.disabled = true;

        editBtn.textContent = 'Edit Information 🗒️';

        firstnameInput.classList.add('disabled');
        lastnameInput.classList.add('disabled');
        middleInput.classList.add(`disabled`);
         genderInput.classList.add(`disabled`);
        ageInput.classList.add(`disabled`);
        birthInput.classList.add(`disabled`);
        programInput.classList.add(`disabled`);
        yearInput.classList.add(`disabled`);
        parentInput.classList.add(`disabled`);
        parentNumInput.classList.add(`disabled`);
        addressInput.classList.add(`disabled`);
        CpassInput.classList.add(`disabled`);
        NpassInput.classList.add(`disabled`);
        CFpassInput.classList.add(`disabled`);
        
        alert('Your changes have been saved.');
    }
});

// show at hide part ng password perd

const toggleBtn = document.getElementById('toggleBtn');
const toggleImg = document.getElementById('toggleImg');

toggleBtn.addEventListener('click', function() {
    if (CpassInput.type === 'password') {
        CpassInput.type = 'text';
        NpassInput.type = 'text';
        CFpassInput.type = 'text';
        toggleImg.src = 'images/view.png'; 
    }
    else {
        CpassInput.type = 'password';
        NpassInput.type = 'password';
        CFpassInput.type = 'password';
        toggleImg.src = 'images/hide.png'; 
    }
});