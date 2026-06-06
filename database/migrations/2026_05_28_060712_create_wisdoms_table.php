<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wisdoms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('text');
            $table->text('search_text');
            $table->integer('likes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisdoms');
    }
};
