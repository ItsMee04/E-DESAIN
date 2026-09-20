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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 30)->unique();
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('statuspengajuan_id');
            $table->string('nama_desain', 255);
            $table->string('ukuran', 100)->nullable();
            $table->unsignedInteger('jumlah')->default(0);
            $table->text('keperluan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('restrict');
            $table->foreign('unit_id')->references('id')->on('unit')->onDelete('restrict');
            $table->foreign('statuspengajuan_id')->references('id')->on('statuspengajuan')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
