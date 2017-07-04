<?php 
   $self -> document -> setTitle($lang['create']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['create'] ?></h3>
   </div>
   <!-- Form-validation -->

   <div class="row">
         <div class="col-lg-12">
            
            <div class="panel panel-default pd-panel">
               <div class="panel-heading">
                  <h3 class="panel-title"><?php echo $lang['create'] ?></h3>
                  
               </div>
               <div class="panel-body panel-custom">
                  <div class="col-md-12" style="padding:0px">
                     <div id="sucess-alert" class="alert  alert-success alert-edit-account" style="display:none">
                        <i class="fa fa-check"></i> <?php echo $lang['successfull'] ?>.
                     </div>
                     <div class="alert alert-dismissable alert-danger" style="display:none">
                        <?php echo $lang['eligible'] ?>.
                     </div>
                     <div id="err-c-wallet" class="alert alert-dismissable alert-danger" style="display:none">
                        You cannot withdraw for C-wallet, please check back for C-wallet to more than 10 USD.
                     </div>
                     <div id="err-passs" class="alert alert-dismissable alert-danger" style="display:none">
                        You password transction error. Please try agin !
                     </div>
                     <div id="err-pin" class="alert alert-dismissable alert-danger" style="display:none">
                        Please buy pin packet !
                     </div>
                     <div id="err-checkConfirmPD" class="alert alert-dismissable alert-danger" style="display:none">
                        Please create new request PD !
                     </div>
                  </div>
                  <form id="createGD" action="<?php echo $self -> url -> link('account/gd/submit', '', 'SSL'); ?>" class="form-horizontal margin-none" method="post" novalidate="novalidate">
                     <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo $lang['Receivable'] ?>:</label>
                        <div class="col-md-8">
                           <input class="form-control" id="amount" type="text" readonly="true" disabled="true" />
                           <span id="amount-error" class="field-validation-error" style="display: none;">
                           <span><?php echo $lang['err_Receivable'] ?></span>
                           </span>
                           <br/>
                           <p class="help-none" id="c-wallet" data-value="<?php echo $c_wallet ?>"><?php echo $lang['c_wallet'] ?>: <code> <?php echo number_format($c_wallet); ?> USD </code></p>
                           <p class="help-none" id="r-wallet" data-value="<?php echo $r_wallet ?>"><?php echo $lang['r_wallet'] ?>: <code> <?php echo number_format($r_wallet); ?> USD </code></p>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3 control-label">From Wallet: </label>
                        <div class="col-md-8" style="margin-left:25px;">
                           <!-- Please check the type of wallet -->
                           <label class="radio">
                           <input id="C_Wallet" name="FromWallet" type="radio" value="1"/>&nbsp; &nbsp;&nbsp; &nbsp;<?php echo $lang['c_wallet'] ?>
                           </label>
                           <label class="radio">
                           <input id="R_Wallet" name="FromWallet" type="radio" value="2"/>&nbsp; &nbsp;&nbsp; &nbsp;<?php echo $lang['r_wallet'] ?>
                           </label>
                         
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo $lang['Password'] ?>:</label>
                        <div class="col-md-8">
                           <input class="form-control" id="Password2" name="Password2" type="password"/>
                           <span id="Password2-error" class="field-validation-error" style="display: none;">
                           <span ><?php echo $lang['err_Password'] ?></span>
                           </span>
                        </div>
                     </div>
                     <div class="control-group form-group">
                        <div class="controls">
                           <div class="col-md-offset-3 ">
                              <div class="loading"></div>
                              <button type="submit" class="btn-register btn btn-primary">Create</button>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <!-- /.panel-body -->
            </div> 
             
            <!-- /.panel -->
         </div>
         <!-- /.col-lg-12 -->
      </div>
   <!-- End row -->
</div>


<?php echo $self->load->controller('common/footer') ?>