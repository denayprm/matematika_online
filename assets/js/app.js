if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/matematika_online/');
}
document.addEventListener('DOMContentLoaded', function () {
    console.log('Matematika Online app loaded');
});

function confirmLogout() {
    if (confirm("Apakah Anda yakin ingin keluar?")) {
        window.location.href = "<?= BASE_URL ?>auth/logout.php";
    }
}