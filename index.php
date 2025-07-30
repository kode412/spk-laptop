<?php
include 'header.php';
?>

<body>
    <div class="navbar-fixed">
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <!-- <a href="#" data-target="mobile-nav" class="sidenav-trigger"><i class="material-icons">menu</i></a> -->
                       <ul class="left" style="margin-left: -52px;">
                        <li><a href="index.php">HOME</a></li>
                        <li><a href="daftar_laptop.php">DAFTAR LAPTOP</a></li>
                        <li><a href="login.php">LOGIN</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Mobile Side Navigation -->
    <!-- <ul class="sidenav" id="mobile-nav">
        <li><a href="index.php">HOME</a></li>
        <li><a href="daftar_laptop.php">DAFTAR LAPTOP</a></li>
        <li><a href="login.php">LOGIN</a></li>
    </ul> -->

    <!-- Jumbotron Start -->
    <div id="index-banner" class="parallax-container">
        <div class="section no-pad-bot">
            <div class="container">
                <h1 class="header jarak center white-text" style="font-size: 2rem; line-height: 1.2; padding: 0 10px;">
                    SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN LAPTOP MENGGUNAKAN METODE WEIGHT PRODUCT (WP)
                </h1>
                <div class="row center">
                    <a href="rekomendasi.php" id="download-button" class="waves-effect waves-light btn-large blue darken-1"
                        style="border-radius:20px; width: 80%; max-width: 230px; padding:0 15px; margin-top: 40px; color: #fff; font-weight: bold;">
                        Pilih Rekomendasi
                    </a>
                </div>
            </div>
        </div>
        <div class="parallax"><img src="assets/image/laptop.png" alt="Laptop"></div>
    </div>
    <!-- Jumbotron End -->

    <!-- Info Start -->
    <div style="background-color: white">
        <div class="container">
            <div class="section-card" style="padding: 20px 0">
                <div class="row">
                    <div class="col s12 m6">
                        <center>
                            <h5 class="header" style="margin-bottom: 15px; margin-top: 15px; color: #635c73">
                                INFO SISTEM
                            </h5>
                        </center>
                        <p style="text-align: justify; padding: 0 16px;">
                            Sistem Pendukung Keputusan Pemilihan Laptop Menggunakan
                            Metode WEight Product,Tech yang digunakan adalah PHP, HTML dan CSS. Sistem ini di gunakan
                            untuk Pemenuhan
                            Tugas Akhir Pada Universitas Muhammadiyah Ponorogo.
                        </p>
                    </div>
                    <div class="col s12 m6">
                        <center>
                            <h5 class="header" style="margin-bottom: 15px; margin-top: 15px; color: #635c73">
                                INFO METODE
                            </h5>
                        </center>
                        <p style="text-align: justify; padding: 0 16px;">
                            Metode yang digunakan adalah metode Weight
                            Product (WP) Metode ini Memanfaatkan Kriteria setiap Laptop yang sudah diberikan Bobot.
                            Kemudian Metode
                            ini akan menghitung nilai dari setiap kriteria dan menghitung nilai dari setiap laptop.
                            Nilai dari setiap laptop
                            akan diurutkan Hasil tertinggi merupakan Rekomendasi Utama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize mobile sidenav
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.sidenav');
            var instances = M.Sidenav.init(elems);
        });
    </script>
</body>

<?php
    include 'footer.php';
?>