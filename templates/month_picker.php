<?php
/**
 * Month dropdown
 *
 * Set before including:
 *   $months         list of "YYYY-MM" strings (from available_months())
 *   $selected_month "YYYY-MM" or "all"
 *   $allow_all      true to show an "All time" option (optional)
 */
$allow_all = $allow_all ?? false;
$current_label = ($selected_month === 'all') ? 'All time' : month_label($selected_month);
?>
<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-calendar3 me-1"></i><?php echo h($current_label); ?>
  </button>
  <ul class="dropdown-menu">
    <?php if ($allow_all): ?>
      <li><a class="dropdown-item <?php echo $selected_month === 'all' ? 'active' : ''; ?>" href="?month=all">All time</a></li>
      <li><hr class="dropdown-divider"></li>
    <?php endif; ?>
    <?php foreach ($months as $ym): ?>
      <li>
        <a class="dropdown-item <?php echo $ym === $selected_month ? 'active' : ''; ?>" href="?month=<?php echo h($ym); ?>">
          <?php echo h(month_label($ym)); ?><?php echo $ym === date('Y-m') ? ' (current)' : ''; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
