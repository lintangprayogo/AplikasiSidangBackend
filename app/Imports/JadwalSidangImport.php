<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\PelaksanaSidang;
use App\Models\Sidang;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class JadwalSidangImport implements ToCollection
{
    private $response=[];
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $i=0;
        foreach ($collection as $data) 
        {
            try{
                if($i!=0){
                    $nim = $data[0];
                    $kode_penguji1=$data[1];
                    $kode_penguji2=$data[2];
                    $data[3]=str_replace('/', '-', $data[3]);
                    $tanggal_sidang=date("Y-m-d", strtotime($data[3]));  
                    $jam_mulai=date("H:i", strtotime($data[4]));  
                    $jam_akhir =date('H:i',strtotime('+1 hour +30 minutes',strtotime($data[4])));
                    $today=date("Y-m-d");
                    $dataSidang=Sidang::join('sk','sk.id',"=","sidang.sk_id")
                    ->join('periode_sidang','periode_sidang.id',"=","sidang.periode_id")
                    ->join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
                    ->where("sk_mhs_nim","=",$nim)->where("tanggal_sidang",">=",$today)->
                    orWhere("tanggal_sidang","=",null)
                    ->first();
                   

                    $pembimbing1=PelaksanaSidang::where("status","=","PEMBIMBING1")->where("sk_id","=",$dataSidang->sk_id)->first()->dosen();
                    $pembimbing2=PelaksanaSidang::where("status","=","PEMBIMBING2")->where("sk_id","=",$dataSidang->sk_id)->first()->dosen();
                    $penguji1= Dosen::where("dsn_kode",$kode_penguji1)->first();
                    $penguji2= Dosen::where("dsn_kode",$kode_penguji2)->first();
                    if($dataSidang&&$pembimbing1!=$penguji1&&
                    $pembimbing2!=$penguji2&&$penguji1!=null&&$penguji2!=null
                    &&($dataSidang->jalur_sidang=="TERJADWAL"||
                    ($dataSidang->jalur_sidang=="REGULER" &&
                     $dataSidang->persetujuan_pembimbing_1="DISETUJUI"
                     &&($dataSidang->persetujuan_pembimbing_2="DISETUJUI"||!$pembimbing2))
                    
                    
                    
                    ) 

                    ){   
                       
                         Sidang::where("id","=",$dataSidang->id)->update(
                             ["tanggal_sidang"=>$tanggal_sidang,
                             "jam_mulai"=>"$jam_mulai",
                             "jam_berakhir"=>"$jam_akhir"
                            ]);
                        
                        PelaksanaSidang::where("sk_id","=",$dataSidang->sk_id)->where("status","=","PENGUJI1")->
                        orWhere("status","=","PENGUJI2")->delete();
                        
                       
                        PelaksanaSidang::create(["sk_id"=>$dataSidang->sk_id,
                            "pelaksana_dsn_nip"=>$penguji1->dsn_nip,
                            "status"=>"PENGUJI1",
                            "tanggal_sidang"=>$tanggal_sidang
                        ]);
                        PelaksanaSidang::create(["sk_id"=>$dataSidang->sk_id,
                            "pelaksana_dsn_nip"=>$penguji2->dsn_nip,
                            "status"=>"PENGUJI2",
                            "tanggal_sidang"=>$tanggal_sidang
                        ]);

                        array_push($this->response,$dataSidang);    
                            
                    }
               
                }
                $i++;
               
              
        
    
            }catch(Exception $e){
                
            }
      
        }
    }
    

    public function response(){
        return $this->response;
       }
}
