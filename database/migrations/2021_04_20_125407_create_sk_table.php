<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sk', function (Blueprint $table) {
            $table->id();
            $table->string("nomor_sk");
            $table->string("sk_mhs_nim",15);
            $table->string("judul_indonesia");
            $table->string("judul_inggris");
            $table->date("tanggal_persetujuan");
            $table->date("tanggal_kadaluarsa");
            $table->integer('extend_count')->default(0);
            $table->integer("sidang_count")->default(0);
            $table->timestamps();
        });
        Schema::table('sk', function($table){
            $table->foreign('sk_mhs_nim')->references('mhs_nim')->on('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sk');
    }
}
