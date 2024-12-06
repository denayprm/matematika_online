<?php
require_once __DIR__ . '/../config/init.php';

function run_test($name, $callback)
{
    try {
        $callback();
        echo "✅ {$name} passed\n";
    } catch (Exception $e) {
        echo "❌ {$name} failed: {$e->getMessage()}\n";
    }
}

// Test database connection
run_test('Database Connection', function () {
    global $conn;
    $stmt = $conn->query("SELECT 1");
    $result = $stmt->fetch();
    if (!$result) {
        throw new Exception("Database connection failed");
    }
});

// Test user authentication
run_test('User Authentication', function () {
    $email = 'test@example.com';
    $password = 'password123';

    // Create test user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO users (email, password, full_name, role)
        VALUES (?, ?, 'Test User', 'siswa')
    ");
    $stmt->execute([$email, $hashed_password]);

    // Test login
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!password_verify($password, $user['password'])) {
        throw new Exception("Password verification failed");
    }

    // Clean up
    $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
    $stmt->execute([$email]);
});

// Test materi CRUD
run_test('Materi CRUD', function () {
    global $conn;

    // Create
    $stmt = $conn->prepare("
        INSERT INTO materi (judul, konten, created_by)
        VALUES ('Test Materi', 'Test Content', 1)
    ");
    $stmt->execute();
    $materi_id = $conn->lastInsertId();

    // Read
    $stmt = $conn->prepare("SELECT * FROM materi WHERE materi_id = ?");
    $stmt->execute([$materi_id]);
    $materi = $stmt->fetch();

    if ($materi['judul'] !== 'Test Materi') {
        throw new Exception("Materi creation failed");
    }

    // Update
    $stmt = $conn->prepare("
        UPDATE materi SET judul = 'Updated Materi' WHERE materi_id = ?
    ");
    $stmt->execute([$materi_id]);

    // Verify update
    $stmt = $conn->prepare("SELECT judul FROM materi WHERE materi_id = ?");
    $stmt->execute([$materi_id]);
    $updated = $stmt->fetch();

    if ($updated['judul'] !== 'Updated Materi') {
        throw new Exception("Materi update failed");
    }

    // Delete
    $stmt = $conn->prepare("DELETE FROM materi WHERE materi_id = ?");
    $stmt->execute([$materi_id]);
});

// Test file upload
run_test('File Upload', function () {
    if (!is_dir('../uploads')) {
        throw new Exception("Upload directory doesn't exist");
    }

    if (!is_writable('../uploads')) {
        throw new Exception("Upload directory is not writable");
    }
});

// Test session handling
run_test('Session Handling', function () {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        throw new Exception("Session is not active");
    }
});
