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
        Schema::create('module', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('module')
                ->nullOnDelete();
            $table->string('name', 100);
            $table->string('key', 100)->unique();
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module');
    }
};
