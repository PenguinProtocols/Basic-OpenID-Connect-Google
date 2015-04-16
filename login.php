<?php
session_start();

// Copy these values from https://console.developers.google.com/
$client_id = 'XXXXXXXXX.apps.googleusercontent.com';
$client_secret = 'XXXXXXXXX';
$url = 'http://example.com/login.php'; // Make sure this full URL is added to your Redirect URIs in the Developers Console!

if ($_GET['state']) {
  if ($_GET['state'] != $_SESSION['state']) {
    $error = "Session is invalid. Make sure cookies are enabled.";
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://www.googleapis.com/oauth2/v3/token");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, 
    "code={$_GET['code']}&client_id={$client_id}&client_secret={$client_secret}&redirect_uri={$url}&grant_type=authorization_code&openid.realm={$url}");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec ($ch);
  curl_close ($ch);
  $output = json_decode($output);
  $token = $output->id_token;

  if (!$token) {
    $error = "Token expired, try again";
  } else {
    require_once 'JWT.php';
    $info = JWT::decode($token, $client_secret, false);

    if (!$info->sub) {
      $error = "Could not decode the JWT token.";
    } else {
      $success = true;
      // User has logged in. You can view all values by uncommenting the next line:
      // var_dump($info);
      // You can save the user info in a database, check it with existing users, etc. 
      // This barebone example simply shows you some login info of the user below.
    }
  }

} else {
  $state = md5(rand());
  $_SESSION['state'] = $state;
}
?>
<!doctype html>
<html>
<head>
  <title>Google OpenID Connect Login</title>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <style type="text/css">
  .google{background-color:#4285F4}
  .jumbotron{margin-top:20px}
  </style>
</head>
<body>
<div class="container">
  <div class="jumbotron">
    <h1>Login</h1>
      <?php if ($error) { ?>
        <div class="alert alert-danger">
          Sorry, login failed. Error message: <strong><?php echo $error ?></strong>
        </div>
      <?php } elseif ($success) { ?>
      <div class="alert alert-success">
        You are successfully logged in. Welcome, <strong><?php echo $info->email ?></strong>
      </div>
        <dl class="dl-horizontal">
          <dt>ISS</dt>
          <dd><?php echo $info->iss ?></dd>

          <dt>Sub</dt>
          <dd><?php echo $info->sub ?></dd>
          <dd><em>Note: this is the unique ID of this user, so if you want to save this user in your database, use the sub value.</em></dd>

          <dt>E-mail</dt>
          <dd><?php echo $info->email ?></dd>

          <dt>E-mail verified</dt>
          <dd><?php echo $info->email_verified ? "true" : "false"; ?></dd>

          <dt>OpenID ID</dt>
          <dd><?php echo $info->openid_id ?></dd>
          <dd><em>Note: this value is provided if you are migrating from the previous OpenID 2.0 system. You can use this to map the new sub value with the old OpenID ID</em></dd>

          <dt>Expiration</dt>
          <dd><?php echo date("r", $info->exp) ?></dd>
        </dl>
      <?php } else { ?>
        <p>
          <a href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo $client_id ?>&response_type=code&scope=openid%20email&redirect_uri=<?php echo $url ?>&state=<?php echo $state ?>&openid.realm=<?php echo $url ?>" class="btn btn-lg btn-primary google">
            <i class="fa fa-google"></i>
            <span>
              Sign in with Google
            </span>
          </a>
        </p>
      <?php } ?>
  </div>
</div>
</body>
</html>
