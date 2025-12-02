
let currentFilter = 'all';

// Search function
function searchColleagues() {
    const searchTerm = document.getElementById('searchBox').value.toLowerCase();
    const cards = document.querySelectorAll('.colleague-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const name = card.dataset.name || '';
        const id = card.dataset.id || '';
        const section = card.dataset.section || '';
        
        const matchesSearch = name.includes(searchTerm) || 
                                id.includes(searchTerm) || 
                                section.includes(searchTerm);
        
        const matchesFilter = checkFilter(card, currentFilter);
        
        if (matchesSearch && matchesFilter) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    document.getElementById('displayCount').textContent = visibleCount;
}

// Filter function
function filterColleagues(filterType) {
    currentFilter = filterType;
    
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    const cards = document.querySelectorAll('.colleague-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        if (checkFilter(card, filterType)) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    document.getElementById('displayCount').textContent = visibleCount;
}

// Check if card matches filter
function checkFilter(card, filterType) {
    const userSection = card.dataset.userSection;
    const cardSection = card.dataset.section;
    const role = card.dataset.role;
    const isNew = card.dataset.new === '1';
    
    switch(filterType) {
        case 'all':
            return true;
        case 'same-section':
            return cardSection === userSection;
        case 'admins':
            return role === 'admin';
        case 'users':
            return role === 'user';
        case 'new':
            return isNew;
        default:
            return true;
    }
}