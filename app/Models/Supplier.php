<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'telepon',
        'email',
        'pic',
        'catatan'
    ];

    // Generate kode supplier otomatis: SP001, SP002, dst.
    public static function getKodeSupplier()
    {
        $sql = "SELECT IFNULL(MAX(kode_supplier), 'SP000') as kode_supplier FROM suppliers";
        $kodeSupplier = DB::select($sql);

        foreach ($kodeSupplier as $kd) {
            $kode = $kd->kode_supplier;
        }

        $noawal = substr($kode, -3);
        $noakhir = $noawal + 1;
        $noakhir = 'SP' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
        return $noakhir;
    }

    // Relasi: 1 supplier punya banyak pembelian
    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'supplier_id');
    }
}