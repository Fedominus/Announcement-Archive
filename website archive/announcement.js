const addAnns = document.getElementById('addAnns');
const formContainer = document.getElementById('formContainer');
const cancelBtn = document.getElementById('cancelBtn');
const board = document.getElementById('hidden');
const textarea = document.getElementById('textBox');
const uploadBtn = document.getElementById('uploadBtn');
const expirationDate = document.getElementById('expirationDate');

// Toggle form visibility
addAnns.addEventListener('click', () => {
    formContainer.classList.remove('hidden');
    board.removeAttribute('id');
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    expirationDate.min = today;
});

cancelBtn.addEventListener('click', () => {
    formContainer.classList.add('hidden');
    board.id = 'hidden';
    textarea.value = '';
    expirationDate.value = '';
    textarea.style.height = 'auto';
});

// Auto-expand textarea
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

// UPLOAD BUTTON - AJAX submission
uploadBtn.addEventListener('click', async function() {
    const announcementText = textarea.value.trim();
    const expiration = expirationDate.value;

    if (announcementText === '') {
        alert('Please enter announcement text.');
        return;
    }
    
    if (expiration === '') {
        alert('Please select an expiration date.');
        return;
    }

    // Disable button during processing
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = 'Uploading...';

    try {
        const formData = new FormData();
        formData.append('announcement', announcementText);
        formData.append('expiration', expiration);
        formData.append('add_announcement_ajax', true);

        // Send to same file
        const response = await fetch('announcement.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Add new announcement to the top
            const scrollBox = document.querySelector('.scroll-box');
            const newBoard = document.createElement('div');
            newBoard.className = 'board';
            
            const newH3 = document.createElement('h3');
            newH3.textContent = `(${result.section}) ${result.full_name}`;
            
            const newContainer = document.createElement('div');
            newContainer.className = 'container';
            
            const newP = document.createElement('p');
            newP.textContent = announcementText;
            
            const newSmall = document.createElement('small');
            const expDate = new Date(result.expires_at);
            newSmall.textContent = `Expires: ${expDate.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            })}`;
            
            newContainer.appendChild(newP);
            newContainer.appendChild(newSmall);
            newBoard.appendChild(newH3);
            newBoard.appendChild(newContainer);
            
            // Insert after the form (before other announcements)
            const formBoard = document.querySelector('#hidden');
            if (formBoard) {
                scrollBox.insertBefore(newBoard, formBoard.nextSibling);
            }
            
            // Clear and reset form
            textarea.value = '';
            expirationDate.value = '';
            textarea.style.height = 'auto';
            formContainer.classList.add('hidden');
            board.id = 'hidden';
            
            // Show success message
            alert('Announcement added successfully!');
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while saving the announcement.');
    } finally {
        // Re-enable button
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = 'Upload';
    }
});