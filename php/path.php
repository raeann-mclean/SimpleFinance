<?php
//======================================================================
// DATABASE CONNECTION
//======================================================================

$directory = "/csc235group/SimpleFinance-1"; // Change to your project folder

/* Define base url path */
DEFINE("BASE_URL", $directory);
DEFINE("ROOT_PATH", dirname(__DIR__));
DEFINE("ROOT_SRC_PATH", ROOT_PATH . "/php");
DEFINE("TEMPLATE_PATH", ROOT_PATH . "/templates");
DEFINE("ASSET_URL", $directory . "/assets");
DEFINE("SRC_PATH", $directory . "/php");
?>