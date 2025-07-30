<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
    include('koneksi.php');

    session_start();
    // Redirect if not logged in or not a user
    if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
        header('Location: login.php');
        exit;
    }

    // Logic for adding a new laptop
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
        header("Location: user_dashboard.php");
        exit;
    }

    // Logout logic
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>
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
                        <li><a class="active" href="user_dashboard.php">DASHBOARD</a></li>
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
                        USER DASHBOARD</h4>
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
                                    <h5 style=" TEXT-ALIGN: CENTER; margin-bottom: 16px; margin-top: -2px;">DAFTAR LAPTOP</h5>
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
                    
                </ul>
            </div>
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
                                            <input type="hidden" name="vga_teks" id="vga_teks">
                                        </div>
                                        <div style="flex: 1;">
                                            <select name="ram" required>
                                                <option value="" disabled selected>Pilih RAM</option>
                                                <option value="4">4 Gb</option>
                                                <option value="8">8 Gb</option>
                                                <option value="16">16 Gb</option>
                                                <option value="32">32 Gb</option>
                                            </select>
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

        // Inisialisasi DataTables untuk tabel laptop
        $('#table_laptop').DataTable({
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
</body>

</html>