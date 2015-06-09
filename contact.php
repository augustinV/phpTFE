<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Page contact, contactez-moi">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Contact | Vercammen Augustin</title>
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
function is_valid_mail($email){
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}

//traitement du formulaire

if($_POST){  

    //honeypot chech
    if($_POST['user_email'] != ''){
        die(' ne rien écrir !! ');
    }

    //sanitization

    $email = strip_tags(trim($_POST['email']));
    $message = strip_tags(trim($message));
    $name = strip_tags(trim($_POST['name']));
    $message = $_POST['message'];

    //validation
    //tableau erreur
    $errors = array();

    //si is_valid_mail($email) est vide ou invalide on retourne un message erreur
    if(is_valid_mail($email)== false){
        $errors['email'] = 'Email invalide.';
    }
    //si $message est vide on retourne un message erreur
    if($message==''){
        $errors['message'] = 'Le message est vide. Veuillez le rajouter.';
    }

    $sujet = 'Message du site gaspi-';
    //si on arrive ici, tout est en ordre

    //connection à la base de donnée
    $host = 'my host';
    $dbname = 'my dbname';
    $user = 'my user ';
    $password = 'my password';
    try{
    $connexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    }catch(PDOException $e){
    echo $e->getMessage();
    }    
    //Si l'adresse email est valide et que le message n'est pas vide, alors on envoi les infos dans la base de donnée et on envoi le mail
    if(is_valid_mail($email)!= false and $message!= ''){
        $query = "INSERT INTO formulairetfe (name, email, message) VALUE (:name, :email, :message)";
        $preparedStatement = $connexion->prepare($query);
        $preparedStatement->bindParam(":name", $name);
        $preparedStatement->bindParam(":email", $email);
        $preparedStatement->bindParam(":message", $message);
        $preparedStatement->execute();

        $result = mail('mail@augustinvercammen.be', $sujet, $message, $name.' '. $email);
        echo('<p id="envoi">Merci votre Mail est envoyé.</p>');       
    }else{ 
    //Sinon on inform que l'email n'a pas été envoyé
        echo('<p id="envoiNul">Ouch! Mail non envoyé.</p>');
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
                        <g id="partie-3" class="active">
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
                        <li><a href="contact.php" class="active">Contact</a></li>
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
            <section id="contact">
                <div class="container">
                    <h1>Contact</h1>
                    <h3>Pour réagir c'est ici</h3>
                    <p>Pour réagir sur le sujet, obtenir plus d'informations ou tout simplement en parler autour d'un café, contactez-moi via le formulaire ci-dessous.</p>
                    <form id="contact"  action="" method="POST" >
                        <label style="display:none;">Ne pas remplir : </label>
                        <input style="display:none;" type="text" value="" name="user_email" placeholder="Ne rien mettre ici" autocomplete="off"/>
                        <input name="name" type="text"  value="<?php echo $name; ?>" class="feedback-input" placeholder="Votre nom" autocomplete="off"/> 
                        <?php echo message_erreur($errors,'name')?>
                        <input name="email" type="text" value="<?php echo $email; ?>" class="feedback-input" placeholder="Votre adresse email" autocomplete="off"/>
                        <?php echo message_erreur($errors,'email')?>
                        <textarea name="message" type="text" value="<?php echo $message; ?>" class="feedback-input" placeholder="Votre commentaire"></textarea>
                        <?php echo message_erreur($errors,'message')?>
                        <input type="submit" name="envoyer" value="ENVOYER" id="submit"/>
                    </form>
                </div>
            </section>
            <footer>
                <div id="contentFooter">
                    <h6>© Augustin Vercammen 2015 - <a href="https://dwm.re/" target="_blank">DWM</a> -</h6>
                </div>
            </footer>        
        <script src="js/main.js"></script>
       
    </body>
</html>