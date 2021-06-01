<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\SK;
use App\Models\PelaksanaSidang;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SKImport implements ToCollection
{
 
    private $tanggal_persetujuan; 
    private $nomor_sk;
    private $response;

    public function __construct($tanggal_persetujuan,$nomor_sk)
    {
        $this->tanggal_persetujuan = $tanggal_persetujuan;
        $this->nomor_sk = $nomor_sk;  
        $this->response=[];
    }
    public function collection(Collection $collection)
    {
      $i=0;
        foreach ($collection as $data) 
        {
         
              $nim = $data[0];
              $judul_indonesia=$data[1];
              $judul_inggris=$data[2];
              $kode_pembimbing1=$data[3];
              $kode_pembimbing2=$data[4];
              $pembimbing1= Dosen::where("dsn_kode",$kode_pembimbing1)->first();
              $pembimbing2= Dosen::where("dsn_kode",$kode_pembimbing2)->first();
              $mahasiswa = Mahasiswa::where("mhs_nim",$nim)->first();
            
             try{
         
                $object=null;
                    if($pembimbing1&&$mahasiswa){
                        SK::where('sk_mhs_nim', $nim)->delete();
                        $sk= SK::create([
                             "nomor_sk"=>$this->nomor_sk,
                             "sk_mhs_nim"=>$nim,
                             "judul_indonesia"=>$judul_indonesia,
                             "judul_inggris"=>$judul_inggris,
                             "tanggal_persetujuan"=>$this->tanggal_persetujuan,
                             "tanggal_kadaluarsa"=>date('Y-m-d', strtotime('+6 month', strtotime($this->tanggal_persetujuan)))
                             ]);
     
                             PelaksanaSidang::create(["sk_id"=>$sk->id,
                             "pelaksana_dsn_nip"=>$pembimbing1->dsn_nip,
                             "status"=>"PEMBIMBING1"]);
          
                            $object= (object) [
                                'sk_id' => $sk->id,
                                "nomor_sk"=>$this->nomor_sk,
                                'judul_indonesia' =>str_replace("\n","",$sk->judul_indonesia) ,
                                'judul_inggris' =>str_replace("\n","", $sk->judul_inggris) ,
                                'mhs_nim'=>$mahasiswa->mhs_nim,
                                'mhs_nama'=>$mahasiswa->mhs_nama,
                                'nip_pembimbing1'=>$pembimbing1->dsn_nip,
                                'nama_pembimbing1'=>$pembimbing1->dsn_nama,
                                'nip_pembimbing2'=>null,
                                'nama_pembimbing2'=>null,
                                'tanggal_persetujuan'=>$sk->tanggal_persetujuan,
                                'tanggal_kadaluarsa'=>$sk->tanggal_kadaluarsa,
                              ];
                            
                             if($pembimbing2){
                                 PelaksanaSidang::create(["sk_id"=>$sk->id,
                                 "pelaksana_dsn_nip"=>$pembimbing2->dsn_nip,
                                 "status"=>"PEMBIMBING2"]);
                                 $object->nama_pembimbing2=$pembimbing2->dsn_nama;
                                 $object->nip_pembimbing2=$pembimbing2->dsn_nip;
                             }
                             array_push($this->response,$object);
                       }
                 
                  
                  

              }catch(Exception $exception){
                   echo $exception->getMessage();
              }
          
          $i++;            
        }
    }
    public function response(){
     return $this->response;
    }

}
