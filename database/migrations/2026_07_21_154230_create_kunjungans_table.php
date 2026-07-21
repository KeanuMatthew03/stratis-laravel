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
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->increments('id_kunjungan');

            $table->unsignedInteger('id_negara_asal');

            $table->unsignedInteger('id_negara_tujuan');

            $table->enum('bulan', [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei'
            ]);

            $table->integer('jumlah');

            $table->foreign('id_negara_asal')
                ->references('id_negara')
                ->on('negara')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_negara_tujuan')
                ->references('id_negara')
                ->on('negara')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};