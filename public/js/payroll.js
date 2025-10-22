// class PayrollManager {
//     constructor() {
//         this.employees = [];
//         this.initializeEventListeners();
//         this.initializeDataTable();
//     }

//     initializeEventListeners() {
//         // Toast notifications
//         this.showToast = (type, message) => {
//             const toast = $(`
//                 <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
//                     <div class="d-flex">
//                         <div class="toast-body">
//                             <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
//                             ${message}
//                         </div>
//                         <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
//                     </div>
//                 </div>`
//             );
            
//             $('.toast-container').append(toast);
//             const toastInstance = new bootstrap.Toast(toast[0], { delay: 3000 });
//             toastInstance.show();
            
//             toast.on('hidden.bs.toast', function() {
//                 $(this).remove();
//             });
//         };

//         // Generate Payroll Button
//         $('#generatePayrollBtn').click(() => {
//             this.showToast('success', `Payroll generated for ${$('#payrollMonth option:selected').text()} ${$('#payrollYear').val()}`);
//         });

//         // Process Payroll Button
//         $('#runPayrollBtn').click(() => {
//             this.showToast('success', 'Payroll processed successfully!');
//         });

//         // Import CSV Modal
//         $('#confirmImport').click(this.handleCSVImport.bind(this));

//         // Edit Payroll Modal
//         $('#editPayrollModal').on('show.bs.modal', (e) => this.handleEditModalShow(e));

//         // Add Allowance/Deduction Rows
//         $('#addAllowance').click(() => this.addAllowanceRow());
//         $('#addDeduction').click(() => this.addDeductionRow());

//         // Remove Item Buttons
//         $(document).on('click', '.remove-item', (e) => this.removeItem(e));

//         // Input Changes
//         $(document).on('input', '.allowance-amount, .deduction-amount, [name="basic_salary"], [name="overtime"]', 
//             () => this.updateNetPay());

//         // Form Submission
//         $('#payrollEntryForm').on('submit', (e) => this.handleFormSubmit(e));

//         // Bank Details Toggle
//         $('#showAccountNumber').click(this.toggleBankAccountVisibility);

//         // View Payslip Modal
//         $('#viewPayslipModal').on('show.bs.modal', (e) => this.handlePayslipModalShow(e));

//         // Print and Download Buttons
//         $('#printPayslipBtn').click(() => window.print());
//         $('#downloadPayslipBtn').click(() => this.showToast('success', 'Payslip PDF downloaded!'));

//         // Process Bonus Modal
//         $('#processBonusModal').on('show.bs.modal', () => this.handleBonusModalShow());
//         $('#processBonusForm').on('submit', (e) => this.handleBonusFormSubmit(e));
//     }

//     initializeDataTable() {
//         // DataTable is already initialized in the Blade template
//         // Additional DataTable configurations can be added here if needed
//     }

//     handleCSVImport() {
//         const fileInput = $('#csvFile')[0];
//         if (!fileInput.files.length) {
//             this.showToast('danger', 'Please select a CSV file.');
//             return;
//         }
        
//         const progressBar = $('#importProgress');
//         progressBar.removeClass('d-none');
        
//         // Simulate import
//         let progress = 0;
//         const interval = setInterval(() => {
//             progress += 10;
//             progressBar.find('.progress-bar').css('width', `${progress}%`);
            
//             if (progress >= 100) {
//                 clearInterval(interval);
//                 this.showToast('success', 'Payroll data imported successfully!');
//                 $('#importCSVModal').modal('hide');
//                 progressBar.addClass('d-none');
//                 progressBar.find('.progress-bar').css('width', '0%');
//                 fileInput.value = '';
//             }
//         }, 200);
//     }

//     handleEditModalShow(e) {
//         const button = $(e.relatedTarget);
//         const empId = button.data('employee-id');
//         const employee = this.employees.find(e => e.id === empId);
        
//         if (!employee) return;

//         $('#payrollEntryForm [name="employee"]').val(employee.name);
//         $('#payrollEntryForm [name="employee_id"]').val(employee.id);
//         $('#payrollEntryForm [name="basic_salary"]').val(employee.basic.toFixed(2));
//         $('#payrollEntryForm [name="overtime"]').val(employee.overtime.toFixed(2));
        
