<?php
  
  // Log the user in
  include 'api.php';

  $api = new ApiClass;
  
  $user = $api->getUserInfo($_POST["username"],$_POST["password"],$_POST["hostname"]);
  
  if($user != "Could not authenticate"){
    $user = array(
        'username'    => $_POST["username"],
        'password'    => $_POST["password"],
        'hostname'    => $_POST["hostname"]
    );
    setcookie('mediasilo', json_encode($user), time() + 4800);

    $projects = json_decode($api->getUserProjects());
    // var_dump($projects);
  } else {
      echo "Unable to log you in";
    exit;
  }

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WireDrive Crusher</title>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="style.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script src="js/jgfeed.js"></script>
	<script src="app.js"></script>
  
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <div class="container-fluid" style="margin-top: 10px;">
      <div class="row">
          <div class="col-md-6">
            
            <div class="row">
             
              <div class="col-lg-6">
                <div class="input-group">
                  <input id="feedurl" type="text" class="form-control" placeholder="WireDrive RSS" value="http://www.wdcdn.net/rss/presentation/library/client/xplatform/id/84323d118c3154afa77f5c52311d40bd">
                  <span class="input-group-btn">
                    <button id="preview" class="btn btn-default" type="button" >Preview</button>
                  </span>
                </div>
              </div> 
            </div>

            <div id="results"></div>

          </div>
          <div class="col-md-6">
              <div class="col-lg-6">
                <div class="input-group">
                  <input type="text" class="form-control" name="projectname" id="projectname">
                  <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Select Project <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right" id="targetproject" role="menu">
                      <?php
                        for ($i = 0; $i < sizeOf($projects); $i++){
                          echo '<li><a href="#" projectid="'.$projects[$i]->id.'">' . $projects[$i]->name . '</a></li>';
                        }
                      ?>
                    </ul>
                  </div>
                </div>


              
          </div>
      </div>
    </div>
    
        
  </body>
</html>


