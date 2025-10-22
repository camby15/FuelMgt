<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmailToEmailsInContractsTable extends Migration
{
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Remove the old email column
            if (Schema::hasColumn('contracts', 'email')) {
                $table->dropColumn('email');
            }

            // Add a new JSON column to store multiple emails
            $table->json('emails')->nullable()->after('customer_name');
        });
    }

    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Rollback: remove the JSON column and re-add email column
            $table->dropColumn('emails');
            $table->string('email')->nullable()->after('customer_name');
        });
    }
}
