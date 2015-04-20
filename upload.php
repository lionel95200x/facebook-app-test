<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
	
	session_start();
	
	require('facebook_api_4.4.0/autoload.php');
	
	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookSDKException;
	use Facebook\GraphUser;
	use Facebook\FacebookRequest;
	use Facebook\FacebookRequestException;
	
	require('constant.php');
	
        FacebookSession::setDefaultApplication(APPID, APPSECRET);

	$helper = new FacebookRedirectLoginHelper(REDIRECT_URL);
	$album_name = 'concours';
        $album_description = 'Album crée via l\'app Facebook';
 
	
	if(isset($_SESSION) && isset($_SESSION['fb_token'])){
		$session = new FacebookSession($_SESSION['fb_token']);
	}else{
		$session = $helper->getSessionFromRedirect();
	}

	
	if($session){
		try{
			$token = (string) $session->getAccessToken();
			$_SESSION['fb_token'] = $token;
                        
			$user_profile = (new FacebookRequest(
				$session, 'GET', '/me'
			))->execute()->getGraphObject("Facebook\GraphUser");
                        
                        $prenom = $user_profile->getFirstName();
			$nom = $user_profile->getLastName();
			$id = $user_profile->getId();
                        
                        $user_albums = (new FacebookRequest(
				$session, 'GET', '/me/albums'
			))->execute()->getGraphObject();
                        $album =  $user_albums->getProperty('data');

                        $album_data = $album->asArray();//this will do all job for you..
                        
                        if ($user_albums) {
                            foreach($album_data as $row){
                              if ($row->name == $album_name) {
                                $album_id = $row->id;
                                break;
                              }
                              else {
                                $album_id = 'blank';
                              }
                            }
                          }
                              if ($album_id == 'blank') {       
                                    $album_data = array(
                                        'name' => $album_name,
                                        'message' => $album_description,
                                    );
                                    $new_album = (new FacebookRequest(
                                        $session, 'POST', '/me/albums', $album_data
                                        ))->execute()->getGraphObject("Facebook\GraphUser");
                                        $album_id = $new_album->getId();
                              }
		}catch(FacebookRequestException $e){
			echo 'erreur';
		}
              
	}else{
		$loginUrl = $helper->getLoginUrl(['user_photos']);
		$message = 'Not connected. Please <a href="'.$loginUrl.'">Connect</a>';
		//echo 'Not connected. Please <a href="'.$loginUrl.'">Connect</a>';
	}
        
        
        

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Facebook App</title>
		<meta name="description" content="App Facebook">
		<link rel="stylesheet" href="./css/bootstrap.min.css">
	</head>
	<body>
		                
                <div class="container">
				
				
                <div class="starter-template jumbotron">
                    <h1>Récupération des informations</h1>
                    <p class="lead">
                    <?php if(!$session) { 
                        echo $message;
                    } else { ?>
                        Nom : <?php echo $nom; ?> <br />
                        Pr&eacute;nom : <?php echo $prenom; ?> <br />
                        ID Facebook : <?php echo $id; ?>
                    <?php } ?>
                        </p>
                </div>
                    
                <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">
                <fieldset>

                <!-- Form Name -->
                <legend>Upload de photos</legend>

                <!-- File Button --> 
                <div class="form-group">
                  <label class="col-md-4 control-label" for="filebutton">File Button</label>
                  <div class="col-md-4">
                    <input id="filebutton" name="filebutton" class="input-file" type="file">
                  </div>
					<div class="col-md-4">
						<button id="submit" type="submit" class="btn btn-primary">Upload</button>
					</div>
                </div>


                </fieldset>
                </form>
     

              </div><!-- /.container -->
                
        <script src="./js/jquery-1.11.2.min.js"></script>      
        <script src="./js/bootstrap.min.js"></script>     
        <script>
			  window.fbAsyncInit = function() {
			    FB.init({
			      appId      : '921813551202473',
			      xfbml      : true,
			      version    : 'v2.3'
			    });
			  };

			  (function(d, s, id){
			     var js, fjs = d.getElementsByTagName(s)[0];
			     if (d.getElementById(id)) {return;}
			     js = d.createElement(s); js.id = id;
			     js.src = "//connect.facebook.net/fr_FR/sdk.js";
			     fjs.parentNode.insertBefore(js, fjs);
			   }(document, 'script', 'facebook-jssdk'));
		</script>   
	</body>
</html>