<?php
    require_once "dbconfig.php";

    if (isset($_GET['username'])){

        $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
        $username= mysqli_real_escape_string($conn,$_GET['username']);
        $query = "SELECT id FROM `users` WHERE username='". $username."'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $response=array('exist'=> (mysqli_num_rows($res)===0 ? false :true));
        
        echo json_encode($response);

        mysqli_free_result($res);
        mysqli_close($conn);
    }

?>