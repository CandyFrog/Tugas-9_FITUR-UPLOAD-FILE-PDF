<?php

include 'koneksi.php';

if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES['filepdf']) && $_FILES['filepdf']['error'] == 0) {

        $nama_asli = $_FILES['filepdf']['name'];
        $tipe = $_FILES['filepdf']['type'];
        $ukuran = $_FILES['filepdf']['size'];
        $tmp = $_FILES['filepdf']['tmp_name'];

        // validasi type pdf
        if ($tipe != "application/pdf") {
            echo "<h3>Gagal! File harus bertipe PDF.</h3>";
            exit;
        }

        // validasi ukuran max 10MB
        if ($ukuran > 10485760) {
            echo "<h3>Gagal! Ukuran file maksimal 10MB.</h3>";
            exit;
        }

        // rename file
        $timestamp = time();
        $nama_baru = "Ahmad_Syihabullail_" . $timestamp . "_" . $nama_asli;

        // tujuan upload
        $tujuan = "uploads/" . $nama_baru;

        // pindahkan file
        if (move_uploaded_file($tmp, $tujuan)) {

            // simpan ke database
            $sql = "INSERT INTO upload_pdf (path, name) VALUES ('$tujuan', '$nama_asli')";
            mysqli_query($koneksi, $sql);

            echo "<h2>Upload Berhasil!</h2>";
            echo "<p>Nama File Asli: $nama_asli</p>";
            echo "<p>Lokasi File: $tujuan</p>";
            echo "<a href='upload-pdf.html'>Kembali</a>";

        } else {
            echo "<h3>Gagal memindahkan file ke folder uploads.</h3>";
        }

    } else {
        echo "<h3>Tidak ada file yang diupload atau terjadi error.</h3>";
    }

}
?>
