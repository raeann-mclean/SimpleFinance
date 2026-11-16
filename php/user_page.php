<?php
/**
 * Setup for every page in dashboards/user/
 *
 * One include gives a page: the logged-in user ($user_id, $user_role, ...),
 * the database ($db_connection), the role check, and the finance helpers.
 *
 *   include_once (realpath(dirname(__FILE__, 3).'/php/user_page.php'));
 */

include_once (realpath(dirname(__FILE__).'/session.php'));   // login check + $user_id + $db_connection
include_once (ROOT_SRC_PATH . '/check_user.php');            // only role 2 (user) may continue
include_once (ROOT_SRC_PATH . '/finance.php');               // query helpers, h(), money(), csrf

/* Where the user pages live, for links and redirects */
DEFINE('USER_URL', BASE_URL . '/dashboards/user');