//         // Clear existing rows
//         $('#allowancesContainer, #deductionsContainer').empty();
        
//         // Add sample data
//         this.addAllowanceRow('Housing', employee.allowances);
//         this.addDeductionRow('Tax', employee.deductions);
        
//         this.updateNetPay();
//     }

//     addAllowanceRow(description = '', amount = 0) {
//         const row = `
//             <div class="row g-2 mb-2">
//                 <div class="col-md-6">
//                     <input type="text" class="form-control" placeholder="Description (e.g., Housing, Transport)" value="${description}">
//                 </div>
//                 <div class="col-md-4">
//                     <div class="input-group">
//                         <span class="input-group-text">GHS</span>
//                         <input type="number" class="form-control allowance-amount" placeholder="Amount" value="${amount.toFixed(2)}" step="0.01">
//                     </div>
//                 </div>
//                 <div class="col-md-2">
//                     <button type="button" class="btn btn-outline-danger w-100 remove-item">
//                         <i class="fas fa-times"></i>
//                     </button>
//                 </div>
//             </div>`;
//         $('#allowancesContainer').append(row);
//     }

//     addDeductionRow(description = '', amount = 0) {
//         const row = `
//             <div class="row g-2 mb-2">
//                 <div class="col-md-6">
//                     <input type="text" class="form-control" placeholder="Description (e.g., Tax, Loan)" value="${description}">
//                 </div>
//                 <div class="col-md-4">
//                     <div class="input-group">
//                         <span class="input-group-text">GHS</span>
//                         <input type="number" class="form-control deduction-amount" placeholder="Amount" value="${amount.toFixed(2)}" step="0.01">
//                     </div>
//                 </div>
//                 <div class="col-md-2">
//                     <button type="button" class="btn btn-outline-danger w-100 remove-item">
//                         <i class="fas fa-times"></i>
//                     </button>
//                 </div>
//             </div>`;
//         $('#deductionsContainer').append(row);
//     }

//     removeItem(e) {
//         $(e.currentTarget).closest('.row').remove();
//         this.updateNetPay();
//     }

//     updateNetPay() {
//         const basic = parseFloat($('#payrollEntryForm [name="basic_salary"]').val()) || 0;
//         const overtime = parseFloat($('#payrollEntryForm [name="overtime"]').val()) || 0;
        
//         let allowances = 0;
//         $('.allowance-amount').each(function() {
//             allowances += parseFloat($(this).val()) || 0;
//         });
        
//         let deductions = 0;
//         $('.deduction-amount').each(function() {
//             deductions += parseFloat($(this).val()) || 0;
//         });
        
//         const netPay = basic + overtime + allowances - deductions;
//         $('#payrollEntryForm .alert-info .float-end').text(`GHS ${netPay.toFixed(2)}`);
//     }

//     handleFormSubmit(e) {
//         e.preventDefault();
//         this.showToast('success', 'Payroll entry updated successfully!');
//         $('#editPayrollModal').modal('hide');
//     }

//     toggleBankAccountVisibility() {
//         const input = $('.bank-account-masked');
//         input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
//         $(this).find('i').toggleClass('fa-eye fa-eye-slash');
//     }

//     handlePayslipModalShow(e) {
//         const button = $(e.relatedTarget);
//         const row = button.closest('tr');
//         const employee = {
//             name: row.find('td:eq(1)').text(),
//             id: row.find('td:eq(2)').text(),
//             department: row.find('td:eq(3)').text(),
//             basic: parseFloat(row.find('td:eq(4)').text().replace(/,/g, '')),
//             allowances: parseFloat(row.find('td:eq(5)').text().replace(/[^0-9.-]+/g, '')),
//             deductions: parseFloat(row.find('td:eq(6)').text().replace(/[^0-9.-]+/g, '')),
//             overtime: parseFloat(row.find('td:eq(7)').text().replace(/[^0-9.-]+/g, '')),
//             netPay: parseFloat(row.find('td:eq(8)').text().replace(/,/g, ''))
//         };

