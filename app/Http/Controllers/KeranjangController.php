<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    // method daftar barang
    public function daftarbarang()
    {

        // ambil data barang
        $barang = Barang::all();
        // kirim ke halaman view
        return view('galeri2',
                        [ 
                            'barang'=>$barang,
                        ]
                    ); 
    }
}
