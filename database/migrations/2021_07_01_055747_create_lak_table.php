<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lak', function (Blueprint $table) {
             $table->string('lak_nip', 15)->primary();
            $table->string('lak_nama', 64);
            $table->string('lak_kontak', 12)->nullable()->unique();
            $table->text('lak_foto')->nullable();
            $table->string('lak_email', 64)->nullable()->unique();
            $table->unsignedBigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lak');
    }
}
