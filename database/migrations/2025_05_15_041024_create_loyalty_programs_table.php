<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('loyalty_programs')) {
            Schema::create('loyalty_programs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('user_id');
                $table->string('name');
                $table->enum('program_type', ['points', 'tier', 'hybrid']);
                $table->json('customer_type')->nullable();
                $table->text('description')->nullable();
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->integer('points')->default(0);
                $table->decimal('currency_value', 10, 2)->default(0.00);
                $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
                $table->boolean('is_active')->default(true);
                $table->softDeletes();
                $table->timestamps();
                $table->index(['company_id', 'user_id']);
            });

            Schema::table('loyalty_programs', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }


    public function down()
    {
        Schema::dropIfExists('loyalty_programs');
    }
};