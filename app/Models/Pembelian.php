<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';
    protected $fillable = [
        'no_pembelian',
        'supplier_id',
        'tgl_pembelian',
        'status',
        'total_harga',
        'catatan'
    ];

    protected $casts = [
        'tgl_pembelian' => 'date',
        'total_harga' => 'decimal:2'
    ];

    // Generate nomor pembelian otomatis
    public static function generateNoPembelian()
    {
        $sql = "SELECT IFNULL(MAX(CAST(SUBSTRING(no_pembelian, 5) AS UNSIGNED)), 0) as no_pembelian FROM pembelians";
        $noPembelian = \DB::selectOne($sql);
        
        $kodePembelian = $noPembelian->no_pembelian + 1;
        $nomor = substr('0000' . $kodePembelian, -5);
        
        return 'PB-' . $nomor;
    }

    // Relasi: Pembelian milik 1 supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Relasi: 1 pembelian punya banyak detail barang
    public function pembelianBarangs()
    {
        return $this->hasMany(PembelianBarang::class, 'pembelian_id');
    }
}
