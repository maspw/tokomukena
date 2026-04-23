<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelian_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 12, 2);
            $table->decimal('subtotal', 15, 2);
            $table->date('tgl');
            $table->timestamps();

            // Foreign keys dengan constraint yang lebih lenient
            $table->foreign('pembelian_id')
                ->references('id')
                ->on('pembelians')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->index('barang_id');
            // Jangan pakai cascade untuk barang, pakai restrict
            // $table->foreign('barang_id')
            //     ->references('id')
            //     ->on('barangs')
            //     ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_barangs');
    }
};
