<?php

    require_once "dbconfig.php";
    require_once "checkPermission.php";

    $response=null;
    $error=array();

    session_start();

    function checkField(){
        GLOBAL $error;
        //CONTROLLO DEI DATI INSERITI
        if (!preg_match('/^[a-zA-Z \']{1,255}$/', $_POST['jobTitle'])) {
            $error[] = "Titolo non valido: ".$_POST['jobTitle'];
        }
        if (!preg_match('/^[a-zA-Z -.\/]{1,255}$/', $_POST['jobCustomer'])) {
            $error[] = "Cliente non valido";
        }
        if (!preg_match('/^[0-9a-zA-Z -.\/]{1,255}$/', $_POST['jobDevice'])) {
            $error[] = "Dispositivo non valido";
        }
        if (!preg_match('/^[0-9]{0,4}$/', $_POST['jobEndingYear'])) {
            $error[] = "Anno di fine non valido";
        }
        if (!(strlen($_POST['jobDescription'])>=1 && strlen($_POST['jobDescription'])<=1000)) {
            $error[] = "Descrizione non valida: ". strlen($_POST['jobDescription']);
        }
        if (!preg_match('/^[0-9.,]{1,20}$/', $_POST['jobLat'])) {
            $error[] = "Latitudine non valida";
        }
        if (!preg_match('/^[0-9.,]{1,20}$/', $_POST['jobLong'])) {
            $error[] = "Longitudine non valida";
        }
        if (!preg_match('/^[0-9a-zA-Z -]{0,255}$/', $_POST['jobKeywords'])) {
            $error[] = "Parole Chiave non valide";
        }
        if (!is_bool(boolval($_POST['jobHasVideo']))) {
            $error[] = 'Errore nel parametro booleano "Esistono Video" '. $_POST['jobHasVideo'];
        }
        if (!is_bool(boolval($_POST['jobEnded']))) {
            $error[] = 'Errore nel parametro booleano "Lavoro Finito" '. $_POST['jobEnded'];
        }

        # Verifica immagine  
        
        if (!preg_match('/^[0-9a-zA-Z.-:\/]{0,255}$/', $_POST['jobImage'])) {
            $error[] = "Percorso immagine non valido";
        }
    }

    if(isset($_POST["type"])){
        switch (intval($_POST["type"])) {
            case 0:
                $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
                if(isset($_POST["jobId"])){
                    $query = 'SELECT * FROM `jobs` WHERE id='.$_POST["jobId"];
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if (mysqli_num_rows($res)==0){
                        $response=array('result'=>0,'message'=>"Non e' presente alcun job con questo id!");
                    } else{
                        $jobData = mysqli_fetch_assoc($res);
                        $jobDataArray= array('jobId'=>$jobData['id'],'jobTitle'=>$jobData['title'],
                                            'jobCustomer'=>$jobData['customer'],'jobDevice'=>$jobData['device'],
                                            'jobEndingYear'=>$jobData['endingYear'],'jobDescription'=>$jobData['description'],
                                            'jobLat'=>$jobData['latitude'],'jobLong'=>$jobData['longitude'],
                                            'jobKeywords'=>$jobData['keywords'],'jobHasVideo'=>boolval($jobData['hasVideo']),
                                            'jobEnded'=>boolval($jobData['jobEnded']),'jobNLikes'=>$jobData['nLikes'],
                                            'jobNTasks'=>$jobData['nTasks'],'jobImage'=>$jobData['image']);
                                            
                        $response=array('result'=>1,'message'=>"Job trovato!","jobData"=>$jobDataArray);
                    }
                } else {
                    $query = 'SELECT * FROM `jobs`';
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if (mysqli_num_rows($res)==0){
                        $response=array('result'=>0,'message'=>"Non e' presente alcun job!");
                    } else{
                        while ($jobData = mysqli_fetch_assoc($res)){
                            $jobDataArray[]= array('jobId'=>$jobData['id'],'jobTitle'=>$jobData['title'],
                                            'jobCustomer'=>$jobData['customer'],'jobDevice'=>$jobData['device'],
                                            'jobEndingYear'=>$jobData['endingYear'],'jobDescription'=>$jobData['description'],
                                            'jobLat'=>$jobData['latitude'],'jobLong'=>$jobData['longitude'],
                                            'jobKeywords'=>$jobData['keywords'],'jobHasVideo'=>boolval($jobData['hasVideo']),
                                            'jobEnded'=>boolval($jobData['jobEnded']),'jobNLikes'=>$jobData['nLikes'],
                                            'jobNTasks'=>$jobData['nTasks'],'jobImage'=>$jobData['image']);
                        }
                        $response=array('result'=>1,'message'=>"Jobs trovati!","jobList"=>$jobDataArray);
                    }
                }
                mysqli_free_result($res);
                mysqli_close($conn);
                break;
            case 1:
                if(isset($_SESSION['_hw1_user_id'])){
                    if (checkJobPermissions($_SESSION['_hw1_user_id'])){
                        if(isset($_POST["jobId"])){
                            if(!isset($_POST["jobTitle"])||!isset($_POST["jobCustomer"])
                            ||!isset($_POST["jobDevice"])||!isset($_POST["jobEndingYear"])
                            ||!isset($_POST["jobDescription"])||!isset($_POST["jobLat"])
                            ||!isset($_POST["jobLong"])||!isset($_POST["jobKeywords"])
                            ||!isset($_POST["jobHasVideo"])||!isset($_POST["jobEnded"])){
                                $response=array('result'=>0,'message'=>"Mancano alcuni paramentri per la modifica del Job!");
                            } else{
                                checkField();
                                if (count($error) == 0) {
                                    $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
                                    
                                    $jobTitle = mysqli_real_escape_string($conn, $_POST['jobTitle']);
                                    $jobCustomer = mysqli_real_escape_string($conn, $_POST['jobCustomer']);
                                    $jobDevice = mysqli_real_escape_string($conn, $_POST['jobDevice']);
                                    $jobEndingYear = mysqli_real_escape_string($conn, $_POST['jobEndingYear']);
                                    $jobDescription = mysqli_real_escape_string($conn, $_POST['jobDescription']);
                                    $jobLat = mysqli_real_escape_string($conn, $_POST['jobLat']);
                                    $jobLong = mysqli_real_escape_string($conn, $_POST['jobLong']);
                                    $jobKeywords = mysqli_real_escape_string($conn, $_POST['jobKeywords']);
                                    $jobHasVideo = mysqli_real_escape_string($conn, $_POST['jobHasVideo']);
                                    $jobEnded = mysqli_real_escape_string($conn, $_POST['jobEnded']);
                                    $jobImage = mysqli_real_escape_string($conn, $_POST['jobImage']);
                                    if ($jobImage===""){
                                        $immagine="";
                                    }else{
                                        $immagine=",image='$jobImage' ";
                                    }
                                    $query = "UPDATE `jobs` SET
                                                title='$jobTitle',
                                                customer='$jobCustomer',
                                                device='$jobDevice',
                                                endingYear='$jobEndingYear',
                                                description='$jobDescription',
                                                latitude='$jobLat',
                                                longitude='$jobLong',
                                                keywords='$jobKeywords',
                                                hasVideo=".$jobHasVideo.",
                                                jobEnded=".$jobEnded.$immagine."
                                                WHERE id=".$_POST['jobId']."";
                                    if (mysqli_query($conn, $query)){
                                        $response=array('result'=>1,'message'=>"Job Aggiornato. Ricorda di caricare in FTP l'immagine.");
                                    } else{
                                        $response=array('result'=>0,'message'=>"Errore durante l'aggiornamento del job!");
                                    }
                                    mysqli_close($conn);
                                } else {
                                    $response=array('result'=>3,'message'=>"Errore nei dati di aggiornamento",'errori'=>$error);
                                }
                            }
                        } else {
                            $response=array('result'=>0,'message'=>"Non e' presente l'identificativo del job da modificare!");
                        }
                    } else {
                        $response=array('result'=>0,'message'=>"L'utente non ha i permessi per eseguire la richiesta.");
                    }
                }else {
                    $response=array('result'=>0,'message'=>"Utente non loggato.");
                }
                break;
            case 2:
                if(isset($_SESSION['_hw1_user_id'])){
                    if (checkJobPermissions($_SESSION['_hw1_user_id'])){
                        if(!isset($_POST["jobTitle"])||!isset($_POST["jobCustomer"])
                        ||!isset($_POST["jobDevice"])||!isset($_POST["jobEndingYear"])
                        ||!isset($_POST["jobDescription"])||!isset($_POST["jobLat"])
                        ||!isset($_POST["jobLong"])||!isset($_POST["jobKeywords"])
                        ||!isset($_POST["jobHasVideo"])||!isset($_POST["jobEnded"])){
                            $response=array('result'=>0,'message'=>"Mancano alcuni paramentri per la modifica del Job!");
                        } else{
                            checkField();
                            if (count($error) == 0) {
                                $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
                                
                                $jobTitle = mysqli_real_escape_string($conn, $_POST['jobTitle']);
                                $jobCustomer = mysqli_real_escape_string($conn, $_POST['jobCustomer']);
                                $jobDevice = mysqli_real_escape_string($conn, $_POST['jobDevice']);
                                $jobEndingYear = mysqli_real_escape_string($conn, $_POST['jobEndingYear']);
                                $jobDescription = mysqli_real_escape_string($conn, $_POST['jobDescription']);
                                $jobLat = mysqli_real_escape_string($conn, $_POST['jobLat']);
                                $jobLong = mysqli_real_escape_string($conn, $_POST['jobLong']);
                                $jobKeywords = mysqli_real_escape_string($conn, $_POST['jobKeywords']);
                                $jobHasVideo = mysqli_real_escape_string($conn, $_POST['jobHasVideo']);
                                $jobEnded = mysqli_real_escape_string($conn, $_POST['jobEnded']);
                                $jobImage = mysqli_real_escape_string($conn, $_POST['jobImage']);
                                if ($jobImage===""){
                                    $colImmagine="";
                                    $immagine="";
                                }else{
                                    $colImmagine=",image";
                                    $immagine=",'$jobImage' ";
                                }
                                $query = "INSERT INTO jobs(
                                            title,
                                            customer,
                                            device,
                                            endingYear,
                                            description,
                                            latitude,
                                            longitude,
                                            keywords,
                                            hasVideo,
                                            jobEnded".$colImmagine."
                                            ) VALUES(
                                            '$jobTitle',
                                            '$jobCustomer',
                                            '$jobDevice',
                                            '".intval($jobEndingYear)."',
                                            '$jobDescription',
                                            '$jobLat',
                                            '$jobLong',
                                            '$jobKeywords',
                                            ".$jobHasVideo.",
                                            ".$jobEnded.$immagine."
                                            )";
                                if (mysqli_query($conn, $query)){
                                    $jobId=mysqli_insert_id($conn);
                                    $response=array('result'=>2,'message'=>"Job Inserito. Ricorda di caricare in FTP l'immagine.",'jobId'=>$jobId);
                                } else{
                                    $response=array('result'=>0,'message'=>"Errore durante l'inserimento del job!");
                                }
                                mysqli_close($conn);
                            } else {
                                $response=array('result'=>3,'message'=>"Errore nei dati di inserimento",'errori'=>$error);
                            }
                        }
                    } else {
                        $response=array('result'=>0,'message'=>"L'utente non ha i permessi per eseguire la richiesta.");
                    }
                }else {
                    $response=array('result'=>0,'message'=>"Utente non loggato.");
                }
                break;
            case 3: //ricerca tramite parole nella descrizione o nelle keywords
                $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
                if(isset($_POST["keyRicerca"])){
                    //Effettuo il controllo all'interno perchè non mi serve farlo insieme al resto
                    if (!preg_match('/^[0-9a-zA-Z -]{0,255}$/', $_POST['keyRicerca'])) {
                        $error[] = "Chiave di ricerca non valida: ".$_POST['keyRicerca'];
                        $response=array('result'=>3,'message'=>"Errore nelle chiavi di ricerca. Non sono consentiti simboli!",'errori'=>$error);
                    } else {
                        $paroleChiave=explode(" ", mysqli_real_escape_string($conn,$_POST["keyRicerca"]));
                        $nParole=0;
                        $query = 'SELECT * FROM `jobs` WHERE ';
                        foreach($paroleChiave as $word){
                            if ($nParole>0){
                                $query=$query." OR ";
                            }
                            $query=$query."keywords LIKE '%$word%' OR description LIKE '%$word%'";
                            $nParole++;
                        }
                        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                        if (mysqli_num_rows($res)==0){
                            $response=array('result'=>0,'message'=>"Non e' presente alcun job con queste chiavi!");
                        } else{
                            while ($jobData = mysqli_fetch_assoc($res)){
                                $jobDataArray[]= array('jobId'=>$jobData['id'],'jobTitle'=>$jobData['title'],
                                                'jobCustomer'=>$jobData['customer'],'jobDevice'=>$jobData['device'],
                                                'jobEndingYear'=>$jobData['endingYear'],'jobDescription'=>$jobData['description'],
                                                'jobLat'=>$jobData['latitude'],'jobLong'=>$jobData['longitude'],
                                                'jobKeywords'=>$jobData['keywords'],'jobHasVideo'=>boolval($jobData['hasVideo']),
                                                'jobEnded'=>boolval($jobData['jobEnded']),'jobNLikes'=>$jobData['nLikes'],
                                                'jobNTasks'=>$jobData['nTasks'],'jobImage'=>$jobData['image']);
                            }                                                
                            $response=array('result'=>1,'message'=>"Job trovati!","jobList"=>$jobDataArray);
                        }
                        mysqli_free_result($res);
                    }
                    
                } else {
                    $query = 'SELECT * FROM `jobs`';
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if (mysqli_num_rows($res)==0){
                        $response=array('result'=>0,'message'=>"Non e' presente alcun job!");
                    } else{
                        while ($jobData = mysqli_fetch_assoc($res)){
                            $jobDataArray[]= array('jobId'=>$jobData['id'],'jobTitle'=>$jobData['title'],
                                            'jobCustomer'=>$jobData['customer'],'jobDevice'=>$jobData['device'],
                                            'jobEndingYear'=>$jobData['endingYear'],'jobDescription'=>$jobData['description'],
                                            'jobLat'=>$jobData['latitude'],'jobLong'=>$jobData['longitude'],
                                            'jobKeywords'=>$jobData['keywords'],'jobHasVideo'=>boolval($jobData['hasVideo']),
                                            'jobEnded'=>boolval($jobData['jobEnded']),'jobNLikes'=>$jobData['nLikes'],
                                            'jobNTasks'=>$jobData['nTasks'],'jobImage'=>$jobData['image']);
                        }
                        $response=array('result'=>1,'message'=>"Jobs trovati!","jobList"=>$jobDataArray);
                    }
                    mysqli_free_result($res);
                }
                mysqli_close($conn);
                break;
            default:
                $response=array('result'=>0,'message'=>"Tipo di azione non corretto. Type: ".$_POST["type"]);
        }        
    } else {
        $response=array('result'=>0,'message'=>"Nessun tipo di interazione selezionata!");
    }

    echo json_encode($response);
?>