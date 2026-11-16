<?php

/**
 * Check User Role
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026 
 * @date      2026-02-24
 * @version   1.0
 */

// Check if user role is 2 (standard user).
// Include after php/session.php, which sets $user_role.

if ($user_role != 2) {
  // Access Denied
  $_SESSION['message'] = "Access Denied";
  // Redirect to logout, and stop here so the protected page is never sent
  header("location: " . SRC_PATH . "/logout.php");
  exit();
}
