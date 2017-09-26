<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed
require_once __DIR__ . '/config.php';

$fb = new \Facebook\Facebook([
  'app_id' => FB_APP_ID,
  'app_secret' => FB_APP_SECRET,
  'default_graph_version' => 'v2.10',
  //'default_access_token' => '{access-token}', // optional
]);
$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://'.$_SERVER['HTTP_HOST'].'/fb-callback.php', $permissions);

if(isset($_SESSION['fb_access_token'])){
    $token=$_SESSION['fb_access_token'];

    try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,picture', $token);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();
$request = new FacebookRequest(
  $session,
  'GET',
  '...?fields=url'
);
$response = $request->execute();
$graphObject = $response->getGraphObject();
var_dump($graphObject);
echo 'Name: ' . $user->getName();
}else {
    echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
}

?>
