<?php
/**
 * Header Include File
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026 
 * @date      2026-02-24
 * @version   1.0
 */
// 
if (!isset($_SESSION)) {
    // Only include session if not already started
    include_once (realpath(dirname(__FILE__, 2).'/php/path.php'));
    // Include session file
    include_once (ROOT_PATH . '/php/session.php');
}
?>
<?php
// Header Section
include_once (TEMPLATE_PATH . '/head.php');
?>
<header>
<?php
// navigation bar
?>
<nav class="navbar navbar-expand-lg" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>/">   
    <img src="<?php echo ASSET_URL; ?>/img/SimpleFinanceLogo.png" alt="Simple Finance" height="30" class="me-2">Simple Finance</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
          /* Show Home link only when user is NOT logged in */
          if (!isset($_SESSION['user_name'])) {
            echo '<li class="nav-item">';
            echo '<a class="nav-link active" aria-current="page" href="' . BASE_URL . '/">Home</a>';
            echo '</li>';
          }
        ?>
        <?php
          /* User Information */
          if (isset($_SESSION['user_name']) && $_SESSION['user_role'] == 1) { // Only display when user is logged in
            echo '<li class="nav-item"><a class="nav-link" href="' . BASE_URL . '/dashboards/admin/">Admin Dashboard</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="#">New Page</a></li>';
          }
        ?>
        <?php
          /* User Information */
          if (isset($_SESSION['user_name']) && $_SESSION['user_role'] == 2) { // Only display when user is logged in
            echo '<li class="nav-item"><a class="nav-link" href="' . BASE_URL . '/dashboards/user/">User Dashboard</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="#">New Page</a></li>';
          }
        ?>
        <?php
          /* User Information */
          if (isset($_SESSION['user_name']) && $_SESSION['user_role'] == 3) { // Only display when user is logged in
            echo '<li class="nav-item"><a class="nav-link" href="' . BASE_URL . '/dashboards/guest/">Guest Dashboard</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="#">New Page</a></li>';
          }
        ?>
      </ul>
      <span class="navbar-text">
        <?php
            /* User Information */
            if (isset($_SESSION['user_first']) && isset($_SESSION['user_type'])) {
                echo 'Welcome ' . $_SESSION['user_first'] . ' (' . $_SESSION['user_type'] . ')';
                echo ' | <a href="' . SRC_PATH . '/logout.php">Logout</a>';
            } else {
                echo 'Not Logged In';
            }
            ?>
      </span>
    </div>
  </div>
</nav>
</header>