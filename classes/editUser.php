<?php 
  require_once "checkPermission.php";
  require_once "dbconfig.php";

    session_start();
    if (isset($_SESSION['_hw1_user_id'])){
        if (checkAdminPermissions($_SESSION["_hw1_user_id"])){
            if (isset($_GET["user_id"]) && isset($_GET["group_id"])){
                $userId=$_GET["user_id"];
                $groupId=$_GET["group_id"];

                $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
                
                //MIGLIORIE: prima di effettuare UPDATE potrebbe essere opportuno testare la presenza di user id e group id sul DB

                $query="UPDATE users SET group_id='$groupId' WHERE id = '$userId'";
                if (mysqli_query($conn, $query)) {
                    $response = "Modifica utente ". $userId ." eseguita con successo";
                } else {
                    $response = "Errore di connessione al Database";
                }
                mysqli_close($conn);
            } else{
                $response="Mancano parametri";
            }
        } else {
            $response="Non si hanno privilegi di amministrazione";
        }
    } else {
        $response="Nessun Utente loggato";
    }

    echo json_encode($response);
?>
 