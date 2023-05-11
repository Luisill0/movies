<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    if(isset($_SESSION['current_user'])) {header('Location: http://localhost/movies');}
    require_once("shared/DBClient.php");

    if(isset($_POST['email']) && isset($_POST['password'])) {
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];

        $client = new DBClient(user:'root', passwd:'');
        $userID = $client->getUser(email:$email, password:$password);
        echo $userID;
        if($userID) {
            $_SESSION['current_user']=$userID;
            header('Location: http://localhost/movies');
        }
    }
    unset($_POST['email']);
    unset($_POST['password']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <script src="script.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/login.css" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
    <div class="header">
        <div class="headerChild searchbar">
            <input class="search-text" type="text" placeholder="search" required/> 
            <img src="assets/search.svg" class="search-icon"/>
        </div>
        <div class="headerChild homeBtn prevent-select"
            onclick="navigate('/movies')"
        >
            Home
        </div>
    </div>
    <div class="content">
        <div class="sign-in prevent-select">
            <span>Sign In</span>
        </div>
        <form class="credentials-form" action='login.php' method='post'>
            <div class="email input-field">
                <label for="email" class="prevent-select">email:</label>
                <input type="email" id="email" name="email"
                required
                >
            </div>
            <div class="password input-field">
                <label for="password" class="prevent-select">Password:</label>
                <input type="password" id="password" name="password"
                required
                >
            </div>
            <input class="sign-in-btn" type="submit" value="Sign In">
        </form>
    </div>
</body>
</html>