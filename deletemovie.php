<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    require_once('shared/DBClient.php');

    if(isset($_SESSION['current_user'])) {
        $my_uid = $_SESSION['current_user'];
    }else {
        header("Location: /movies");
    }

    $client = new DBClient(user: 'root', passwd:'');

    if(!$client->isAdmin($my_uid)) {
        header("Location: /movies");
    }

    if(isset($_GET['id'])) {
        $movie_id = $_GET['id'];
        unset($_GET['id']);
        
        $res = $client->deleteMovie($movie_id);

        if($res) {
            header("Location: /movies");
        }else {
            echo
            "
                <dialog open>
                    <span>Something went wrong<span>
                    <form action='/movies/' method='get' >
                        <button >OK</button>
                    </form>
                </dialog>
            ";
        }
    }
?>