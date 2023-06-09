<?php
    if(session_status() == PHP_SESSION_NONE) {session_start();}
    if(!isset($_SESSION['current_user'])) {header('Location: http://localhost/movies');}
    require_once("shared/DBClient.php");
        
    $uid = $_SESSION['current_user'];
    $client = new DBClient(user:'root',passwd:'');
    $row = $client->getUserInfo($uid);
    if($row) {
        $profile_username = $row[0];
        $profile_email = $row[1];
        $profile_about = $row[2];
        $profile_photo = $row[3];
        $profile_favorite1 = $row[4];
        $profile_favorite2 = $row[5];
        $profile_favorite3 = $row[6];
    }
    else {
        header('Location: http://localhost/movies/home');
    }

    if(
        isset($_POST['email']) || isset($_POST['about']) || isset($_POST['imageURL']) ||
        isset($_POST['favorite1']) || isset($_POST['favorite2']) || isset($_POST['favorite3'])
    ) {
        $email = isset($_POST['email']) ? strtolower($_POST['email']) : null;
        $about = isset($_POST['about']) ? $_POST['about'] : null;
        $favorite1 = isset($_POST['favorite1']) ? $_POST['favorite1'] : null;
        $favorite2 = isset($_POST['favorite2']) ? $_POST['favorite2'] : null;
        $favorite3 = isset($_POST['favorite3']) ? $_POST['favorite3'] : null;
        
        $photo = isset($_POST['imageURL']) ? $_POST['imageURL'] : null;

        unset($_POST['email']);
        unset($_POST['about']);
        unset($_POST['favorite1']);
        unset($_POST['favorite2']);
        unset($_POST['favorite3']);
        unset($_POST['photo']);

        $client = new DBClient(user:'root', passwd:'');
        try{
            $result = $client->updateUserInfo(
                $uid, $email, $about, $favorite1, $favorite2, $favorite3, $photo
            );
        }catch(Exception $ex){
            print_r($ex);
        }
        if($result){
            header('Location: http://localhost/movies/profile.php');
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <script src='shared/script.js' type='text/javascript'></script>
    <script src='scripts/editprofile.js' type='text/javascript'></script>
    <link rel='stylesheet' href='shared/style.css' type='text/css'/>
    <link rel='stylesheet' href='styles/editprofile.css' type='text/css'/>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'/>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ' crossorigin='anonymous'>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe' crossorigin='anonymous'></script>
</head>
<body>
    <div class='header'>
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
        <div class='headerChild homeBtn prevent-select'
            onclick='navigate("/movies")'
        >
            Home
        </div>
    </div>
    <div class='container-sm-fluid m-0 pt-2 px-3' id='edit-profile-content'>
        <div class='row mb-4 px-2'>
            <span class='fs-1 fw-bold darkblue prevent-select' id='section-title'>
                Edit profile
            </span>
        </div>
        <form action='editprofile.php' method='post'>
            <div class='container-fluid m-0 p-0'>
                <div class='row'>
                    <div class='col-sm-8'>
                        <div class='container-fluid mb-3'>
                            <label for='email' class='form-label prevent-select darkblue'>Email:</label>
                            <input 
                                class='form-control text-box no-focus'
                                type='email' id='email' name='email'
                                <?php
                                    echo 'placeholder='.$profile_email;
                                ?>
                            />
                        </div>
                        <div class='container-fluid mb-3'>
                            <label for='about' class='form-label prevent-select darkblue'>About:</label>
                            <textarea 
                                class='form-control text-box no-focus'
                                id='about'
                                name='about'
                                <?php
                                    echo 'placeholder='.str_replace(' ','-',$profile_about);
                                ?>
                            ></textarea>
                        </div>
                        <div class='container-fluid mb-3'>
                            <span class='prevent-select darkblue'>Favorite movies:</span>
                            <div class='row'>
                                <div class='col-sm-4'>
                                    <input class='form-control text-box no-focus'
                                        name='favorite1'
                                        type='text' 
                                        <?php
                                            echo 'placeholder='.str_replace(' ','-',$profile_favorite1);
                                        ?>
                                    />
                                </div>
                                <div class='col-sm-4'>
                                    <input class='form-control text-box no-focus'
                                        name='favorite2'
                                        type='text'
                                        <?php
                                            echo 'placeholder='.str_replace(' ','-',$profile_favorite2);
                                        ?>
                                    />
                                </div>
                                <div class='col-sm-4'>
                                    <input class='form-control text-box no-focus'
                                        name='favorite3'
                                        type='text'
                                        <?php
                                            echo 'placeholder='.str_replace(' ','-',$profile_favorite3);
                                        ?>
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='col-sm-4'>
                        <div class='container-fluid mb-3 p-0 prevent-select' id='img-form'>
                            <label for='img-container' class='form-label prevent-select'>Photo:</label>
                            <div class='container-fluid p-0' id='img-container'>
                                <label 
                                    class='d-flex justify-content-center align-items-center fs-1' 
                                    id='add-img-label' for='add-image'
                                >
                                    <?php
                                        if($profile_photo){
                                            echo "<img id='pfp' draggable='false' src='$profile_photo' />";
                                        }else {
                                            echo "+";
                                        }
                                    ?>
                                </label>
                                <input 
                                    type='file' id='add-image'
                                    accept='image/jpeg image/png image/jpg'
                                    name='photo'
                                    onchange='previewImg()'
                                />
                            </div>
                        </div>
                    </div>
                    <input type='hidden' name='imageURL' id='image-url' />
                    <div class='row'>
                        <div class='col-auto ps-4'>
                            <button type="submit" class="btn mb-2 px-3 py-2" id='btn-submit'>
                                Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>    
</html>