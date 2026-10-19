<?php
/**
 * Forgot Password  Page
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026 
 * @date      2026-02-24
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
  <body>
    <?php include_once TEMPLATE_PATH . "/header.php"; ?>
    <main role="main" class="container">
      <div class="row justify-content-sm-center">
        <div class="col-sm-4">
          <h1>Account Recovery</h1>
          <form action="./db/recovery.php" method="post">
            <div class="input-group mb-3">
              <input type="email" class="form-control" id="email" aria-describedby="email" placeholder="Enter Email" name="email" required>
            </div>
            <?php
              /* Error Message */
              if (isset($error)) {
                // uses bootstrap alert style for error messages
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
              }
            ?>
            <button type="submit" class="btn btn-primary">Next</button>
          </form>
          <hr />
          <p class="text-center">
            <small>
              <a href="<?php echo BASE_URL; ?>/pages/create_account.php">Create An Account</a> | <a href="<?php echo BASE_URL; ?>/">Login</a>
            </small>
          </p>
        </div>
      </div>
    </main>
    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>