<?php 
   $self -> document -> setTitle($lang['C_titleBank_Transfer']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['t_titleConfirm_'] ?></h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">

                                 <h3 class="panel-title pull-left"><?php echo $lang['C_titleConfirm'] ?></h3>                 
                                <div class="options pull-right">
                                    <div class="btn-toolbar">
                                        <span class="countdown" style="float:right; color:red" data-countdown="<?php echo $transferConfirm['date_finish'] ?>"></span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                             
                            <div class="panel-body">
                                <div class="row">
                                <div class="col-md-12">
                                <?php if (intval($transferConfirm['pd_satatus']) === 0 ) {?>
                                    <!-- <form id="comfim-pd" action="<?php echo $self -> url -> link('account/pd/confirmSubmit', '', 'SSL'); ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" value="<?php echo $self -> request -> get['token'] ?>" name="token"/> -->
                                <?php } ?>
                               
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <pre><?php echo $transferConfirm['account_number'] ?></pre>
                                                            <?php if(!$transferConfirm['image']){ ?>
                                                            <input type="file" name="avatar" id="file"  accept="image/jpg,image/png,image/jpeg,image/gif">  
                                                            <img id="blah" src="#" style="display:none ; margin-top:20px;" />
                                                            <?php }?>
                                                            <?php if($transferConfirm['image']){ ?>
                                                                <img style="max-width:100%" src="<?php echo $transferConfirm['image'] ?>" style="display:block ; margin-top:20px;" />
                                                            <?php } ?>
                                                            <div class="error-file alert alert-dismissable alert-danger" style="display:none; margin:20px 0px;">
                                                                <i class="fa fa-fw fa-times"></i><?php echo $lang['C_titleUpload']   ?> : 'jpeg', 'jpg', 'png', 'gif', 'bmp'
                                                            </div>                                                
                                                        </div>
                                                        <?php if (intval($transferConfirm['pd_satatus']) === 0 ) {?>
                                                            <div class="loading">

                                                            </div>
                                                           
                                                        <?php } ?>
                                                    </div>
                                                   
                                                   
                                                </div>
                                             
                                        </div>
                                    <?php if (intval($transferConfirm['pd_satatus']) === 0 ) {?>
                                    </form>
                                    <?php } ?>
                                </div>
                            </div>
                            </div>
                           
                        </div>
                    </div>
                    
                </div> <!-- End Row -->
   <!-- End row -->
</div>

<?php echo $self->load->controller('common/footer') ?>