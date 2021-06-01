<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFormSk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_sk', function (Blueprint $table) {
            $table->id();
            $table->string('form_sk_mhs_nim', 15);
            $table->string('form_sk_mhs_nama');
            $table->string('form_sk_nip_1', 15);
            $table->string('form_sk_nip_2', 15)->nullable();
            $table->string('form_sk_nip_new_1', 15)->nullable();
            $table->string('form_sk_nip_new_2', 15)->nullable();
            $table->string('form_sk_dsn_nama_1');
            $table->string('form_sk_dsn_nama_2')->nullable();
            $table->string('form_sk_dsn_nama_new_1')->nullable();
            $table->string('form_sk_dsn_nama_new_2')->nullable();
            $table->string('judul_lama');
            $table->string('juduL_baru')->nullable();
            $table->string('alasan');
            $table->string('jenis');
            $table->string('sk_ta_lama');
            $table->string('persetujuan_pembimbing_1')->default("PENDING");
            $table->string('persetujuan_pembimbing_2')->default("PENDING");
            $table->string('persetujuan_pembimbing_new_1')->default("PENDING");
            $table->string('persetujuan_pembimbing_new_2')->default("PENDING");
            $table->string('persetujuan_prodi')->default("PENDING");
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
        Schema::dropIfExists('form_sk');
    }
}
