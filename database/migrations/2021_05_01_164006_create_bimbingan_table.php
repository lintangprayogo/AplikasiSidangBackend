<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBimbinganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->id();
            $table->string("bimbingan_mhs_nim",15);
            $table->string("bimbingan_dsn_nip",15);
            $table->text("bimbingan_review");
            $table->string("bimbingan_kehadiran",5);
            $table->date("bimbingan_tanggal");
            $table->string("bimbingan_status",11);
            $table->timestamps();

          
        });

        Schema::table('bimbingan', function($table){
            $table->foreign('bimbingan_mhs_nim')->references('mhs_nim')->on('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('bimbingan_dsn_nip')->references('dsn_nip')->on('dosen')->onUpdate('cascade')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bimbingan');
    }
}
