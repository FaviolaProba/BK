<?php
if (!isset($_SESSION)) {
    session_start();
}

?>
</main>
<div class="container">
    <!--Form Input Data-->
    <h2>Riwayat Pasien</h2>
<br>
    <?php
include'koneksi.php';
?>
<body>
<div class="container">
    <form class="form row"  method="POST" action="" name="myForm" onsubmit="return(validate());">
    <!-- Kode php untuk menghubungkan form dengan database -->
    <?php
    $id_pasein= '';
    $id_jadwal= '';
    $keluhan = '';
    $no_antrian ='';
    if (isset($_GET['id'])) {
        $ambil = mysqli_query($mysqli, 
        "SELECT * FROM daftar_poli 
        WHERE id='" . $_GET['id'] . "'");
        while ($row = mysqli_fetch_array($ambil)) {
            $id_pasien = $row['id_pasien'];
            $id_jadwal = $row['id_jadwal'];
            $keluhan = $row['keluhan'];
            $no_antrian= $row['no_antrian'];
        }
    ?>
        <input type="hidden" name="id" value="<?php echo
        $_GET['id'] ?>">
    <?php
    }
    ?>
    <?php
    $id_daftar_poli= '';
    $tgl_periksa= '';
    $catatan = '';
    $biaya_periksa ='';
    if (isset($_GET['id'])) {
        $ambil = mysqli_query($mysqli, 
        "SELECT * FROM periksa
        WHERE id='" . $_GET['id'] . "'");
        while ($row = mysqli_fetch_array($ambil)) {
            $id_daftar_poli = $row['id_daftar_poli'];
            $tgl_periksa = $row['tgl_periksa'];
            $catatan = $row['catatan'];
            $biaya_periksa= $row['biaya_periksa'];
        }
    ?>
        <input type="hidden" name="id" value="<?php echo
        $_GET['id'] ?>">
    <?php
    }
    ?>
    <br>
    <br>
    <!-- Table-->
    <table class="table table-hover">
    <table class="table table-hover">
    <!--thead atau baris judul-->
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Pasien</th>
            <th scope="col">ID Jadwal</th>
            <th scope="col">Keluhan</th>
            <th scope="col">No Antrian</th>
            <th scope="col">ID Daftar Poli</th>
            <th scope="col">Tanggal Periksa</th>
            <th scope="col">Catatan</th>
            <th scope="col">Biaya Periksa</th>
        </tr>
    </thead>
        <!--thead atau baris judul-->
        <tbody>
    <?php
   
   $result = mysqli_query($mysqli, "
    SELECT dp.*, p.nama as 'nama_pasien', p.id as 'id_pasien', pr.*
    FROM daftar_poli dp
    LEFT JOIN pasien p ON dp.id_pasien = p.id
    LEFT JOIN periksa pr ON dp.id = pr.id_daftar_poli
    ORDER BY dp.id ASC");


    if (!$result) {
        die("Error: " . mysqli_error($mysqli));
    }
    
// Previous code...

if (mysqli_num_rows($result) > 0) {
    $no = 1;
    while ($data = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . (isset($data['nama_pasien']) ? $data['nama_pasien'] : '') . "</td>";
        echo "<td>" . (isset($data['id_jadwal']) ? $data['id_jadwal'] : '') . "</td>";
        echo "<td>" . (isset($data['keluhan']) ? $data['keluhan'] : '') . "</td>";
        echo "<td>" . (isset($data['no_antrian']) ? $data['no_antrian'] : '') . "</td>";
        echo "<td>" . (isset($data['id_daftar_poli']) ? $data['id_daftar_poli'] : '') . "</td>";
        echo "<td>" . (isset($data['tgl_periksa']) ? $data['tgl_periksa'] : '') . "</td>";
        echo "<td>" . (isset($data['catatan']) ? $data['catatan'] : '') . "</td>";
        echo "<td>" . (isset($data['biaya_periksa']) ? $data['biaya_periksa'] : '') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9'>No rows returned.</td></tr>";
}
?>

    
    
    </tbody>
    </table>