<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en"> <!--<![endif]-->

<head>
  <meta charset="utf-8">
  <title>Home Page | Caligroup</title>

  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <link rel="icon" href="catalog/view/theme/default/img/icoo.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">   
  <link href="catalog/view/theme/default/assets/css/bootstrap.css" rel="stylesheet">
  <link href="catalog/view/theme/default/assets/css/simple-line-icons.css" rel="stylesheet">
  <link href="catalog/view/theme/default/assets/css/font-awesome.css" rel="stylesheet">
  <link href="catalog/view/theme/default/assets/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="all" />
  <link href="catalog/view/theme/default/assets/css/home_style.css" rel="stylesheet"> 
  <link href='http://fonts.googleapis.com/css?family=Raleway:400,700,800&amp;subsetting=all' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,800,700,300' rel='stylesheet' type='text/css'>
   <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <!--[if lt IE 9]>
    <script src="./js/html5shiv.js"></script>
    <script src="./js/respond.js"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <![endif]-->
  <style>
  a.btn-home {
    color: #fce20d !important;
    font-weight: bold;
}</style>
</head>

<body data-spy="scroll" data-target=".navigation">
  
  <!-- Banner --> 
  <div id="video-header"><video autoplay="" loop="" muted="" poster="images/video-poster.jpg"><source src="https://theme-background-videos.s3.amazonaws.com/corporate.m4v" type="video/mp4"></video><div class="video-header-message" id="mas">
    <h1>FINANCE PROFESSIONALS WITH DEEP EXPERIENCE</h1>
    <!-- <h2 style="
    color: #fff;
">The easiest way to mine Bitcoins</h2>
              <p style="
    color: #fff;
