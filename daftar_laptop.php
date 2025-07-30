<?php 
include('koneksi.php');

?>

<?php include('header.php'); // Asumsikan Anda memiliki file header.php ?>

<body>
    <div class="navbar-fixed">
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <ul class="left" style="margin-left: -52px;">
                        <li><a href="index.php">HOME</a></li>
                        <li><a class="active" href="daftar_laptop.php">DAFTAR LAPTOP</a></li>
                        <li><a href="login.php">LOGIN</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div style="background-color: #efefef">
        <div class="container">
            <div class="section-card" style="padding: 40px 0px 20px 0px;">
                <ul>
                    <li>
                        <div class="row">
                            <div class="card">
                                <div class="card-content">
                                    <center>
                                        <h4 style="margin-bottom: 20px; margin-top: -8px;">DAFTAR LAPTOP</h4>
                                    </center>


                                    <table id="table_id" class="hover dataTablesCustom" style="width:100%">
                                        <thead style="border-top: 1px solid #d0d0d0;">
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
                                                    <center>Ukuran LCD</center>
                                                </th>
             
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                // Mengganti `data_hp` menjadi `data_laptop`
												$query=mysqli_query($selectdb,"SELECT * FROM data_laptop");
												$no=1;
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
                                                    <center><?php echo $data['ram_angka'],' GB' ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['vga_teks'] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['memori_angka'], ' GB' ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data['lcd_angka'], ' inch' ?></center>
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
    <?php include 'footer.php'; // Asumsikan Anda memiliki file footer.php ?>

    <script type="text/javascript">
    $(document).ready(function() {
        $('.modal').modal();
        $('select').formSelect(); // Inisialisasi select Materialize
        $('#table_id').DataTable({
            "paging": true,
            "ordering": true,
            "info": true
        });
    });
    </script>
</body>

</html>