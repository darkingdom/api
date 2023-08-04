<?php
include "connectdb.php";

$json = array();
$query = mysqli_query($conn, "SELECT * FROM member");
while ($data = mysqli_fetch_assoc($query)) {
    $result['id'] = $data['ID'];
    $result['nama'] = $data['nama'];
    $result['kota'] = $data['kota'];
    array_push($json, $result);
}
echo json_encode($json);