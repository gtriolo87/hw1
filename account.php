
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebProgramming - Homework 1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@500&family=Kanit&family=Righteous&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/form.css" />
    <script src="scripts/form.js" defer></script>
</head>

    <body>
        <article>
            <?php require_once "classes/header.php"; ?>
            
            <?php 
                $error = array();
                if (isset($logged)){
                    $userId=$_SESSION["_hw1_user_id"];
                    $username=$_SESSION["_hw1_user_name"];
                    $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
                    $query = "SELECT * FROM users WHERE id='".$userId."'";
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    $entry = mysqli_fetch_assoc($res);
                    $oldPassword=$entry['password'];
                    $oldName=$entry['nome'];
                    $oldSurname=$entry['cognome'];
                    $oldEmail=$entry['email'];
                    mysqli_close($conn);
                }
                
                if (!empty($_POST["new_username"]) && !empty($_POST["new_password"]) && !empty($_POST["email"]) && !empty($_POST["name"]) && !empty($_POST["surname"]) && !empty($_POST["checkPassword"])){
                    
                    $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));

                    //Controllo Username POST
                    if(!preg_match('/^[0-9a-zA-Z_.-]{2,15}$/', $_POST['new_username'])) {
                        $error[] = "Username non valido";
                    } else {
                        $username = mysqli_real_escape_string($conn, $_POST['new_username']);
                        // Controllo la disponibilità dello Username
                        if(!isset($logged)){
                            $query = "SELECT id FROM `users` WHERE username='". $username."'";
                            $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                            if (mysqli_num_rows($res) > 0) {
                                $error[] = "Username già utilizzato";
                            }
                        }
                    }

                    //Controllo Password
                    if (!preg_match('/^[0-9a-zA-Z_!.-]{5,10}$/', $_POST['new_password'])) {
                        $error[] = "La password non rispetta i requisiti";
                    } 

                    // Controlla conferma PASSWORD
                    if (strcmp($_POST["new_password"], $_POST["checkPassword"]) != 0) {
                        $error[] = "Le password non coincidono";
                    }

                    // Controlla E-mail
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $error[] = "Email non valida";
                    } else {
                        $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
                        if(!isset($logged)){
                            $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
                            if (mysqli_num_rows($res) > 0) {
                                $error[] = "Email già utilizzata";
                            }
                        }
                    }

                    // Controlla Nome
                    if (!preg_match('/^[a-zA-Z ]{1,25}$/', $_POST['name'])) {
                        $error[] = "Nome non valido";
                    }

                    // Controlla Cognome
                    if (!preg_match('/^[a-zA-Z ]{1,25}$/', $_POST['surname'])) {
                        $error[] = "Cognome non valido";
                    }

                    //Controllo per inserimento utente o aggiornamento
                    if (count($error) == 0) {
                        
                        $name = mysqli_real_escape_string($conn, $_POST['name']);
                        $surname = mysqli_real_escape_string($conn, $_POST['surname']);

                        $password = mysqli_real_escape_string($conn, $_POST['new_password']);
                        
                        if (isset($logged)){
                            $userId=$_SESSION['_hw1_user_id'];
                            $query="UPDATE users SET password='$password',nome='$name',cognome='$surname',email='$email' WHERE id = '$userId'";
                        } else {
                            $query = "INSERT INTO users(username, password, nome, cognome, email) VALUES('$username', '$password', '$name', '$surname', '$email')";
                        }
                        
                        if (mysqli_query($conn, $query)) {
                            if(!isset($logged)){
                                session_start();
                                $_SESSION["_hw1_user_name"] = $_POST["new_username"];
                                $_SESSION["_hw1_user_id"] = mysqli_insert_id($conn);
                            }
                            mysqli_close($conn);
                            header("Location: index.php");
                            exit;
                        } else {
                            $error[] = "Errore di connessione al Database";
                        }
                    }
                } else if (isset($_POST["new_username"])) {
                    $error = array("Riempi tutti i campi");
                } /* parte di controllo per debug
                else{
                    echo "".$_POST["new_username"]."<br/>";
                    echo "".$_POST["new_password"]."<br/>";
                    echo "".$_POST["email"]."<br/>";
                    echo "".$_POST["name"]."<br/>";
                    echo "".$_POST["surname"]."<br/>";
                    echo "".$_POST["checkPassword"]."<br/>";
                }*/
            ?>
            <section id="content">
                <?php
                
                    if(count($error)>0){
                        echo "Sono presenti errori: <br/>";
                        $n=0;
                        foreach($error as $msg) {
                            $n++;
                            echo "nr.".$n.":".$msg."<br/>";
                        }
                    }
                    if (isset($logged)){
                        echo "<h1>Modifica Profilo</h1>";
                        echo "<h3>Qui puoi modificare il tuo profilo. Devi inserire la password corretta nella conferma</h3>";
                    } else{
                        echo "<h1>Modulo di registrazione al sito</h1>";
                        echo "<h3>Registrati per avere la possibilità di interagire con 
                        i lavori presenti sul sito. 
                        Un amministratore valuterà se il tuo profilo ha necessità 
                        di permessi aggiuntivi.</h3>";
                    }
                ?>
                
                <form name="formSignup" method='post' enctype="multipart/form-data" autocomplete="off">
                    <div id="divFormSignup">
                        <div class="new_username">
                        <label>Username: <input type="text" name="new_username"<?php if (isset($logged)){echo ' value="'.$username.'" readonly="readonly"';}?>></label>
                        <span class="hidden">Nome utente non valido o già usato.</span>
                        </div>
                        <div class="new_password">
                        <label>Password: <input type="password" name="new_password"<?php if (isset($logged)){echo ' value="'.$oldPassword.'" readonly="readonly"';}?>></label>
                        <span class="hidden">Password tra 5 e 10 caratteri.</span>
                        </div>
                        <div class="checkPassword">
                        <label>Conferma Password: <input type="password" name="checkPassword"></label>
                        <span class="hidden">Le Password non coincidono.</span>
                        </div>
                        <div class="name">
                        <label>Nome: <input type="text" name="name"<?php if (isset($logged)){echo ' value="'.$oldName.'"';}?>></label>
                        <span class="hidden">Nome non valido</span>
                        </div>
                        <div class="surname">
                        <label>Cognome: <input type="text" name="surname"<?php if (isset($logged)){echo ' value="'.$oldSurname.'"';}?>></label>
                        <span class="hidden">Cognome non valido</span>
                        </div>
                        <div class="email">
                        <label>E-mail: <input type="text" name="email"<?php if (isset($logged)){echo ' value="'.$oldEmail.'"';}?>></label>
                        <span class="hidden">E-mail non valida</span>
                        </div>
                        <input id="btnSignup" type="submit" <?php if (isset($logged)){echo ' value="Modifica"';}else{echo ' value="Registrati"';}?>>
                    </div>
                </form>
            </section>

            <?php require_once "classes/footer.php"; ?>
        </article>
    </body>
</html>