// Simple test to check if jQuery and Bootstrap are loaded
console.log('jQuery version:', $.fn.jquery);
console.log('Bootstrap Modal test:', typeof $.fn.modal === 'function' ? 'Loaded' : 'Not loaded');

// Test click handler
$(document).on('click', '.edit-project', function(e) {
    console.log('Edit button clicked!', $(this).data());
    e.preventDefault();
    
    // Manually show the modal
    $('#editProjectModal').modal('show');
});
