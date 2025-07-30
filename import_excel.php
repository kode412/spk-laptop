<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

    session_start();
    include('koneksi.php');

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
?>