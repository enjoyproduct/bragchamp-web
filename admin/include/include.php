<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 19/05/2016
 * Time: 7:55 PM
 */

session_start();

define("PASSWORD_KEY", "bragchamp_Admin_Panel");
define("SESSION_KEY", "bragchamp_Admin_User");
define("SITE_TITLE", "Brag Champ ");

include_once ("config.php");
include_once("db.php");

// connection databse
$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_DATABASE, DB_HOST);

function encrypt_password($pwd) {
    // 123456 : 9e49ccf8068e0f2c73d8f48032f0b4d8
    return md5(PASSWORD_KEY . $pwd);
}

function set_session_adminid($adminid) {
    $_SESSION[SESSION_KEY] = $adminid;
}

function clear_session_adminid() {
    $_SESSION[SESSION_KEY] = "";
    session_destroy();
}

// check session
$current_page = basename($_SERVER['PHP_SELF']);
if (isset($_SESSION[SESSION_KEY])) {
    if ($current_page == "login.php") {
        header('Location: users.php');
    }

    $adminid = $_SESSION[SESSION_KEY];
    $adminuser = $wpdb->get_row("SELECT * FROM admins WHERE `id`='".$adminid."'");
    if ($adminuser) {

    } else {
        clear_session_adminid();
        header('Location: login.php');
    }
} elseif ($current_page != "login.php") {
    header('Location: login.php');
}
