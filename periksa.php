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
        $ubah = mysqli_query($mysqli, "UPDATE periksa SET 
                                        id_daftar_poli = '" . $_POST['id_daftar_poli'] . "',
                                        tgl_periksa = '" . $_POST['tgl_periksa'] . "',
                                        catatan = '" . $_POST['catatan'] . "',
                                        biaya_periksa = '" . $_POST['biaya_periksa'] . "'
                                        WHERE
                                        id = '" . $_POST['id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan,biaya_periksa) 
        VALUES (
                                        '" . $_POST['id_daftar_poli'] . "',
                                        '" . $_POST['tgl_periksa'] . "',
                                        '" . $_POST['catatan'] . "',
                                        '" . $_POST['biaya_periksa'] . "'
                                                                            )");
    }
    echo "<script> 
    document.location='index.php?page=periksa';;
            </script>";
}

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM periksa WHERE id = '" . $_GET['id'] . "'");
    } else if ($_GET['aksi'] == 'ubah_status') {
        $ubah_status = mysqli_query($mysqli, "UPDATE periksa SET 
                                        status = '" . $_GET['status'] . "' 
                                        WHERE
                                        id = '" . $_GET['id'] . "'");
    }

    echo "<script> 
    document.location='index.php?page=periksa';
            </script>";
}
?>
</main>
<div class="container">
    <!--Form Input Data-->
    <h2>Periksa</h2>
<br>
    <?php
include'koneksi.php';
?>
<body>
<div class="container">
    <form class="form row"  method="POST" action="" name="myForm" onsubmit="return(validate());">
    <!-- Kode php untuk menghubungkan form dengan database -->
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
    <div class="row">
        <!-- Form -->
        <div class="col">
        <label for="inputNama" class="form-label fw-bold">
            ID Daftar Poli
        </label>
        <select class="form-control" name="id_daftar_poli">
        <?php
        $selected = '';
        $daftar_poli = mysqli_query($mysqli, "SELECT * FROM daftar_poli");
        while ($data = mysqli_fetch_array($daftar_poli)) {
            if ($data['id'] == $daftar_poli) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
        ?>
            <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['id_pasien'] ?></option>
        <?php
        }
        ?>
    </select>
    </div>
    <div class="col">
        <label for="inputTgl_periksa" class="form-label fw-bold">
            Tanggal Periksa
        </label>
        <input type="date" class="form-control" name="tgl_periksa" id="inputTgl_periksa" placeholder="tgl_periksa" value="<?php echo $tgl_periksa ?>">
    </div>
    
    <div class="col">
        <label for="inputCatatan" class="form-label fw-bold">
            Catatan
        </label>
        <input type="text" class="form-control" name="catatan" id="inputCatatan" placeholder="Catatan" value="<?php echo $catatan ?>">
    </div>
    <div class="col">
        <label for="inputBiaya_periksa" class="form-label fw-bold">
            Biaya Periksa
        </label>
        <input type="integer" class="form-control" name="biaya_periksa" id="inputBiayaPeriksa" placeholder="Biaya Periksa" value="<?php echo $biaya_periksa ?>">
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
            <th scope="col">ID Daftar Poli</th>
            <th scope="col">Tanggal Periksa</th>
            <th scope="col">Catatan</th>
            <th scope="col">Biaya Periksa</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
        <!--thead atau baris judul-->
        <tbody>
    <?php
    $result = mysqli_query($mysqli, "SELECT periksa.*, daftar_poli.id_pasien as 'id_pasien' FROM periksa LEFT JOIN daftar_poli ON (periksa.id_daftar_poli=daftar_poli.id) ORDER BY id ASC");

    if (!$result) {
        die("Error: " . mysqli_error($mysqli));
    }
    
    if (mysqli_num_rows($result) > 0) {
        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $data['id_daftar_poli'] . "</td>";
            echo "<td>" . $data['tgl_periksa'] . "</td>";
            echo "<td>" . $data['catatan'] . "</td>"; 
            echo "<td>" . $data['biaya_periksa'] . "</td>";
            echo "<td>";
            echo '<a class="btn btn-success rounded-pill px-3" href="index.php?page=periksa&id=' . $data['id'] . '">Ubah</a>';
            echo '<a class="btn btn-danger rounded-pill px-3" href="index.php?page=periksa&id=' . $data['id'] . '&aksi=hapus">Hapus</a>';            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "No rows returned.";
    }
    
    ?>
    </tbody>
    </table>