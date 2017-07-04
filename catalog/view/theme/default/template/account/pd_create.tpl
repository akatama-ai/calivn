<?php 
   $self -> document -> setTitle($lang['createPD']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['createPD'] ?></h3>
   </div>
   <!-- Form-validation -->

   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <h2 class="text-center panel-title" style="margin-bottom: 20px;">Bank Investment</h2>
           <!--  <div class="panel-heading">
               <h3 class="panel-title"><?php echo $lang['text_button_create'] ?></h3>
            </div> -->
            <div class="panel-body">
       
              
               <div class="row">
                  <div class="col-md-12">
                     <div class="alert  alert-success alert-edit-account" style="display:none">
                        <i class="fa fa-check"></i> <?php echo $lang['ok'] ?>.
                     </div>
                     <div id="checkPD-error" class="alert alert-dismissable alert-danger" style="display:none">
                     </div>
                     <div id="checkWaiting-error" class="alert alert-dismissable alert-danger" style="background-color: rgba(255, 0, 0, 0.09); display:none">
                     </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <form id="submitPD" class="form-horizontal margin-none" name="buy_share_form" action="<?php echo $self -> url -> link('account/pd/submit', '', 'SSL'); ?>" method="post" novalidate="novalidate">
                     <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo $lang['amount']?></label>
                        <div class="col-md-8">
                                         <?php 
                                                $filled = $self -> check_filled_pd();
                                            
                                             ?>  
                                        <select class="form-control valid" id="amount" name="amount">
                                        <option value=""><?php echo $lang['choise']?></option>
                                            <option value="500"><?php echo number_format("500")."<br>"; ?> USD</option>
                                            <option value="1000"><?php echo number_format("1000")."<br>"; ?> USD</option>
                                            <option value="2000"><?php echo number_format("2000")."<br>"; ?> USD</option>
                                            <option value="4000"><?php echo number_format("4000")."<br>"; ?> USD</option>
                                          
                                        </select>
                                          <span id="amount-error" class="field-validation-error" style="display: none;">
                                                            <span><?php echo $lang['err_amount']?></span>
                                                        </span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo $lang['password']?></label>
                        <div class="col-md-8">
                           <input class="form-control" id="Password2" name="Password2" type="password"/>
                           <span id="Password2-error" class="field-validation-error" style="display: none;">
                           <span >The transaction password field is required.</span>
                           </span>
                        </div>
                     </div>
                     <div class="control-group form-group">
                        <div class="controls">
                           <div class="col-md-offset-3 ">
                              <div class="loading"></div>
                              <button type="submit" class="btn-register btn btn-primary"> <?php echo $lang['text_button_create'] ?></button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
            </div>
         </div>
      </div>
   </div>
  
   </div>
   <script type="text/javascript">
   
   window.err_password = '<?php echo $lang['err_password'] ?>';
   
   window.err_pd = '<?php echo $lang['err_pd'] ?>';
   
   window.err_pin = '<?php echo $lang['err_pin'] ?>';
   window.err_account = '<?php echo $lang['err_account'] ?>';
   
   
   window.err_password_2 = '<?php echo $lang['err_password_2'] ?>';
   
   </script>

            
         

         </div>
      </div>
   </div>
</div>
<?php echo $self->load->controller('common/footer') ?>