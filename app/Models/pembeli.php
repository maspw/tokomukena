<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya 'pembeli' sesuai migration lu tadi
    protected $table = 'pembeli'; 
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}