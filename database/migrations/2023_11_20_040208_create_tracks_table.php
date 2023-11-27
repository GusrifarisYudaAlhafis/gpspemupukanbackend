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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('imei', 15);
            $table->double('latitude');
            $table->double('longitude');
            $table->integer('angle')->nullable();
            $table->integer('altitude')->nullable();
            $table->integer('satellites')->nullable();
            $table->integer('speed')->nullable();
            $table->integer('battery')->nullable();
            $table->dateTime('datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
