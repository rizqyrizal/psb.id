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
        Schema::create('uang_masuks', function (Blueprint $table) {
            $table->id();
            $table->integer('santri_id');
            $table->double('amount');
            $table->string('keterangan');
            $table->string('payment_method');
            $table->string('tipe_transaksi');
            $table->string('id_transaksi');
            $table->dateTime('tanggal_masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uang_masuks');
    }
};
