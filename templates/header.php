<?php
/**
 * Header Include File
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026
 * @date      2026-02-24
 * @version   1.0
 */
if (!isset($_SESSION)) {
    include_once (realpath(dirname(__FILE__, 2).'/php/path.php'));
    include_once (ROOT_PATH . '/php/session.php');
}

$dashboard_link = isset($_SESSION['user_type'])
    ? BASE_URL . '/dashboards/' . strtolower($_SESSION['user_type'])
    : BASE_URL . '/index.php';
?>
<header class="py-5">
  <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary border-bottom border-primary-subtle nav-pills bs-success">
    <div class="container-fluid">
        <a class="navbar-brand px-3 py-2 rounded text-white d-flex align-items-center" style="background-color: #050359;" href="<?php echo $dashboard_link; ?>"><img src="<?php echo ASSET_URL; ?>/img/SimpleFinanceLogo.png" alt="Simple Finance" height="30" class="me-2">Simple Finance</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="dashboard" href="<?php echo $dashboard_link; ?>">
                Dashboard
            </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo BASE_URL; ?>/mockups/second.php">Link</a>
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                Dropdown
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li>
                  <a class="dropdown-item" href="#">Another action</a>
                </li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                  <a class="dropdown-item" href="#"
                    >Something else here</a
                  >
                </li>

              </ul>
            </li>
          </ul>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if (isset($_SESSION['user_name'])) { ?>
          <li class="nav-item">
            <span class="navbar-text me-2">Welcome <?php echo htmlspecialchars($_SESSION['user_first']); ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo SRC_PATH; ?>/logout.php">Logout</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/index.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/pages/create_account.php">Register</a>
          </li>
        <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
</header>
