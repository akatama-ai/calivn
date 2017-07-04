<?php 
   $self -> document -> setTitle('Register User'); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title">Register</h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
      <div class="col-sm-12">
         <div class="panel panel-default">
            <div>
               <div class="panel-body">
                  <div class=" form">
                 
                     <form id="register-account" action="<?php echo $self -> url -> link('account/register', '', 'SSL'); ?>" class="form-horizontal" method="post" novalidate="novalidate">
                     
               
               
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="col-md-3 control-label" for="username">User Name</label>
                              <div class="col-md-8">
                                 <input class="form-control" name="username" id="username" value="" data-link="<?php echo $self -> url -> link('account/register/checkuser', '', 'SSL'); ?>" />
                                 <span id="user-error" class="field-validation-error" style="display: none;">
                                 <span >Please enter user name</span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="col-md-3 control-label" for="email">Email Address</label>
                              <div class="col-md-8">
                                 <input class="form-control" name="email" id="email" data-link="<?php echo $self -> url -> link('account/register/checkemail', '', 'SSL'); ?>" />
                                 <span id="email-error" class="field-validation-error" style="display: none;">
                                 <span id="Email-error">Please enter Email Address</span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="col-md-3 control-label" for="phone">Phone Number</label>
                              <div class="col-md-8">
                                 <input class="form-control" name="telephone" id="phone" data-link="<?php echo $self -> url -> link('account/register/checkphone', '', 'SSL'); ?>" />
                                 <span id="phone-error" class="field-validation-error" style="display: none;">
                                 <span>Please enter Phone Number</span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="col-md-3 control-label" for="cmnd">Citizenship Card/Passport No</label>
                              <div class="col-md-8">
                                 <input class="form-control" name="cmnd" id="cmnd" data-link="<?php echo $self -> url -> link('account/register/checkcmnd', '', 'SSL'); ?>" />
                                 <span id="cmnd-error" class="field-validation-error" style="display: none;">
                                 <span id="CardId-error" >The Citizenship card/passport no field is required.</span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="form-group required">
                           <label class="col-sm-3 control-label" for="input-country">Country</label>
                           <div class="col-sm-8">
                             <select name="country_id" id="country" class="form-control">
                               <option value="">--Please Select--</option>
                               <?php foreach ($countries as $country) { ?>
                               <?php if ($country['country_id'] == $country_id) { ?>
                               <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                               <?php } else { ?>
                               <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                               <?php } ?>
                               <?php } ?>
                             </select>
                              <span id="country-error" class="field-validation-error" style="display: none;">
                                 <span>The country field is required.</span>
                              </span>
                           </div>
                         </div>
                         <div class="form-group required">
                           <label class="col-sm-3 control-label" for="input-zone">Province</label>
                           <div class="col-sm-8">
                             <select name="zone_id" id="input-zone" class="form-control">
                             </select>
                            
                           </div>
                         </div>


                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="col-md-3 control-label" for="password">Password For Login</label>
                              <div class="col-md-8">
                                 <input class="form-control" id="password" name="password" type="password" />
                                 <span id="password-error" class="field-validation-error" style="display: none;">
                                 <span>Please enter password for login</span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="col-md-3 control-label" for="ConfirmPassword">Repeat Password For Login</label>
                              <div class="col-md-8">
                                 <input class="form-control valid" id="confirmpassword" type="password" />
                                 <span id="confirmpassword-error" class="field-validation-error" style="display: none;">
                                 <span>Repeat Password For Login not correct</span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="col-md-3 control-label" for="transaction_password">Transaction Password</label>
                              <div class="col-md-8">
                                 <input class="form-control" id="password2" name="transaction_password" type="password" />
                                 <span id="password2-error" class="field-validation-error" style="display: none;">
                                 <span >Please enter transaction password</span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="col-md-3 control-label" for="ConfirmPassword2">Repeat Transaction Password</label>
                              <div class="col-md-8">
                                 <input class="form-control valid" id="confirmpasswordtransaction" type="password" />
                                 <span id="confirmpasswordtransaction-error" class="field-validation-error" style="display: none;">
                                 <span>Repeat Transaction Password is not correct</span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <div class="checkbox-inline col-md-offset-3">
                                 <input id="agreeTerm" class="mr-l" type="checkbox" value="true" />
                                 <label for="agreeTerm">Agree to our Terms and Condition</label>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <div class="col-md-offset-3 ">
                                 <div id="success"></div>
                                 <button type="submit" class="btn-register btn btn-primary">Submit</button>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
                  <!-- .form -->
               </div>
               <!-- panel-body -->
            </div>
            <!-- panel -->
         </div>
      </div>
      <!-- col -->
   </div>
   <!-- End row -->
</div>
<?php echo $self->load->controller('common/footer') ?>
<script type="text/javascript"><!--

$('select[name=\'country_id\']').on('change', function() {
   $.ajax({
      type : 'GET',
      url: 'index.php?route=account/register/country&country_id=' + this.value,
      dataType: 'json',
      success: function(json) {
         if (json['postcode_required'] == '1') {
            $('input[name=\'postcode\']').parent().parent().addClass('required');
         } else {
            $('input[name=\'postcode\']').parent().parent().removeClass('required');
         }
         
         html = '<option value="">--Please Select--</option>';
         
         if (json['zone'] != '') {
            for (i = 0; i < json['zone'].length; i++) {
               html += '<option value="' + json['zone'][i]['zone_id'] + '"';
               
            
            
               html += '>' + json['zone'][i]['name'] + '</option>';
            }
         } else {
            html += '<option value="0" selected="selected">--Please Select--</option>';
         }
         
         $('select[name=\'zone_id\']').html(html);
      },
      error: function(xhr, ajaxOptions, thrownError) {
         alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
   });
});

//--></script>
<script type="text/javascript">
   if (location.hash === '#success') {
      alertify.set('notifier','delay', 100000000);
      alertify.set('notifier','position', 'top-right');
      alertify.success('Create user successfull !!!');
   }
   
</script>
