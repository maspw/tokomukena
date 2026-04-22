<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;
    protected $guarded = [];

public static function getNoKamar()
{
    $lastKamar = self::latest()->first();
    $number = $lastKamar ? (int) substr($lastKamar->no_kamar, -3) + 1 : 1;
    return 'KMR-' . str_pad($number, 3, '0', STR_PAD_LEFT);
}
}
