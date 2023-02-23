<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('whatsapp_subscribers', function (Blueprint $table) {
            // Rename column1 to new_column1
            $table->renameColumn('name', 'first_name');
            $table->string('email')->after('telephone');
            $table->string('country_code')->before('telephone');
            $table->string('last_name')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
