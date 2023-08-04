<?php
include "connectdb.php";

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$id = $_GET['id'];
$nama = $data['nama'];
$kota = $data['alamat'];
$result = mysqli_query($conn, "UPDATE member SET nama='$nama', kota='$kota' WHERE ID='$id'");

if ($result) {
    echo json_encode('Insert successfully');
} else {
    echo json_encode('Insert failed');
}

mysqli_close($conn);