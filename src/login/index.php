<?php 
    session_start();

    require '../php/config.php';

    $pdo = new PDO($pdoDatabase, $pdoUsername, $pdoPassword);
    
    if(isset($_GET['login'])) {

        $ip = $_SERVER["REMOTE_ADDR"];

        $statement = $pdo->prepare("SELECT COUNT(*) AS 'Count' FROM loginAttempts WHERE ip LIKE :ip AND `created_at` > (now() - interval 5 minute)");
        $result = $statement->execute(array('ip' => $ip));
        $loginAttempts = $statement->fetch();

        if($loginAttempts['Count'] < 5){

            $statement = $pdo->prepare("INSERT INTO loginAttempts(ip) VALUES (:ip)");
            $result = $statement->execute(array('ip' => $ip));

            $username = $_POST['username'];
            $passwort = $_POST['pwd'];
        
            $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $result = $statement->execute(array('username' => $username));
            $user = $statement->fetch();
            
            /* Checking for password */
            if ($user !== false && password_verify($passwort, $user['password'])) {

                /*
                 *
                 *  If the login was successful, the Id of the user is saved in the session and the user will be redirected  
                 *
                 */
                $_SESSION['userid'] = $user['id'];
                header("Location: ../yourLocation");

            } else {

                $error = true;

            }

        } else {

            $statement = $pdo->prepare("SELECT `created_at`FROM loginAttempts WHERE ip LIKE :ip AND `created_at` > (now() - interval 5 minute) LIMIT 1");
            $result = $statement->execute(array('ip' => $ip));
            $timestampFirstAttempt = $statement->fetch();

            $firstAttemptTime = strtotime($timestampFirstAttempt[0]);

            /* This was created because my vServer had a offset of 2 hours and 5 minutes */
            $firstAttemptTime = date('H:i', strtotime('+2 hour +5 minutes', $firstAttemptTime));

            $attemptError = true;

        }
        
    }
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>

        <link rel="stylesheet" href="../style/style.css">
 
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    </head>
    <body style="background-color: rgb(237, 237, 237);">
        <div class="container-fluid">
            <div class="row" style="margin-top: 3vh;">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="margin: 1vh;">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <a href="../was_ist_bodycount/"><img src="../images/q&a_thumbnail.png" class="d-block w-100" style="max-height: 20vh; border-radius: 5px;"></a>
                            </div>
                            <div class="carousel-item">
                                <img src="../images/closedAlpha_thumbnail.png" class="d-block w-100" style="max-height: 20vh; border-radius: 5px;">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="nonClickableContainer">
                        <form action="?login=1" method="post" style="margin-top: -2vh;">
                            <?php
                                
                                /* This is the error message if the user needed to much login attempts */
                                if($attemptError == true){
                                    ?>
                                        <div class="alert alert-danger alert-dismissible" style="margin-top: 1vh;">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                            Limit der Versuche überschritten! Versuche es um <?php echo $firstAttemptTime; ?> nocheinmal.
                                        </div>
                                    <?php

                                /* This is the error message if username and password did not match with the entries in the Database */
                                } else if($error == true){
                                    ?>
                                        <div class="alert alert-danger alert-dismissible" style="margin-top: 1vh;">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                            Benutzername oder Passwort ist falsch.
                                        </div>
                                    <?php
                                }
                            ?>
                            <div class="mb-3 mt-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="username" placeholder="Benutzername" name="username">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="pwd" placeholder="Passwort" name="pwd">
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary" style="width: 100%;" value="Anmelden">
                        </form>
                    </div>
                    <p style="text-align: center; font-size: 12px; margin: 1vh;">ODER</p>
                    <div class="nonClickableContainer">
                        <button class="btn btn-success" style="width: 100%;" onclick="window.location.href = '../createAccount'">Account erstellen</button>
                    </div>
                </div>
                <div class="col-sm-4"></div>
            </div>
        </div>
    </body>
</html>