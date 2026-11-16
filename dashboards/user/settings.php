<?php
/**
 * Settings Page
 *
 * @author    James Montgomery (design, from mockups/settings.php)
 * @copyright 2026
 * @version   2.0
 */

  /* Login, role check, database and finance helpers */
  include_once (realpath(dirname(__FILE__, 3).'/php/user_page.php'));

  /* Page Name */
  $page_name = "user";
  $active_page = 'settings';

  $profile = get_profile($db_connection, $user_id);
?>
<!doctype html>
<html lang="en">
  <head>
    <?php include_once TEMPLATE_PATH . "/head.php"; ?>
  </head>
  <body class="<?php echo $page_name; ?>">
    <?php include_once TEMPLATE_PATH . "/header.php"; ?>

    <div class="d-flex min-vh-100 m-0">
      <?php include_once TEMPLATE_PATH . "/sidebar.php"; ?>

      <main role="main" class="flex-grow-1 p-4 pb-5">
        <h1>Settings</h1>
        <p>Manage your account settings and preferences.</p>
        <?php include ROOT_SRC_PATH . '/error_rprt.php'; ?>

        <div class="row g-4">
          <div class="col-lg-7">
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Profile Settings</h5>
              </div>
              <div class="card-body">
                <form method="post" action="<?php echo SRC_PATH; ?>/settings_submit.php">
                  <?php echo csrf_field(); ?>
                  <div class="row">
                    <div class="col-sm-6 mb-3">
                      <label for="first_name" class="form-label">First Name</label>
                      <input type="text" class="form-control" id="first_name" name="first_name" maxlength="255" required value="<?php echo h($profile['first_name']); ?>">
                    </div>
                    <div class="col-sm-6 mb-3">
                      <label for="last_name" class="form-label">Last Name</label>
                      <input type="text" class="form-control" id="last_name" name="last_name" maxlength="255" required value="<?php echo h($profile['last_name']); ?>">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" maxlength="255" required value="<?php echo h($profile['email']); ?>">
                  </div>
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="phone" name="phone" maxlength="255" required value="<?php echo h($profile['phone']); ?>">
                  </div>
                  <button type="submit" class="btn btn-primary" style="background-color: #050359; border-color: #050359;">Save Changes</button>
                </form>
              </div>
            </div>
          </div>

          <div class="col-lg-5">
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Account</h5>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">Username <span><?php echo h($profile['username']); ?></span></li>
                <li class="list-group-item d-flex justify-content-between">Role <span><?php echo h(ucfirst($profile['role_type'])); ?></span></li>
                <?php if (!empty($profile['creation_date'])): ?>
                  <li class="list-group-item d-flex justify-content-between">Member since <span><?php echo h(date('M j, Y', strtotime($profile['creation_date']))); ?></span></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
      </main>
    </div>

    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
