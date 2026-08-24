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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 100)->unique();
            $table->string('nama', 100);
            $table->string('tempat', 100);
            $table->date('tanggal');
            $table->unsignedBigInteger('jeniskelamin_id');
            $table->text('alamat')->nullable();
            $table->unsignedBigInteger('agama_id');
            $table->unsignedBigInteger('profesi_id');
            $table->string('kontak', 100)->nullable();
            $table->string('image', 100)->nullable();
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('jeniskelamin_id')->references('id')->on('jeniskelamin')->onDelete('cascade');
            $table->foreign('agama_id')->references('id')->on('agama')->onDelete('cascade');
            $table->foreign('profesi_id')->references('id')->on('profesi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
