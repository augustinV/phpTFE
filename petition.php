<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Page pétition, permette de signer la pétition contre le gaspillage alimentaire.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pétition | Vercammen Augustin</title>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/style.css">
        <link rel="author" href="humans.txt">
        <script src="js/jquery-2.0.2.min.js"></script>
    </head>
    <body>

<?php error_reporting(E_ALL);?>

<?php
//fonciton message_erreur
function message_erreur($erreurs, $input){
    if($_POST){
        if ($erreurs[$input] != ''){
            return '<p class="error_message">'.$erreurs[$input].'</p>';
            }
        }
    }
?>

<?php
// fonciton qui vérifie si la variable $email a une adresse email valide
function is_valid_mail($mail){
    return filter_var(trim($mail), FILTER_VALIDATE_EMAIL);
}

//traitement du formulaire

if($_POST){  

    //honeypot chech
    if($_POST['user_email'] != ''){
        die(' ne rien écrir !! ');
    }

    //sanitization

    $mail = strip_tags(trim($_POST['mail']));
    $comment = strip_tags(trim($comment));
    $lastname = strip_tags(trim($_POST['lastname']));
    $firstname = strip_tags(trim($_POST['firstname']));
    $comment = $_POST['comment'];

    //validation
    //tableau erreur
    $errors = array();
    //si is_valid_mail($email) est vide ou invalide on retourne un message erreur
    if(is_valid_mail($mail)== false){
        $errors['mail'] = '* Email invalide.';
    }
    //si $lastname est vide on retourne un message erreur
    if($lastname==''){
        $errors['lastname'] = '* Veuillez rajouter votre Nom.';
    }
    //si $firstname est vide on retourne un message erreur
    if($firstname==''){
        $errors['firstname'] = '* Veuillez rajouter votre Nom.';
    }

    $sujet = 'Pétition du site gaspi-';
    //si on arrive ici, tout est en ordre
    //connection à la base de donnée
    $host = 'my host';
    $dbname = 'my dbname ';
    $user = 'my user ';
    $password = 'my password';
    try{
    $connexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    }catch(PDOException $e){
    echo $e->getMessage();
    }
    //on vérifie que l'adresse email n'est pas encore repris dans la base de donnée
    if (isset($_POST['mail'])) {
        $sql = $connexion->prepare('SELECT mail FROM signaturetfe WHERE mail = \''.$mail.'\';');
        $sql->execute(array('.$mail.' => $_POST['mail']));    
        $res = $sql->fetch();
    
        if ($res) {
            $ok = true;
        }else{ 
            $ok = false;
        }
    }
    //on reprend le nombre d'id déjà repris dans la base de donnée pour déterminer le nombre total
    $requete = $connexion->query ('SELECT COUNT(id) as countid FROM signaturetfe');      
    $nbligne = $requete->fetch();
    $signature = '300';

    //Si l'adresse email est valide et que $ok est différent de true et que $lastname,$firstname ne sont pas vide, alors on envoi les infos dans la base de donnée et on envoi le mail
    if(is_valid_mail($mail)!= false and $ok!=true and $lastname!=''and $firstname!=''){

        $query = "INSERT INTO signaturetfe (lastname, firstname, mail, comment) VALUE ( :lastname, :firstname, :mail, :comment)";
        $preparedStatement = $connexion->prepare($query);
        $preparedStatement->bindParam(":mail", $mail);
        $preparedStatement->bindParam(":comment", $comment);
        $preparedStatement->bindParam(":lastname", $lastname);
        $preparedStatement->bindParam(":firstname", $firstname);
        $preparedStatement->execute();

        $result = mail('mail@augustinvercammen.be', $sujet, $comment, $lastname.' '.$firstname.' '.$mail);
        //on reprend le nom et prénom de la personne et on fait le décompte du nombre de signatures restant
        echo('<p id="envoi">Merci'.' '.$lastname.' '.$firstname.' '.'d\'avoir signé :) Déjà ' . $nbligne['countid'] . ' signatures, plus que '. ($signature - $nbligne['countid']).' '.'signatures pour envoyer la pétition. <a href="index2.html">Retour à l\'accueil.</a></p>');
    }else{
        //si il a déjà signé (si l'adresse email est déjà reprise dans la base de donnée)
        if ($ok == true) {
            echo('<p id="envoiNul">Votre avez déjà signer.</p>');
        }else{ 
            //on l'infomre que il y a une erreure et donc que la signature n'à pas été prise en compte
        echo('<p id="envoiNul">Votre signature n\'a pas été prise en compte.</p>');
        }
    }
}
?>

        <header>
            <div class="container">
                <nav>
                    <h1 id="logo">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="91.72px"
                         height="100px" viewBox="0 0 91.72 100" enable-background="new 0 0 91.72 100" xml:space="preserve">
                        <g id="partie-4">
                        </g>
                        <g id="partie-3">
                            <path opacity="0.3" fill="#C6C6C6" d="M67.566,69.129c-3.438,2.784-7.814,4.455-12.584,4.455c-2.006,0-3.941-0.298-5.768-0.847
                                l-5.429,17.555c3.499,1.088,7.22,1.673,11.077,1.673c9.407,0,17.999-3.485,24.56-9.231L67.566,69.129z"/>
                        </g>
                        <g id="partie-2">
                            <path opacity="0.3" fill="#C6C6C6" d="M35.738,59.054l-22.424,4.904c3.316,14.92,14.463,26.893,28.906,31.379l6.996-22.6
                                C42.708,70.781,37.6,65.602,35.738,59.054z"/>
                        </g>
                        <g id="partie-1">
                            <path opacity="0.3" fill="#C6C6C6" d="M34.973,53.575c0-10.995,8.87-19.917,19.845-20.007L54.804,6.09C27.997,6.122,6.274,27.864,6.274,54.678
                                c0,3.631,0.401,7.168,1.156,10.571l28.306-6.204C35.243,57.306,34.973,55.473,34.973,53.575z"/>
                        </g>
                        </svg>
                    </h1>
                    <ul>
                        <li><a href="index.html">Accueil</a></li>
                        <li><a href="apropos.html">À propos</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li></li>
                    </ul>
                </nav>
            </div>
            <div id="menuHam" title="menu">
              <div id="bar1"></div>
              <div id="bar2"></div>
              <div id="bar3"></div>
            </div>
        </header>
            <section id="petition">
                <div class="container">
                    <h1>Signez la pétition</h1>
                    <h3>Signer c'est déjà lutter contre le gaspillage alimentaire</h3>
                    <img src="img/lutter.png" alt="lutter">
                    <p>Une fois le quota de 300 signatures atteint, la pétition ainsi que toutes vos signatures, seront envoyés au Ministère concerné dans le but de montrer notre intéret face à cette problématique ainsi que notre engagement face à celle-ci.</p>
                    <form id="petition"  action="" method="POST" >
                        <label style="display:none;">Ne pas remplir : </label>
                        <input style="display:none;" type="text" value="" name="user_email" placeholder="Ne rien mettre ici" autocomplete="off"/>
                        <input name="lastname" type="text"  value="<?php echo $lastname; ?>" class="feedback-input" placeholder="Votre nom" autocomplete="off" /> 
                        <?php echo message_erreur($errors,'lastname')?>
                        <input name="firstname" type="text"  value="<?php echo $firstname; ?>" class="feedback-input" placeholder="Votre prénom" autocomplete="off"/> 
                        <?php echo message_erreur($errors,'firstname')?>
                        <input name="mail" type="text" value="<?php echo $mail; ?>" class="feedback-input" placeholder="Votre adresse email" autocomplete="off"/>
                        <?php echo message_erreur($errors,'mail')?>
                        <textarea name="comment" type="text" value="<?php echo $comment; ?>" class="feedback-input" placeholder="Pourquoi est-ce important pour vous de lutter contre le gaspillage alimentaire ? (facultatif)"></textarea>
                        <input type="submit" name="envoyer" value="SIGNER" id="submit"/>
                    </form>
                </div>
            </section>
            <footer class="second">
                <div id="contentFooter">
                    <h6>© Augustin Vercammen 2015 - <a href="https://dwm.re/" target="_blank">DWM</a> -</h6>
                </div>
            </footer>        
        <script src="js/main.js"></script>
       
    </body>
</html>