<?php 
include 'header.php';
include 'koneksi.php';
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
            <div class="section-card" style="padding: 5px 0px">
                <div class="row" style="margin-top:20px;">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title" style="color: #635c73; text-align: center; font-weight: bold;">
                                REKOMENDASI LAPTOP</span>
                            <p style=" text-align: center;">Silakan pilih spenggunaan yang sesuai, atau atur bobot
                                secara manual untuk mendapatkan rekomendasi laptop yang sesuai.</p>
                        </div>
                    </div>
                </div>
                <div class="row" style="display: flex; align-items: stretch;">
                    <div class="col s12">
                        <div class="card" style="margin-bottom: 10px;">
                            <div class="card-content">
                                <div class="row">
                                    <!-- <div class="col s12 m6"
                                        style="display: flex; flex-direction: column; justify-content: center;">
                                        <center>
                                            <h5 style="margin-bottom: 38px; fontt-weight: bold;">Pilih Penggunaan</h5>
                                        </center>
                                        <form action="hasil.php" method="post">
                                            <p style="margin-bottom:20px;">
                                                <input name="preset" type="radio" id="preset_gaming" value="gaming"
                                                    checked />
                                                <label for="preset_gaming">Untuk Gaming (Prioritas: Grafis VGA dan
                                                    RAM)</label>
                                            </p>
                                            <p style="margin-bottom:20px;">
                                                <input name="preset" type="radio" id="preset_kantor" value="kantor" />
                                                <label for="preset_kantor">Untuk Produktivitas Kantor
                                                    (Prioritas:Procesor dan RAM)</label>
                                            </p>
                                            <p style="margin-bottom:15px;">
                                                <input name="preset" type="radio" id="preset_desain" value="desain" />
                                                <label for="preset_desain">Untuk Desain Grafis / Video Editing
                                                    (Prioritas: VGA, RAM dan LCD)</label>
                                            </p>
                                            <p style="margin-bottom:30px;">
                                                <input name="preset" type="radio" id="preset_pelajar" value="pelajar" />
                                                <label for="preset_pelajar">Untuk Pelajar (Prioritas: Harga)</label>
                                            </p>
                                            <p style="margin-bottom:30px;">
                                                <input name="preset" type="radio" id="preset_umum" value="umum" />
                                                <label for="preset_umum">Umum / Seimbang (Prioritas: Seimbang)</label>
                                            </p>
                                            <div class="center-align" style="margin-top:24px; margin-bottom: 20px">
                                                <button class="btn waves-effect waves-light blue" type="submit"
                                                    name="action" style="border-radius:15px; padding:0 15px;">
                                                    Lihat Rekomendasi
                                                    <i class="material-icons right">send</i>
                                                </button>
                                            </div>
                                        </form>
                                    </div> -->
                                    <div class="col s12 m6"
                                        style="display: flex; flex-direction: column; justify-content: center;">
                                        <center>
                                            <h5 style="margin-bottom: 38px; font-weight: bold;">Pilih Penggunaan</h5>
                                        </center>
                                        <form action="hasil.php" method="post">
                                            <?php
                                                $preset_q = $db->query("SELECT * FROM preset");
                                                $first = true;
                                                while($p = $preset_q->fetch(PDO::FETCH_ASSOC)) {
                                                    $id_radio = "preset_" . strtolower(preg_replace('/\s+/', '_', $p['nama']));
                                                    ?>
                                            <p style="margin-bottom:20px;">
                                                <input name="preset" type="radio" id="<?= $id_radio ?>"
                                                    value="<?= htmlspecialchars($p['nama']) ?>"
                                                    <?= $first ? 'checked' : '' ?> />
                                                <label style="font-weight: Bold; text-size: 16px;"
                                                    for="<?= $id_radio ?>"> Untuk Penggunaan <?= ucfirst(htmlspecialchars($p['nama'])) ?></label>
                                            </p>
                                            <?php
                                                    $first = false;
                                                }
                                                ?>
                                            <div class="center-align" style="margin-top:24px; margin-bottom: 20px">
                                                <button class="btn waves-effect waves-light blue" type="submit"
                                                    name="action" style="border-radius:15px; padding:0 15px;">
                                                    Lihat Rekomendasi
                                                    <i class="material-icons right">send</i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col s12 m6"
                                        style="display: flex; flex-direction: column; justify-content: center;">
                                        <center>
                                            <h5 style="margin-bottom:20px; ">Atur Bobot Manual</h5>
                                        </center>
                                        <form action="hasil.php" method="post">
                                            <div class="row" style="margin-bottom:10px;">
                                                <div class="col s12 "
                                                    style="display: flex; align-items: center; margin-bottom: 0px;">
                                                    <label for="harga_manual" style="flex:1; margin-left:20px;">Harga
                                                    </label>
                                                    <div class="col s6">
                                                        <select required name="harga_manual" id="harga_manual"
                                                            style="width:220px; margin-left:10px;">
                                                            <option value="" disabled selected>Kriteria Harga</option>
                                                            <option value="5">&lt; Rp. 5.000.000</option>
                                                            <option value="4">7.000.000 - 10.000.000</option>
                                                            <option value="3">10.000.000 - 15.000.000</option>
                                                            <option value="2">15.000.000 - 20.000.000</option>
                                                            <option value="1">&gt; 20.000.000</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col s12"
                                                    style="display: flex; align-items: center; margin-bottom: 0px;">
                                                    <label for="processor_manual"
                                                        style="flex:1; margin-left:20px;">Processor
                                                    </label>
                                                    <div class="col s6">
                                                        <select required name="processor_manual" id="processor_manual"
                                                            style="width:220px; margin-left:12px;">
                                                            <option value="" disabled selected>Kriteria Processor
                                                            </option>
                                                            <option value="1">Intel Celeron </option>
                                                            <option value="2">AMD Gen 3-7 seri U </option>
                                                            <option value="3">Intel gen 5-12 Seri U </option>
                                                            <option value="4">Intel Core Ultra</option>
                                                            <option value="5"> Intel/AMD Seri H</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col s12"
                                                    style="display: flex; align-items: center; margin-bottom: 0px;">
                                                    <label for="ram_manual" style="flex:1; margin-left:20px;">RAM
                                                    </label>
                                                    <div class="col s6">
                                                        <select required name="ram_manual" id="ram_manual"
                                                            style="width:220px; margin-left:12px;">
                                                            <option value="" disabled selected>Kriteria RAM</option>
                                                            <option value="1"> 4 Gb</option>
                                                            <option value="2"> 8 Gb</option>
                                                            <option value="3"> 16 Gb</option>
                                                            <option value="4"> 32 Gb</option>
                                                            <option value="5"> 64 Gb</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col s12"
                                                    style="display: flex; align-items: center; margin-bottom: 0px;">
                                                    <label for="vga_manual" style="flex:1; margin-left:20px;">Grafis VGA
                                                    </label>
                                                    <div class="col s6">
                                                        <select required name="vga_manual" id="vga_manual"
                                                            style="width:220px; margin-left:12px;">
                                                            <option value="" disabled selected>Kriteria VGA</option>
                                                            <option value="1"> UHD/Radeon</option>
                                                            <option value="2"> Intel iris Xe </option>
                                                            <option value="3"> RTX 1050/2050</option>
                                                            <option value="4"> Intel Arc Graphic</option>
                                                            <option value="5"> RTX 3050/4050</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col s12"
                                                    style="display: flex; align-items: center; margin-bottom: 0px;">
                                                    <label for="memori_manual" style="flex:1; margin-left:20px;">Memory
                                                    </label>
                                                    <div class="col s6">
                                                        <select required name="memori_manual" id="memori_manual"
                                                            style="width:220px; margin-left:12px;">
                                                            <option value="" disabled selected>Kriteria Memory</option>
                                                            <option value="1"> 128 Gb</option>
                                                            <option value="2"> 256 Gb</option>
                                                            <option value="3"> 512 Gb</option>
                                                            <option value="4"> 1 TB</option>
                                                            <option value="5"> 2 TB</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col s12"
                                                    style="display: flex; align-items: center; margin-bottom: 0px;">
                                                    <label for="lcd_manual" style="flex:1; margin-left:20px;">LCD
                                                    </label>
                                                    <div class="col s6">
                                                        <select required name="lcd_manual" id="lcd_manual"
                                                            style="width:220px; margin-left:12px;">
                                                            <option value="" disabled selected>Kriteria LCD</option>
                                                            <option value="1">13 Inch </option>
                                                            <option value="2">14 Inch </option>
                                                            <option value="3">15 Inch </option>
                                                            <option value="4">16 Inch </option>
                                                            <option value="5">17 Inch </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="center-align" style="margin-top:15px;">
                                                <button class="btn waves-effect waves-light orange" type="submit"
                                                    name="preset_manual"
                                                    style="border-radius:24px; padding:0 15px; color: #000; font-weight: bold;">
                                                    Lihat Rekomendasi
                                                    <i class="material-icons right">send</i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        $('select').material_select();
    });
    </script>
</body>

<?php include 'footer.php'; ?>