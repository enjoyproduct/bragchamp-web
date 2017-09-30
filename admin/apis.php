<?php
/**
 * Created by PhpStorm.
 * User: oliver
 * Date: 19/05/2016
 * Time: 10:22 PM
 */
include_once "include/include.php";

$action = $_GET['action'];

$action = $_GET["action"];

if ($action == 'getusers') {

    $sql = "SELECT * FROM user";
    $users = $wpdb->get_results($sql);

    exit( json_encode($users) );

} else if ($action == "deleteusers") {

    $userids = $_POST['selected_ids'];

    $sql = "DELETE FROM user WHERE id IN (" . $userids . ")";
    $state = $wpdb->query($sql);
    if ($state)
        exit('OK');
    else
        exit("Error");
    
} 

echo $action;