<?php 
$self->document->setTitle('Order Pin');
echo $self->load->controller('common/header'); 
echo $self->load->controller('common/column_left');
?>
<div class="container">
    <div id="wrapper">
        <div id="layout-static">
            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb">
                            <li>
                                <a href="<?php echo $self->url->link('account/dashboard', '', 'SSL'); ?>">Home</a>
                            </li>
                            <li style="padding:0">
                               >
                            </li>
                            <li>
                                <a href="<?php echo $self->url->link('account/token', '', 'SSL'); ?>">Pin</a>
                            </li>
                            <li style="padding:0">
                               >
                            </li>
                            <li class="active">
                                <a href="javascript:void(0)">Order Pin</a>
                            </li>
                        </ol>
                        <div class="page-heading mb0" style="margin-top:0px;">
                            <h1>Order Pin</h1>
                        </div>
                        
                        <div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-edit-account" style="display:none">
                                        <i class="fa fa-check"></i> Edit account successfull
                                    </div>
                                </div>
                                <?php if(array_key_exists('token' , $self -> request -> get)) { ?>
                                <div class="col-md-12">
                                    <div class="alert alert-danger" style="display:block">
                                        <i class="fa fa-check"></i> Please buy pin for PD<?php echo $self -> request -> get['token']; ?>.
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <form  id="frmCreateOrderPin" action="<?php echo $self->url->link('account/token/ordersubmit', '', 'SSL'); ?>" class="form-horizontal margin-none" method="post" novalidate="novalidate">
                                        <div class="panel panel-default">
                                            <div class="panel-body">    
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="pin">
                               							 Amount:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input autocomplete="off" value="" class="form-control" id="Quantity" name="pin" type="text" />
                                                        <span id="Quantity-error" class="field-validation-error">
                                                            <span></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="pin">
                               							 Transfer:
                                                    </label>
                                                    <div class="col-md-6">
                                                        1 Pin = $20
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" >
                                						Bitcoin Wallet Address
                                                        <!-- <span class="ast">*</span> -->
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input value="<?php echo $self->config->get('config_wallet'); ?>" class="form-control" readonly="true" type="test"/>
                                                        <span id="TransferPassword-error" class="field-validation-error">
                                                            <span></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" >
                                						QR Code
                                                        <!-- <span class="ast">*</span> -->
                                                    </label>
                                                    <div class="col-md-6">
                                                        <img src="https://blockchain.info//qr?data=<?php echo $self->config->get('config_wallet'); ?>&size=150" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" >
                                						
                                                        <!-- <span class="ast">*</span> -->
                                                    </label>
                                                    <div class="col-md-6">
                                                       <label for="BitcoinWalletAddress">Please transfer <b style="font-size:15px;">$<span id="transfer_usd">0</span></b> to above wallet to active the account via <a target="_blank" href="https://blockchain.info/"><b>blockchain.info</b> </a>, then click Confirm</label>
                                                    </div>
                                                    <input type="hidden" name="totalMoney" id="totalMoney"/>
                                                </div>

                                                
                                                
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" >
                                                        <!-- <span class="ast">*</span> -->
                                                    </label>
                                                    <div class="col-md-6">
                                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                                        <a id="cancel" href="<?php echo $self->url->link('account/token', '', 'SSL'); ?>" class="btn btn-default">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- #page-content -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $self->load->controller('common/footer') ?>