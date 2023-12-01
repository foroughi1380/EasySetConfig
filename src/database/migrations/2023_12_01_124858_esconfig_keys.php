<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("esconfig_keys" , function (Blueprint $table){
            $table->id();
            $table->string("key");
            $table->text("value");
            $table->text("type");
            $table->string("title")->nullable();
            $table->text("description")->nullable();
            $table->text("extras")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("esconfig_keys");
    }
};
