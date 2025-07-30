<?php
session_start();
include('koneksi.php');

$W1 = 0; // Harga
$W2 = 0; // Processor
$W3 = 0; // RAM
$W4 = 0; // VGA
$W5 = 0; // Memori
$W6 = 0; // LCD

$min_harga_filter = null;
$max_harga_filter = null;

if (isset($_POST['action'])) {
    $preset = $_POST['preset'];
    // Ambil preset dari database (using PDO, as you've already implemented)
    $stmt = $db->prepare("SELECT * FROM preset WHERE nama = :nama LIMIT 1");
    $stmt->execute([':nama' => $preset]);
    $preset_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($preset_data) {
        $W1 = $preset_data['w1'];
        $W2 = $preset_data['w2'];
        $W3 = $preset_data['w3'];
        $W4 = $preset_data['w4'];
        $W5 = $preset_data['w5'];
        $W6 = $preset_data['w6'];
    } else {
        // fallback default jika preset tidak ditemukan
        $W1 = 3; $W2 = 3; $W3 = 3; $W4 = 3; $W5 = 3; $W6 = 3;
    }
} elseif (isset($_POST['preset_manual'])) { // Jika form manual yang disubmit
    $W1 = $_POST['harga_manual'];
    $W2 = $_POST['processor_manual'];
    $W3 = $_POST['ram_manual'];
    $W4 = $_POST['vga_manual'];
    $W5 = $_POST['memori_manual'];
    $W6 = $_POST['lcd_manual'];

    // --- NEW LOGIC FOR MANUAL PRICE FILTER ---
    $harga_manual_rating = (int)$_POST['harga_manual'];

    // Define price ranges based on the rating (adjust these values as needed for your data)
    switch ($harga_manual_rating) {
        case 1: // Sangat Rendah
            $min_harga_filter = 0;
            $max_harga_filter = 5000000;
            break;
        case 2: // Rendah
            $min_harga_filter = 5000001;
            $max_harga_filter = 10000000;
            break;
        case 3: // Sedang
            $min_harga_filter = 10000001;
            $max_harga_filter = 15000000;
            break;
        case 4: // Tinggi
            $min_harga_filter = 15000001;
            $max_harga_filter = 20000000;
            break;
        case 5: // Sangat Tinggi
            $min_harga_filter = 20000001;
            $max_harga_filter = 999999999; // A very high number to represent "above"
            break;
        default:
            // No price filter if rating is invalid or not set
            $min_harga_filter = null;
            $max_harga_filter = null;
            break;
    }
    // --- END NEW LOGIC ---

} else {
    header('Location: rekomendasi.php');
    exit;
}

// Normalisasi bobot yang sudah didapatkan dari preset/manual
$total_bobot = $W1 + $W2 + $W3 + $W4 + $W5 + $W6;

// Avoid division by zero if total_bobot is 0
if ($total_bobot == 0) {
    // Handle this error or set normalized weights to default/zero
    $W1_norm = $W2_norm = $W3_norm = $W4_norm = $W5_norm = $W6_norm = 0;
} else {
    $W1_norm = $W1 / $total_bobot;
    $W2_norm = $W2 / $total_bobot;
    $W3_norm = $W3 / $total_bobot;
    $W4_norm = $W4 / $total_bobot;
    $W5_norm = $W5 / $total_bobot;
    $W6_norm = $W6 / $total_bobot;
}
?>
<?php
include ('header.php');
?>

<body>
    <div class="navbar-fixed">
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <ul class="left" style="margin-left: -52px;">
                        <li><a href="index.php">HOME</a></li>
                        <li><a href="daftar_laptop.php">DAFTAR LAPTOP</a></li>
                        <li><a href="login.php">LOGIN</a></li>
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
                        HASIL REKOMENDASI LAPTOP <?php echo isset($preset) ? strtoupper($preset) : 'MANUAL'; ?></h4>                </center>
                <ul>
                    <li>
                        <div class="row">
                            <div class="card">
                                <div class="card-content">
                                    <h5 style="margin-bottom: 16px; margin-top: -6px;">Matrik Laptop</h5>
                                    <table class="responsive-table">

                                        <thead style="border-top: 1px solid #d0d0d0;">
                                            <tr>
                                                <th>
                                                    <center>Alternatif</center>
                                                </th>
                                                <th>
                                                    <center>C1 (Harga - Cost)</center>
                                                </th>
                                                <th>
                                                    <center>C2 (Processor - Benefit)</center>
                                                </th>
                                                <th>
                                                    <center>C3 (RAM - Benefit)</center>
                                                </th>
                                                <th>
                                                    <center>C4 (VGA - Benefit)</center>
                                                </th>
                                                <th>
                                                    <center>C5 (Memory - Benefit)</center>
                                                </th>
                                                <th>
                                                    <center>C6 (LCD - Benefit)</center>
                                                </th>
                                            </tr>
                                        </thead>