">start mining Bitcoin without buying mining equipment</p> -->
<?php if(!$self -> customer -> isLogged()) { ?>
                <a class="purchase-btn" href="login" style="
    background: #970001;
    border-color: #970001;
        color: #fff;
">START LOGIN NOW</a>
              <?php  } else{?>
                <a class="purchase-btn" href="dashboard" style="
    background: #970001;
    border-color: #970001;
        color: #fff;
">DASHBOARD</a>
              <?php }?>
    </div></div>
    
  <!-- End Banner -->
  
  <div class="clearfix"></div>
  <section id="feature">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="title-area">
            <h2 class="title">Create your account right now and get your gift!</h2>
            <span class="line"></span>
            <!-- <p>We give every new user, after the first payment, 5-15 KH/s as gift.</p> -->
          </div>
        </div>
        <div class="col-md-12">
          <div class="feature-content">
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="single-feature wow zoomIn" style="visibility: visible; animation-name: zoomIn;">
                  <i class="fa fa-leaf feature-icon"></i>
                  <h4 class="feat-title">Create your account</h4>
                  <p>Use your email to create a new account. We will use this address to send access details.</p>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="single-feature wow zoomIn" style="visibility: visible; animation-name: zoomIn;">
                  <i class="fa fa-mobile feature-icon"></i>
                  <h4 class="feat-title">Log In</h4>
                  <p>Use login and password that have been sent on your email address and log in the service.</p>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="single-feature wow zoomIn" style="visibility: visible; animation-name: zoomIn;">
                  <i class="fa fa-thumbs-o-up feature-icon"></i>
                  <h4 class="feat-title">Earn your profit!</h4>
                  <p>You will get your profit every day. You can transfer this profit on your wallet automatically.</p>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="single-feature wow zoomIn" style="visibility: visible; animation-name: zoomIn;">
                  <i class="fa fa-gears feature-icon"></i>
                  <h4 class="feat-title">Increase your profit!</h4>
                  <p>If you want to increase your profit you can purchase more powers or use Referral Program.</p>
                </div>
              </div>
             <div class="col-md-4 col-sm-6">
                <div class="single-feature wow zoomIn" style="visibility: visible; animation-name: zoomIn;">
                  <i class="fa fa-leaf feature-icon"></i>
                  <h4 class="feat-title">Referral Program</h4>
                  <p>Get 8% of powers for invited users.</p>
                </div>
              </div>
            

          
        
           

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="clearfix"></div>
  <section id="pricing-table">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="title-area">
            <h2 class="title">Our Pricing</h2>
            <span class="line"></span>
            <p>Choose your quantity</p>
          </div>
        </div>
        <div class="col-md-12">
          <div class="pricing-table-content">
            <div class="row">
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="single-table-price wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.5s; animation-name: fadeInUp;">
                  <div class="price-header">
                    <span class="price-title">Plan 1</span>
                    <div class="price">
                      <sup class="price-up"></sup>
                      <?php echo number_format('400'); ?>
                      <span class="price-down">USD</span>
                    </div>
                  </div>
                  <div class="price-article">
                    <ul>
     
          
                      <li>Referral commission : 8%</li>
                      <li>24/7 Support</li>
                    </ul>
                  </div>
                  <div class="price-footer">
                    <a class="purchase-btn" href="<?php echo $self -> url -> link('account/login', '', 'SSL') ?>">Purchase</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="single-table-price wow fadeInUp" data-wow-duration="0.75s" data-wow-delay="0.75s" style="visibility: visible; animation-duration: 0.75s; animation-delay: 0.75s; animation-name: fadeInUp;">
                  <div class="price-header">
                    <span class="price-title">Plan 2</span>
                    <div class="price">
                      <sup class="price-up"></sup>
                      <?php echo number_format('800'); ?>
                      <span class="price-down">USD</span>
                    </div>
                  </div>
                  <div class="price-article">
                    <ul>
   
     
                      <li>Referral commission : 8%</li>
                      <li>24/7 Support</li>
                    </ul>
                  </div>
                  <div class="price-footer">
                    <a class="purchase-btn" href="<?php echo $self -> url -> link('account/login', '', 'SSL') ?>">Purchase</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="single-table-price featured-price wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s" style="visibility: visible; animation-duration: 1s; animation-delay: 1s; animation-name: fadeInUp;">
                  <div class="price-header">
                    <span class="price-title">Plan 3</span>
                    <div class="price">
                      <sup class="price-up"></sup>
                      <?php echo number_format('1600'); ?>
                      <span class="price-down">USD</span>
                    </div>
                  </div>
                  <div class="price-article">
                    <ul>
      
      
                      <li>Referral commission : 8%</li>
                      <li>24/7 Support</li>
                    </ul>
                  </div>
                  <div class="price-footer">
                    <a class="purchase-btn" href="<?php echo $self -> url -> link('account/login', '', 'SSL') ?>">Purchase</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="single-table-price featured-price wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s" style="visibility: visible; animation-duration: 1s; animation-delay: 1s; animation-name: fadeInUp;">
                  <div class="price-header">
                    <span class="price-title">Plan 4</span>
                    <div class="price">
                      <sup class="price-up"></sup>
                      <?php echo number_format('3200'); ?>
                      <span class="price-down">USD</span>
                    </div>
                  </div>
                  <div class="price-article">
                    <ul>
      
     
                      <li>Referral commission : 8%</li>
                      <li>24/7 Support</li>
                    </ul>
                  </div>
                  <div class="price-footer">
                    <a class="purchase-btn" href="<?php echo $self -> url -> link('account/login', '', 'SSL') ?>">Purchase</a>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="clearfix"></div>
  <!-- Intro -->
  <section id="intro" class="section ">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <article class="s-12 l-8 center">
                        <!-- <h1 style="color: #fff;font-weight:;">2.8% Daily For 60 Days</h1>
                          <h3 style="color: #fff;font-weight:;">Minimum Deposit 0.5 BTC</h3>
                          <h3 style="color:#fff;font-weight:;">No Max Limit</h3> -->
                          <h3 style="color:#fff;font-weight:;">Referral commission :8% </h3>
                <?php if(!$self -> customer -> isLogged()) { ?>
                <a class="purchase-btn" href="<?php echo $self -> url -> link('account/login', '', 'SSL') ?>" style="
    background: #970001;
    border-color: #970001;
        color: #fff;
">START LOGIN NOW</a>
              <?php  } else{?>
                <a class="purchase-btn" href="<?php echo $self -> url -> link('account/dashboard', '', 'SSL') ?>" style="
    background: #970001;
    border-color: #970001;
        color: #fff;
">DASHBOARD</a>
              <?php }?>   
                     </article>
          
        </div>
      </div>

    </div>
  </section>
  <div class="clearfix"></div>
  <section class="contact-us" id="section5">
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2 support">
<h3 class="heading">Need any help? Contact us now!</h3>
<a href="mailto:info@caligroup.info">info@caligroup.info</a>
</div>
</div>
</div>
</section>


  
  
  <footer class="bgcolor-2">
<div class="container">
<div class="footer-logo">
<img src="catalog/view/theme/default/img/logo.png" alt="caligroup.info">
</div>
<div class="copyright">
© 2016 caligroup.info | The nuclear coin networks
</div>

</div>
</footer>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'OGmqsibjQ5';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
</body>

</html>
