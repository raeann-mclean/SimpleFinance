<?php
/**
 * Sidebar Navigation Include File
 *
 * Set $active_page before including this file to highlight the current page.
 * Valid values: 'dashboard', 'accounts', 'transactions', 'reports', 'settings'
 *
 * Links go to the live pages in dashboards/user/ (the old designs are still in mockups/).
 */
if (!isset($active_page)) {
    $active_page = '';
}

$sidebar_links = [
  'dashboard'    => ['/dashboards/user/',                 'bi-speedometer2',    'Dashboard'],
  'accounts'     => ['/dashboards/user/accounts.php',     'bi-wallet2',         'Accounts'],
  'transactions' => ['/dashboards/user/transactions.php', 'bi-arrow-left-right', 'Transactions'],
  'reports'      => ['/dashboards/user/reports.php',      'bi-graph-up',        'Reports'],
  'settings'     => ['/dashboards/user/settings.php',     'bi-gear',            'Settings'],
];
?>
<nav class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 250px; background-color: #050359;">
  <ul class="nav nav-pills flex-column mb-auto">
    <?php foreach ($sidebar_links as $key => [$path, $icon, $label]): $is_active = ($active_page === $key); ?>
      <li class="nav-item">
        <a href="<?php echo BASE_URL . $path; ?>"
           class="nav-link text-white <?php echo $is_active ? 'active' : ''; ?>"
           <?php if ($is_active) echo 'style="background-color: #1a0f8a;" aria-current="page"'; ?>>
          <i class="bi <?php echo $icon; ?> me-2"></i><?php echo $label; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>
