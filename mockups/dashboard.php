<?php
/**
 * Dashboard Page
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
    <!-- One head file for all pages -->
    <?php include_once TEMPLATE_PATH . "/head.php"; ?>
  </head>
  <body>
    <?php include_once TEMPLATE_PATH . "/header.php"; ?>
    <main role="main" class="container">
      <h1>Simple Finance Dashboard</h1>
      <p>Welcome to Simple Money</p>
    </main>
    <!-- One footer file for all pages -->
    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>
