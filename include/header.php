<?php
/**
 * Header Include File
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026
 * @date      2026-02-24
 * @version   1.0
 */
// Make sure BASE_URL is defined so links work from any folder
include_once (realpath(dirname(__FILE__, 2).'/php/path.php'));

// Pages should call session_start() before any HTML is sent
$is_logged_in = isset($_SESSION['user_name']) && !empty($_SESSION['user_name']);
$current_page = isset($page_name) ? $page_name : '';

// Dashboard link for each role
$dashboard_links = [
  1 => ['url' => BASE_URL . '/admin/', 'label' => 'Admin Dashboard'],
  2 => ['url' => BASE_URL . '/user/',  'label' => 'User Dashboard'],
  3 => ['url' => BASE_URL . '/guest/', 'label' => 'Guest Dashboard'],
];
?>
<?php
// Header Section
?>
<header class="py-5">
<?php
// navigation bar
?>
<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary border-bottom border-primary-subtle" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>/index.php">Simple Finance</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
          /* Dashboard link for the logged in user's role */
          if ($is_logged_in && isset($dashboard_links[$_SESSION['user_role']])) {
            $link = $dashboard_links[$_SESSION['user_role']];
            echo '<li class="nav-item"><a class="nav-link" href="' . $link['url'] . '">' . $link['label'] . '</a></li>';
          }
        ?>
      </ul>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if ($is_logged_in) { ?>
          <li class="nav-item">
            <span class="navbar-text me-2">
              Welcome <?php echo htmlspecialchars($_SESSION['user_first']); ?> (<?php echo htmlspecialchars($_SESSION['user_type']); ?>)
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo SRC_PATH; ?>/logout.php">Logout</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link<?php echo $current_page == 'home' ? ' active' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?php echo $current_page == 'create_account' ? ' active' : ''; ?>" href="<?php echo BASE_URL; ?>/create_account.php">Register</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
</header>
