
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MediaSilo RSS Import Tool</title>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    
    <!-- Latest compiled and minified CSS -->
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

  	<!-- Optional theme -->
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../style.css">

  	<!-- Latest compiled and minified JavaScript -->
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  	<script src="../app.js"></script>
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
  <body>

    <div class="container" style="margin-top: 200px;">
      <div class="panel panel-default center-block" style="width: 280px;" >
        <div class="panel-body">

          <form name="loginform" method="post" action="/login">
            <div class="form-group">
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="username" placeholder="Username" style="margin: 5px;">
              </div>

              <div class="input-group input-group-lg">
                <input type="password" class="form-control" name="password" placeholder="Password" style="margin: 5px;">
              </div>

              <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="hostname" placeholder="Hostname" style="margin: 5px;">
              </div>

               <input type="submit" class="btn btn-default" value="Logon" style="margin: 5px;"></button>

              </div>
            </form>
          
        </div>
      </div>
    </div>        
  </body>
</html>


