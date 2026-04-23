<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER after_update_pembelian_status
            AFTER UPDATE ON pembelians
            FOR EACH ROW
            BEGIN
                IF NEW.status = "diterima" AND OLD.status != "diterima" THEN
                    UPDATE barangs b
                    JOIN pembelian_barangs pb ON (b.id = pb.barang_id)
                    SET b.stok = b.stok + pb.jumlah
                    WHERE pb.pembelian_id = NEW.id;
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_delete_pembelian
            BEFORE DELETE ON pembelians
            FOR EACH ROW
            BEGIN
                IF OLD.status = "diterima" THEN
                    UPDATE barangs b
                    JOIN pembelian_barangs pb ON (b.id = pb.barang_id)
                    SET b.stok = b.stok - pb.jumlah
                    WHERE pb.pembelian_id = OLD.id;
                END IF;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_pembelian_status');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_pembelian');
    }
};
