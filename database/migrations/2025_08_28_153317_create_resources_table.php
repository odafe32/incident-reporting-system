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
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->enum('type', ['bed', 'equipment', 'staff']);
            $table->enum('status', ['available', 'in_use', 'maintenance'])->default('available');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->uuid('current_incident_id')->nullable(); 
            $table->timestamps();

            $table->foreign('current_incident_id')->references('id')->on('incidents')->onDelete('set null');
            $table->index(['type', 'status']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};