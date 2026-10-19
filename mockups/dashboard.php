<?php
/**
 * Dashboard Page
 */
  /* Quick Paths */
  /* note the 3 after __FILE__, because it's 3 directories deep */
  include_once (realpath(dirname(__FILE__, 3).'/php/path.php'));

  /* Start The Session (must happen before any HTML) */
  session_start();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <title>Simple Finance Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" href="favicon.png">
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
    crossorigin="anonymous"
    />
</head>

  <body>
    <header class="py-5">
        <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary border-bottom border-primary-subtle nav-pills bs-success">
          <div class="container-fluid">
              <a class="navbar-brand" href="dashboard.php">Simple Finance</a>
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
                    <a class="nav-link active" aria-current="dashboard" href="dashboard.php">
                      Dashboard
                  </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="second.html">Link</a>
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
                  <a class="nav-link" href="<?php echo BASE_URL; ?>/create_account.php">Register</a>
                </li>
              <?php } ?>
              </ul>
            </div>
          </div>
        </nav>
      </header>
    <h1>Simple Finance Dashboard</h1>
    <p>Welcome to the Simple Money </p>
    <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
    crossorigin="anonymous">
    </script>
  </body>
</html>