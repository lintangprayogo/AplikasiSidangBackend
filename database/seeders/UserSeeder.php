<?php

namespace Database\Seeders;

use App\Models\user;
use App\Models\dosen;
use App\Models\mahasiswa;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;       
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $faker = Faker::create();
         
         //Mahasiswa
         for($i=0; $i<=5; $i++){
            $nim=intval("130119".rand(0,9999));
            $user=User::create(["username" => $nim, "password" => Hash::make("12345678"),"pengguna"=>"mahasiswa"]);
            $name=$faker->name;
            Mahasiswa::create([
               'mhs_nim'=>$nim, 
               'mhs_nama'=>$name,
               'mhs_kontak'=>$faker->e164PhoneNumber, 
               'mhs_foto'=>null,
               'angkatan'=>"2017",
               'status'=>"",
               'user_id'=>$user->id,
               'mhs_email'=>$faker->email,]);
         }
         
         //Lak
         for($i=0; $i<=5; $i++){
            User::create(["username" => $faker->userName, "password" => Hash::make("12345678"),"pengguna"=>"lak"]);
         }

           //Prodi
           for($i=0; $i<=5; $i++){
            User::create(["username" => $faker->userName, "password" => Hash::make("12345678"),"pengguna"=>"prodi"]);
         }

          //Dosen
          for($i=0; $i<=5; $i++){
             $nip=intval("1489".rand(0,9999));
           $user= User::create(["username" => $nip, "password" => Hash::make("12345678"),"pengguna"=>"dosen"]);
            $name= $faker->firstName;
            Dosen::create([
            'dsn_nip'=>$nip, 
            'dsn_nama'=>$name,
            'dsn_kode'=>strtoupper(substr($name,0,3)), 
            'dsn_kontak'=>$faker->e164PhoneNumber, 
            'dsn_foto'=>null,
            'batas_bimbingan'=>4,
            'batas_penguji'=>4,
            'user_id'=>$user->id,
            'dsn_email'=>$faker->email,]);

         }
        
    }
}
