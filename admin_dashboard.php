<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
    include('koneksi.php');

    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
        header('Location: login.php');
        exit;
    }
    if (isset($_POST['import_excel'])) {
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
            require 'vendor/autoload.php'; // pastikan sudah install phpoffice/phpspreadsheet
            $fileTmpPath = $_FILES['excel_file']['tmp_name'];
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                $nama = $row[0];
                $harga = $row[1];
                $processor = $row[2];
                $ram = $row[3];
                $vga = $row[4];
                $memori = $row[5];
                $lcd = $row[6];

                $sql = "INSERT INTO `data_laptop` (`id_laptop`, `nama_laptop`, `harga_angka`, `processor_angka`, `ram_angka`, `vga_angka`, `memori_angka`, `lcd_angka`) 
                        VALUES (NULL, :nama_laptop, :harga_angka, :processor_angka, :ram_angka, :vga_angka, :memori_angka, :lcd_angka)";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':nama_laptop', $nama);
                $stmt->bindValue(':harga_angka', $harga);
                $stmt->bindValue(':processor_angka', $processor);
                $stmt->bindValue(':ram_angka', $ram);
                $stmt->bindValue(':vga_angka', $vga);
                $stmt->bindValue(':memori_angka', $memori);
                $stmt->bindValue(':lcd_angka', $lcd);
                $stmt->execute();
            }
        }
    }
    // Logika untuk logout
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
?>

