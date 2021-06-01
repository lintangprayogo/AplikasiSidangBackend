<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelaksanaSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelaksana_sidang', function (Blueprint $table) {
            $table->id();
            $table->string("status");
            $table->string("pelaksana_dsn_nip",15);
            $table->unsignedBigInteger("sk_id");            
            $table->timestamps();
        });
        Schema::table('pelaksana_sidang', function($table){
            $table->foreign('sk_id')->references('id')->on('sk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pelaksana_dsn_nip')->references('dsn_nip')->on('dosen')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelaksana_sidang');
    }
}
