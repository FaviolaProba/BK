<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    // Jika pengguna sudah login, tampilkan tombol "Logout"
    header("Location: index.php?page=login");
    exit;
}
if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {
        $ubah = mysqli_query($mysqli, "UPDATE daftar_poli SET 
                                        id_pasien = '" . $_POST['id_pasien'] . "',
                                        id_jadwal = '" . $_POST['id_jadwal'] . "',
                                        keluhan = '" .$_POST['keluhan'] . "',
                                        no_antrian = '" . $_POST['no_antrian'] . "'
                                        WHERE
                                        id = '" . $_POST['id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian) 
        VALUES (
                                        '" . $_POST['id_pasien'] . "',
                                        '" . $_POST['id_jadwal'] . "',
                                        '" . $_POST['keluhan'] . "',
                                        '" . $_POST['no_antrian'] . "'
                                                                            )");
    }

    echo "<script> 
    document.location='index.php?page=daftar_poli';;
            </script>";
}

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM daftar_poli WHERE id = '" . $_GET['id'] . "'");
    } else if ($_GET['aksi'] == 'ubah_status') {
        $ubah_status = mysqli_query($mysqli, "UPDATE daftar_poli SET 
                                        status = '" . $_GET['status'] . "' 
                                        WHERE
                                        id = '" . $_GET['id'] . "'");
    }

    echo "<script> 
    document.location='index.php?page=daftar_poli';
            </script>";
}
?>
</main>
<div class="container">
    <!--Form Input Data-->
    <h2>Daftar Poli</h2>
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
    <div class="row">
        <!-- Form -->
        <div class="col">
        <label for="inputNama" class="form-label fw-bold">
            Nama Pasien
        </label>
        <select class="form-control" name="id_pasien">
        <?php
        $selected = '';
        $pasien = mysqli_query($mysqli, "SELECT * FROM pasien");
        while ($data = mysqli_fetch_array($pasien)) {
            if ($data['id'] == $id_pasien) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
        ?>
            <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
        <?php
        }
        ?>
    </select>
    </div>
    <div class="col">
        <label for="inputNama" class="form-label fw-bold">
            ID Jadwal
        </label>
        <select class="form-control" name="id_jadwal">
        <?php
        $selected = '';
        $jadwal_periksa = mysqli_query($mysqli, "SELECT * FROM jadwal_periksa");
        while ($data = mysqli_fetch_array($jadwal_periksa)) {
            if ($data['id'] == $id_jadwal) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
        ?>
            <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['id_dokter'] ?></option>
        <?php
        }
        ?>
    </select>
    </div>
        <div class="col">
        <label for="inputKeluhan" class="form-label fw-bold">
            Keluhan
        </label>
        <input type="text" class="form-control" name="keluhan" id="inputKeluhan" placeholder="Keluhan" value="<?php echo $keluhan ?>">
    </div>
    <div class="col">
        <label for="inputNoAntrian" class="form-label fw-bold">
            No Antrian
        </label>
        <input type="integer" class="form-control" name="no_antrian" id="inputAntrian" placeholder="No Antrian" value="<?php echo $no_antrian ?>">
    </div>
    <div class="col">
        <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
    </div>
    </form>
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
            <th scope="col">Aksi</th>
        </tr>
    </thead>
        <!--thead atau baris judul-->
        <tbody>
    <?php
    $result = mysqli_query($mysqli, "SELECT dp.*, p.nama as 'nama_pasien' FROM daftar_poli dp LEFT JOIN pasien p ON (dp.id_pasien=p.id) ORDER BY id ASC");

    if (!$result) {
        die("Error: " . mysqli_error($mysqli));
    }
    
    if (mysqli_num_rows($result) > 0) {
        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $data['nama_pasien'] . "</td>";
            echo "<td>" . $data['id_jadwal'] . "</td>";
            echo "<td>" . $data['keluhan'] . "</td>"; // 'keluhan' instead of 'Keluhan'
            echo "<td>" . $data['no_antrian'] . "</td>";
            echo "<td>";
            echo '<a class="btn btn-success rounded-pill px-3" href="index.php?page=daftar_poli&id=' . $data['id'] . '">Ubah</a>';
            echo '<a class="btn btn-danger rounded-pill px-3" href="index.php?page=daftar_poli&id=' . $data['id'] . '&aksi=hapus">Hapus</a>';
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "No rows returned.";
    }
    
    ?>
    </tbody>
    </table>