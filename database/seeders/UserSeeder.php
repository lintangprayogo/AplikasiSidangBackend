<?php

namespace Database\Seeders;

use App\Models\User;
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
            User::create(["username" => $faker->userName, "password" => Hash::make("12345678"),"pengguna"=>"mahasiswa"]);
         }
         
         //Lak
         for($i=0; $i<=5; $i++){
            User::create(["username" => $faker->userName, "password" => Hash::make("12345678"),"pengguna"=>"lak"]);
         }

           //Prodi
           for($i=0; $i<=5; $i++){
            User::create(["username" => $faker->userName, "password" => Hash::make("12345678"),"pengguna"=>"prodi"]);
         }

          //Prodi
          for($i=0; $i<=5; $i++){
            User::create(["username" => $faker->userName, "password" => Hash::make("12345678"),"pengguna"=>"dosen"]);
         }
        
    }
}
