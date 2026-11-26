<?php
require_once __DIR__ . '/path.php';
session_start();
function registrationError(string $message): void {
    $_SESSION['error'] = $message;
    header('Location: ' . BASE_URL . '/pages/create_account.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/pages/create_account.php');
    exit();
}
$account = [];
foreach (['first_name', 'last_name', 'email', 'phone', 'username', 'password',
    'street', 'street_additional', 'city', 'state', 'zip'] as $field) {
    $value = $_POST[$field] ?? '';
    if (!is_string($value) || strlen($value) > 255) {
        registrationError('Please enter valid account details, at most 255 characters per field.');
    }
    $account[$field] = $field === 'password' ? $value : trim($value);
}
foreach (['first_name', 'last_name', 'email', 'phone', 'username', 'password'] as $required) {
    if ($account[$required] === '') registrationError('Please complete all required fields.');
}
if (!filter_var($account['email'], FILTER_VALIDATE_EMAIL)) {
    registrationError('Please enter a valid email address.');
}
if ($account['zip'] !== '' && !preg_match('/^\d{5}$/D', $account['zip'])) {
    registrationError('Please enter a five-digit ZIP code.');
}
$account['zip'] = $account['zip'] === '' ? null : $account['zip'];
try {
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/account_service.php';
    createAccount($db_connection, $account);
    $_SESSION['message'] = 'Account created successfully. You can now log in.';
    header('Location: ' . BASE_URL . '/');
} catch (mysqli_sql_exception $error) {
    if ($error->getCode() === 1062) registrationError('That username is already taken. Please choose another.');
    error_log('Registration failed: ' . $error->getMessage());
    registrationError('We could not create your account. No account details were saved. Please try again.');
} catch (Throwable $error) {
    error_log('Registration failed: ' . $error->getMessage());
    registrationError('We could not create your account. Please try again.');
}
exit();
