<?php
/**
 * Template Page
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026 
 * @date      2026-02-24
 * @version   1.0
 *
 * Copy this file into pages/ (or a dashboards/ folder) to start a new page.
 * Fix the number in dirname(__FILE__, 2) if the copy is deeper than one folder.
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
      <!-- Primary Content Goes Here -->
    </main>
    <!-- One footer file for all pages -->
    <?php include_once TEMPLATE_PATH . "/footer.php"; ?>
  </body>
</html>