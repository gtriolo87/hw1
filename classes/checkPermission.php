<?php

    require_once "dbconfig.php";



    function checkAdminPermissions($UserId){

        GLOBAL $dbconfig;

        $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));

        

        $query = "SELECT * FROM `v_user_permissions` WHERE id=". $UserId;

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        $userRow=mysqli_fetch_assoc($res);

        if ($userRow['canManageUsers']){

            return true;

        } else {

            return false;

        }
        mysqli_free_result($res);
        mysqli_close($conn);

    }

    function checkJobPermissions($UserId){

        GLOBAL $dbconfig;

        $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));

        

        $query = "SELECT * FROM `v_user_permissions` WHERE id=". $UserId;

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        $userRow=mysqli_fetch_assoc($res);

        if ($userRow['canAddJob'] || $userRow['canEditJob']){

            return true;

        } else {

            return false;

        }
        mysqli_free_result($res);
        mysqli_close($conn);

    }

    function checkLikePermissions($UserId){

        GLOBAL $dbconfig;

        $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));

        

        $query = "SELECT * FROM `v_user_permissions` WHERE id=". $UserId;

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        $userRow=mysqli_fetch_assoc($res);

        if ($userRow['canLike']){

            return true;

        } else {

            return false;

        }
        mysqli_free_result($res);
        mysqli_close($conn);

    }


    function checkManageTaskPermissions($UserId){

        GLOBAL $dbconfig;

        $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));

        

        $query = "SELECT * FROM `v_user_permissions` WHERE id=". $UserId;

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        $userRow=mysqli_fetch_assoc($res);

        if ($userRow['canAddTask']||$userRow['canEditTask']){

            return true;

        } else {

            return false;

        }
        mysqli_free_result($res);
        mysqli_close($conn);

    }



    function checkWorkTaskPermissions($UserId){

        GLOBAL $dbconfig;

        $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));

        

        $query = "SELECT * FROM `v_user_permissions` WHERE id=". $UserId;

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        $userRow=mysqli_fetch_assoc($res);

        if ($userRow['canWorkTask']){

            return true;

        } else {

            return false;

        }
        mysqli_free_result($res);
        mysqli_close($conn);

    }

?>