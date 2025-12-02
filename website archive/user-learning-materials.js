function filterMaterials() {
    const select = document.getElementById('subjectSelect');
    const selectedSubject = select.value;
    const materials = document.querySelectorAll('.material-item');
    
    let hasVisible = false;
    
    materials.forEach(material => {
        const materialSubject = material.getAttribute('data-subject');
        
        if (selectedSubject === 'all' || materialSubject === selectedSubject) {
            material.style.display = 'flex'; // Change to flex to match CSS
            hasVisible = true;
        } else {
            material.style.display = 'none';
        }
    });
    
    // Show "no materials" message if none are visible
    const noMaterialsDiv = document.querySelector('.no-materials');
    if (!hasVisible) {
        if (!noMaterialsDiv) {
            const container = document.getElementById('materialsContainer');
            const message = document.createElement('div');
            message.className = 'no-materials';
            message.innerHTML = '<p>No learning materials available for this subject.</p>';
            container.appendChild(message);
        }
    } else if (noMaterialsDiv) {
        noMaterialsDiv.remove();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    filterMaterials();
});