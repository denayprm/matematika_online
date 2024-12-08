<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once 'header.php';
?>

<?php
// Data materi
$materi = [
    [
        'Pengenalan Aljabar',
        'Aljabar adalah cabang matematika yang mempelajari simbol-simbol dan aturan-aturan untuk memanipulasinya. Dalam aljabar, simbol-simbol digunakan untuk mewakili angka dan operasi matematika, sehingga memungkinkan penyelesaian masalah yang lebih umum dan abstrak. Konsep-konsep dasar aljabar mencakup bilangan variabel, konstanta, operasi penjumlahan, pengurangan, perkalian, pembagian, serta pemfaktoran. Aljabar menjadi landasan bagi banyak bidang matematika lainnya, seperti persamaan, fungsi, dan analisis data.',
    ],
    [
        'Geometri Dasar',
        'Geometri adalah cabang matematika yang berfokus pada studi tentang bentuk, ukuran, posisi relatif, dan sifat ruang. Geometri dasar mencakup konsep-konsep seperti titik, garis, sudut, segitiga, persegi, lingkaran, dan berbagai bentuk dua dimensi lainnya. Selain itu, konsep-konsep seperti luas, keliling, volume, dan teorema penting seperti Teorema Pythagoras sering digunakan untuk menganalisis hubungan antara elemen-elemen geometris. Geometri juga membantu memahami dunia fisik melalui representasi visual.',
    ],
    [
        'Statistika Dasar',
        'Statistika adalah ilmu yang mempelajari cara mengumpulkan, menganalisis, menyajikan, dan menginterpretasikan data. Dalam statistika dasar, konsep-konsep seperti data tunggal dan kelompok, rata-rata (mean), median, modus, penyebaran data (simpangan baku), dan distribusi sering digunakan. Statistika membantu dalam pengambilan keputusan berdasarkan data dan memiliki aplikasi luas dalam berbagai bidang, termasuk ekonomi, kesehatan, dan penelitian ilmiah.',
    ]
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi Pembelajaran</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="/matematika_online/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <h1 align="center">Materi Pembelajaran</h1>
    <div class="container">
        <?php foreach ($materi as $item): ?>
            <div class="materi-card">
                <h2><?php echo $item[0]; ?></h2>
                <div class="card-text" align="justify">
                    <p><?php echo $item[1]; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>

<?php
require_once 'footer.php';
?>