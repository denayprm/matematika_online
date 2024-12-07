<?php
require_once '../includes/auth_middleware.php';
checkAdminAuth(); // Pastikan hanya siswa yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Admin</title>
    <!--<link rel="icon" type="image/x-icon" href="matematika_online/assets/img/denay.png">-->
</head>

<body>
    <h1>Ini Profile Admin</h1>
</body>

</html>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>