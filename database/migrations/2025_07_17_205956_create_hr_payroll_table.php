<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_payroll', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('employee_id');
            $table->string('pay_period'); // monthly, bi-weekly, weekly
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('payment_date');
            
            // Earnings
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('housing_allowance', 12, 2)->default(0);
            $table->decimal('transport_allowance', 12, 2)->default(0);
            $table->decimal('overtime', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('other_allowances', 12, 2)->default(0);
            
            // Deductions
            $table->decimal('ssnit', 12, 2)->default(0);
            $table->decimal('paye', 12, 2)->default(0);
            $table->decimal('tier2_pension', 12, 2)->default(0);
            $table->decimal('other_deductions', 12, 2)->default(0);
            
            // Totals
            $table->decimal('gross_pay', 12, 2);
            $table->decimal('total_deductions', 12, 2);
            $table->decimal('net_pay', 12, 2);
            
            // Status
            $table->enum('status', ['draft', 'pending', 'approved', 'paid', 'rejected'])->default('draft');
            
            // Payment info
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            
            // Metadata
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'employee_id']);
            $table->index(['payment_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_payroll');
    }
};