//         // Update modal with employee data
//         $('#payslipEmployeeName, #payslipEmpName').text(employee.name);
//         $('#payslipEmpId').text(employee.id);
//         $('#payslipDept').text(employee.department);
//         $('#payslipPayPeriod').text($('#currentPayPeriod').text());
//         $('#payslipPayDate').text(new Date().toLocaleDateString());
//         $('#payslipBankAccount').text('•••• •••• •••• 1234');
        
//         // Update earnings and deductions
//         const earningsTable = $('#earningsTable');
//         earningsTable.empty();
        
//         if (employee.basic > 0) {
//             earningsTable.append(`
//                 <tr>
//                     <td>Basic Salary</td>
//                     <td class="text-end">GHS ${employee.basic.toFixed(2)}</td>
//                 </tr>`
//             );
//         }
        
//         if (employee.allowances > 0) {
//             earningsTable.append(`
//                 <tr>
//                     <td>Allowances</td>
//                     <td class="text-end">GHS ${employee.allowances.toFixed(2)}</td>
//                 </tr>`
//             );
//         }
        
//         if (employee.overtime > 0) {
//             earningsTable.append(`
//                 <tr>
//                     <td>Overtime</td>
//                     <td class="text-end">GHS ${employee.overtime.toFixed(2)}</td>
//                 </tr>`
//             );
//         }
        
//         const deductionsTable = $('#deductionsTable');
//         deductionsTable.empty();
        
//         if (employee.deductions > 0) {
//             deductionsTable.append(`
//                 <tr>
//                     <td>Deductions</td>
//                     <td class="text-end">GHS ${employee.deductions.toFixed(2)}</td>
//                 </tr>`
//             );
//         }
        
//         // Update totals
//         const totalEarnings = employee.basic + employee.allowances + employee.overtime;
//         $('#totalEarnings').text(`GHS ${totalEarnings.toFixed(2)}`);
//         $('#totalDeductions').text(`GHS ${employee.deductions.toFixed(2)}`);
//         $('#netPayAmount').text(`GHS ${employee.netPay.toFixed(2)}`);

//         // Set the current period in the modal
//         const month = $('#payrollMonth option:selected').text();
//         const year = $('#payrollYear').val();
//         $('#payslipPeriod').text(`${month} ${year}`);
//     }

//     handleBonusModalShow() {
//         const select = $('#bonusEmployee');
//         select.empty().append('<option value="">Select Employee</option>');
//         this.employees.forEach(emp => {
//             select.append(`<option value="${emp.id}">${emp.name} (${emp.id})</option>`);
//         });
//         $('#bonusDate').val(new Date().toISOString().split('T')[0]);
//     }

//     handleBonusFormSubmit(e) {
//         e.preventDefault();
//         if (!e.target.checkValidity()) {
//             e.target.reportValidity();
//             return;
//         }
//         this.showToast('success', 'Bonus processed successfully!');
//         $('#processBonusModal').modal('hide');
//         e.target.reset();
//     }
// }

// // Initialize the PayrollManager when the document is ready
// $(document).ready(function() {
//     // Load employee data from the table rows
//     const employees = [];
//     $('#payroll-datatable tbody tr').each(function() {
//         const row = $(this);
//         employees.push({
//             id: row.find('td:eq(2)').text().trim(),
//             name: row.find('td:eq(1)').text().trim(),
//             department: row.find('td:eq(3)').text().trim(),
//             basic: parseFloat(row.find('td:eq(4)').text().replace(/[^0-9.-]+/g, '')),
//             allowances: parseFloat(row.find('td:eq(5)').text().replace(/[^0-9.-]+/g, '')),
//             deductions: parseFloat(row.find('td:eq(6)').text().replace(/[^0-9.-]+/g, '')),
//             overtime: parseFloat(row.find('td:eq(7)').text().replace(/[^0-9.-]+/g, '')),
//             status: row.find('td:eq(9)').find('.badge').text().trim().toLowerCase()
//         });
//     });

//     // Initialize the PayrollManager with the employee data
//     const payrollManager = new PayrollManager();
//     payrollManager.employees = employees;
    
//     // Make it globally available for debugging if needed
//     window.payrollManager = payrollManager;
// });
