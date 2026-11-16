<?php
//======================================================================
// Settings form handler (dashboards/user/settings.php)
// Updates the logged-in user's name, email and phone in Contacts.
//======================================================================

include_once (realpath(dirname(__FILE__).'/user_page.php'));

$back = USER_URL . '/settings.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("location: " . $back);
  exit();
}
if (!csrf_check()) {
  redirect_with($back, 'error', 'Your session expired. Please try again.');
}

$first = trim($_POST['first_name'] ?? '');
$last  = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

foreach (['First name' => $first, 'Last name' => $last, 'Phone' => $phone] as $label => $value) {
  if ($value === '' || strlen($value) > 255) {
    redirect_with($back, 'error', $label . ' is required (255 characters max).');
  }
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
  redirect_with($back, 'error', 'Please enter a valid email address.');
}

/* Only ever update the contact row that belongs to this user */
$stmt = $db_connection->prepare(
  "UPDATE Contacts c
   JOIN Users u ON u.contact_id = c.contact_id
   SET c.first_name = ?, c.last_name = ?, c.email = ?, c.phone = ?
   WHERE u.user_id = ?");
$stmt->bind_param("ssssi", $first, $last, $email, $phone, $user_id);
$stmt->execute();
$stmt->close();

/* Keep the "Welcome <name>" in the header in sync */
$_SESSION['user_first'] = $first;

redirect_with($back, 'success', 'Your profile was updated.');
