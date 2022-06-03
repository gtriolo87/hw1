<?php 
    require_once "checkPermission.php";
    require_once "classes/dbconfig.php";

    session_start();
    if (isset($_SESSION['_hw1_user_id'])){
        echo "loggeD?";
        $logged=1;
    } else {
        if ( isset($_POST['username']) && isset($_POST['password']) ) {
            $conn=mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']) or die(mysqli_error($conn));
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $query = "SELECT id, username, password FROM users WHERE username = '$username' AND password='$password'";
            $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
            if (mysqli_num_rows($res) > 0) {
                $entry = mysqli_fetch_assoc($res);
                $_SESSION["_hw1_user_id"] = $entry['id'];
                $_SESSION["_hw1_user_name"] = $entry['username'];
                $logged=1;
            } else {
                $error = 1;
            }
            mysqli_free_result($res);
            mysqli_close($conn);
        }
        else if(isset($_POST['username']) || isset($_POST['password'])){
            $error=1;
        }
    }
    
?>

<script src="scripts/header.js" defer></script>

<header>
    <nav>
        <div id="menu">
            <a class="menu-item" href="index.php">Home</a>
            <a class="menu-item" id="btnLogin">Login</a>
            <!-- RIMUOVO PER ASSENZA FUNZIONALITA' <a class="menu-item" id="btnSearch">Search</a> -->
        </div>
        <div id="loginWindow" <?php if(!isset($error)){echo 'class="hidden"';}?>>
            <div id="divFormLogin" <?php if(isset($logged)){echo 'class="hidden"';}?>>
                <form name="formLogin" method="post">
                    <label>Nome Utente: <input type="text" name="username"></label>
                    <label>Password: <input type="password" name="password"></label>
                    <label>
                        <?php 
                            if(isset($error)){
                                echo "<span>Dati Errati</span>";
                            }
                        ?>
                        <input id="btnSignin" type="submit" value="Accedi">
                    </label>
                </form>
                <div class="signup">Non hai un account? <a href="account.php">Iscriviti</a></div>
            </div>
            <div id="divLogged" <?php if(!isset($logged)){echo 'class="hidden"';}else{echo 'data-user-id="'.$_SESSION["_hw1_user_id"].'"';}?>>
                <div>
                    <span>Benvenuto <?php if(isset($logged)){echo $_SESSION["_hw1_user_name"];}?>!</span>
                    <a href="account.php">Profilo</a>
                    <?php 
                        if(isset($logged)){
                            if(checkAdminPermissions($_SESSION["_hw1_user_id"])){
                                echo '<a href="administration.php">Amministrazione</a>';
                            }
                            if(checkJobPermissions($_SESSION["_hw1_user_id"])){
                                echo '<a href="job.php">Nuovo Job</a>';
                            }
                        }
                    ?>
                    <a href="logout.php">LogOut</a>
                </div>
            </div>
        </div>
    </nav>
    <div id="title">
        <h1>BitControl: Gestione lavori</h1>
    </div>
</header>