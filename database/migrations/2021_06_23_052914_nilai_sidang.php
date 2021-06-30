<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NilaiSidang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('nilai_sidang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sidang_id');
            $table->integer("nilai_laporan");
            $table->integer("nilai_presentasi");
            $table->integer("nilai_produk");
            $table->string("sumber");
            $table->timestamps();
        });

        Schema::table('nilai_sidang', function($table){
            $table->foreign('sidang_id')->references('id')->on('sidang')->onUpdate('cascade')->onDelete("cascade");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
