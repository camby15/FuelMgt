<!-- Inactivity Tracker -->
<script src="{{ asset('js/inactivity-tracker.js') }}"></script><!-- bundle -->

<!-- Initialize Bootstrap Dropdowns -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Enable hover functionality for user dropdown
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        const dropdownMenu = userDropdown.nextElementSibling;
        
        // Show on hover
        userDropdown.parentElement.addEventListener('mouseenter', function() {
            dropdownMenu.classList.add('show');
            userDropdown.setAttribute('aria-expanded', 'true');
        });
        
        // Hide when mouse leaves
        userDropdown.parentElement.addEventListener('mouseleave', function() {
            dropdownMenu.classList.remove('show');
            userDropdown.setAttribute('aria-expanded', 'false');
        });
        
        // Also allow click to toggle
        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
                userDropdown.setAttribute('aria-expanded', 'false');
            } else {
                dropdownMenu.classList.add('show');
                userDropdown.setAttribute('aria-expanded', 'true');
            }
        });
    }
});
</script>

@yield('script')
<!-- App js -->
@yield('script-bottom')
