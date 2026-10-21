<?php
/**
 * User Index Page
 *
 * @author    Joshua Connor <connorj4@southernct.edu>
 * @copyright 2026 
 * @date      2026-02-24
 * @version   1.0
 * new update: 2026-06-01
 */
error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 0); // set to 1 to display errors, 0 to hide them

  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 3 directories deep */
  include_once (realpath(dirname(__FILE__, 3).'/php/session.php')); // Session will be included in header.php
  include_once (realpath(dirname(__FILE__, 3).'/php/path.php')); // Path will be included in header.php
  // Session will be included in header.php

  /* Page Name */
  $page_name = "user";

?>
<?php
//======================================================================
// USER DASHBOARD PAGE
//======================================================================

error_reporting(E_ALL);
ini_set('display_errors', 0); // set to 1 to display errors, 0 to hide them

  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 3 directories deep */
  include_once (realpath(dirname(__FILE__, 3).'/php/session.php')); // Session will be included in header.php
  include_once (realpath(dirname(__FILE__, 3).'/php/path.php')); // Path will be included in header.php
  // Session will be included in header.php
  
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_user.php'); // Check user role and redirect if not authorized

  $user_check = $_SESSION['login_user'];
  // Check user and get roll session from database

  /* Page Name */
  $page_name = "admin"; // Set page name for active link in header

?>
<!doctype html>
<html lang="en">
  <head>
  <?php include_once (TEMPLATE_PATH . '/head.php'); // Include head.php ?>
  </head>
  <body class="<?php echo $page_name; ?>">

  <?php include_once (TEMPLATE_PATH . '/header.php'); // Include header.php ?>

    <?php $active_page = 'dashboard'; ?>
    <div class="d-flex vh-100 m-0">
      <?php include_once TEMPLATE_PATH . "/sidebar.php"; ?>

      <main role="main" class="flex-grow-1 p-4">
        <div class="container text-center">
          <div class="row align-items-center">
            <div class="col">
              <div class="mb-6">
                <h1>Welcome <?php echo $_SESSION['user_first'] ?></h1>
                <p class="lead">This is the <?php echo $_SESSION['user_type'] ?> dashboard.</p>
                <p>Only users with the role can access this page.</p>
              </div>
              <hr class="mb-4">
              <section class="mb-4">
                <h2>User Information</h2>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">First</th>
                      <th scope="col">Last</th>
                      <th scope="col">Email</th>
                      <th scope="col">Username</th>
                      <th scope="col">Role</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      include_once (ROOT_PATH . '/php/config.php');

                      $result = $db_connection->query(
                        "SELECT
                            u.user_id,
                            c.first_name,
                            c.last_name,
                            c.email,
                            cr.username,
                            r.role_type
                        FROM Users u
                        INNER JOIN Contacts c ON u.contact_id = c.contact_id
                        INNER JOIN Credentials cr ON u.user_id = cr.user_id
                        INNER JOIN Roles r ON u.role_id = r.role_id
                        WHERE cr.username = '$user_check'
                        ");

                      if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<th scope='row'>" . $row["user_id"] . "</th>";
                          echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
                          echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
                          echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                          echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                          echo "<td>" . htmlspecialchars($row["role_type"]) . "</td>";
                          echo "</tr>";
                        }
                      } else {
                        echo "<tr><td colspan='6'>No users found</td></tr>";
                      }
                      $db_connection->close();
                    ?>
                  </tbody>
                </table>
              </section>
            </div>
          </div>
        </div>
      </main>
    </div>

    <?php include_once (TEMPLATE_PATH . '/footer.php'); ?>
  </body>
</html>

