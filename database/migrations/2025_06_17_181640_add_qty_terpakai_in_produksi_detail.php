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
        Schema::table('produksi_details', function (Blueprint $table) {
            $table->decimal('qty_terpakai', 10, 2)->after('bahan_baku_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produksi_details', function (Blueprint $table) {
            $table->dropColumn('qty_terpakai');
        });
    }
};
