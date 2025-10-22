<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->after('status');
            $table->string('contact_person_email')->nullable()->after('contact_person');
            $table->string('contact_person_phone')->nullable()->after('contact_person_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person',
                'contact_person_email',
                'contact_person_phone'
            ]);
        });
    }
};