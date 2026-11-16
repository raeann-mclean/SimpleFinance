<?php

/**
 * Check Admin Role
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026 
 * @date      2026-02-24
 * @version   1.0
 */

// Check if user role is 1 (admin user).
// Include after php/session.php, which sets $user_role.

if ($user_role != 1) {
  // Access Denied
  $_SESSION['message'] = "Access Denied";
  // Redirect to logout, and stop here so the protected page is never sent
  header("location: " . SRC_PATH . "/logout.php");
  exit();
}
