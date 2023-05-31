<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION['current_user'])) {
        unset($_SESSION['current_user']);
        header('Location: http://localhost/movies');
    }
?>