<?php 
    if(isset($_POST["tambah_laptop"])){
        $merk            = $_POST["merk"];
        $nama            = $_POST["nama"];
        $harga           = preg_replace('/[^\d]/', '', $_POST["harga"]);
        $processor       = isset($_POST["processor"]) ? intval($_POST["processor"]) : 1;
        $processorH      = isset($_POST['proc_seri_h']) ? intval($_POST['proc_seri_h']) : 1;
        $processorTotal  = $processor + $processorH;
        $ram             = isset($_POST["ram"]) ? intval($_POST["ram"]) : 1;
        $vga             = isset($_POST["vga"]) ? intval($_POST["vga"]) : 1;
        $memori          = isset($_POST["memori"]) ? intval($_POST["memori"]) : 1;
        $lcd             = isset($_POST["lcd"]) ? intval($_POST["lcd"]) : 1;
        $lcd_oled        = isset($_POST['lcd_oled']) ? intval($_POST['lcd_oled']) : 1;
        $lcd_total       = $lcd + $lcd_oled;
        $processorteks   = isset($_POST["processor_teks"]) ? $_POST["processor_teks"] : '';
        $vgateks         = isset($_POST["vga_teks"]) ? $_POST["vga_teks"] : '';

        $sql = "INSERT INTO `data_laptop` (`id_laptop`, `merk`, `nama_laptop`, `harga_angka`, `processor_angka`, `ram_angka`, `vga_angka`, `memori_angka`, `lcd_angka`, `processor_h`, `lcd_oled`, `processor_teks`, `vga_teks`) 
                VALUES (NULL, :merk, :nama_laptop, :harga_angka, :processor_angka, :ram_angka, :vga_angka, :memori_angka, :lcd_angka, :processor_h, :lcd_oled, :processor_teks, :vga_teks)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':merk', $merk);
        $stmt->bindValue(':nama_laptop', $nama);
        $stmt->bindValue(':harga_angka', $harga);
        $stmt->bindValue(':processor_angka', $processorTotal);
        $stmt->bindValue(':ram_angka', $ram);
        $stmt->bindValue(':vga_angka', $vga);
        $stmt->bindValue(':memori_angka', $memori);
        $stmt->bindValue(':lcd_angka', $lcd_total);
        $stmt->bindValue(':processor_h', $processorH);
        $stmt->bindValue(':lcd_oled', $lcd_oled);
        $stmt->bindValue(':processor_teks', $processorteks);
        $stmt->bindValue(':vga_teks', $vgateks);
        $stmt->execute();
        $_SESSION['pesan_sukses'] = "Data laptop berhasil ditambahkan!";
        header("Location: admin_dashboard.php");
        exit;
    }

    if(isset($_POST["hapus_laptop"])){
        $id_hapus_laptop = $_POST['id_hapus_laptop'];
        $sql_delete = "DELETE FROM `data_laptop` WHERE `id_laptop` = :id_hapus_laptop";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bindValue(':id_hapus_laptop', $id_hapus_laptop);
        $stmt_delete->execute();


        $sql_reorder = "SET @num := 0";
        $db->query($sql_reorder);
        $sql_update = "UPDATE data_laptop SET id_laptop = (@num := @num + 1) ORDER BY id_laptop";
        $db->query($sql_update);
        $sql_reset_ai = "ALTER TABLE data_laptop AUTO_INCREMENT = 1";
        $db->query($sql_reset_ai); 
    }
    if(isset($_POST["tambah_user"])){
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role     = $_POST["role"];
    $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);
    $stmt->bindValue(':role', $role);
    $stmt->execute();
    }
    if(isset($_POST['tambah_preset'])) {
        $nama = $_POST['nama_preset'];
        $w1 = intval($_POST['w1']);
        $w2 = intval($_POST['w2']);
        $w3 = intval($_POST['w3']);
        $w4 = intval($_POST['w4']);
        $w5 = intval($_POST['w5']);
        $w6 = intval($_POST['w6']);
        $stmt = $db->prepare("INSERT INTO preset (nama, w1, w2, w3, w4, w5, w6) VALUES (:nama, :w1, :w2, :w3, :w4, :w5, :w6)");
        $stmt->execute([
            ':nama' => $nama,
            ':w1' => $w1,
            ':w2' => $w2,
            ':w3' => $w3,
            ':w4' => $w4,
            ':w5' => $w5,
            ':w6' => $w6
        ]);
        $_SESSION['pesan_sukses'] = "Preset berhasil ditambah!";
        header("Location: admin_dashboard.php");
        exit;
    }
    if(isset($_POST['hapus_preset'])) {
        $id = intval($_POST['id_preset']);
        $stmt = $db->prepare("DELETE FROM preset WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['pesan_sukses'] = "Preset berhasil dihapus!";
        header("Location: admin_dashboard.php");
        exit;
    }
    if(isset($_POST['edit_preset'])) {
        $id = intval($_POST['id_edit_preset']);
        $nama = $_POST['edit_nama_preset'];
        $w1 = intval($_POST['edit_w1']);
        $w2 = intval($_POST['edit_w2']);
        $w3 = intval($_POST['edit_w3']);
        $w4 = intval($_POST['edit_w4']);
        $w5 = intval($_POST['edit_w5']);
        $w6 = intval($_POST['edit_w6']);
        $stmt = $db->prepare("UPDATE preset SET nama=:nama, w1=:w1, w2=:w2, w3=:w3, w4=:w4, w5=:w5, w6=:w6 WHERE id=:id");
        $stmt->execute([
            ':nama' => $nama,
            ':w1' => $w1,
            ':w2' => $w2,
            ':w3' => $w3,
            ':w4' => $w4,
            ':w5' => $w5,
            ':w6' => $w6,
            ':id' => $id
        ]);
        $_SESSION['pesan_sukses'] = "Preset berhasil diubah!";
        header("Location: admin_dashboard.php");
        exit;
    }
    if(isset($_POST["hapus_user"])){
        $id_hapus_user = $_POST['id_hapus_user'];
        $sql_delete = "DELETE FROM `users` WHERE `id` = :id_hapus_user";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bindValue(':id_hapus_user', $id_hapus_user);
        $stmt_delete->execute();
        $query_reorder_id=mysqli_query($selectdb,"ALTER TABLE users AUTO_INCREMENT = 1");
    }
?>


<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"
        media="screen,projection" />
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js">
    </script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<style>
body {
    display: flex;
    min-height: 100vh;
    flex-direction: column;
    font-family: 'Roboto', sans-serif;
}

main {
    flex: 1 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-card {
    background-color: #ffffff;
    padding: 40px;
    border-radius: 8px;
    /* box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); */
    width: 90%;
    max-width: 400px;
    text-align: center;
}


.login-card h4 {
    color: #388e3c;
    margin-bottom: 30px;
    font-weight: 500;
}

nav {
    color: #000ff;
    background-color: #ffffff !important;
    /* box-shadow: 0 2px 10px rgba(0, 0, 20, 0.2); */
    width: 100%;
    height: 56px;
    line-height: 56px;
}

.container {
    max-width: 1280px !important;
    width: 80% !important;
    margin-left: auto;
    margin-right: auto;
}

.card {
    width: 100%;
    max-width: 1280px;
    margin: 0 auto 24px auto;
    box-sizing: border-box;
}

.card-content {
    padding-left: 40px;
    padding-right: 40px;
}
</style>

<body>
    <div class="navbar-fixed">
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <ul class="left" style="margin-left: -52px;">
                        <li><a href="index.php">HOME</a></li>
                        <li><a class="active" href="admin_dashboard.php">ADMIN</a></li>
                        <li><a href="?action=logout">LOGOUT</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div style="background-color: #efefef">
        <div class="container">
            <div class="section-card" style="padding: 20px 0px">
                <center>
                    <h4 class="header"
                        style="margin-left: 24px; margin-bottom: 35px; margin-top: 10px; color: #635c73;">
                        ADMIN DASHBOARD</h4>
                </center>

                <?php if (isset($_SESSION['pesan_sukses'])): ?>
                <div class="card-panel green lighten-4 green-text text-darken-4">
                    <?php echo $_SESSION['pesan_sukses']; unset($_SESSION['pesan_sukses']); ?>
                </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['pesan_error'])): ?>
                <div class="card-panel red lighten-4 red-text text-darken-4">
                    <?php echo $_SESSION['pesan_error']; unset($_SESSION['pesan_error']); ?>
                </div>
                <?php endif; ?>

                <ul>
                    <li>
                        <div class="row">
                            <div class="card">
                                <div class="card-content">
                                    <h5 style=" TEXT-ALIGN: CENTER; margin-bottom: 16px; margin-top: -2px;">KELOLA
                                        DAFTAR LAPTOP</h5>
                                    <a href="#tambah" class="waves-effect waves-light btn green modal-trigger"
                                        style="margin-bottom: 10px; border-radius: 8px;">
                                        <i class="material-icons left">add</i>Tambah Data
                                    </a>

                                    <table id="table_laptop" class="responsive-table striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center>No </center>
                                                </th>
                                                <th>
                                                    <center>Nama Laptop</center>
                                                </th>
                                                <th>
                                                    <center>Harga</center>
                                                </th>
                                                <th>
                                                    <center>Processor</center>
                                                </th>
                                                <th>
                                                    <center>RAM</center>
                                                </th>
                                                <th>
                                                    <center>Kartu Grafis</center>
                                                </th>
                                                <th>
                                                    <center>Memori</center>
                                                </th>
                                                <th>
                                                    <center>LCD</center>
                                                </th>
                                                <th>
                                                    <center>Hapus</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = mysqli_query($selectdb, "SELECT * FROM data_laptop ORDER BY id_laptop ASC");
                                            $no = 1;
												while ($data=mysqli_fetch_array($query)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <center><?php echo $no++; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['nama_laptop'] ?></center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo 'Rp. ', number_format($data['harga_angka'], 0, ',', '.'); ?>
                                                    </center>
                                                </td>

                                                <td>
                                                    <center><?php echo $data['processor_teks'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['ram_angka'],' GB'?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['vga_teks'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['memori_angka'], ' GB' ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['lcd_angka'], ' Inch' ?></center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <!-- <a href="edit_laptop.php?id=<?php echo $data['id_laptop'] ?>"
                                                            class="btn-floating btn-small waves-effect waves-light blue"
                                                            style="height: 32px; width: 32px;"><i
                                                                style="line-height: 32px;"
                                                                class="material-icons">edit</i></a> -->
                                                        <form method="POST" style="display:inline-block;">
                                                            <input type="hidden" name="id_hapus_laptop"
                                                                value="<?php echo $data['id_laptop'] ?>">
                                                            <button type="submit" name="hapus_laptop"
                                                                style="height: 32px; width: 32px;"
                                                                class="btn-floating btn-small waves-effect waves-light red"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i
                                                                    style="line-height: 32px;"
                                                                    class="material-icons">delete</i></button>
                                                        </form>
                                                    </center>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="card">
                                <div class="card-content">
                                    <h5 style="text-align: center; margin-bottom: 16px;">KELOLA PRESET REKOMENDASI</h5>
                                    <form method="POST" action="">
                                        <div class="row">
                                            <div class="input-field col s2">
                                                <input name="nama_preset" type="text" required
                                                    placeholder="Nama preset (misal: gaming)">
                                            </div>
                                            <div class="input-field col s1"><input name="w1" type="number" min="1"
                                                    max="5" required placeholder="Harga"></div>
                                            <div class="input-field col s1"><input name="w2" type="number" min="1"
                                                    max="5" required placeholder="Processor"></div>
                                            <div class="input-field col s1"><input name="w3" type="number" min="1"
                                                    max="5" required placeholder="RAM"></div>
                                            <div class="input-field col s1"><input name="w4" type="number" min="1"
                                                    max="5" required placeholder="VGA"></div>
                                            <div class="input-field col s1"><input name="w5" type="number" min="1"
                                                    max="5" required placeholder="Memori"></div>
                                            <div class="input-field col s1"><input name="w6" type="number" min="1"
                                                    max="5" required placeholder="LCD"></div>
                                            <div class="input-field col s2" style="margin-left: 80px;">
                                                <button name="tambah_preset" type="submit" class="btn primary"
                                                    style="width: 100%; border-radius: 8px; border-radius: 10px;">
                                                    <i class="material-icons left">add</i>Tambah</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="striped">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Harga</th>
                                                <th>Processor</th>
                                                <th>RAM</th>
                                                <th>VGA</th>
                                                <th>Memori</th>
                                                <th>LCD</th>
                                                <th>Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $preset_q = $db->query("SELECT * FROM preset");
                                            while($p = $preset_q->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>
                                                        <td>{$p['nama']}</td>
                                                        <td>{$p['w1']}</td>
                                                        <td>{$p['w2']}</td>
                                                        <td>{$p['w3']}</td>
                                                        <td>{$p['w4']}</td>
                                                        <td>{$p['w5']}</td>
                                                        <td>{$p['w6']}</td>
                                                        <td>
                                                            <form method='POST' style='display:inline;'>
                                                                <input type='hidden' name='id_preset' value='{$p['id']}'>
                                                                <button type='submit' name='hapus_preset' class='btn red btn-small' onclick=\"return confirm('Hapus preset ini?')\">Hapus</button>
                                                            </form>
                                                            <button 
                                                                class=\"btn blue btn-small edit-preset-btn\" 
                                                                style=\"margin-left:5px;\"
                                                                data-id=\"{$p['id']}\"
                                                                data-nama=\"".htmlspecialchars($p['nama'])."\"
                                                                data-w1=\"{$p['w1']}\"
                                                                data-w2=\"{$p['w2']}\"
                                                                data-w3=\"{$p['w3']}\"
                                                                data-w4=\"{$p['w4']}\"
                                                                data-w5=\"{$p['w5']}\"
                                                                data-w6=\"{$p['w6']}\"
                                                                type=\"button\">
                                                                Edit
                                                            </button>
                                                        </td>
                                                    </tr>";
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="card">
                                <div class="card-content">
                                    <h5 style=" text-align: center; margin-bottom: 16px; margin-top: -6px;">KELOLA USER
                                    </h5>
                                    <a href="#tambah_user" class="waves-effect waves-light btn blue modal-trigger"
                                        style="margin-bottom: 10px; border-radius: 8px;">
                                        <i class="material-icons left">person_add</i>Tambah User
                                    </a>
                                    <table id="table_user" class="responsive-table striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center>No</center>
                                                </th>
                                                <th>
                                                    <center>Username</center>
                                                </th>
                                                <th>
                                                    <center>Role</center>
                                                </th>
                                                <th>
                                                    <center>Hapus</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                            $query_user = mysqli_query($selectdb, "SELECT * FROM users ORDER BY id ASC"); // Menggunakan tabel `users`
                            $no_user = 1;
                            while ($user = mysqli_fetch_array($query_user)) {
                            ?>
                                            <tr>
                                                <td>
                                                    <center><?= $no_user++; ?></center>
                                                </td>
                                                <td>
                                                    <center><?= htmlspecialchars($user['username']); ?></center>
                                                </td>
                                                <td>
                                                    <center><?= htmlspecialchars($user['role']); ?></center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <!-- <a href="edit_user.php?id=<?= $user['id'] ?>"
                                                            class="btn-floating btn-small waves-effect waves-light blue"
                                                            style="height: 32px; width: 32px;"><i
                                                                style="line-height: 32px;"
                                                                class="material-icons">edit</i></a> -->
                                                        <form method="POST" style="display:inline-block;">
                                                            <input type="hidden" name="id_hapus_user"
                                                                value="<?= $user['id'] ?>">
                                                            <button type="submit" name="hapus_user"
                                                                style="height: 32px; width: 32px;"
                                                                class="btn-floating btn-small waves-effect waves-light red"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');"><i
                                                                    style="line-height: 32px;"
                                                                    class="material-icons">delete</i></button>
                                                        </form>
                                                    </center>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal Edit Preset -->
    <div id="modal_edit_preset" class="modal">
        <div class="modal-content">
            <h5>Edit Preset</h5>
            <form method="POST" action="">
                <input type="hidden" name="id_edit_preset" id="edit_id_preset">
                <div class="row">
                    <div class="input-field col s3">
                        <input name="edit_nama_preset" id="edit_nama_preset" type="text" required
                            placeholder="Nama preset">
                    </div>
                    <div class="input-field col s1"><input name="edit_w1" id="edit_w1" type="number" min="1" max="5"
                            required placeholder="Harga"></div>
                    <div class="input-field col s1"><input name="edit_w2" id="edit_w2" type="number" min="1" max="5"
                            required placeholder="Processor"></div>
                    <div class="input-field col s1"><input name="edit_w3" id="edit_w3" type="number" min="1" max="5"
                            required placeholder="RAM"></div>
                    <div class="input-field col s1"><input name="edit_w4" id="edit_w4" type="number" min="1" max="5"
                            required placeholder="VGA"></div>
                    <div class="input-field col s1"><input name="edit_w5" id="edit_w5" type="number" min="1" max="5"
                            required placeholder="Memori"></div>
                    <div class="input-field col s1"><input name="edit_w6" id="edit_w6" type="number" min="1" max="5"
                            required placeholder="LCD"></div>
                    <div class="input-field col s2">
                        <button name="edit_preset" type="submit" class="btn green"
                            style="width:100%;border-radius:8px;">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="tambah" class="modal" style="width: 45%; height: 1100%;">
        <div class="modal-content ">
            <div class="col s12">
                <div class="card-content">
                    <div class="row">
                        <center>
                            <h5 style="margin-top:-8px;">Masukan Data Laptop</h5>
                        </center>
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field col s12" style="display: flex; align-items: center;">
                                        <div style="flex: 0 0 180px; margin-right: 10px;">
                                            <select name="merk" required style="width: 100px;">
                                                <option value="" disabled selected>Merk Laptop</option>
                                                <option value="Acer">Acer</option>
                                                <option value="Asus">Asus</option>
                                                <option value="HP">HP</option>
                                                <option value="Lenovo">Lenovo</option>
                                                <option value="MSI">MSI</option>
                                                <option value="Macbook">Macbook</option>
                                                <option value="Lokal">Lokal</option>
                                            </select>
                                            <label for="Laptop">Seri Laptop</label>
                                        </div>
                                        <div style="flex: 1;">
                                            <input id="nama" name="nama" type="text"
                                                placeholder="misal: Asus Vivobook 15">
                                        </div>
                                    </div>

                                    <div class="input-field col s12" style="display: flex; align-items: center;">
                                        <div style="flex: 0 0 180px; margin-right: 10px; margin-top: -10px;">
                                            <select name="processor" required style="width: 100px;">
                                                <option value="" disabled selected>Kriteria Processor</option>
                                                <option value="35">Intel Celeron Seri N</option>
                                                <option value="40">AMD/Intel Series 3</option>
                                                <option value="50">AMD/Intel Series 5</option>
                                                <option value="60">AMD/Intel Series 7</option>
                                                <option value="70">Intel Gen > 10 </option>
                                                <option value="90">Intel Core Ultra</option>
                                            </select>
                                        </div>
                                        <div style="padding-right: 10px;flex: 1; margin-top: -10px;">
                                            <input id=" processor_teks" name="processor_teks" type="text"
                                                placeholder="Masukkan (misal: i5-1235U)">

                                        </div>
                                        <div style="display: flex: 1; margin- margin-top: 20px;">
                                            <label>
                                                <input type="checkbox" class="filled-in" name="proc_seri_h"
                                                    value="15" />
                                                <span>Seri H</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="input-field col s12" style="display: flex; align-items: center;">
                                        <div style="flex: 0 0 180px; margin-right: 10px;">
                                            <select name="vga" id="vga_select" required>
                                                <option value="" disabled selected>Kriteria VGA</option>
                                                <option value="40"> Intel UHD</option>
                                                <option value="45"> AMD Radeon</option>
                                                <option value="505"> Intel iris Xe </option>
                                                <option value="65"> RTX 2050</option>
                                                <option value="75"> RTX 3050</option>
                                                <option value="90"> Intel Arc Graphic</option>
                                                <option value="95">RTX 4050</option>
                                            </select>
                                            <!-- <label for="vga">Seri VGA</label> -->
                                            <input type="hidden" name="vga_teks" id="vga_teks">
                                        </div>
                                        <div style="flex: 1;">
                                            <select name="ram" required>
                                                <option value="" disabled selected>Pilih RAM</option>
                                                <!-- <option value="2">2 Gb</option> -->
                                                <option value="4">4 Gb</option>
                                                <option value="8">8 Gb</option>
                                                <option value="16">16 Gb</option>
                                                <option value="32">32 Gb</option>
                                            </select>
                                            <!-- <label>RAM</label> -->
                                        </div>
                                    </div>

                                    <div class="input-field col s12" style="display: flex; align-items: center;">
                                        <div style="flex: 0 0 180px; margin-top: -60px;  margin-right: 10px;">
                                            <select name="memori" required>
                                                <option value="" disabled selected>Pilih Penyimpanan</option>
                                                <option value="256">256 GB</option>
                                                <option value="512">512 GB</option>
                                                <option value="1024">1 Tera</option>
                                            </select>
                                        </div>
                                        <div class="input-field col s12" style="margin-top: -10px;">
                                            <select name="lcd" required>
                                                <option value="" disabled selected>Pilih Ukuran LCD</option>
                                                <option value="13">13 Inch</option>
                                                <option value="14">14 Inch</option>
                                                <option value="15">15 Inch</option>
                                                <option value="16">16 Inch</option>
                                            </select>

                                            <!-- <label>Ukuran LCD</label> -->
                                            <div style="margin-top:20px;">
                                                <label>
                                                    <input type="checkbox" class="filled-in" name="lcd_oled"
                                                        value="10" />
                                                    <span>OLED</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="input-field col s12" style="margin-top: -30px;">
                                        <input id="harga" name="harga" type="text" required oninput="formatRupiah(this)"
                                            placeholder="Masukkan harga"> <label for="harga">Harga</label>
                                    </div>

                                </div>
                            </div>
                            <center><button name="tambah_laptop" type="submit" class="waves-effect waves-light btn teal"
                                    style="margin-top: 2px;">Tambah</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="modal-footer" style="height: auto;">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Tutup</a>
        </div> -->
    </div>
    <div id="tambah_user" class="modal" style="width: 30%; height: auto;">
        <div class="modal-content">
            <h5>Tambah User</h5>
            <form method="POST" action="">
                <div class="input-field">
                    <input id="username_user" name="username" type="text" required>
                    <label for="username_user">Username</label>
                </div>
                <div class="input-field">
                    <input id="password_user" name="password" type="password" required>
                    <label for="password_user">Password</label>
                </div>
                <div class="input-field">
                    <select name="role" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    <label>Role</label>
                </div>
                <center>
                    <button name="tambah_user" type="submit" class="waves-effect waves-light btn blue"
                        style="margin-top: 10px;">Tambah</button>
                </center>
            </form>
        </div>
        <div class="modal-footer" style="height: auto;">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Tutup</a>
        </div>
    </div>
    <div class="footer-copyright" style="padding: 0px 0px; background-color: white">
        <div class="container">
            <p align="center" style="color: #999">&copy; Sistem Pendukung Keputusan Pemilihan Smartphone 2018.</p>
        </div>
    </div>
    <script>
    function formatRupiah(el) {
        let angka = el.value.replace(/[^,\d]/g, '').toString();
        let split = angka.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        el.value = rupiah ? 'Rp. ' + rupiah : '';
    }
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        // Inisialisasi semua modal
        $('.modal').modal();
        // Inisialisasi semua select Materialize
        $('select').formSelect();

        // Inisialisasi DataTables untuk masing-masing tabel
        $('#table_laptop').DataTable({
            "paging": true, // Aktifkan pagination
            "ordering": true, // Aktifkan sorting
            "info": true // Tampilkan info entri
        });
        $('#table_user').DataTable({
            "paging": true, // Aktifkan pagination
            "ordering": true, // Aktifkan sorting
            "info": true // Tampilkan info entri
        });
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var vgaSelect = document.getElementById('vga_select');
        var vgaTeks = document.getElementById('vga_teks');
        if (vgaSelect) {
            vgaSelect.addEventListener('change', function() {
                var selectedText = vgaSelect.options[vgaSelect.selectedIndex].text;
                vgaTeks.value = selectedText.trim();
            });
        }
    });
    </script>
    // ...existing code...
    <script>
        $(document).ready(function() {
            // ...existing code...
            $('.edit-preset-btn').on('click', function() {
                $('#edit_id_preset').val($(this).data('id'));
                $('#edit_nama_preset').val($(this).data('nama'));
                $('#edit_w1').val($(this).data('w1'));
                $('#edit_w2').val($(this).data('w2'));
                $('#edit_w3').val($(this).data('w3'));
                $('#edit_w4').val($(this).data('w4'));
                $('#edit_w5').val($(this).data('w5'));
                $('#edit_w6').val($(this).data('w6'));
                M.updateTextFields();
                $('#modal_edit_preset').modal('open');
            });
            // ...existing code...
        });
    </script>
</body>

</html>