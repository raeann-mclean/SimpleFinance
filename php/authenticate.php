<?php
require_once __DIR__ . '/path.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/');
    exit();
}
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
if (!is_string($username) || !is_string($password) || trim($username) === '' || $password === '') {
    $_SESSION['error'] = 'Please enter your username and password.';
    header('Location: ' . BASE_URL . '/');
    exit();
}
$username = trim($username);
try {
    require_once __DIR__ . '/config.php';
    $statement = $db_connection->prepare('SELECT u.user_id, u.role_id, cr.username, cr.password_salted
        FROM Users u JOIN Credentials cr ON u.user_id = cr.user_id WHERE cr.username = ? LIMIT 1');
    $statement->bind_param('s', $username);
    $statement->execute();
    $user = $statement->get_result()->fetch_assoc();
    $statement->close();
    // Support both the existing seeded credentials and new password_hash values.
    $valid = $user && (password_verify($password, $user['password_salted']) ||
        hash_equals($user['password_salted'], crypt($password, SALT)));
    if ($valid && in_array((int) $user['role_id'], [1, 2, 3], true)) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['user_id'];
        $_SESSION['login_user'] = $user['username'];
        $_SESSION['user_role'] = (int) $user['role_id'];
        $destinations = [1 => 'admin', 2 => 'user', 3 => 'guest'];
        header('Location: ' . BASE_URL . '/dashboards/' . $destinations[$_SESSION['user_role']] . '/');
        exit();
    }
    $_SESSION['error'] = 'Username or password did not match.';
} catch (Throwable $error) {
    error_log('Login failed: ' . $error->getMessage());
    $_SESSION['error'] = 'Login is temporarily unavailable. Please try again.';
}
header('Location: ' . BASE_URL . '/');
exit();
