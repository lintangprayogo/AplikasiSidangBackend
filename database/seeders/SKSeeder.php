<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PelaksanaSidang;
use App\Models\SK;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;  
class SKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $dosens=Dosen::get();
        $lotre=[0,1,2,3,4];
        $mahasiswas=Mahasiswa::get();


        foreach($mahasiswas as $mahasiswa){
            $nomor_sk="232/AKD9/IF-DEK/".date("Y");
            $nim = $mahasiswa->mhs_nim;
            $judul_indonesia=$faker->text;
            $judul_inggris=$faker->text;
            $pembimbings=array_rand($lotre,2);
            $pembimbing1= $dosens[$pembimbings[0]];
            $pembimbing2= $dosens[$pembimbings[1]];
            $mahasiswa = Mahasiswa::where("mhs_nim",$nim)->first();
            $today=date("Y-m-d");
            $sk= SK::create([
                "nomor_sk"=>$nomor_sk,
                "sk_mhs_nim"=>$nim,
                "judul_indonesia"=>$judul_indonesia,
                "judul_inggris"=>$judul_inggris,
                "tanggal_persetujuan"=>$today,
                "tanggal_kadaluarsa"=>date('Y-m-d', strtotime('+6 month', strtotime("$today")))
                ]);

                PelaksanaSidang::create(["sk_id"=>$sk->id,
                "pelaksana_dsn_nip"=>$pembimbing1->dsn_nip,
                "status"=>"PEMBIMBING1"]);
                PelaksanaSidang::create(["sk_id"=>$sk->id,
                "pelaksana_dsn_nip"=>$pembimbing2->dsn_nip,
                "status"=>"PEMBIMBING2"]);
        }
    }
}
