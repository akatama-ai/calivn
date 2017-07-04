<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="icon" href="catalog/view/theme/default/img/icoo.png">
      <title>Login</title>
      <script src="catalog/view/javascript/jquery.js"></script>
      <script src="catalog/view/javascript/bootstrap.min.js"></script>
      <script src="catalog/view/javascript/pace.min.js"></script>
      <script src="catalog/view/javascript/jquery.nicescroll.js" type="text/javascript"></script>
      <script src="catalog/view/javascript/jquery.app.js"></script>
      <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:100,300,400,600,700,900,400italic' rel='stylesheet'>
      <!-- Bootstrap core CSS -->
      <link href="catalog/view/theme/default/css/bootstrap.min.css" rel="stylesheet">
      <link href="catalog/view/theme/default/css/bootstrap-reset.css" rel="stylesheet">
      <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
      <link href="catalog/view/theme/default/css/style.css" rel="stylesheet">
      <link href="catalog/view/theme/default/css/helper.css" rel="stylesheet">
      <link href="catalog/view/theme/default/css/style-responsive.css" rel="stylesheet" >
      <script src="catalog/view/javascript/jquery.form.min.js" type="text/javascript"></script>
   </head>
   <body>
      <div class="forgotten wrapper-page animated fadeInDown">
            <div class="panel panel-color panel-primary">

            
                  <form action="forgotten" method="post" enctype="multipart/form-data" class="text-center">
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        Enter your <b>Username</b> and instructions will be sent to you!
                    </div>
                    <div class="form-group m-b-0"> 
                        <div class="input-group"> 
                            <input type="text" name="email" value="" placeholder="Your Username" id="input-email" class="form-control" />
                            <span class="input-group-btn"> <button type="submit" class="btn btn-primary">Reset</button> </span> 
                        </div> 
                    </div>
                      <div class="buttons clearfix" style="margin-top:15px;">
            <div class="pull-left"><a href="login" class="btn btn-default">Back</a></div>
            
          </div>
                    <div class="form-group m-t-15"> 
                        <div class="input-group"> 
                            <?php if ($error_warning) { ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
        <?php } ?>
                        </div> 
                    </div> 
                </form>

                                        
                
            </div>
        </div>
   </body>
</html>