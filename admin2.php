<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

    session_start();
    include('koneksi.php'); // Pastikan file koneksi.php Anda menginisialisasi $selectdb dengan mysqli_connect()

    // Proteksi halaman admin
    if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
        header('Location: login.php');
        exit;
    }

    // Logika untuk import excel
    if (isset($_POST['import_excel'])) {
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
            require 'vendor/autoload.php'; // pastikan sudah install phpoffice/phpspreadsheet

            $fileTmpPath = $_FILES['excel_file']['tmp_name'];
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Lewati baris header, mulai dari baris ke-2
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                // Pastikan urutan kolom sesuai: nama, harga, processor, ram, vga, memori, lcd
                $nama = $row[0];
                $harga = $row[1];
                $processor = $row[2];
                $ram = $row[3];
                $vga = $row[4];
                $memori = $row[5];
                $lcd = $row[6];

                // Mapping nilai angka untuk disimpan ke database
                // Ini harus sesuai dengan mapping yang Anda gunakan di daftar_laptop.php
                // Jika data excel langsung berisi nilai angka yang benar, Anda bisa langsung menggunakannya.
                // Jika tidak, Anda perlu menerapkan logika mapping di sini.
                // Untuk contoh ini, saya asumsikan harga_angka, processor_angka, dll. sudah dalam bentuk angka yang sesuai
                // Jika di Excel hanya ada nilai mentah (misal: "8GB", "Intel Core i5"), maka Anda harus mengkonversinya di sini
                // Contoh:
                $harga_angka_mapped = 0;
                if($harga < 1000000){ $harga_angka_mapped = 5; } 
                elseif($harga >= 1000000 && $harga <= 3000000){ $harga_angka_mapped = 4; }
                elseif($harga > 3000000 && $harga <= 4000000){ $harga_angka_mapped = 3; }
                elseif($harga > 4000000 && $harga <= 5000000){ $harga_angka_mapped = 2; }
                elseif($harga > 5000000){ $harga_angka_mapped = 1; }

                $ram_angka_mapped = $ram; // Asumsi langsung nilai angka
                
                $memori_angka_mapped = 0; // Contoh mapping memori
                if($memori == 4){ $memori_angka_mapped = 1; }
                elseif($memori == 8){ $memori_angka_mapped = 2; }
                elseif($memori == 16){ $memori_angka_mapped = 3; }
                elseif($memori == 32){ $memori_angka_mapped = 4; }
                elseif($memori == 64){ $memori_angka_mapped = 5; }
                elseif($memori == 128){ $memori_angka_mapped = 6; }
                elseif($memori == 256){ $memori_angka_mapped = 7; }
                elseif($memori == 512){ $memori_angka_mapped = 8; }
                elseif($memori == 1000){ $memori_angka_mapped = 9; }

                $processor_angka_mapped = 0; // Contoh mapping processor
                // Anda perlu menyesuaikan ini dengan string processor dari excel
                if($processor == "Dualcore"){ $processor_angka_mapped = 1; }
                elseif($processor == "Quadcore"){ $processor_angka_mapped = 3; }
                elseif($processor == "Octacore"){ $processor_angka_mapped = 5; }
                elseif($processor == "Intel Core i3" || $processor == "AMD Ryzen 3"){ $processor_angka_mapped = 2; }
                elseif($processor == "Intel Core i5" || $processor == "AMD Ryzen 5"){ $processor_angka_mapped = 4; }
                elseif($processor == "Intel Core i7" || $processor == "AMD Ryzen 7"){ $processor_angka_mapped = 6; }
                elseif($processor == "Intel Core i9" || $processor == "AMD Ryzen 9"){ $processor_angka_mapped = 7; }

                $vga_angka_mapped = 0; // Contoh mapping VGA
                if($vga == "Integrated"){ $vga_angka_mapped = 1; }
                elseif($vga == "Dedicated Entry"){ $vga_angka_mapped = 3; }
                elseif($vga == "Dedicated Mid"){ $vga_angka_mapped = 4; }
                elseif($vga == "Dedicated High"){ $vga_angka_mapped = 5; }

                $lcd_angka_mapped = 0; // Contoh mapping LCD
                if($lcd == 8){ $lcd_angka_mapped = 1; }
                elseif($lcd == 13){ $lcd_angka_mapped = 3; }
                elseif($lcd == 14){ $lcd_angka_mapped = 4; }
                elseif($lcd == 15){ $lcd_angka_mapped = 5; }
                elseif($lcd == 16){ $lcd_angka_mapped = 6; }
                elseif($lcd == 17){ $lcd_angka_mapped = 7; }

                $kamera_angka_mapped = $lcd_angka_mapped; // Menggunakan LCD untuk kamera seperti di daftar_laptop.php


                // Query menggunakan MySQLi
                $sql = "INSERT INTO `data_laptop` (`nama_laptop`, `harga_angka`, `processor_angka`, `ram_angka`, `vga_angka`, `memori_angka`, `lcd_angka`, `kamera_angka`, `harga_teks`, `processor_teks`, `ram_teks`, `vga_teks`, `memori_teks`, `lcd_teks`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($selectdb, $sql);
                mysqli_stmt_bind_param($stmt, "siiiiiisssssss", 
                    $nama, 
                    $harga_angka_mapped, 
                    $processor_angka_mapped, 
                    $ram_angka_mapped, 
                    $vga_angka_mapped, 
                    $memori_angka_mapped, 
                    $lcd_angka_mapped, 
                    $kamera_angka_mapped,
                    "Rp. " . number_format($harga, 0, ',', '.'), // Harga teks dari nilai mentah
                    $processor, // Processor teks dari nilai mentah
                    $ram . " GB", // RAM teks dari nilai mentah
                    $vga, // VGA teks dari nilai mentah
                    $memori . " GB", // Memori teks dari nilai mentah
                    $lcd . " Inch" // LCD teks dari nilai mentah
                );
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            $_SESSION['pesan_sukses'] = "Data laptop berhasil diimport dari Excel!";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $_SESSION['pesan_error'] = "Gagal mengimport file Excel.";
            header('Location: admin_dashboard.php');
            exit();
        }
    }

    // Logika untuk logout
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }

    // Logika untuk menambah data laptop
    if(isset($_POST["tambah_laptop"])){
        $nama      = $_POST["nama"];
        $harga     = $_POST["harga"];
        $ram       = $_POST["ram"];
        $vga       = $_POST["vga"]; 
        $memori    = $_POST["memori"];
        $processor = $_POST["processor"];
        $lcd       = $_POST["lcd"]; 

        // Mapping nilai ke angka (sesuai logika Anda dari daftar_laptop.php)
        $harga_angka = 0;
        if($harga < 1000000){ $harga_angka = 5; } 
        elseif($harga >= 1000000 && $harga <= 3000000){ $harga_angka = 4; }
        elseif($harga > 3000000 && $harga <= 4000000){ $harga_angka = 3; }
        elseif($harga > 4000000 && $harga <= 5000000){ $harga_angka = 2; }
        elseif($harga > 5000000){ $harga_angka = 1; }

        $ram_angka = $ram; // Anda langsung menggunakan nilai RAM

        $memori_angka = 0;
        if($memori == 4){ $memori_angka = 1; }
        elseif($memori == 8){ $memori_angka = 2; }
        elseif($memori == 16){ $memori_angka = 3; }
        elseif($memori == 32){ $memori_angka = 4; }
        elseif($memori == 64){ $memori_angka = 5; }
        elseif($memori == 128){ $memori_angka = 6; } 
        elseif($memori == 256){ $memori_angka = 7; }
        elseif($memori == 512){ $memori_angka = 8; }
        elseif($memori == 1000){ $memori_angka = 9; }

        $processor_angka = 0;
        if($processor == "Dualcore"){ $processor_angka = 1; }
        elseif($processor == "Quadcore"){ $processor_angka = 3; }
        elseif($processor == "Octacore"){ $processor_angka = 5; }
        elseif($processor == "Intel Core i3" || $processor == "AMD Ryzen 3"){ $processor_angka = 2; }
        elseif($processor == "Intel Core i5" || $processor == "AMD Ryzen 5"){ $processor_angka = 4; }
        elseif($processor == "Intel Core i7" || $processor == "AMD Ryzen 7"){ $processor_angka = 6; }
        elseif($processor == "Intel Core i9" || $processor == "AMD Ryzen 9"){ $processor_angka = 7; }

        $vga_angka = 0;
        if($vga == "Integrated"){ $vga_angka = 1; }
        elseif($vga == "Dedicated Entry"){ $vga_angka = 3; }
        elseif($vga == "Dedicated Mid"){ $vga_angka = 4; }
        elseif($vga == "Dedicated High"){ $vga_angka = 5; }
        
        $lcd_angka = 0;
        if($lcd == 8){ $lcd_angka = 1; }
        elseif($lcd == 13){ $lcd_angka = 3; }
        elseif($lcd == 14){ $lcd_angka = 4; }
        elseif($lcd == 15){ $lcd_angka = 5; }
        elseif($lcd == 16){ $lcd_angka = 6; }
        elseif($lcd == 17){ $lcd_angka = 7; }

        $kamera_wp_val = $lcd_angka; // Menggunakan lcd_angka untuk kamera_angka di WP


        // Query INSERT menggunakan MySQLi
        $sql = "INSERT INTO `data_laptop` (`nama_laptop`, `harga_angka`, `ram_angka`, `memori_angka`, `processor_angka`, `vga_angka`, `lcd_angka`, `kamera_angka`, `harga_teks`, `ram_teks`, `memori_teks`, `processor_teks`, `vga_teks`, `lcd_teks`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($selectdb, $sql);
        
        mysqli_stmt_bind_param($stmt, "siiiiiisssssss", 
            $nama, 
            $harga_angka, 
            $ram_angka, 
            $memori_angka, 
            $processor_angka, 
            $vga_angka, 
            $lcd_angka, 
            $kamera_wp_val, 
            "Rp. " . number_format($harga, 0, ',', '.'), 
            $ram . " GB", 
            $memori . " GB", 
            $processor, 
            $vga, 
            $lcd . " Inch" 
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Data laptop berhasil ditambahkan!";
        } else {
            $_SESSION['pesan_error'] = "Gagal menambah data: " . mysqli_error($selectdb);
        }
        mysqli_stmt_close($stmt);
        header('Location: admin_dashboard.php');
        exit();
    }

    // Logika untuk menghapus data laptop
    if(isset($_POST["hapus_laptop"])){
        $id_hapus_laptop = $_POST['id_hapus_laptop'];
        $stmt_delete = mysqli_prepare($selectdb, "DELETE FROM `data_laptop` WHERE `id_laptop` = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $id_hapus_laptop);
        mysqli_stmt_execute($stmt_delete);
        mysqli_stmt_close($stmt_delete);
        // Reset AUTO_INCREMENT (opsional, tapi bisa membantu menjaga ID tetap rapi)
        mysqli_query($selectdb, "ALTER TABLE data_laptop AUTO_INCREMENT = 1"); 
        $_SESSION['pesan_sukses'] = "Data laptop berhasil dihapus!";
        header('Location: admin_dashboard.php');
        exit();
    }

    // Logika untuk menambah user
    if(isset($_POST["tambah_user"])){
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $role     = $_POST["role"];

        $stmt_user = mysqli_prepare($selectdb, "INSERT INTO `users` (`username`, `password`, `role`) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_user, "sss", $username, $password, $role);
        if (mysqli_stmt_execute($stmt_user)) {
            $_SESSION['pesan_sukses'] = "User baru berhasil ditambahkan!";
        } else {
            $_SESSION['pesan_error'] = "Gagal menambah user: " . mysqli_error($selectdb);
        }
        mysqli_stmt_close($stmt_user);
        header('Location: admin_dashboard.php');
        exit();
    }

    // Logika untuk menghapus user
    if(isset($_POST["hapus_user"])){
        $id_hapus_user = $_POST['id_hapus_user'];
        $stmt_delete_user = mysqli_prepare($selectdb, "DELETE FROM `users` WHERE `id` = ?");
        mysqli_stmt_bind_param($stmt_delete_user, "i", $id_hapus_user);
        if (mysqli_stmt_execute($stmt_delete_user)) {
            $_SESSION['pesan_sukses'] = "User berhasil dihapus!";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus user: " . mysqli_error($selectdb);
        }
        mysqli_stmt_close($stmt_delete_user);
        header('Location: admin_dashboard.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" media="screen,projection" />
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>

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
                    <h4 class="header" style="margin-left: 24px; margin-bottom: 0px; margin-top: 24px; color: #635c73;">
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
                                    <h5 style="margin-bottom: 16px; margin-top: -6px;">Kelola Daftar Laptop</h5>
                                    <a href="#tambah" class="waves-effect waves-light btn green modal-trigger"
                                        style="margin-bottom: 10px;">
                                        <i class="material-icons left">add</i>Tambah Data
                                    </a>
                                    <a href="export_excel.php" class="waves-effect waves-light btn blue"
                                        style="margin-bottom: 10px;">
                                        <i class="material-icons left">file_download</i>Export Data
                                    </a>
                                    <a href="import_excel.php" class="waves-effect waves-light btn orange"
                                        style="margin-bottom: 10px;">
                                        <i class="material-icons left">file_upload</i>Import Excel
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
                                                    <center>Aksi</center>
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
                                                        <?php echo $data['harga_teks']; // Tampilkan teks harga yang sudah diformat ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['processor_teks'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['ram_teks'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['vga_teks'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['memori_teks'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['lcd_teks'] ?></center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <a href="edit_laptop.php?id=<?php echo $data['id_laptop'] ?>"
                                                            class="btn-floating btn-small waves-effect waves-light blue"
                                                            style="height: 32px; width: 32px;"><i
                                                                style="line-height: 32px;"
                                                                class="material-icons">edit</i></a>
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
                                    <h5 style="margin-bottom: 16px; margin-top: -6px;">Kelola User</h5>
                                    <a href="#tambah_user" class="waves-effect waves-light btn blue modal-trigger"
                                        style="margin-bottom: 10px;">
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
                                                    <center>Aksi</center>
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
                                                        <a href="edit_user.php?id=<?= $user['id'] ?>"
                                                            class="btn-floating btn-small waves-effect waves-light blue"
                                                            style="height: 32px; width: 32px;"><i
                                                                style="line-height: 32px;"
                                                                class="material-icons">edit</i></a>
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

    <div id="tambah" class="modal" style="width: 40%; height: auto;"> <div class="modal-content ">
            <div class="col s12"> <div class="card-content">
                    <div class="row">
                        <center>
                            <h5 style="margin-top:-8px;">Masukan Laptop</h5>
                        </center>
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field col s12">
                                        <input id="nama_laptop" name="nama" type="text" required>
                                        <label for="nama_laptop">Nama Laptop</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input id="harga_laptop" name="harga" type="number" required>
                                        <label for="harga_laptop">Harga</label>
                                    </div>
                                    
                                    <div class="input-field col s12">
                                        <select name="ram" required>
                                            <option value="" disabled selected>Pilih RAM</option>
                                            <option value="1">1 GB</option>
                                            <option value="2">2 GB</option>
                                            <option value="3">3 GB</option>
                                            <option value="4">4 GB</option>
                                            <option value="6">6 GB</option>
                                            <option value="8">8 GB</option>
                                            <option value="12">12 GB</option>
                                            <option value="16">16 GB</option>
                                            <option value="32">32 GB</option>
                                        </select>
                                        <label>RAM</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <select name="memori" required>
                                            <option value="" disabled selected>Pilih Memori</option>
                                            <option value="4">4 GB</option>
                                            <option value="8">8 GB</option>
                                            <option value="16">16 GB</option>
                                            <option value="32">32 GB</option>
                                            <option value="64">64 GB</option>
                                            <option value="128">128 GB</option>
                                            <option value="256">256 GB</option>
                                            <option value="512">512 GB</option>
                                            <option value="1000">1 TB</option>
                                        </select>
                                        <label>Memori (Penyimpanan)</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <select name="processor" required>
                                            <option value="" disabled selected>Pilih Processor</option>
                                            <option value="Dualcore">Dualcore</option>
                                            <option value="Quadcore">Quadcore</option>
                                            <option value="Octacore">Octacore</option>
                                            <option value="Intel Core i3">Intel Core i3</option>
                                            <option value="Intel Core i5">Intel Core i5</option>
                                            <option value="Intel Core i7">Intel Core i7</option>
                                            <option value="Intel Core i9">Intel Core i9</option>
                                            <option value="AMD Ryzen 3">AMD Ryzen 3</option>
                                            <option value="AMD Ryzen 5">AMD Ryzen 5</option>
                                            <option value="AMD Ryzen 7">AMD Ryzen 7</option>
                                            <option value="AMD Ryzen 9">AMD Ryzen 9</option>
                                        </select>
                                        <label>Processor</label>
                                    </div>
                                    
                                    <div class="input-field col s12">
                                        <select name="vga" required>
                                            <option value="" disabled selected>Pilih Kartu Grafis (VGA)</option>
                                            <option value="Integrated">Integrated (Intel UHD/Iris Xe, AMD Radeon)</option>
                                            <option value="Dedicated Entry">Dedicated Entry (GTX 1650, RTX 2050)</option>
                                            <option value="Dedicated Mid">Dedicated Mid (RTX 3050, RTX 4050)</option>
                                            <option value="Dedicated High">Dedicated High (RTX 3060+, RTX 4060+)</option>
                                        </select>
                                        <label>Kartu Grafis (VGA)</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <select name="lcd" required>
                                            <option value="" disabled selected>Pilih Ukuran LCD</option>
                                            <option value="8">8 inci (misal untuk tablet)</option>
                                            <option value="13">13 inci</option>
                                            <option value="14">14 inci</option>
                                            <option value="15">15 inci</option>
                                            <option value="16">16 inci</option>
                                            <option value="17">17 inci</option>
                                        </select>
                                        <label>Ukuran LCD</label>
                                    </div>

                                </div>
                            </div>
                            <center><button name="tambah_laptop" type="submit" class="waves-effect waves-light btn teal"
                                    style="margin-top: 0px;">Tambah</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="height: auto;">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Tutup</a>
        </div>
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
</body>

</html>