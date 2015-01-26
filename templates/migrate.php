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
    <script src="../js/jgfeed.js"></script>
  	<script src="../app.js"></script>
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
                  <input id="feedurl" type="text" class="form-control" placeholder="RSS Link" value="">
                  <span class="input-group-btn">
                    <button id="preview" class="btn btn-default" type="button">Preview</button>
                  </span>
                </div>
              </div> 
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
              <input type="text" class="form-control" name="projectname" id="projectname" disabled="disabled">
              <div class="input-group-btn">
                <button type="button" class="btn btn-default" tabindex="-1" data-toggle="modal" data-target="#newprojectmodal">+</button>
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

      <div class="row">
        <div class="col-md-12">
            <div class="progress" style="margin-top: 15px; display: none">
              <div class="progress-bar progress-bar-striped active" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
        </div>
      </div>

      <div class="row">  
        <!-- Left Row  - File Import -->
        <div class="col-md-6">
        
          <div class="panel panel-default" style="margin-top: 20px;min-height: 300px">
            <div class="panel-heading" id="importheading">RSS Files </div>
              <div class="panel-body">
                <div id="results"></div>
              </div>
          </div>

        </div>
        
        <div class="col-md-6">
           
          <div class="panel panel-default" style="margin-top: 20px; min-height: 300px">
            <div class="panel-heading" id="importheading"> 
               Copied
            </div>
            <div class="panel-body">
              <div id="transferredfiles">
                <table class='table'>
                  <tbody id="tr"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>

    <!-- Modal for creating new projects -->
    <div class="modal fade" id="newprojectmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="exampleModalLabel">New Project</h4>
          </div>
          <div class="modal-body">
            <form role="form">
              <div class="form-group">
                <label for="recipient-name" class="control-label">Project Name:</label>
                <input type="text" name="newprojectname" class="form-control" id="newprojectname">
              </div>
              <div class="form-group">
                <label for="recipient-name" class="control-label">Description:</label>
                <input type="text" name="description" class="form-control" id="description">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="createproject" class="btn btn-primary">Create Project</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for reminding user to select a project first -->
    <div class="modal fade" id="selectproject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Warning</h4>
          </div>
          <div class="modal-body">
            Please select a project in MediaSilo to move this media to.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for conforming the completion of import actiom -->
    <div class="modal fade" id="importcomplete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Success</h4>
          </div>
          <div class="modal-body">
            Your import is complete. Import additional feeds by pasting the RSS link.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>  
  </body>
</html>


