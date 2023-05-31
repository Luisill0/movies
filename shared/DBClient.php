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

    public function getUserName(int $user_id):string | null {
        $link = $this->connect('users');
        $query = $link->prepare(
            "SELECT username FROM users WHERE user_id=$user_id"
        );

        if($query->execute()) {
            $data = $query->fetch(PDO::FETCH_NUM);
            if($data){
                return $data[0];
            }
        }
        return null;
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
        string $year,
        string $plot, 
        string $poster
    ):bool {
        $link = $this->connect('users');

        $query = $link->prepare(
            "INSERT INTO movies (title, year, plot, poster) VALUES ('$title', '$year', '$plot', '$poster')"
        );

        return $query->execute() ? true : false;
    }

    public function getMovieById(string $id) {
        $link = $this->connect('users');

        $query = $link->prepare(
            "SELECT * FROM movies WHERE movie_id=$id"
        );

        if($query->execute()){
            $row = $query->fetchAll(PDO::FETCH_NAMED)[0];
            if($row){
                return $row;
            }
        }
        return null;
    }

    public function getMovieByName(string $name) {
        $link = $this->connect('users');

        $query = $link->prepare(
            "SELECT * FROM movies WHERE title='$name'"
        );

        if($query->execute()){
            $row = $query->fetchAll(PDO::FETCH_NAMED)[0];
            if($row){
                return $row;
            }
        }
        return null;
    }

    public function getMoviePosterByName(string $name) {
        $link = $this->connect('users');
        
        $query = $link->prepare(
            "SELECT poster FROM movies WHERE title='$name'"
        );

        if($query->execute()){
            return $query->fetch(PDO::FETCH_NUM)[0];
        }
    }

    public function getPopularMovies() {
        $link = $this->connect('users');

        $query = $link->prepare(
            "
            SELECT m.movie_id, m.title, m.year, m.poster, COUNT(r.movie_id) AS review_count
            FROM movies m
            LEFT JOIN reviews r ON m.movie_id = r.movie_id
            GROUP BY m.movie_id, m.title
            ORDER BY review_count DESC
            LIMIT 4;
            "
        );

        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_NAMED);
            return $data ? $data : null;
        }else {
            return null;
        }
    }

    public function getAllMovies() {
        $link = $this->connect('users');
        $query = $link->prepare(
            "SELECT * FROM movies"
        );

        if($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_NAMED);
            return $data ?? null;
        }
    }

    public function deleteMovie(int $movie_id) {
        $link = $this->connect('users');

        $query = $link->prepare(
            "DELETE FROM movies WHERE movie_id=$movie_id"
        );

        return $query->execute();
    }

    public function editMovie(
        int $movie_id,
        string $title,
        string $year,
        string $plot,
        string $poster
    ):bool {
        $link = $this->connect('users');

        $query = $link->prepare(
            "UPDATE movies
            SET title='$title', year='$year', plot='$plot', poster='$poster'
            WHERE movie_id=$movie_id
            "
        );

        return $query->execute();
    }

    /***********************************************
    *                REVIEWS FUNCS                 *
    ***********************************************/

    public function reviewMovie(
        int $user_id,
        int $movie_id,
        string $rating,
        string $review,
        mixed $date
    ) {
        $link = $this->connect('users');

        $query = $link->prepare(
            "
            INSERT INTO reviews 
            (user_id, movie_id, rating, review, date) VALUES
            ($user_id, $movie_id, '$rating', '$review', '$date')
            "
        );

        return $query->execute() ? true : false;
    }

    public function getReviews(int $movie_id) {
        $link = $this->connect('users');

        $query = $link->prepare(
            "SELECT * FROM reviews WHERE movie_id=$movie_id"
        );

        if($query->execute()){
            $data = $query->fetchAll(PDO::FETCH_NAMED);
            if($data){
                return $data;
            }
        }
        return null;
    }

    public function hasReviewed(int $user_id, int $movie_id) {
        $link = $this->connect('users');

        $query = $link->prepare(
            "
            SELECT COUNT(*) FROM reviews WHERE user_id=$user_id AND
            movie_id=$movie_id
            "
        );

        if($query->execute()){
            $data = $query->fetch(PDO::FETCH_NUM)[0];
            return $data > 0;
        }
    }
}
?>