<?php

    require_once "dbconfig.php";


    $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
    $query = "SELECT a.id as id,a.username as username,a.nome as nome,a.cognome as cognome,a.email as email,a.group_id as group_id,b.name as group_name FROM `users` AS a JOIN groups AS b on a.group_id=b.id";
    if (isset($_GET['filterProfile'])){
        $groupId=$_GET['filterProfile'];
        $query=$query." WHERE a.group_id='". $groupId."'";
    }
    
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    
    //devo popolare l'array "userList" con ogni riga restituita dalla query.
    $userList = array();
    while($user = mysqli_fetch_assoc($res)) {
        $userList[] = array('user_id' => $user['id'],'username' => $user['username'],
                            'name' => $user['nome'], 'surname' => $user['cognome'], 
                            'email' => $user['email'],
                            'group_id' => $user['group_id'],'group_name' => $user['group_name']);
    }
        
    $response=$userList;

    echo json_encode($response);



    mysqli_free_result($res);
    mysqli_close($conn);
?>