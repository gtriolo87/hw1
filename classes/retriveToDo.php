<?php
    $URL_API_TODO = 'https://api.todoist.com/rest/v1/projects';
    $URL_AUTH_TODO = 'https://todoist.com/oauth/access_token';
    $TODO_CLIENT_ID = 'ef23d98f84c74251892f571d2269bd1a';
    $TODO_CLIENT_SECRET = 'baf733ce1640426c8307a8fca14513b7';
    $TODO_CODE = '82f1232ce332f1d708f2ea5bc4b6f1bebe76a88f';

    $response=array();

    $curl = curl_init($URL_API_TODO);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$TODO_CODE)); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $projects = json_decode(curl_exec($curl));
    if($projects===false){
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
        $response[] =$projects;
    }
    curl_close($curl);
    

    echo json_encode($response);
    
?>