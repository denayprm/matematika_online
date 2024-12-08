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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .materi {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
        }

        .materi img {
            width: 100%;
            height: auto;
        }

        .content {
            padding: 15px;
        }

        .content h2 {
            font-size: 1.5em;
            margin: 0;
            color: #333;
        }

        .content p {
            color: #555;
        }

        .content strong {
            color: #000;
        }
    </style>
</head>

<body>
    <h1>Materi Pembelajaran</h1>
    <div class="container">
        <?php foreach ($materi as $item): ?>
            <div class="materi">
                <div class="content">
                    <h2><?php echo $item[1]; ?></h2>
                    <p><?php echo $item[2]; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>