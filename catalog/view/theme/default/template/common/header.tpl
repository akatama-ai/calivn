<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
    <!--<![endif]-->

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> <?php echo $title; ?> </title>
        <meta http-equiv="cache-control" content="no-cache"/>
        <base href="<?php echo $base; ?>" />
        <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <?php } ?>
        <?php if ($keywords) { ?>
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <?php } ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="catalog/view/theme/default/img/icoo.png">
        <script src="catalog/view/javascript/jquery/underscorejs/underscorejs.js" type="text/javascript"></script>
        <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
        <script src="catalog/view/javascript/jquery/jquery.easyui.min.js" type="text/javascript"></script>
        <script src="catalog/view/javascript/jquery/jquery-ui.js" type="text/javascript"></script>
        <script src="catalog/view/javascript/jquery/jquery.quick.pagination.min.js" type="text/javascript"></script>
        <script src="catalog/view/javascript/loading.js" type="text/javascript"></script>
        <script src="catalog/view/javascript/bootstrap.min.js"></script>
        <!--<script src="catalog/view/javascript/pace.min.js"></script> -->
        <script src="catalog/view/javascript/jquery.nicescroll.js" type="text/javascript"></script>
        <script src="catalog/view/javascript/jquery.app.js"></script>
        <!-- <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:100,300,400,600,700,900,400italic' rel='stylesheet'> -->
        <!-- Bootstrap core CSS -->
        <link href="catalog/view/theme/default/css/bootstrap.min.css" rel="stylesheet">
        <link href="catalog/view/theme/default/css/bootstrap-reset.css" rel="stylesheet">
        <link href="catalog/view/theme/default/stylesheet/fakeloader.css" rel="stylesheet">
        <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <link href="catalog/view/theme/default/css/style.css" rel="stylesheet">
        <link href="catalog/view/theme/default/css/style_2.css" rel="stylesheet">
        <link href="catalog/view/theme/default/css/helper.css" rel="stylesheet">
        <link href="catalog/view/theme/default/css/style-responsive.css" rel="stylesheet" />
        <link href="catalog/view/theme/default/stylesheet/tree.css" rel="stylesheet">

        <link href="catalog/view/theme/default/stylesheet/icon.css" rel="stylesheet">
<link href="catalog/view/theme/default/stylesheet/jquery.simplyscroll.css" rel="stylesheet" type="text/css">
        <?php foreach ($styles as $style) { ?>
        <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
        <?php } ?>
        <script src="catalog/view/javascript/common.js" type="text/javascript"></script>
 <script type="text/javascript" src="catalog/view/javascript/jquery.simplyscroll.js"></script>
        <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>" type="text/javascript"></script>
        <?php } ?>
        <?php echo $google_analytics; ?>
         <script type="text/javascript">

      (function($) {
          $(function() {
                  $("#scroller").simplyScroll({frameRate:'20'});

          });
       })(jQuery);
        
      </script>
        <script type="text/javascript">
            window.funLazyLoad = {
                start : function() {
                    $("#fakeloader").fakeLoader({
                        timeToHide : 99999999999, //Time in milliseconds for fakeLoader disappear
                        zIndex : "999", //Default zIndex
                        spinner : "spinner2",
                        bgColor : "rgba(0,0,0,0.5)", //Hex, RGB or RGBA colors
                    });
                },
                reset : function() {
                    $("#fakeloader").hide();
                },
                show : function() {
                    $("#fakeloader").show();
                }
            }

        </script>

        <script src="catalog/view/javascript/jquery.form.min.js" type="text/javascript"></script>
        <script src="catalog/view/javascript/alertifyjs/alertify.js" type="text/javascript"></script>   
        <link href="catalog/view/theme/default/css/al_css/alertify.css" rel="stylesheet">
        <script src="catalog/view/javascript/changeLang.js" type="text/javascript"></script>
    </head>
    <body class="<?php echo $class; ?>">
    <div id="fakeloader"></div
        <!-- Aside Start-->
        
