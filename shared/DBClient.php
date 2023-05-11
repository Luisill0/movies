<?php
class DBClient {
    private string $host;
    private string $user;
    private string $passwd;
    private string $charset;
     
    public function __construct(
        string $user,
        string $passwd,
        string $host="localhost",
        string $charset="utf8"
    ) {
        $this->host = $host;
        $this->user = $user;
        $this->passwd = $passwd;
        $this->charset = $charset;
    }

    private function connect(string $db): PDO {
        $com = "mysql:host=".$this->host.";dbname=".$db.";charset=".$this->charset;
        $link = new PDO($com, $this->user, $this->passwd);
        return $link;
    }

    /***********************************************
    *               USERS FUNCS                    *
    ***********************************************/

    public function addUser(
        string $username,
        string $email,
        string $password
    ) {
        $link = $this->connect('users');
        $query = $link->prepare(
            "INSERT INTO users (username, email, password) VALUES ('$username','$email','$password')"
        );
        if($query->execute()){
            return $link->lastInsertId();
        }else {
            return null;
        }
    }

    public function getUser(
        string $email,
        string $password
    ) {
        $link = $this->connect('users');
        $query = $link->prepare(
            "SELECT user_id FROM users WHERE email='$email' AND password='$password'"
        );
        if($query->execute()) {
            $row = $query->fetch(PDO::FETCH_NUM);
            if($row) {
                return $row[0];
            }else {
                echo "<script>alert('incorrect information')</script>";
            }
        }else {
            echo "<script>alert('incorrect information')</script>";
            return null;
        }
    }

    public function getUserInfo(
        int $user_id
    ) {
        $link = $this->connect('users');
        $selected = 'username, email, about, photo, favorite1, favorite2, favorite3';
        $query = $link->prepare(
            "SELECT ".$selected." FROM users WHERE user_id=$user_id"
        );
        if($query->execute()) {
            $row = $query->fetch(PDO::FETCH_NUM);
            if($row) {
                return $row;
            }else {
                return null;
            }
        }else {
            return null;
        }
    }

    public function isAdmin(int $user_id):bool {
        $link = $this->connect('users');
        $query = $link->prepare("SELECT admin FROM users WHERE user_id=$user_id");
        
        if($query->execute()){
            $row = $query->fetch(PDO::FETCH_NUM);
            if($row){
                return $row[0] == 1;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function updateUserInfo(
        int $user_id,
        string $email=null,
        string $about=null,
        string $favorite1=null,
        string $favorite2=null,
        string $favorite3=null,
        string $photo=null
    ) {
        $query_set = '';
        if($email) {
            $query_set = $query_set."email='$email'";
        }
        if($about) {
            $comma = $email ? ' , ' : '';
            $query_set = $query_set.$comma."about='$about'";
        }
        if($favorite1) {
            $comma = $email || $about ? ' , ': '';
            $query_set = $query_set.$comma."favorite1='$favorite1'";
        }
        if($favorite2) {
            $comma = $email || $about || $favorite1 ? ' , ': '';
            $query_set = $query_set.$comma."favorite2='$favorite2'";
        }
        if($favorite3) {
            $comma = $email || $about || $favorite1 || $favorite2 ? ' , ': '';
            $query_set = $query_set.$comma."favorite3='$favorite3'";
        }
        if($photo) {
            $comma = $email || $about || $favorite1 || $favorite2 || $favorite3 ? ' , ' : '';
            $query_set = $query_set.$comma."photo='$photo'";
        }

        $link = $this->connect('users');
        
        echo "query: ".$query_set;

        $query = $link->prepare(
            'UPDATE users SET '.$query_set.'WHERE user_id='.$user_id
        );

        if($query->execute()){
            return true;
        }
        else{
            print_r($link->errorInfo());
            return false;
        }
    }

    /***********************************************
    *                MOVIES FUNCS                  *
    ***********************************************/

    public function addMovie(
        string $title, 
        string $plot, 
        string $poster
    ):bool {
        $link = $this->connect('users');

        $query = $link->prepare(
            "INSERT INTO movies (title, plot, poster) VALUES ('$title', '$plot', '$poster')"
        );

        return $query->execute() ? true : false;
    }

    public function getMoviePosterByName(string $title) {
        $link = $this->connect('users');

        $query = $link->prepare(
            "SELECT poster from movies WHERE title='$title'"
        );

        if($query->execute()){
            $row = $query->fetch(PDO::FETCH_NUM);
            if($row) {
                return $row[0];
            }
            else {
                return null;
            }
        }else {
            return null;
        }
    }
}

?>