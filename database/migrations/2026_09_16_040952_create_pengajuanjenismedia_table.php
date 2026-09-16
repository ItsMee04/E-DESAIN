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
        Schema::create('pengajuanjenismedia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedBigInteger('jenismedia_id');
            $table->timestamps();
            $table->unique(['pengajuan_id','jenismedia_id']);

            $table->foreign('pengajuan_id')->references('id')->on('pengajuan')->onDelete('restrict');
            $table->foreign('jenismedia_id')->references('id')->on('jenismedia')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuanjenismedia');
    }
};
