<?php
foreach (['error' => 'danger', 'message' => 'success'] as $key => $level) {
    if (isset($_SESSION[$key])) {
        echo '<div class="alert alert-' . $level . '" role="alert">' .
            htmlspecialchars((string) $_SESSION[$key], ENT_QUOTES, 'UTF-8') . '</div>';
        unset($_SESSION[$key]);
    }
}
