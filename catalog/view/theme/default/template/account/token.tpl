<?php 
   $self -> document -> setTitle('Pin History'); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>

<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['heading_title'] ?></h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title pull-left"><?php echo $lang['heading_title'] ?></h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                     <!-- <a href="<?php echo $self->url->link('account/token/buypin', '', 'SSL'); ?>" class="btn btn-success"><i class="fa fa-fw fa-plus"></i><?php echo $lang['text_buy'] ?></a> -->
                     <a href="transfer-pin" class="btn btn-default"><i class="fa fa-fw fa-exchange "></i><?php echo $lang['text_button'] ?></a>
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <?php if(count($history) > 0){ ?>
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                     <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th><?php echo $lang['text_type'] ?></th>
                              <th><?php echo $lang['text_AMOUNT'] ?></th>
                              <th><?php echo $lang['text_SYSTEM'] ?></th>
                              <th><?php echo $lang['text_USER'] ?></th>
                              <th><?php echo $lang['text_DATE'] ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($history as $value => $key){ ?>
                           <tr>
                              <td data-title="<?php echo $lang['text_type'] ?>" align="left"><?php echo $key['type'] ?></td>
                              <td data-title="<?php echo $lang['text_AMOUNT'] ?>" align="left">
                                 <strong class="amount"><?php echo $key['amount'] ?></strong>
                              </td>
                              <td data-title="<?php echo $lang['text_SYSTEM'] ?>" align="left"><?php echo $key['system_description'] ?></td>
                              <td data-title="<?php echo $lang['text_USER'] ?>"  align="left"><?php echo !$key['user_description'] ? '&nbsp;' : $key['user_description'] ?></td>
                              <td data-title="<?php echo $lang['text_DATE'] ?>" align="left">
                                 <span class="title-date"><?php echo date("d/m/Y H:i A", strtotime($key['date_added'])); ?></span>
                              </td>
                           </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                     <?php echo $pagination; ?>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
   </div>
   <!-- End Row -->
   <!-- End row -->
</div>

<?php echo $self->load->controller('common/footer') ?>