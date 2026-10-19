<?php
/**
 * login Page
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026 
 * @date      2026-02-24
 * @version   1.0
 */
  /* Quick Paths */
  include_once (realpath(dirname(__FILE__).'/php/path.php'));


  /* Page Name */
  $page_name = "home";

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
          <h1>Login</h1>
          <form action="<?php echo SRC_PATH; ?>/authenticate.php" method="post">
            <div class="input-group mb-3">
              <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="username" placeholder="Enter username" name="username" required>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter Password" name="password" required>
            </div>
            
            <?php include_once __DIR__ . '/php/error_rprt.php';?>
            <button type="submit" class="btn btn-primary">Log In</button>
          </form>
          <hr />
          <p class="text-center">
              <small>
                <a href="<?php echo BASE_URL; ?>/pages/create_account.php">Create An Account</a> | <a href="<?php echo BASE_URL; ?>/pages/forgot_pass.php">Forgot Password</a>
              </small>
          </p>
        </div>
      </div>
    </main>
    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>