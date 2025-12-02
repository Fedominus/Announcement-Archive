// ========== EXISTING CODE: Subject Selector ==========
const selectElement = document.getElementById('select69');

selectElement.addEventListener('change', function() {
    const divs = document.querySelectorAll('.content-div');
    
    // Hide all divs
    divs.forEach(div => {
        div.style.display = 'none';
    });

    // Show the div that matches the selected value (if not empty)
    if (this.value) {
        const selectedDiv = document.getElementById(this.value);
        if (selectedDiv) {
            selectedDiv.style.display = 'block';
            
            // Load files for selected subject
            if (subjectMapping[this.value]) {
                loadFiles(subjectMapping[this.value]);
            }
        }
    }
});

// ========== NEW CODE: File Upload System ==========

// Subject code mapping
const subjectMapping = {
    'container2': 'IT-221',
    'container3': 'IT-211',
    'container4': 'NET-201',
    'container5': 'RIZAL-201',
    'container6': 'ACCTG-201',
    'container7': 'ENV-201',
    'container8': 'DIS-201',
    'container9': 'RPH-201',
    'container10': 'PATHFit-3'
};

let currentSubject = '';

// Open upload modal
function openUploadModal(subjectCode) {
    currentSubject = subjectCode;
    document.getElementById('subjectCode').value = subjectCode;
    document.getElementById('uploadModal').style.display = 'flex';
}

// Close upload modal
function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    document.getElementById('uploadForm').reset();
    document.getElementById('uploadProgress').style.display = 'none';
    document.getElementById('progressBar').style.width = '0%';
}

// Handle file upload
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const progressDiv = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        
        progressDiv.style.display = 'block';
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        
        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percentComplete + '%';
                progressBar.textContent = percentComplete + '%';
            }
        });
        
        xhr.addEventListener('load', function() {
            console.log('XHR Status:', xhr.status);
            console.log('XHR Response:', xhr.responseText);
            
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('File uploaded successfully!');
                        closeUploadModal();
                        loadFiles(currentSubject);
                    } else {
                        alert('Upload Error: ' + response.message);
                    }
                } catch (e) {
                    console.error('JSON Parse Error:', e);
                    alert('Server returned invalid JSON. Check console for details.');
                }
            } else {
                alert('Upload failed with status: ' + xhr.status + '. Response: ' + xhr.responseText);
            }
            document.getElementById('uploadProgress').style.display = 'none';
        });

        xhr.addEventListener('error', function() {
            console.error('XHR Error Event Fired');
            alert('Network Error: Cannot connect to server. Check if upload_file.php exists.');
            document.getElementById('uploadProgress').style.display = 'none';
        });
        
        xhr.addEventListener('error', function() {
            alert('Upload failed. Please try again.');
            progressDiv.style.display = 'none';
        });
        
        xhr.open('POST', 'upload_file.php', true);
        xhr.send(formData);
    });

    // Load files for all subjects on page load
    Object.values(subjectMapping).forEach(subjectCode => {
        const container = document.getElementById('files-' + subjectCode);
        if (container) {
            loadFiles(subjectCode);
        }
    });
});

// Load files for a subject
function loadFiles(subjectCode) {
    console.log('Loading files for:', subjectCode); // Debug
    
    const container = document.getElementById('files-' + subjectCode);
    
    if (!container) {
        console.error('Container not found for:', subjectCode);
        return;
    }
    
    // Show loading message
    container.innerHTML = '<p style="text-align: center; color: #999; padding: 20px;">Loading files...</p>';
    
    fetch('get_file.php?subject_code=' + encodeURIComponent(subjectCode))
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Files data:', data); // Debug
            
            if (data.success) {
                if (data.files.length === 0) {
                    container.innerHTML = '<p style="text-align: center; color: #999; padding: 20px;">No files uploaded yet.</p>';
                } else {
                    container.innerHTML = '';
                    data.files.forEach(file => {
                        const fileDiv = document.createElement('div');
                        fileDiv.style.cssText = 'display: flex; align-items: center; justify-content: space-between; padding: 15px; margin: 10px 0; background: #f9f9f9; border-radius: 8px; border: 1px solid #ddd;';
                        
                        const fileIcon = getFileIcon(file.file_name);
                        
                        fileDiv.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                                <img src="images/${fileIcon}" style="width: 40px; height: 40px;" />
                                <div style="flex: 1;">
                                    <h4 style="margin: 0; color: #333;">${file.file_name}</h4>
                                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">
                                        Uploaded by: ${file.uploaded_by} | 
                                        ${formatDate(file.upload_date)} | 
                                        ${formatFileSize(file.file_size)}
                                    </p>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <a href="${file.file_path}" download="${file.file_name}" style="padding: 8px 15px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">Download</a>
                                <button onclick="deleteFile(${file.id}, '${subjectCode}')" style="padding: 8px 15px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">Delete</button>
                            </div>
                        `;
                        
                        container.appendChild(fileDiv);
                    });
                }
            } else {
                container.innerHTML = '<p style="text-align: center; color: #f44336; padding: 20px;">Error: ' + data.message + '</p>';
            }
        })
        .catch(error => {
            console.error('Error loading files:', error);
            container.innerHTML = '<p style="text-align: center; color: #f44336; padding: 20px;">Error loading files. Check console.</p>';
        });
}

// Delete file
function deleteFile(fileId, subjectCode) {
    if (confirm('Are you sure you want to delete this file?')) {
        console.log('Attempting to delete file ID:', fileId, 'for subject:', subjectCode);
        
        const formData = new FormData();
        formData.append('file_id', fileId);
        
        fetch('delete_file.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File deleted successfully!');
                loadFiles(subjectCode);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting file:', error);
            alert('Error deleting file. Check console for details.');
        });
    }
}

// Function to load materials for a specific subject
function loadMaterials(subjectCode) {
const container = document.getElementById(`files-${subjectCode}`);
    
// Clear existing content
container.innerHTML = '<p>Loading materials...</p>';

// Fetch materials via AJAX
fetch(`get_file.php?subject_code=${subjectCode}`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.files.length > 0) {
            let html = '';
            data.files.forEach(file => {
                html += `
                    <div class="material-item">
                        <div class="material-content">
                            <img src="images/${getFileIcon(file.file_name)}" />
                            <div class="material-info">
                                <h4>${file.file_name}</h4>
                                <p>Uploaded: ${file.upload_date}</p>
                            </div>
                            <div class="material-actions">
                                <a href="download_file.php?id=${file.id}" class="download-btn">Download</a>
                                <button onclick="deleteFile(${file.id}, '${subjectCode}')" class="delete-btn">Delete</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        } else {
            container.innerHTML = '<p>No materials uploaded yet.</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = '<p>Error loading materials.</p>';
    });
}




// function getFileIcon(filename) {
//     const ext = filename.split('.').pop().toLowerCase();
//     if (ext === 'pdf') return 'pdf.png';
//     if (['doc', 'docx'].includes(ext)) return 'word.png';
//     if (['ppt', 'pptx'].includes(ext)) return 'ppt.png';
//     if (['xls', 'xlsx'].includes(ext)) return 'excel.png';
//     return 'file-icon.png';
// }





// Get file icon based on extension
function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        'pdf': 'pdf.png',
        'doc': 'doc.png',
        'docx': 'doc.png',
        'ppt': 'ppt.png',
        'pptx': 'ppt.png',
        'xls': 'excel.png',
        'xlsx': 'excel.png',
        'txt': 'txt.png',
        'zip': 'zip.png',
        'rar': 'zip.png'
    };
    return icons[ext] || 'pdf.png';
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

