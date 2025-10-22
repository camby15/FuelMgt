<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToCompanyPartners extends Migration
{
    public function up()
    {
        Schema::table('company_partners', function (Blueprint $table) {
            // Add company_id column if it doesn't exist
            if (!Schema::hasColumn('company_partners', 'company_id')) {
                $table->unsignedBigInteger('company_id')->after('id');
                $table->foreign('company_id')
                    ->references('id')
                    ->on('companies')
                    ->onDelete('cascade');
            }

            // Get the list of existing indexes
            $indexes = collect(Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableIndexes('company_partners'));

            // Drop existing unique constraints if they exist
            if ($indexes->has('company_partners_company_name_unique')) {
                $table->dropUnique('company_partners_company_name_unique');
            }
            if ($indexes->has('company_partners_email_unique')) {
                $table->dropUnique('company_partners_email_unique');
            }

            // Add new composite unique constraints
            if (!$indexes->has('company_partners_user_company_unique')) {
                $table->unique(['user_id', 'company_name'], 'company_partners_user_company_unique');
            }
            if (!$indexes->has('company_partners_user_email_unique')) {
                $table->unique(['user_id', 'email'], 'company_partners_user_email_unique');
            }
        });
    }

    public function down()
    {
        Schema::table('company_partners', function (Blueprint $table) {
            // Get the list of existing indexes
            $indexes = collect(Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableIndexes('company_partners'));

            // Drop composite unique constraints if they exist
            if ($indexes->has('company_partners_user_company_unique')) {
                $table->dropUnique('company_partners_user_company_unique');
            }
            if ($indexes->has('company_partners_user_email_unique')) {
                $table->dropUnique('company_partners_user_email_unique');
            }

            // Drop foreign key and column if they exist
            if (Schema::hasColumn('company_partners', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }

            // Restore original unique constraints
            if (!$indexes->has('company_partners_company_name_unique')) {
                $table->unique('company_name', 'company_partners_company_name_unique');
            }
            if (!$indexes->has('company_partners_email_unique')) {
                $table->unique('email', 'company_partners_email_unique');
            }
        });
    }
}
