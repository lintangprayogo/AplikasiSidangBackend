<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sk_id")->nullable();
            $table->unsignedBigInteger("periode_id")->nullable();
            $table->date("tanggal_sidang")->nullable();
            $table->time("jam_mulai")->nullable();
            $table->time("jam_berakhir")->nullable();
            $table->string("revisi")->nullable();
            $table->text("catatan_revisi")->nullable();
            $table->string("draft_jurnal");
            $table->string("persetujuan_pembimbing_1",15)->nullable();
            $table->string("persetujuan_pembimbing_2",15)->nullable();
            $table->timestamps();
        });

        Schema::table('sidang', function($table){
            $table->foreign('sk_id')->references('id')->on('sk')->onUpdate('cascade')->onDelete("set null");
            $table->foreign('periode_id')->references('id')->on('periode_sidang')->onUpdate('cascade')->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendaftaran_sidang');
    }
}
