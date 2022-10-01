<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('wisdoms', function (Blueprint $table) {
            $table->text("search_text");
        });
        DB::statement('UPDATE wisdoms SET search_text = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(text, "-", ""), "َ", ""), "ّ", ""), "ً", ""), "ِ", ""), "ٍ", ""),"ُ", ""),"ٌ", ""),"ْ", ""), "ْ", "")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wisdoms', function (Blueprint $table) {
            $table->dropColumn("search_text");
        });
    }
};
