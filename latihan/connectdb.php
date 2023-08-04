<?php
$conn = mysqli_connect("localhost", "root", "veronica99", "coba_api_js");
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_errno();
}