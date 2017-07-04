<?php 
   $self -> document -> setTitle($lang['heading_title']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['c_titleConfirm'] ?></h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                 <h3 class="panel-title pull-left"><?php echo $lang['c_information'] ?></h3>                 
                                <div class="options pull-right">
                                    <div class="btn-toolbar">
                                        <span class="countdown" style="float:right; color:red" data-countdown="<?php echo $transferConfirm['date_finish'] ?>"></span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                           
                            <div class="panel-body">
                                <div class="row">
                                                <div class="col-md-4">
                                                    <h4><?php echo $lang['c_infoTranfer'] ?></h4>
                                                    <dl>
                                                        <dt><?php echo $lang['c_accountTranfer'] ?></dt>
                                                        <dd><?php echo $transferConfirm['username'] ?></dd>
                                                        <dt><?php echo $lang['c_accountAmount'] ?></dt>
                                                        <dd>
                                                            <code><?php echo number_format($transferConfirm['amount']); ?> VND</code>
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <?php if($transferConfirm['image']){?>
                                                    <div class="col-md-8">
                                                        <img style="max-width:100%" src="<?php echo $transferConfirm['image'] ;?>">
                                                    </div>
                                                <?php  } ?>
                                            </div>
                            </div>
                           
                        </div>
                    </div>
                    
                </div> <!-- End Row -->
   <!-- End row -->
</div>
  

<?php echo $self->load->controller('common/footer') ?>