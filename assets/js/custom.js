// Initialize custom searchable dropdowns
function initializeDropdowns() {
    document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
        const hiddenInput = wrapper.querySelector('input[type="hidden"]');
        const toggleInput = wrapper.querySelector('.dropdown-toggle');
        const dropdownList = wrapper.querySelector('.dropdown-list');
        const searchInput = wrapper.querySelector('.dropdown-search');
        const optionsList = wrapper.querySelectorAll('.dropdown-list li');

        // Determine dropdown position (above or below)
        const setDropdownPosition = () => {
            const rect = toggleInput.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const isLongList = hiddenInput.id === 'farmProduce' || hiddenInput.id === 'incomeCategory';
            const dropdownHeight = Math.min(200, dropdownList.scrollHeight); 

            if (isLongList || spaceBelow < dropdownHeight + 20) {
                dropdownList.classList.add('above');
                dropdownList.classList.remove('below');
            } else {
                dropdownList.classList.add('below');
                dropdownList.classList.remove('above');
            }
        };

        // Toggle dropdown on click
        toggleInput.addEventListener('click', () => {
            setDropdownPosition();
            dropdownList.classList.toggle('active');
            if (dropdownList.classList.contains('active')) {
                searchInput.focus();
            }
        });

        // Filter options based on search
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            optionsList.forEach(option => {
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(filter) ? 'block' : 'none';
            });
        });

        // Select option
        optionsList.forEach(option => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-value');
                const text = option.textContent;
                hiddenInput.value = value;
                toggleInput.value = text;
                dropdownList.classList.remove('active');
                searchInput.value = '';
                optionsList.forEach(opt => opt.style.display = 'block');
                // Trigger change event for custom handling (e.g., exchange rate update)
                if (hiddenInput.id === 'currency' && typeof jQuery !== 'undefined') {
                    jQuery(hiddenInput).trigger('change');
                }
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', e => {
            if (!wrapper.contains(e.target)) {
                dropdownList.classList.remove('active');
                searchInput.value = '';
                optionsList.forEach(opt => opt.style.display = 'block');
            }
        });

        // Keyboard navigation
        searchInput.addEventListener('keydown', e => {
            const visibleOptions = Array.from(optionsList).filter(opt => opt.style.display !== 'none');
            if (visibleOptions.length === 0) return;
            let selectedIndex = visibleOptions.findIndex(opt => opt.classList.contains('focused'));
            if (selectedIndex === -1) selectedIndex = 0;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % visibleOptions.length;
                visibleOptions.forEach(opt => opt.classList.remove('focused'));
                visibleOptions[selectedIndex].classList.add('focused');
                visibleOptions[selectedIndex].scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + visibleOptions.length) % visibleOptions.length;
                visibleOptions.forEach(opt => opt.classList.remove('focused'));
                visibleOptions[selectedIndex].classList.add('focused');
                visibleOptions[selectedIndex].scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                const option = visibleOptions[selectedIndex];
                hiddenInput.value = option.getAttribute('data-value');
                toggleInput.value = option.textContent;
                dropdownList.classList.remove('active');
                searchInput.value = '';
                optionsList.forEach(opt => opt.style.display = 'block');
                if (hiddenInput.id === 'currency' && typeof jQuery !== 'undefined') {
                    jQuery(hiddenInput).trigger('change');
                }
            } else if (e.key === 'Escape') {
                dropdownList.classList.remove('active');
                searchInput.value = '';
                optionsList.forEach(opt => opt.style.display = 'block');
            }
        });
    });
}

// Custom alert handling
function showAlert(type, message) {
    const alert = document.getElementById('customAlert');
    const alertText = alert.querySelector('.alert-text');
    const alertIcon = alert.querySelector('.alert-icon');

    alertText.textContent = message;
    alert.classList.remove('alert-error', 'alert-success');
    alert.classList.add(`alert-${type}`);
    alertIcon.textContent = type === 'success' ? '✅' : '❗';
    alert.classList.add('show');
}

function hideAlert() {
    const alert = document.getElementById('customAlert');
    alert.classList.remove('show');
}

// Initialize components on page load
document.addEventListener('DOMContentLoaded', () => {
    initializeDropdowns();
    const alertCloseBtn = document.getElementById('customAlert')?.querySelector('.btn-close');
    if (alertCloseBtn) {
        alertCloseBtn.addEventListener('click', hideAlert);
    }
});