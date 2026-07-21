<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus kolom yang tidak perlu (kalau masih ada)
            if (Schema::hasColumn('transactions', 'qty')) {
                $table->dropColumn('qty');
            }
            if (Schema::hasColumn('transactions', 'total_harga')) {
                $table->dropColumn('total_harga');
            }
            if (Schema::hasColumn('transactions', 'harga_satuan')) {
                $table->dropColumn('harga_satuan');
            }

            // Tambah kolom yang dibutuhkan
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_pembeli');
            $table->date('tanggal_pembelian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
