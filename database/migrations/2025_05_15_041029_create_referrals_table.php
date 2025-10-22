<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure referenced tables exist before creating referrals table
        if (Schema::hasTable('company_profiles') && Schema::hasTable('customers') && Schema::hasTable('loyalty_programs')) {
            Schema::create('referrals', function (Blueprint $table) {
                $table->id();
                // Explicitly specify the referenced table and column
                $table->foreignId('company_id')->constrained('company_profiles', 'id')->onDelete('cascade');
                $table->foreignId('referrer_id')->constrained('customers', 'id')->onDelete('cascade');
                $table->foreignId('referee_id')->nullable()->constrained('customers', 'id')->onDelete('cascade');
                $table->foreignId('loyalty_program_id')->constrained('loyalty_programs', 'id')->onDelete('cascade');
                $table->string('email')->nullable();
                $table->string('token')->unique();
                $table->enum('status', ['pending', 'completed', 'expired'])->default('pending');
                $table->integer('points_awarded')->nullable();
                $table->dateTime('completed_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
                
                $table->index(['company_id', 'referrer_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('referrals');
    }
};