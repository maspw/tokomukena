<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianBarang extends Model
{
    use HasFactory;

    protected $table = 'pembelian_barangs';
    protected $fillable = [
        'pembelian_id',
        'barang_id',
        'jumlah',
        'harga_beli',
        'subtotal',
        'tgl'
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tgl' => 'date'
    ];

    // Relasi: Detail pembelian milik 1 pembelian
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    // Relasi: Detail pembelian mengacu ke 1 barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
