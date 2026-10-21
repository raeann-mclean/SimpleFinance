<?php
/**
 * Settings Page
 *
 * @author    James Montgomery
 * @copyright 2026
 * @version   1.0
 */

  /* Quick Paths */
  include_once (realpath(dirname(__FILE__, 2).'/php/path.php'));

  /* Start The Session */
  session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <?php include_once TEMPLATE_PATH . "/head.php"; ?>
  </head>
  <body class="h-100">
    <?php include_once TEMPLATE_PATH . "/header.php"; ?>

    <?php $active_page = 'settings'; ?>
    <div class="d-flex vh-100 m-0" style="margin-top: 56px;">
      <?php include_once TEMPLATE_PATH . "/sidebar.php"; ?>

      <!-- main -->
      <main role="main" class="flex-grow-1 p-4">
        <h1>Settings</h1>
        <p>Manage your account settings and preferences.</p>

        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-person me-2"></i>Profile Settings</h5>
          </div>
          <div class="card-body">
            <form>
              <div class="mb-3">
                <label for="displayName" class="form-label">Display Name</label>
                <input type="text" class="form-control" id="displayName" placeholder="Your name">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" placeholder="you@example.com">
              </div>
              <button type="button" class="btn btn-primary" style="background-color: #050359; border-color: #050359;">Save Changes</button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Notification Preferences</h5>
          </div>
          <div class="card-body">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="emailNotif" checked>
              <label class="form-check-label" for="emailNotif">Email Notifications</label>
            </div>
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="budgetAlerts" checked>
              <label class="form-check-label" for="budgetAlerts">Budget Alerts</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="monthlyReport">
              <label class="form-check-label" for="monthlyReport">Monthly Report Summary</label>
            </div>
          </div>
        </div>
      </main>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
