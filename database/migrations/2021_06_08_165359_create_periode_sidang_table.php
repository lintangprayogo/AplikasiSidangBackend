<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodeSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periode_sidang', function (Blueprint $table) {
            $table->id();
            $table->string("periode_judul");
            $table->date("periode_mulai");
            $table->date("periode_akhir");
            $table->string("jalur_sidang");
            $table->string("status")->default("AKTIF");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periode_sidang');
    }
}
