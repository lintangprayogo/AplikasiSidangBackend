<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\user;
use App\Models\informasi;
use Faker\Factory as Faker;        
class InformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
       for($i=0; $i<10; $i++){
        $faker = Faker::create();
           Informasi::create([    
           'informasi_judul'=>$faker->text(10),
           'informasi_isi'=>$faker->text, 
           'penerbit'=>$faker->name, 
           'informasi_waktu'=> $faker->date($format = 'Y-m-d', $min = 'now'), ]);
       }
    }
}
