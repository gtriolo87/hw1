<?php
    
    $YOU_TUBE_URL='https://youtube.googleapis.com/youtube/v3/search?part=snippet&maxResults=5&type=video&q=';
    
    if(isset($_GET['keywords'])){
        $response=array();
        //echo $_GET['keywords'];
        $curl = curl_init($YOU_TUBE_URL.strtr($_GET['keywords']," ","%20").'&key=AIzaSyA18CoxcFIRxukRKRhyLQentY99KGJOnEQ');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $videos = json_decode(curl_exec($curl));
        if($videos===false){
            //echo "Errore: ". curl_error($curl)."<br/>";
            $response[] = false;
            $response[] ="Errore: ". curl_error($curl)."<br/>";
        } else {
    //Effettutato primo test per ciclare i risultati
            /*var_dump($projects);
            foreach ($projects as $project) {
                echo "<br/><br/>";
                //var_dump($project);
                if($project->name!=='Inbox'){
                    echo $project->name . "<br/>";
                }
            }*/
            
            $response[] = true;
            $response[] =$videos;
        }
        curl_close($curl);
    } else{
        $response[] = false;
        $response[] ="Errore: Nessuna Keywords presente<br/>";
    } 

    echo json_encode($response);
    
?>