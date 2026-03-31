<?php
class Koneksi {
    private $server = "localhost";
    private $username = "root";
    private $pass = "";
    private $database = "pengaduan_sekolah";
    public $koneksi;

    function __construct() {
        $this->koneksi = mysqli_connect($this->server, $this->username, $this->pass, $this->database);
        if (!$this->koneksi) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }
    }
}