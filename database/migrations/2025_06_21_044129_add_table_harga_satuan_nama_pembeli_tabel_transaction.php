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
        Schema::table('Transactions', function (Blueprint $table) {
            $table->string('nama_pembeli')->after('id');
            $table->integer('harga_satuan')->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Transactions', function (Blueprint $table) {
            $table->dropColumn(['nama_pembeli','harga_satuan']);
        });
    }
};
