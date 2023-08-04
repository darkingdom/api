<?php
include "connectdb.php";


$json = file_get_contents('php://input');
$data = json_decode($json, true);
$nama = $data['nama'];
$kota = $data['alamat'];
$result = mysqli_query($conn, "INSERT INTO member (nama,kota)VALUES('$nama','$kota')");

if ($result) {
    echo json_encode('Insert successfully');
} else {
    echo json_encode('Insert failed');
}

mysqli_close($conn);