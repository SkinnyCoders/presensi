<?php
function getDataAll($sql,$conn){
    $get = mysqli_query($conn,$sql);
    if($get){
        if(mysqli_num_rows($get)){
            while($data = mysqli_fetch_assoc($get)){
                $all[] = $data;
            }
            return $all;
        }else{
            return false;
        }
    }else{
        echo mysqli_error($conn);
    }
}

function getData($query){
    global $koneksi;

    $result = mysqli_query($koneksi, $query);

    return mysqli_fetch_assoc($result);
}

function cekDataSiswa($token,$ipmesin){
    $url = "http://sims.imersa.co.id/api/presensi?token=".$token."&siswa";
    $url = file_get_contents($url);
    $siswa = json_decode($url);
    $server = $siswa;//data data in server
    $local = getData("SELECT count(*) as total FROM siswa JOIN mesin ON sn = id_mesin WHERE ip = '$ipmesin'")['total']; //total data in local
    if($local != $server->total){ //jika jumlah data lokal dan server berbeda
        return $server; //true => melakukan syncrone data
    }else{
        return FALSE;
    }
}

function cekMesin($ipmesin){
    $Connect = @fsockopen($ipmesin, "80", $errno, $errstr, 1);
    if(is_resource($Connect)){
      return TRUE;
    }else{
      return FALSE;
    }
  }