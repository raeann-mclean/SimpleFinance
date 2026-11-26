<?php
// Run against a local test database after start.php. Never runs start.php itself.
require_once dirname(__DIR__) . '/php/config.php';
require_once dirname(__DIR__) . '/php/account_service.php';

function ensure(bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
}
function registrationCounts(mysqli $db): array {
    $counts = [];
    foreach (['Contacts', 'Users', 'Credentials'] as $table) {
        $counts[$table] = (int) $db->query("SELECT COUNT(*) FROM $table")->fetch_row()[0];
    }
    return $counts;
}

$before = registrationCounts($db_connection);
$username = 'transaction_test_' . bin2hex(random_bytes(6));
$account = ['first_name' => 'Rollback', 'last_name' => 'Test',
    'email' => 'rollback@example.com', 'phone' => '555-0100',
    'street' => '', 'street_additional' => '', 'city' => '', 'state' => '', 'zip' => null,
    'username' => $username, 'password' => 'Transaction-test-password'];
$userId = null;
$contactId = null;
try {
    $userId = createAccount($db_connection, $account);
    $row = $db_connection->query('SELECT contact_id FROM Users WHERE user_id = ' . (int) $userId)->fetch_assoc();
    $contactId = (int) $row['contact_id'];
    $afterSuccess = registrationCounts($db_connection);
    foreach ($before as $table => $count) {
        ensure($afterSuccess[$table] === $count + 1, "Successful commit missing from $table");
    }
    echo "PASS: successful registration commits all three rows\n";

    // The duplicate username fails on the THIRD INSERT, after new Contacts
    // and Users rows have already been inserted inside the transaction.
    $duplicateFailed = false;
    try { createAccount($db_connection, $account); }
    catch (mysqli_sql_exception $error) {
        ensure($error->getCode() === 1062, 'Expected a duplicate username error');
        $duplicateFailed = true;
    }
    ensure($duplicateFailed, 'Duplicate registration unexpectedly succeeded');
    ensure(registrationCounts($db_connection) === $afterSuccess, 'Rollback left partial rows behind');
    echo "PASS: failed third INSERT rolls back Contacts and Users\n";

    $row = $db_connection->query('SELECT password_salted FROM Credentials WHERE user_id = ' . (int) $userId)->fetch_assoc();
    ensure(password_verify($account['password'], $row['password_salted']), 'Password hash does not verify');
    echo "PASS: new account password verifies\n";
} finally {
    if ($userId !== null) {
        $db_connection->query('DELETE FROM Credentials WHERE user_id = ' . (int) $userId);
        $db_connection->query('DELETE FROM Users WHERE user_id = ' . (int) $userId);
    }
    if ($contactId !== null) $db_connection->query('DELETE FROM Contacts WHERE contact_id = ' . $contactId);
}
ensure(registrationCounts($db_connection) === $before, 'Test cleanup changed existing records');
echo "PASS: test fixture cleaned up\n";
