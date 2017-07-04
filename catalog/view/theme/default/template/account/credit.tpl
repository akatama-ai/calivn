<?php 
   $self -> document -> setTitle('Transfer Credit'); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title">Transfer Credit</h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Transfer Credit</h3>                 
                               
                                
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
                                    <form  id="frmTransferCredit" action="<?php echo $self->url->link('account/credit/transfersubmit', '', 'SSL'); ?>" class="form-horizontal margin-none" method="post" novalidate="novalidate">
                                       
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
                        <label class="col-md-2 control-label">From Wallet: </label>
                        <div class="col-md-6" style="margin-left:25px;">
                           <!-- Please check the type of wallet -->
                           <label class="radio">
                           <input id="C_Wallet" name="FromWallet" type="radio" value="1"/>&nbsp; &nbsp;&nbsp; &nbsp;C-Wallet<code> <?php echo number_format($c_wallet); ?> USD </code>
                           </label>
                           <label class="radio">
                           <input id="R_Wallet" name="FromWallet" type="radio" value="2"/>&nbsp; &nbsp;&nbsp; &nbsp;R-Wallet<code> <?php echo number_format($r_wallet); ?> USD </code>
                           </label>
                         <span id="fromWallet-error" class="field-validation-error" style="display: none;">
                           <span>Wallet</span>
                           </span>
                        </div>
                     </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="firstname">
                                <?php echo $lang['text_Amount'] ?>:
                                                        <!-- <span class="ast">*</span> -->
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input autocomplete="off" value="" class="form-control" id="Quantity" name="amount" placeholder='<?php echo $lang['text_Amount'] ?>' type="text" />
                                                         <span id="amount-error" class="field-validation-error" style="display: none;">
                           <span>Wallet</span>
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
                                                    <div class="col-sm-offset-2">
                                                            <div class="btn-toolbar mr-l">
                                                                <button type="submit" class="btn btn-primary"><?php echo $lang['text_ok'] ?></button>

                                                            </div>
                                                        </div>
                                                </div>
                                               
                                       
                                    </form>
                                </div>
                                
                            </div>
                          
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                                <h3 class="panel-title">History</h3>                 
                               
                                
                            </div>
                          <div class="panel-body">
                            <div class="row">
                                  <div role="tabpanel">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                      <li role="presentation" class="active">
                                        <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Transfer Credit</a>
                                      </li>
                                      <li role="presentation">
                                        <a href="#tab" aria-controls="tab" role="tab" data-toggle="tab">Received Credit</a>
                                      </li>
                                    </ul>
                                  
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                      <div role="tabpanel" class="tab-pane active" id="home">
                                          <div  id="no-more-tables">
                                             <table id="datatable" class="table table-striped table-bordered">
                                                <thead>
                                                   <tr>
                                                      <th>Type</th>
                                                      <th>Amount</th>
                                                      <th>Wallet</th>
                                                      <th>Account Received</th>
                                                      <th>Date</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   <?php foreach ($history as $value => $key){ ?>
                                                   <tr>
                                                      <td data-title="Type" align="left"><?php echo $key['type'] ?></td>
                                                      <td data-title="Amount" align="left">
                                                         <strong class="amount">- <?php echo $key['amount'] ?> USD</strong>
                                                      </td>
                                                      <td data-title="Wallet" align="left"><?php echo $key['wallet'] ?></td>
                                                      <td data-title="Account Received"  align="left"><?php echo $self -> get_username($key['customer_id_received']) ?></td>
                                                      <td data-title="Date" align="left">
                                                         <span class="title-date"><?php echo date("d/m/Y H:i A", strtotime($key['date'])); ?></span>
                                                      </td>
                                                   </tr>
                                                   <?php } ?>
                                                </tbody>
                                             </table>
                                             <?php echo $pagination; ?>
                                          </div>
                                      </div>
                                      <div role="tabpanel" class="tab-pane" id="tab">
                                        <div role="tabpanel" class="tab-pane active" id="home">
                                          <div  id="no-more-tables">
                                             <table id="datatable" class="table table-striped table-bordered">
                                                <thead>
                                                   <tr>
                                                      <th>Type</th>
                                                      <th>Amount</th>
                                                      <th>Wallet</th>
                                                      <th>Account Transfer</th>
                                                      <th>Date</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   <?php foreach ($history_received as $value => $key){ ?>
                                                   <tr>
                                                      <td data-title="Type" align="left">Received</td>
                                                      <td data-title="Amount" align="left">
                                                         <strong class="amount">+ <?php echo $key['amount'] ?> USD</strong>
                                                      </td>
                                                      <td data-title="Wallet" align="left"><?php echo $key['wallet'] ?></td>
                                                      <td data-title="Account Transfer"  align="left"><?php echo $self -> get_username($key['customer_id_send']) ?></td>
                                                      <td data-title="Date" align="left">
                                                         <span class="title-date"><?php echo date("d/m/Y H:i A", strtotime($key['date'])); ?></span>
                                                      </td>
                                                   </tr>
                                                   <?php } ?>
                                                </tbody>
                                             </table>
                                             <?php echo $pagination; ?>
                                          </div>
                                      </div>
                                      </div>
                                    </div>
                                  </div>
                                  
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
            url: "<?php echo $base;?>index.php?route=account/credit/getaccount",
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
<script type="text/javascript">
   if (location.hash === '#success') {
      alertify.set('notifier','delay', 100000000);
      alertify.set('notifier','position', 'top-right');
      alertify.success('Transfer Credit successfull !!!');
   }
   
</script>
<?php echo $self->load->controller('common/footer') ?>