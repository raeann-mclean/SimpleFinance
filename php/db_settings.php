<?php
// Optional environment overrides retain the normal XAMPP defaults.
define('DB_HOST', getenv('SF_DB_HOST') ?: 'localhost');
define('DB_PORT', (int) (getenv('SF_DB_PORT') ?: 3306));
define('DB_USER', getenv('SF_DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('SF_DB_PASSWORD') === false ? '' : getenv('SF_DB_PASSWORD'));
define('DB_NAME', getenv('SF_DB_NAME') ?: 'login_db');
// Legacy sample accounts only. New registrations use password_hash().
define('SALT', 'authentication');
