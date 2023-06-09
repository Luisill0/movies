<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    if(isset($_SESSION['current_user'])) {header('Location: http://localhost/movies');}
    require_once("shared/DBClient.php");

    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = strtolower($_POST['username']);
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];

        unset($_POST['username']);
        unset($_POST['email']);
        unset($_POST['password']);

        $client = new DBClient(user:'root', passwd:'');
        $result = $client->addUser(username:$username,email:$email,password:$password);
        if($result){
            $_SESSION['current_user']=$result;
            header('Location: /movies/');
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src="shared/script.js" type="text/javascript"></script>
    <script src="scripts/signup.js" type="text/javascript"></script>
    <link rel="stylesheet" href="shared/style.css" type="text/css"/>
    <link rel="stylesheet" href="styles/signup.css" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body onload="checkPassword()">
    <div class="header">
        <div class="headerChild searchbar">
            <form id='search-form' action='/movies/search.php' method='get'>
                <input class="search-text" id='search-bar-input' 
                    type="text" placeholder="search" name='title'
                    required
                /> 
                <img src="assets/search.svg" draggable="false" 
                    class="search-icon prevent-select"
                    onclick="search()"
                />
            </form>
        </div>
        <div class="headerChild homeBtn prevent-select"
            onclick="navigate('/home')"
        >
            Home
        </div>
    </div>
    <div class="content">
        <div class="sign-up prevent-select">
            <span>Sign Up</span>
        </div>
        <form class="credentials-form" action="signup.php" method="post">
            <div class="username input-field">
                <label for="username" class="prevent-select">Username:</label>
                <input type="text" id="username" name="username"
                pattern="[a-zA-Z0-9]{1,50}"
                title="Only numbers and letters allowed"
                required
                >
            </div>
            <div class="email input-field">
                <label for="email" class="prevent-select">email:</label>
                <input type="email" id="email" name="email"
                required
                >
            </div>
            <div class="password input-field">
                <label for="password" class="prevent-select">Password:</label>
                <input type="password" id="password" name="password"
                pattern="^.*(?=.{8,50})(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$^&*]).*$"
                required
                >
            </div>
            <div class="password-info">
                <span>Requirements:</span>
                <span id="8chars"><span class="red">&#x2718</span>Min. 8 characters</span>
                <span id="upper"><span class="red">&#x2718</span>1 uppercase letter</span>
                <span id="number"><span class="red">&#x2718</span>1 number</span>
                <span id="specialchar"><span class="red">&#x2718</span>1 special character (!@#$^&*)</span>
            </div>
            <input class="sign-up-btn" type="submit" value="Sign Up">
        </form>
    </div>
</body>
</html>