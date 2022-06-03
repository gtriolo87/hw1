

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>WebProgramming - Homework 1</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@500&family=Kanit&family=Righteous&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/administration.css" />
        <script src="scripts/administration.js" defer></script> 
</head>



    <body>

        <article>

            <?php
                require_once "classes/header.php";
            ?>
    

            

            <section id="content">

                <?php

                    if (!isset($logged)){
                        header("Location: index.php");
                    } else {
                        $userId=$_SESSION["_hw1_user_id"];
                        $canManageUsers=checkAdminPermissions($userId);
                        if(!$canManageUsers){
                           header("Location: index.php");
                        }
                    }
                ?>
                
                <h1> Gestione Utenti </h1>
                <?php
                    if(!$canManageUsers){
                        echo "<h3>Non hai i permessi per gestire gli utenti!</h3>";
                    }
                ?>
                <div id="divRicerca" <?php
                    if(!$canManageUsers){echo 'class="hidden"';}?>>
                    <form name="formSearch" id="formSearch">
                        <select name="searchProfile">
                            <option value="0">Tutti</option>
                            <option value="1">Visitatore</option>
                            <option value="2">Amministratore</option>
                            <option value="3">Manager</option>
                            <option value="4">Operatore</option>
                        </select>
                        <input type="submit" value="Ricerca">
                    </form>
                    <span>Puoi ricercare solo gli utenti di un particolare profilo.</span>
                </div>
                
                <table <?php
                    if(!$canManageUsers){echo 'class="hidden"';}?>>
                    <thead>
                        <tr>
                            <th class="colUserId">ID</th>
                            <th class="colUsername">Nome Utente</th>
                            <th class="colName">Nome</th>
                            <th class="colSurname">Cognome</th>
                            <th class="colEmail">E-mail</th>
                            <th class="colProfile">Profilo</th>
                            <th class="colEdit">Salva Modifiche</th>
                        </tr>
                    </thead>
                    
                    <!-- il tbody viene riempito dal js che fa chiamate asincrone al db
                    il js chiamerà l'API Rest interna "retriveUser" che mi restituisce l'elenco utenti
                    e ciclandolo lo stampa uno per riga -->
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td class="colUserId">ID</td>
                            <td class="colUsername">Nome Utente</td>
                            <td class="colName">Nome</td>
                            <td class="colSurname">Cognome</td>
                            <td class="colEmail">E-mail</td>
                            <td class="colProfile">Profilo</td>
                            <td class="colEdit">Salva Modifiche</td>
                        </tr>
                    </tfoot>
                </table>
                <span id="esitoModifica" class="hidden"></span>


            </section>



            <?php require_once "classes/footer.php"; ?>

        </article>

    </body>

</html>