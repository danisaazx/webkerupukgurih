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
    Schema::table('produksis', function (Blueprint $table) {
        $table->decimal('biaya_gas', 15, 2)->default(0)->after('jumlah_produksi');
        $table->decimal('biaya_bensin', 15, 2)->default(0)->after('biaya_gas');
        $table->decimal('total_biaya', 15, 2)->default(0)->after('biaya_bensin');
    });
}

public function down()
{
    Schema::table('produksis', function (Blueprint $table) {
        $table->dropColumn(['biaya_gas', 'biaya_bensin', 'total_biaya']);
    });
}
};
