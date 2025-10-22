class PayrollManager {
    constructor() {
        this.employees = [];
        this.initializeDataTable();
        this.initializeEventListeners();
    }

    initializeDataTable() {
        this.dataTable = $('#payroll-datatable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: '<i class="fas fa-search"></i>',
                searchPlaceholder: 'Search payroll records...',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No records available',
                zeroRecords: 'No matching records found',
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    previous: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>'
                }
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });
    }

    initializeEventListeners() {
        // Payroll Actions
        $('#generatePayrollBtn').on('click', () => this.generatePayroll());
        
        // Add Payroll Modal
        $('#addPayrollModal').on('show.bs.modal', () => this.setupAddPayrollModal());
        $('#addPayrollForm').on('submit', (e) => this.handleAddPayrollSubmit(e));
        
        // Process Payroll Modal
        $('#processPayrollModal').on('show.bs.modal', () => this.setupProcessPayrollModal());
        $('#confirmProcessPayroll').on('click', () => this.processPayroll());
        
        // Import/Export
        $('#confirmImport').on('click', () => this.handleFileImport());
        
        // Modals
        $('#editPayrollModal').on('show.bs.modal', (e) => this.setupEditModal(e));
        $('#bankDetailsModal').on('show.bs.modal', () => this.setupBankDetailsModal());
        $('#viewPayslipModal').on('show.bs.modal', () => this.setupPayslipModal());
        $('#processBonusModal').on('show.bs.modal', () => this.setupBonusModal());
        
        // Forms
        $('#payrollEntryForm').on('submit', (e) => this.handlePayrollSubmit(e));
        $('#processBonusForm').on('submit', (e) => this.handleBonusSubmit(e));
        
        // Dynamic elements
        $('#addAllowance').on('click', () => this.addAllowanceRow());
        $('#addDeduction').on('click', () => this.addDeductionRow());
        $(document).on('click', '.remove-item', (e) => this.removeItem(e));
        
        // Input changes
        $(document).on('input', '.allowance-amount, .deduction-amount, [name="basic_salary"], [name="overtime"]', 
            () => this.updateNetPay());
    }

    // Payroll Actions
    generatePayroll() {
        const month = $('#payrollMonth option:selected').text();
        const year = $('#payrollYear').val();
        this.showToast('success', `Payroll generated for ${month} ${year}`);
    }

    processPayroll() {
        this.showToast('success', 'Payroll processed successfully!');
    }

    // Modal Handlers
    setupEditModal(e) {
        const button = $(e.relatedTarget);
        const empId = button.data('employee-id');
        const employee = this.employees.find(e => e.id === empId);
        
        if (!employee) return;

        $('#payrollEntryForm [name="employee"]').val(employee.name);
        $('#payrollEntryForm [name="employee_id"]').val(employee.id);
        $('#payrollEntryForm [name="basic_salary"]').val(employee.basic.toFixed(2));
        $('#payrollEntryForm [name="overtime"]').val(employee.overtime.toFixed(2));
        
        // Clear existing rows
        $('#allowancesContainer, #deductionsContainer').empty();
        
        // Add sample data
        this.addAllowanceRow('Housing', employee.allowances);
        this.addDeductionRow('Tax', employee.deductions);
        
        this.updateNetPay();
    }

    setupBankDetailsModal() {
        // Reset form
        $('.bank-account-masked').val('•••• •••• •••• 1234');
        $('#showAccountNumber i')
            .removeClass('fa-eye-slash')
            .addClass('fa-eye');
    }

    setupPayslipModal() {
        // Implementation for payslip modal setup
    }

    setupBonusModal() {
        const select = $('#bonusEmployee');
        select.empty().append('<option value="">Select Employee</option>');
        
        this.employees.forEach(emp => {
            select.append(`<option value="${emp.id}">${emp.name} (${emp.id})</option>`);
        });
        
        $('#bonusDate').val(new Date().toISOString().split('T')[0]);
    }

    // Form Handlers
    handlePayrollSubmit(e) {
        e.preventDefault();
        this.showToast('success', 'Payroll entry updated successfully!');
        $('#editPayrollModal').modal('hide');
    }

    handleBonusSubmit(e) {
        e.preventDefault();
        // Implementation for bonus submission
        console.log('Bonus submitted');
        $('#processBonusModal').modal('hide');
        this.showToast('Success', 'Bonus processed successfully!', 'success');
    }

    // Setup the Add Payroll modal with default values
    setupAddPayrollModal() {
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        $('#paymentDate').val(today);
        
        // Reset form
        $('#addPayrollForm')[0].reset();
        
        // Set up event listeners for calculations
        this.setupPayrollCalculations();
        
        // If we have employee data, populate the department when an employee is selected
        if (window.employees && window.employees.length > 0) {
            $('#employeeSelect').on('change', (e) => {
                const employeeId = $(e.target).val();
                const employee = window.employees.find(emp => emp.id == employeeId);
                if (employee) {
                    $('#department').val(employee.department || 'N/A');
                    // Auto-fill basic salary if available
                    if (employee.basic_salary) {
                        $('#basicSalary').val(employee.basic_salary).trigger('input');
                    }
                } else {
                    $('#department').val('');
                }
            });
        }
    }
    
    // Set up event listeners for payroll calculations
    setupPayrollCalculations() {
        // Elements that should trigger calculations when changed
        const calculationElements = [
            '#basicSalary', '#housingAllowance', '#transportAllowance',
            '#overtime', '#bonus', '#otherAllowances', '#otherDeductions'
        ];
        
        // Attach input event to all calculation elements
        calculationElements.forEach(selector => {
            $(document).on('input', selector, () => this.calculatePayrollTotals());
        });
        
        // Initial calculation
        this.calculatePayrollTotals();
    }
    
    // Calculate payroll totals
    calculatePayrollTotals() {
        // Get all earnings
        const basicSalary = parseFloat($('#basicSalary').val()) || 0;
        const housingAllowance = parseFloat($('#housingAllowance').val()) || 0;
        const transportAllowance = parseFloat($('#transportAllowance').val()) || 0;
        const overtime = parseFloat($('#overtime').val()) || 0;
        const bonus = parseFloat($('#bonus').val()) || 0;
        const otherAllowances = parseFloat($('#otherAllowances').val()) || 0;
        
        // Calculate SSNIT (5.5% of basic salary, capped at GHS 247.50)
        const ssnit = Math.min(basicSalary * 0.055, 247.50);
        
        // Calculate 2nd Tier Pension (5% of basic salary)
        const tier2Pension = basicSalary * 0.05;
        
        // Calculate PAYE (simplified calculation - in a real app, use the Ghana PAYE tax bands)
        const taxableIncome = basicSalary + housingAllowance + transportAllowance + overtime + bonus - ssnit - tier2Pension;
        let paye = 0;
        
        if (taxableIncome > 402) {
            const excess = taxableIncome - 402;
            if (excess <= 110) {
                paye = excess * 0.05;
            } else if (excess <= 130) {
                paye = (110 * 0.05) + ((excess - 110) * 0.1);
            } else if (excess <= 3000) {
                paye = (110 * 0.05) + (20 * 0.1) + ((excess - 130) * 0.175);
            } else {
                paye = (110 * 0.05) + (20 * 0.1) + (2870 * 0.175) + ((excess - 3000) * 0.25);
            }
        }
        
        // Get other deductions
        const otherDeductions = parseFloat($('#otherDeductions').val()) || 0;
        
        // Calculate totals
        const totalEarnings = basicSalary + housingAllowance + transportAllowance + overtime + bonus + otherAllowances;
        const totalDeductions = ssnit + paye + tier2Pension + otherDeductions;
        const netPay = totalEarnings - totalDeductions;
        
        // Update the form fields
        $('#ssnit').val(ssnit.toFixed(2));
        $('#paye').val(paye.toFixed(2));
        $('#tier2Pension').val(tier2Pension.toFixed(2));
        
        // Update the summary
        $('#totalEarnings').text(`GHS ${totalEarnings.toFixed(2)}`);
        $('#totalDeductionsSummary').text(`GHS ${totalDeductions.toFixed(2)}`);
        $('#netPay').text(`GHS ${netPay.toFixed(2)}`);
    }
    
    // Handle Add Payroll form submission
    handleAddPayrollSubmit(e) {
        e.preventDefault();
        
        // Validate required fields
        const employeeId = $('#employeeSelect').val();
        const payPeriod = $('#payPeriod').val();
        const paymentDate = $('#paymentDate').val();
        
        if (!employeeId || !payPeriod || !paymentDate) {
            this.showToast('Error', 'Please fill in all required fields', 'error');
            return false;
        }
        
        // Prepare payroll data
        const payrollData = {
            employee_id: employeeId,
            pay_period: payPeriod,
            payment_date: paymentDate,
            basic_salary: parseFloat($('#basicSalary').val()) || 0,
            housing_allowance: parseFloat($('#housingAllowance').val()) || 0,
            transport_allowance: parseFloat($('#transportAllowance').val()) || 0,
            overtime: parseFloat($('#overtime').val()) || 0,
            bonus: parseFloat($('#bonus').val()) || 0,
            other_allowances: parseFloat($('#otherAllowances').val()) || 0,
            ssnit: parseFloat($('#ssnit').val()) || 0,
            paye: parseFloat($('#paye').val()) || 0,
            tier2_pension: parseFloat($('#tier2Pension').val()) || 0,
            other_deductions: parseFloat($('#otherDeductions').val()) || 0,
            total_earnings: parseFloat($('#totalEarnings').text().replace('GHS', '').trim()) || 0,
            total_deductions: parseFloat($('#totalDeductionsSummary').text().replace('GHS', '').trim()) || 0,
            net_pay: parseFloat($('#netPay').text().replace('GHS', '').trim()) || 0,
            notes: $('#payrollNotes').val()
        };
        
        // Show loading state
        const submitBtn = $('#addPayrollForm button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...');
        
        // Simulate API call
        console.log('Saving payroll data:', payrollData);
        
        // In a real app, you would make an AJAX call here:
        // $.ajax({
        //     url: '/api/payroll',
        //     method: 'POST',
        //     data: payrollData,
        //     success: (response) => {
        //         this.handleAddPayrollSuccess(response);
        //     },
        //     error: (error) => {
        //         this.handleAddPayrollError(error);
        //     }
        // });
        
        // For demo purposes, simulate a successful response after 1.5 seconds
        setTimeout(() => {
            submitBtn.prop('disabled', false).html(originalText);
            $('#addPayrollModal').modal('hide');
            this.showToast('Success', 'Payroll entry added successfully!', 'success');
            
            // Refresh the payroll table
            this.refreshPayrollTable();
        }, 1500);
        
        return false;
    }
    
    // Handle successful payroll addition
    handleAddPayrollSuccess(response) {
        // Reset form and close modal
        $('#addPayrollForm')[0].reset();
        $('#addPayrollModal').modal('hide');
        
        // Show success message
        this.showToast('Success', 'Payroll entry added successfully!', 'success');
        
        // Refresh the payroll table
        this.refreshPayrollTable();
    }
    
    // Handle payroll addition error
    handleAddPayrollError(error) {
        console.error('Error adding payroll:', error);
        this.showToast('Error', 'Failed to add payroll entry. Please try again.', 'error');
        
        // Re-enable submit button
        const submitBtn = $('#addPayrollForm button[type="submit"]');
        const originalText = submitBtn.data('original-text') || 'Save Payroll Entry';
        submitBtn.prop('disabled', false).html(originalText);
    }
    
    // Setup the Process Payroll modal with current data
    setupProcessPayrollModal() {
        // Calculate totals from the current DataTable
        const totalEmployees = this.dataTable.rows().count();
        const totalGross = this.calculateColumnTotal('gross-pay');
        const totalDeductions = this.calculateColumnTotal('deductions');
        const netPay = totalGross - totalDeductions;

        // Update the modal with calculated values
        $('#totalEmployees').text(totalEmployees);
        $('#totalGrossPay').text(`GHS ${totalGross.toFixed(2)}`);
        $('#totalDeductions').text(`GHS ${totalDeductions.toFixed(2)}`);
        $('#netPayTotal').text(`GHS ${netPay.toFixed(2)}`);
        
        // Set default payment date to today
        $('#paymentDate').val(new Date().toISOString().split('T')[0]);
    }

    // Helper to calculate column total from DataTable
    calculateColumnTotal(className) {
        return this.dataTable.rows().data().toArray().reduce((sum, row) => {
            const amount = parseFloat($(row[0]).find(`.${className}`).data('value') || 0);
            return sum + (isNaN(amount) ? 0 : amount);
        }, 0);
    }

    // Update the Run Payroll summary
    updateRunPayrollSummary() {
        const totalEmployees = this.dataTable.rows().count();
        $('.employee-count').text(`${totalEmployees} employees`);
        
        // Update the pay period display
        const now = new Date();
        const month = now.toLocaleString('default', { month: 'long' });
        const year = now.getFullYear();
        const lastDay = new Date(year, now.getMonth() + 1, 0).getDate();
        
        $('.pay-period').text(`${month} 1 - ${lastDay}, ${year}`);
        $('.pay-date').text(`${month} ${lastDay}, ${year}`);
    }

    // Process payroll (finalize and save)
    processPayroll() {
        if (!$('#confirmApprove').is(':checked')) {
            this.showToast('Error', 'Please confirm that all data has been reviewed and is accurate', 'error');
            return false;
        }

        const paymentMethod = $('#paymentMethod').val();
        const paymentDate = $('#paymentDate').val();
        const notes = $('#paymentNotes').val();
        const notifyEmployees = $('#notifyEmployees').is(':checked');

        // Show loading state
        const processBtn = $('#confirmProcessPayroll');
        const originalText = processBtn.html();
        processBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...');

        // Simulate API call
        setTimeout(() => {
            // Reset button state
            processBtn.prop('disabled', false).html(originalText);
            
            // Close modal
            $('#processPayrollModal').modal('hide');
            
            // Show success message
            this.showToast('Success', 'Payroll processed successfully!', 'success');
            
            // If notifications were requested
            if (notifyEmployees) {
                this.showToast('Notifications Sent', 'Employees have been notified about their payslips', 'info');
            }
            
            // Refresh the table or update status
            this.refreshPayrollTable();
            
        }, 2000);
        
        return false;
    }

    // Run the payroll process
    runPayroll() {
        if (!$('#confirmRunPayroll').is(':checked')) {
            this.showToast('Error', 'Please confirm that all data has been reviewed and is accurate', 'error');
            return false;
        }

        // Show loading state
        const runBtn = $('#confirmRunPayrollBtn');
        const originalText = runBtn.html();
        runBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Running...');

        // Simulate API call
        setTimeout(() => {
            // Reset button state
            runBtn.prop('disabled', false).html(originalText);
            
            // Close modal
            $('#runPayrollModal').modal('hide');
            
            // Show success message
            this.showToast('Success', 'Payroll run completed successfully!', 'success');
            
            // Show the process payroll modal next
            $('#processPayrollModal').modal('show');
            
        }, 3000);
        
        return false;
    }
    
    // Refresh the payroll table
    refreshPayrollTable() {
        // In a real app, this would fetch fresh data from the server
        this.dataTable.ajax.reload(null, false);
    }
    
    // Dynamic Elements
    addAllowanceRow(description = '', amount = 0) {
        const row = `
            <div class="row g-2 mb-2">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Description" value="${description}">
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">GHS</span>
                        <input type="number" class="form-control allowance-amount" value="${amount}" step="0.01">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-item">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>`;
        $('#allowancesContainer').append(row);
        this.updateNetPay();
    }

    addDeductionRow(description = '', amount = 0) {
        const row = `
            <div class="row g-2 mb-2">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Description" value="${description}">
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">GHS</span>
                        <input type="number" class="form-control deduction-amount" value="${amount}" step="0.01">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-item">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>`;
        $('#deductionsContainer').append(row);
        this.updateNetPay();
    }

    removeItem(e) {
        $(e.target).closest('.row').remove();
        this.updateNetPay();
    }

    // Calculations
    updateNetPay() {
        const basic = parseFloat($('#payrollEntryForm [name="basic_salary"]').val()) || 0;
        const overtime = parseFloat($('#payrollEntryForm [name="overtime"]').val()) || 0;
        
        let allowances = 0;
        $('.allowance-amount').each(function() {
            allowances += parseFloat($(this).val()) || 0;
        });
        
        let deductions = 0;
        $('.deduction-amount').each(function() {
            deductions += parseFloat($(this).val()) || 0;
        });
        
        const netPay = basic + overtime + allowances - deductions;
        $('#payrollEntryForm .net-pay-amount').text(`GHS ${netPay.toFixed(2)}`);
    }

    // File Handling
    handleFileImport() {
        const fileInput = $('#csvFile')[0];
        if (!fileInput.files.length) {
            this.showToast('danger', 'Please select a CSV file.');
            return;
        }
        
        const progressBar = $('#importProgress');
        progressBar.removeClass('d-none');
        
        // Simulate import
        let progress = 0;
        const interval = setInterval(() => {
            progress += 10;
            progressBar.find('.progress-bar').css('width', `${progress}%`);
            
            if (progress >= 100) {
                clearInterval(interval);
                this.showToast('success', 'Payroll data imported successfully!');
                $('#importCSVModal').modal('hide');
                progressBar.addClass('d-none').find('.progress-bar').css('width', '0%');
                fileInput.value = '';
            }
        }, 100);
    }

    // Utilities
    showToast(type, message) {
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`);
        
        $('.toast-container').append(toast);
        const toastInstance = new bootstrap.Toast(toast[0], { delay: 3000 });
        toastInstance.show();
        
        toast.on('hidden.bs.toast', () => {
            toast.remove();
        });
    }
}

// Initialize when document is ready
$(document).ready(function() {
    // Load employees from the table
    const employees = [];
    $('table#payroll-datatable tbody tr').each(function() {
        const id = $(this).find('td:eq(2)').text().trim();
        const name = $(this).find('td:eq(1)').text().trim();
        const department = $(this).find('td:eq(3)').text().trim();
        const basic = parseFloat($(this).find('td:eq(4)').text().replace(/,/g, ''));
        const allowances = parseFloat($(this).find('td:eq(5)').text().replace(/[^0-9.-]+/g, ''));
        const deductions = parseFloat($(this).find('td:eq(6)').text().replace(/[^0-9.-]+/g, ''));
        const overtime = parseFloat($(this).find('td:eq(7)').text().replace(/[^0-9.-]+/g, ''));
        const status = $(this).find('td:eq(9)').text().trim().toLowerCase();
        
        employees.push({
            id, name, department, basic, allowances, deductions, overtime, status
        });
    });
    
    // Initialize Payroll Manager
    const payrollManager = new PayrollManager();
    payrollManager.employees = employees;
    
    // Make it available globally if needed
    window.payrollManager = payrollManager;
});
