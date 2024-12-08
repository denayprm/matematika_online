if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/matematika_online/');
}
document.addEventListener('DOMContentLoaded', function () {
    console.log('Matematika Online app loaded');
});

function confirmLogout() {
    if (confirm("Apakah Anda yakin ingin keluar?")) {
        const logoutUrl = "<?= BASE_URL ?>auth/logout.php";
        // Validasi URL
        if (logoutUrl.startsWith("http://localhost/matematika_online/")) {
            window.location.href = logoutUrl;
        } else {
            console.error("URL tidak valid.");
        }
    }
}