<tbody>
                                            <?php
                                            // Modified query to include price range filter if applicable
                                            $sql_laptop_query = "SELECT * FROM data_laptop";
                                            $params = [];
                                            if ($min_harga_filter !== null && $max_harga_filter !== null) {
                                                // Assuming 'harga_aktual' is the column for actual price
                                                $sql_laptop_query .= " WHERE harga_angka BETWEEN ? AND ?";
                                                $params = [$min_harga_filter, $max_harga_filter];
                                            }
                                            $sql_laptop_query .= " ORDER BY id_laptop ASC"; // Ensure consistent order for $nilaiV indexing

                                            // Using PDO for consistency and prepared statements
                                            $stmt_laptop = $db->prepare($sql_laptop_query);
                                            $stmt_laptop->execute($params);
                                            $query_result = $stmt_laptop->fetchAll(PDO::FETCH_ASSOC);

                                            $no = 1;
                                            $Matrik = array();
                                            foreach ($query_result as $data_laptop) {
                                                $Matrik[$no-1]=array(
                                                    $data_laptop['harga_angka'],
                                                    $data_laptop['processor_angka'],
                                                    $data_laptop['ram_angka'],
                                                    $data_laptop['vga_angka'],
                                                    $data_laptop['memori_angka'],
                                                    $data_laptop['lcd_angka']
                                                );
                                                ?>
                                            <tr>
                                                <td>
                                                    <center><?php echo "A".$no ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data_laptop['harga_angka'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data_laptop['processor_angka'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data_laptop['ram_angka'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data_laptop['vga_angka'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data_laptop['memori_angka'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data_laptop['lcd_angka'] ?></center>
                                                </td>
                                            </tr>
                                            <?php
                                                $no++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <center>
                    <h4 class="header" style="margin-left: 24px; margin-bottom: 0px; margin-top: 24px; color: #635c73;">
                        BOBOT TERNORMALISASI (W)</h4>
                </center>
                <ul>
                    <li>
                        <div class="row">
                            <div class="card">
                                <div class="card-content">
                                    <h5 style="margin-bottom: 16px; margin-top: -6px;">BOBOT TERNORMALISASI (W)</h5>
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center>W1 (Harga)</center>
                                                </th>
                                                <th>
                                                    <center>W2 (Processor)</center>
                                                </th>
                                                <th>
                                                    <center>W3 (RAM)</center>
                                                </th>
                                                <th>
                                                    <center>W4 (VGA)</center>
                                                </th>
                                                <th>
                                                    <center>W5 (Memori)</center>
                                                </th>
                                                <th>
                                                    <center>W6 (LCD)</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <center><?php echo round($W1_norm, 4);?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo round($W2_norm, 4);?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo round($W3_norm, 4);?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo round($W4_norm, 4);?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo round($W5_norm, 4);?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo round($W6_norm, 4);?></center>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>


                <center>
                    <h4 class="header" style="margin-left: 24px; margin-bottom: 0px; margin-top: 24px; color: #635c73;">
                        Nilai Preferensi S:</h4>
                </center>
                <ul>
                    <li>
                        <div class="row">
                            <div class="card">
                                <div class="card-content">
                                    <h5 style="margin-bottom: 16px; margin-top: -6px;">Nilai Preferensi "S" (Weighted
                                        Product)</h5>
                                    <table class="responsive-table">

                                        <thead style="border-top: 1px solid #d0d0d0;">
                                            <tr>
                                                <th>
                                                    <center>Alternatif</center>
                                                </th>
                                                <th>
                                                    <center>Nilai S</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
												$query=mysqli_query($selectdb,"SELECT * FROM data_laptop");
												$no=1;
												$nilaiS = array();
												$total_S = 0;

												while ($data_laptop=mysqli_fetch_array($query)) {
													// Bilai Cost di Invers kan
													$pangkat_W1 = -$W1_norm; // Untuk Cost
													$pangkat_W2 = $W2_norm;   // Untuk Benefit (Processor)
													$pangkat_W3 = $W3_norm;   // Untuk Benefit (RAM)
													$pangkat_W4 = $W4_norm;   // Untuk Benefit (VGA)
													$pangkat_W5 = $W5_norm;   // Untuk Benefit (Memori)
													$pangkat_W6 = $W6_norm;   // Untuk Benefit (LCD)

													// Menghitung nilai S untuk setiap alternatif
													$harga_val = ($data_laptop['harga_angka'] != 0) ? $data_laptop['harga_angka'] : 0.000001; // Hindari 0

													$S_alternatif = pow($harga_val, $pangkat_W1) *
																	pow($data_laptop['processor_angka'], $pangkat_W2) *
																	pow($data_laptop['ram_angka'], $pangkat_W3) *
																	pow($data_laptop['vga_angka'], $pangkat_W4) *
																	pow($data_laptop['memori_angka'], $pangkat_W5) *
																	pow($data_laptop['lcd_angka'], $pangkat_W6);

													$nilaiS[$no-1] = round($S_alternatif, 6);
													$total_S += $S_alternatif;

													?>
                                            <tr>
                                                <td>
                                                    <center><?php echo "A",$no ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $nilaiS[$no-1] ?></center>
                                                </td>
                                            </tr>
                                            <?php
													$no++;
												}
												?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>


                <center>
                    <h4 class="header" style="margin-left: 24px; margin-bottom: 0px; margin-top: 24px; color: #635c73;">
                        Nilai Preferensi Akhir (V)
                    </h4>
                </center>
                <ul>
                    <li>
                        <div class="row">
                            <div class="card" style="margin-left: 320px;margin-right: 320px;">
                                <div class="card-content">
                                    <table class="responsive-table">

                                        <thead style="border-top: 1px solid #d0d0d0;">
                                            <tr>
                                                <th>
                                                    <center>Nilai Preferensi "V"</center>
                                                </th>
                                                <th>
                                                    <center>Nilai</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
													$query=mysqli_query($selectdb,"SELECT * FROM data_laptop"); // Query ulang untuk display
													$no=1;
													$nilaiV = array();
													foreach ($nilaiS as $key => $s_val) {
														$V_alternatif = ($total_S != 0) ? $s_val / $total_S : 0;
														array_push($nilaiV, $V_alternatif);
														?>
                                            <tr>
                                                <td>
                                                    <center><?php echo "V",($key+1) ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo round($V_alternatif, 6); ?></center>
                                                </td>
                                            </tr>
                                            <?php
											$no++; }
											?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <center>
                    <h4 class="header" style="margin-left: 24px; margin-bottom: 0px; margin-top: 24px; color: #635c73;">
                        Rekomendasi Terbaik Tiap Merk
                    </h4>
                </center>
                <ul>
                    <li>
                        <div class="row">
                            <div class="card" style="margin-left: 200px;margin-right: 200px;">
                                <div class="card-content">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center>Merk</center>
                                                </th>
                                                <th>
                                                    <center>Nama Laptop</center>
                                                </th>
                                                <th>
                                                    <center>Nilai Preferensi V</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    $query = mysqli_query($selectdb, "SELECT * FROM data_laptop ORDER BY id_laptop ASC");
                                                    $no = 0;
                                                    $merk_terbaik = array(); 

                                                    while ($data_laptop = mysqli_fetch_array($query)) {
                                                        $merk = $data_laptop['merk'];
                                                        $nilai_v = $nilaiV[$no];

                                                        // Jika merk belum ada, atau nilai V lebih besar, simpan
                                                        if (!isset($merk_terbaik[$merk]) || $nilai_v > $merk_terbaik[$merk]['V']) {
                                                            $merk_terbaik[$merk] = [
                                                                'V' => $nilai_v,
                                                                'data' => $data_laptop
                                                            ];
                                                        }
                                                        $no++;
                                                    }
                                                        ?> <?php foreach ($merk_terbaik as $merk => $info) { ?>
                                            <tr>
                                                <td>
                                                    <center><?php echo htmlspecialchars($merk); ?></center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo htmlspecialchars($info['data']['nama_laptop']); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><?php echo round($info['V'], 6); ?></center>
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

                <center>
                    <h4 class="header" style="margin-left: 24px; margin-bottom: 0px; margin-top: 24px; color: #635c73;">
                        Nilai Preferensi tertinggi
                    </h4>
                </center>
                <ul>
                    <li>
                        <div class="row">
                            <div class="card" style="margin-left: 300px;margin-right: 300px;">
                                <div class="card-content">
                                    <table class="responsive-table">

                                        <thead style="border-top: 1px solid #d0d0d0;">
                                            <tr>
                                                <th>
                                                    <center>Nilai Preferensi tertinggi</center>
                                                </th>
                                                <th>
                                                    <center>Nilai V</center>    
                                                <th>
                                                    <center>Rekomendasi Laptop terpilih</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php
														$testmax = -INF; 
														if (!empty($nilaiV)) {
														    $testmax = max($nilaiV);
														}

														$index_terpilih = -1;
														for ($i=0; $i < count($nilaiV); $i++) {
															if ($nilaiV[$i] == $testmax) {
																$index_terpilih = $i;
																break; 
															}
														}

														if ($index_terpilih != -1) {
															$query=mysqli_query($selectdb,"SELECT * FROM data_laptop where id_laptop = ".($index_terpilih+1));
															$data_laptop_terpilih = mysqli_fetch_array($query);
														?>
                                                <td>
                                                    <center><?php echo "V".($index_terpilih+1); ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo round($nilaiV[$index_terpilih], 6); ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data_laptop_terpilih['nama_laptop']; ?></center>
                                                </td>
                                                <?php
														} else {
															echo "<td colspan='3'><center>Tidak ada rekomendasi</center></td>";
														}
														?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="row center" \>
                    <a href="rekomendasi.php" id="download-button" class="waves-effect waves-light btn"
                        style="margin-top: 0px">Hitung Rekomendasi Ulang</a>
                </div>
            </div>
        </div>
    </div>
</body>

<?php include 'footer.php'; ?>