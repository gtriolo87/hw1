
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebProgramming - Homework 1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@500&family=Kanit&family=Righteous&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/job.css" />
    <script src="scripts/job.js" defer></script>
</head>

    <body>
        <article>
            <?php require_once "classes/header.php";
                require_once "classes/checkPermission.php"; ?>
            
            <section id="content">
                <?php 
                    if(isset($_GET["job_id"])){
                        $apiError=false;
                        $jobId=$_GET["job_id"];
                        //echo "job: ". $jobId."<br/>";
                        $dati = array("type"=>0, "jobId" =>intval($jobId));
                        $data = http_build_query($dati);
                        //echo "dati: ". $data."<br/>";
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, "http://localhost/hw1/classes/retriveJob.php");
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $dati);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $result = json_decode(curl_exec($curl),true);
                        if($result===false){
                            echo "Errore Apertura Job: ". curl_error($curl)."<br/>";
                        } else {
                            if ($result['result']==0){
                                $apiError=TRUE;
                            } else{
                                $jobData=$result['jobData'];
                            }
                            $msgErroreGenerico=$result['message'];
                        }
                        curl_close($curl);
                    } 
                    if (isset($logged)){
                        $userId=$_SESSION["_hw1_user_id"];
                        $canManageJob=checkJobPermissions($userId);
                        //echo "loggato: ". $userId."<br/>";
                    } else{
                        $canManageJob=false;
                    }
                ?>

                <div id="divEditJob">
                    <?php
                        if (isset($jobId) && !$apiError){
                            echo "<h1>Modifica Lavoro</h1>";
                            //var_dump($jobData);
                        } else{
                            echo "<h1>Modulo di Inserimento nuovo Lavoro</h1>";
                        }
                    ?>
                    <form name="formAddJob" enctype="multipart/form-data" autocomplete="off">
                        <div class="jobTitle">
                            <label>Titolo: <input type="text" name="jobTitle" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobTitle'].'"'; } if(!$canManageJob){echo " disabled";} ?>></label>
                            <span class="hidden">Titolo non valido.</span>
                        </div>
                        <div class="jobCustomer">
                            <label>Cliente: <input type="text" name="jobCustomer" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobCustomer'].'"'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden">Cliente non valido.</span>
                        </div>
                        <div class="jobDevice">
                            <label>Dispositivo/SCADA: <input type="text" name="jobDevice" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobDevice'].'"'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden">Dispositivo non valido.</span>
                        </div>
                        <div class="jobEndingYear">
                            <label>Anno fine Lavoro: <input type="text" name="jobEndingYear" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobEndingYear'].'"'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden">Anno non valido.</span>
                        </div>
                        <div class="jobDescription">
                            <label>Descrizione: <input type="textbox" name="jobDescription" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobDescription'].'"'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden">Descrizione non valida.</span>
                        </div>
                        <div class="jobLat">
                            <label>Latitudine: <input type="text" name="jobLat" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobLat'].'"'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden">Latitudine non valida.</span>
                        </div>
                        <div class="jobLong">
                            <label>Longitudine: <input type="text" name="jobLong" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobLong'].'"'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden">Longitudine non valida.</span>
                        </div>
                        <div class="jobKeywords">
                            <label>Parole Chiave: <input type="text" name="jobKeywords" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobKeywords'].'"'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden">Parole chiave non valide.</span>
                        </div>
                        <div class="jobImage">
                            <label>Scegli un'immagine: <input type="text" name="jobImage" <?php if(isset($_GET["job_id"]) && !$apiError){ echo 'value="'.$jobData['jobImage'].'"'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden">Inserisci un url corretto.</span>
                        </div>
                        <div class="jobHasVideo">
                            <label>Presenza Video su YouTube? <input type="checkbox" name="jobHasVideo" <?php if(isset($_GET["job_id"]) && !$apiError && $jobData['jobHasVideo']){ echo 'checked'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden"> </span>
                        </div>
                        <div class="jobEnded">
                            <label>Lavoro Finito? <input type="checkbox" name="jobEnded" <?php if(isset($_GET["job_id"]) && !$apiError && $jobData['jobEnded']){ echo 'checked'; } if(!$canManageJob){echo " disabled";}?>></label>
                            <span class="hidden"> </span>
                        </div>
                        <div class="jobSubmit">
                            <input id="btnSubmit" type="submit" <?php if (isset($jobId) && !$apiError){
                                echo 'value="Modifica" data-job_id="'.$jobId.'"';
                            } else{
                                echo 'value="Inserisci"';
                            }?> disabled>
                        <strong  <?php if(!isset($_GET["job_id"])){ echo 'class="hidden"'; } ?>>Esito:<?php echo " ".$msgErroreGenerico."."?></strong>
                        </div>
                    </form>
                </div>
            </section>

            <?php require_once "classes/footer.php"; ?>
        </article>
    </body>
</html>