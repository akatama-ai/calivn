<?php 
   $self -> document -> setTitle('Create Pin Transfer'); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['text_create'] ?></h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo $lang['text_create'] ?></h3>                 
                               
                                
                            </div>
                           
                            <div class="panel-body">
                                <div class="row">
                                <div class="col-md-12">
                                    
                                    <div class="alert alert-edit-account alert-success" style="display:none">
                                        <i class="fa fa-check"></i> <?php echo $lang['text_successfull'] ?>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <form  id="frmCreatePin" action="<?php echo $self->url->link('account/token/transfersubmit', '', 'SSL'); ?>" class="form-horizontal margin-none" method="post" novalidate="novalidate">
                                       
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="firstname">
                                                        <?php echo $lang['text_Received'] ?>: <!-- <span class="ast">*</span> -->
                                                    </label>
                                                    <div class="col-md-6">
                                                       <input autocomplete="off" value="" class="form-control" id="MemberUserName" name="customer" placeholder='<?php echo $lang['text_Received'] ?>' type="text" />
                                                         <span id="MemberUserName-error"  class="field-validation-error">
                                                            <span></span>
                                                        </span>
                                                        <ul id="suggesstion-box" class="list-group"></ul>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="firstname">
                                <?php echo $lang['text_Amount'] ?>:
                                                        <!-- <span class="ast">*</span> -->
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input autocomplete="off" value="" class="form-control" id="Quantity" name="pin" placeholder='<?php echo $lang['text_Amount'] ?>' type="text" />
                                                        <span id="Quantity-error" class="field-validation-error">
                                                            <span></span>
                                                        </span>
                                                        <div id="errr"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="firstname">
                                <?php echo $lang['text_Transaction_Password'] ?>:
                                                        <!-- <span class="ast">*</span> -->
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input class="form-control" id="TransferPassword" name="TransferPassword" placeholder="<?php echo $lang['text_Transaction_Password'] ?>" type="password"/>
                                                        <span id="TransferPassword-error" class="field-validation-error">
                                                            <span></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="firstname">
                                <?php echo $lang['text_Message'] ?>:
                            </label>
                                                    <div class="col-md-6">
                                                        <textarea class="form-control" cols="20" id="Description" name="description" placeholder="<?php echo $lang['text_Message'] ?>" ></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2">
                                                            <div class="btn-toolbar mr-l">
                                                                <button type="submit" class="btn btn-primary"><?php echo $lang['text_ok'] ?></button>
                                                                <a href="token" class="btn btn-default"><?php echo $lang['text_cancel'] ?></a>
                                                            </div>
                                                        </div>
                                                </div>
                                               
                                       
                                    </form>
                                </div>
                            </div>
                          
                        </div>
                    </div>
                    
                </div> <!-- End Row -->
   <!-- End row -->
</div>
  <script type="text/javascript">
     $(document).ready(function(){
        $("#MemberUserName").keyup(function(){
            $.ajax({
            type: "POST",
            url: "<?php echo $base;?>index.php?route=account/token/getaccount",
            data:'keyword='+$(this).val(),        
            success: function(data){
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
                $("#MemberUserName").css("background","#FFF");            
            }
            });
        });
    }); 
    function selectU(val) {
        $("#MemberUserName").val(val);
        $("#suggesstion-box").hide();
    }
window.err_text_amount = '<?php echo $lang['err_text_amount'] ?>';

window.err_text_passwd = '<?php echo $lang['err_text_passwd'] ?>';

window.err_text_account_notexit = '<?php echo $lang['err_text_account_notexit'] ?>';

window.err_text_account_passwd = '<?php echo $lang['err_text_account_passwd'] ?>';

window.err_text_pin = '<?php echo $lang['err_text_pin'] ?>';

window.err_text_account = '<?php echo $lang['err_text_account'] ?>';

</script>
<?php echo $self->load->controller('common/footer') ?>