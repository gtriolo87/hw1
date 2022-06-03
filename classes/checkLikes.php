<?php
    require_once "dbconfig.php";
    $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));       
    if(isset($_GET['type'])){
        switch (intval($_GET["type"])) {
            case 1: //conta like di un job
                if(isset($_GET['jobId'])){    
                    $query = "SELECT nLikes FROM jobs WHERE id=". $_GET['jobId'];
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if (mysqli_num_rows($res)==0){
                        $response=array('result'=>0,'message'=>"Conta Like: Non e' presente alcun job con questo ID!");
                    } else{
                        $row=mysqli_fetch_assoc($res);
                        $jobNLike=$row['nLikes'];
                    }
                    mysqli_free_result($res);
                    $response=array('result'=>1,'message'=>"Conta Like: Numero di like prelevato",'jobId'=>$_GET['jobId'],'jobNLikes'=>$jobNLike);
                } else{
                    $response=array('result'=>0,'message'=>"Conta Like: Non è stato Inserito l'ID del job");
                }
                break;
            case 2: //Verifica se l'utente ha inserito like ad un job
                if(isset($_GET['jobId'])){
                    if(isset($_GET['userId'])){        
                        $query = "SELECT * FROM likes WHERE job_id=". $_GET['jobId']." AND user_id=". $_GET['userId'];
                        //echo $query;
                        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                        if (mysqli_num_rows($res)==0){
                            $response=array('result'=>2,'message'=>"Controlla Like Utente: Non sono presenti like!",'jobId'=>$_GET['jobId'],'esito'=>false);
                        } else{
                            $response=array('result'=>2,'message'=>"Controlla Like Utente: e' presente il like!",'jobId'=>$_GET['jobId'],'esito'=>true);
                        }
                        mysqli_free_result($res);
                    } else{
                        $response=array('result'=>0,'message'=>"Controlla Like Utente: Non è stato Inserito l'ID dell'utente");
                    }
                } else{
                    $response=array('result'=>0,'message'=>"Controlla Like Utente: Non è stato Inserito l'ID del job");
                }
                break;
            case 3: //Aggiungi o rimuovi un like
                
                session_start();
                if(isset($_SESSION['_hw1_user_id'])){
                    if(isset($_GET['userId'])){
                        if($_SESSION['_hw1_user_id']===$_GET['userId']){
                            if(isset($_GET['jobId'])){  
                                $jobId=$_GET['jobId'];
                                $userId=$_GET['userId'];
                                $query = "SELECT * FROM likes WHERE job_id=". $jobId." AND user_id=". $userId;
                                $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                //echo $query;
                                if (mysqli_num_rows($res)==0){
                                    mysqli_free_result($res);
                                    //non esiste quindi devo aggiungere il like
                                    $in_query = "INSERT INTO likes(user_id, job_id) VALUES($userId, $jobId)";
                                    if (mysqli_query($conn, $in_query)){
                                        $response=array('result'=>3,'message'=>"Modifica Like Utente: Inserimento eseguito",'esito'=>true);
                                    } else{
                                        $response=array('result'=>0,'message'=>"Modifica Like Utente: Inserimento Fallito",'esito'=>false);
                                    }
                                } else{
                                    mysqli_free_result($res);
                                    //esiste quindi devo rimuovere il like
                                    $in_query = "DELETE FROM likes WHERE job_id=". $jobId." AND user_id=". $userId;
                                    if (mysqli_query($conn, $in_query)){
                                        $response=array('result'=>3,'message'=>"Modifica Like Utente: Cancellazione eseguita",'esito'=>true);
                                    } else{
                                        $response=array('result'=>0,'message'=>"Modifica Like Utente: Cancellazione Fallita",'esito'=>false);
                                    }
                                }
                            } else{
                                $response=array('result'=>0,'message'=>"Modifica Like Utente: Non è stato Inserito l'ID del job");
                            }
                        }else{
                            $response=array('result'=>0,'message'=>"Modifica Like Utente: L'utente loggato non corrisponde a quello di cui si vogliono modificare i like");
                        } 
                    } else{
                        $response=array('result'=>0,'message'=>"Modifica Like Utente: Non è stato Inserito l'ID dell'utente");
                    }
                } else {
                    $response=array('result'=>0,'message'=>"Modifica Like Utente: Utente non Loggato!");
                }
                break;
            default:
                $response=array('result'=>0,'message'=>"Tipo di azione non corretto. Type: ".$_POST["type"]);
        }
        
    }
    mysqli_close($conn);

    echo json_encode($response);
?>