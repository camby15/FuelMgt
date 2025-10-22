/**
 * Payroll Management System
 * Handles all payroll related functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize date pickers
    initDatePickers();
    
    // Initialize event listeners
    initEventListeners();
    
    // Load initial data
    loadPayrollSummary();
});

/**
 * Initialize date pickers with default values and constraints
 */
function initDatePickers() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    // Format dates as YYYY-MM-DD
    const formatDate = (date) => date.toISOString().split('T')[0];
    
    // Set default values
    document.getElementById('start_date').value = formatDate(firstDay);
    document.getElementById('end_date').value = formatDate(lastDay);
    document.getElementById('payment_date').value = formatDate(today);
    
    // Set min/max dates
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.min = '2020-01-01';
        input.max = '2030-12-31';
    });
}

/**
 * Initialize event listeners for interactive elements
 */
function initEventListeners() {
    // Run Payroll Form Submission
    const runPayrollForm = document.getElementById('runPayrollForm');
    if (runPayrollForm) {
        runPayrollForm.addEventListener('submit', handleRunPayroll);
    }
    
    // Add Salary Form Submission
    const addSalaryForm = document.getElementById('addSalaryForm');
    if (addSalaryForm) {
        addSalaryForm.addEventListener('submit', handleAddSalary);
    }
    
    // Search functionality
    const searchInput = document.getElementById('searchPayroll');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }
    
    // Refresh button
    const refreshBtn = document.getElementById('refreshPayroll');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', handleRefresh);
    }
    
    // Export button
    const exportBtn = document.getElementById('exportPayroll');
    if (exportBtn) {
        exportBtn.addEventListener('click', handleExport);
    }
    
    // Tab change event
    const tabEls = document.querySelectorAll('#payrollTabs button[data-bs-toggle="tab"]');
    tabEls.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', handleTabChange);
    });
}

/**
 * Handle Run Payroll form submission
 */
function handleRunPayroll(e) {
    e.preventDefault();
    
    const btn = document.querySelector('#runPayrollBtn');
    const spinner = btn.querySelector('.spinner-border');
    const icon = btn.querySelector('i');
    
    // Show loading state
    btn.disabled = true;
    spinner.classList.remove('d-none');
    icon.classList.add('d-none');
    
    // Get form data
    const formData = {
        pay_period: document.getElementById('pay_period').value,
        start_date: document.getElementById('start_date').value,
        end_date: document.getElementById('end_date').value,
        payment_date: document.getElementById('payment_date').value,
        include_bonuses: document.getElementById('include_bonuses').checked,
        include_deductions: document.getElementById('include_deductions').checked
    };
    
    // Simulate API call
    setTimeout(() => {
        // Hide loading state
        btn.disabled = false;
        spinner.classList.add('d-none');
        icon.classList.remove('d-none');
        
        // Show success message
        showAlert('Payroll processed successfully!', 'success');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('runPayrollModal'));
        modal.hide();
        
        // Refresh data
        loadPayrollSummary();
    }, 2000);
}

/**
 * Handle Add Salary form submission
 */
function handleAddSalary(e) {
    e.preventDefault();
    
    // Get form data
    const formData = {
        employee_id: document.getElementById('employee_id').value,
        basic_salary: document.getElementById('basic_salary').value,
        housing_allowance: document.getElementById('housing_allowance').value || 0,
        transport_allowance: document.getElementById('transport_allowance').value || 0,
        // Add other fields as needed
    };
    
    // Here you would typically make an API call to save the data
    console.log('Saving salary data:', formData);
    
    // Show success message
    showAlert('Salary record added successfully!', 'success');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addSalaryModal'));
    modal.hide();
    
    // Reset form
    e.target.reset();
}

/**
 * Handle search functionality
 */
function handleSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    console.log('Searching for:', searchTerm);
    
    // Here you would typically make an API call to search
    // For now, we'll just log it
}

/**
 * Handle refresh button click
 */
function handleRefresh() {
    const btn = document.getElementById('refreshPayroll');
    const icon = btn.querySelector('i');
    
    // Add rotation animation
    icon.classList.add('fa-spin');
    
    // Reload data
    loadPayrollSummary();
    
    // Remove animation after a delay
    setTimeout(() => {
        icon.classList.remove('fa-spin');
    }, 1000);
}

/**
 * Handle export button click
 */
function handleExport() {
    // Here you would typically generate and download a report
    console.log('Exporting payroll data...');
    showAlert('Export started. You will receive an email when ready.', 'info');
}

/**
 * Handle tab changes
 */
function handleTabChange(e) {
    const targetTab = e.target.getAttribute('data-bs-target');
    console.log('Switched to tab:', targetTab);
    
    // Load data for the selected tab
    switch(targetTab) {
        case '#payroll-summary':
            loadPayrollSummary();
            break;
        case '#salary-structure':
            loadSalaryStructures();
            break;
        case '#payslips':
            loadPayslips();
            break;
    }
}

/**
 * Load payroll summary data
 */
function loadPayrollSummary() {
    // Here you would typically make an API call to get the data
    console.log('Loading payroll summary...');
    
    // Simulate API call
    setTimeout(() => {
        // Update UI with data
        console.log('Payroll summary loaded');
    }, 500);
}

/**
 * Load salary structures
 */
function loadSalaryStructures() {
    console.log('Loading salary structures...');
    
    // Simulate API call
    setTimeout(() => {
        console.log('Salary structures loaded');
    }, 500);
}

/**
 * Load payslips
 */
function loadPayslips() {
    console.log('Loading payslips...');
    
    // Simulate API call
    setTimeout(() => {
        console.log('Payslips loaded');
    }, 500);
}

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alert.style.zIndex = '1100';
    alert.role = 'alert';
    
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Add to DOM
    document.body.appendChild(alert);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => {
            alert.remove();
        }, 150);
    }, 5000);
}

/**
 * Debounce function to limit the rate at which a function can fire
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
