<?php $route = $self -> request -> get['route']; ?>
<aside class="left-panel">
 
         <div class="bg-logo">
	         <div class="logo">
	            <a href="dashboard" class="logo-expanded"> <img src="catalog/view/theme/default/img/logo.png" alt="logo" style=" width:100%;
"> </a>
	         </div>
         </div>
 <nav class="navigation">
        <div class="user-panel" style="margin-top: 20px; border-bottom: 1px solid #ccc; padding-bottom: 17px;">
        <div class="image" style="padding-left: 10px; width: 50px; float: left;">
             <?php if(!$customer['img_profile']){ ?>
                      <img src="catalog/view/theme/default/img/user2-160x160.jpg" style="max-width: 57px;" class="img-circle" alt="User Image">
                     <?php } ?>
                     <?php if($customer['img_profile']){ ?>
                   
                                      
                     <img src="<?php echo $customer['img_profile'] ?>"  style="max-width: 57px;" class="img-circle" alt="User Image">
                     <?php } ?>
        
        </div>
        <div class="info" style="margin-left: 10px;">
          <p style="color: #000; margin-left: 57px; padding-top: 10px;"><?php echo ($customer['username']); ?></p>
          <!-- <p style="color: #000; margin-left: 57px;"><code>VIP <?php echo($level); ?></code></p> -->
         
        </div>
        <div class="clearfix"></div>
      </div>
            <ul class="list-unstyled">
               <li <?php echo $route === 'account/dashboard' ? "class='active'" : ''  ?>>
                  <a href="dashboard">
                  	<i class="fa fa-home">
                     </i> <span class="nav-label"><?php echo $lang['dashboard']; ?></span>       

                  </a>  

               </li>
               <li <?php echo $route === 'account/register' ? "class='active'" : ''  ?> >
          <a  href="register">
            <i class="fa fa-sign-out">
                     </i> <span class="nav-label"><?php echo $lang['Register']; ?></span>       

          </a>
        </li>
               <li <?php echo $route === 'account/token' || $route === 'account/token/transfer'? "class='active'" : ''  ?>><a href="token"><i class="fa fa-bar-chart-o">
                 
                          </i> <span class="nav-label"><?php echo $lang['pin']; ?></span>
                  
               </a>                       
               </li>
               <li <?php echo $route === 'account/credit' || $route === 'account/credit/transfer'? "class='active'" : ''  ?>><a href="credit"><i class="fa fa-share">
                 
                          </i> <span class="nav-label">Transfer Credit</span>
                  
               </a>                       
               </li>
               <li <?php echo $route === 'account/pd' || $route === 'account/pd/create' || $route === 'account/pd/transfer' || $route === 'account/pd/confirm' ? "class='active'" : ''  ?>><a href="investment"><i class="fa fa-table">
              
                          </i> <span class="nav-label"><?php echo $lang['provideDonation']; ?></span>
                
                 </a>                       
               </li>
               <li <?php echo $route === 'account/gd' || $route === 'account/gd/transfer' || $route === 'account/gd/confirm' ? "class='active'" : ''  ?>><a href="withdrawal"><i class="fa fa-files-o">
               
                        </i> <span class="nav-label"><?php echo $lang['getDonation']; ?></span>
                   
                 </a>                       
               </li>
                <li <?php echo $route === 'account/transaction_history' ? "class='active'" : ''  ?>><a href="transaction-history"><i class="fa fa-table">
               
                         </i> <span class="nav-label"><?php echo $lang['Transaction']; ?></span>
                   
                 </a>                       
               </li>
               <li <?php echo $route === 'account/refferal' ? "class='active'" : ''  ?>><a href="refferal"><i class="fa fa-flask">
                
                        </i> <span class="nav-label"><?php echo $lang['Refferal(S)']; ?></span>
                 
                 </a>                       
               </li>
               <li <?php echo $route === 'account/personal' ? "class='active'" : ''  ?>><a href="personal-tree"><i class="fa fa-sitemap">
             
                          </i> <span class="nav-label"><?php echo $lang['downlineTree']; ?></span>
                    
                 </a>   
                 </li>
              

               <li <?php echo $route === 'account/setting' ? "class='active'" : ''  ?>>
                <a href="setting"><i class="fa fa-user">
               
                    </i> <span class="nav-label"> <?php echo $lang['proFile']; ?></span>
                 
                 </a>                       
               </li>

               <li>
                <a href="logout">
                	<i class="fa fa-sign-out">
                  
                    </i> <span class="nav-label"> <?php echo $lang['logout']; ?>
                  </span>
                    
                 </a>                       
               </li>
               
            </ul>
         </nav>
      </aside>
      <!-- Aside Ends-->
      <!--Main Content Start -->
      <section class="content">
        <div class="content-bg">
         <!-- Header -->
         <header class="top-head container-fluid" style="padding:0px 15px;">
            <button type="button" class="navbar-toggle pull-left">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <!-- Right navbar -->
            <ul class="list-inline navbar-right top-menu top-right-menu">
               <ul class="nav navbar-nav toolbar pull-right" style=" float:left !important; margin-top:-7px;">
                <li>
                  <!-- <div id="google_translate_element" style="float:left; padding-left:15px"></div> -->

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'th,km,en,vi,ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE,autoDisplay: false}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<style>
body {top: 0px !important; position: static !important; }
.goog-te-banner-frame {display:none !important}
div#google_translate_element div.goog-te-gadget-simple{height:50px;border-radius: 10px;background-color:#147ebc;}
    div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span{color:yellow}
    
    div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span:hover{color:#ffffff}

</style>
                
         <!-- brand -->
                </li>
                <li class="dropdown toolbar-icon-bg">
                  <a id="en" href="javascript:void(0)" data-link="<?php echo $self->url->link('account/dashboard/changeLange', '', 'SSL'); ?>">
                    <img src="<?php echo $base ?>catalog/view/theme/default/img/flags/thailand.png" style="width: 32px;"/>
                  </a>
                </li>
    
                <li class="dropdown toolbar-icon-bg">
                  <a id="en" href="javascript:void(0)" data-link="<?php echo $self->url->link('account/dashboard/changeLange', '', 'SSL'); ?>">
                    <img src="<?php echo $base ?>catalog/view/theme/default/img/flags/cambodia.png" style="width: 32px;"/>
                  </a>
                </li>
                <li class="dropdown toolbar-icon-bg">
                  <a id="en" href="javascript:void(0)" data-link="<?php echo $self->url->link('account/dashboard/changeLange', '', 'SSL'); ?>">
                    <img src="<?php echo $base ?>catalog/view/theme/default/img/flags/United-States.png" style="width: 32px;"/>
                  </a>
                </li>
                <li class="dropdown toolbar-icon-bg">
                  <a id="vn" href="javascript:void(0)" data-link="<?php echo $self->url->link('account/dashboard/changeLange', '', 'SSL'); ?>">
                    <img src="<?php echo $base ?>catalog/view/theme/default/img/flags/af.png" style="width: 32px;"/>
                  </a>
                </li>
              </ul>     
            </ul>
            <!-- End right navbar -->
         </header>
