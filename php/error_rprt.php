<?php
//======================================================================
// ERROR AND MESSAGE REPORTS
// Session messages are shown once, then cleared.
//======================================================================

  /* Error Report */
  if (isset($error)) {
    // uses bootstrap alert style for error messages
    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
  }
  if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
  }
  /* Message Report */
  if (isset($message)) {
    // uses bootstrap alert style for error messages
    echo '<div class="alert alert-warning" role="warning">' . $message . '</div>';
  }
  if (isset($_SESSION['message'])) {
    // uses bootstrap alert style for error messages
    echo '<div class="alert alert-warning" role="warning">' . htmlspecialchars($_SESSION['message']) . '</div>';
    unset($_SESSION['message']);
  }
  /* Success Report (e.g. "Transaction added.") */
  if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
  }
?>
