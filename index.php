<?php
//Autoload fb
error_reporting(E_ALL);

session_start();
require "facebook-php-sdk-v4-4.0-dev/autoload.php";

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;

use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\GraphUser;

const APPID = "1405446186439642";
const APPSECRET = "77c4c9fd31fc2416a773d871ef2bc819";
const CHEMIN = "https://photogameesgi.herokuapp.com/";

FacebookSession::setDefaultApplication(APPID,APPSECRET);

$helper = new FacebookRedirectLoginHelper(CHEMIN);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Page photo jeux FB</title>
		<meta name="description"
		content="description de ma page">
	
	</head>

	<body>
		<h1>Mon appli Facebook</h1>
		<br>
		<br>

		<!-- bloc j'aime -->
		<br>
		<br>
		<br>
			<div
			  class="fb-like"
			  data-share="true"
			  data-width="450"
			  data-show-faces="true">
			</div>

		<!-- bloc commentaire -->
		<br>
		<br>
		<br>
		<div class="fb-comments" data-href="https://photogameesgi.herokuapp.com/" data-numposts="5" data-colorscheme="light"></div>
		
		<?php

	
		
		

		

		if (isset($_SESSION) && isset($_SESSION['fb_token'])){
			$session = new FacebookSession($_SESSION['fb_token']);
		}else{
			$session = $helper->getSessionFromRedirect();
		}

		$loginUrl = $helper->getLoginUrl();
		echo '<a href="' . $loginUrl . '">Login with Facebook</a>';
		
		if($session){
			try{
				$user_profile = (new FacebookRequest(
					$session,'GET','/me'
					))->execute()->getGraphObject(GraphUser::classname());


				echo 'Nom et Prenom : '. $user_profile->getName();
				

			}catch (FacebookRequestException $e){
				echo "Exception occured, code :".$e->getCode();
				echo "with message : ".$e->getMessage();

			}
		}else{
			echo 'Vous n\'etes pas connecter , veuiller vous connecter';
		}

		

		?>






		<script>
			  window.fbAsyncInit = function() {
			    FB.init({
			      appId      : '<?php echo APPID;?>',
			      xfbml      : true,
			      version    : 'v2.3'
			    });
			  };

			  (function(d, s, id){
			     var js, fjs = d.getElementsByTagName(s)[0];
			     if (d.getElementById(id)) {return;}
			     js = d.createElement(s); js.id = id;
			     js.src = "//connect.facebook.net/en_US/sdk.js";
			     fjs.parentNode.insertBefore(js, fjs);
			   }(document, 'script', 'facebook-jssdk'));
		</script>	
	</body>

</html>