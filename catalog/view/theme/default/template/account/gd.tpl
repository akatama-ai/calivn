<?php 
   $self -> document -> setTitle($lang['heading_title']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['text_register_user'] ?></h3>
   </div>
   <!-- Form-validation -->

   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title"><?php echo $lang['text_register_user'] ?></h3>
              <div class="btn-toolbar pull-right">
                  <!-- <a href="<?php echo $self -> url -> link('account/gd/create', '', 'SSL'); ?>" class="btn btn-default"><i class="fa fa-fw fa-plus"></i><?php echo $lang['text_button_create'] ?></a> -->
               </div>
            <div class="clearfix"></div>
            </div>
            <?php if($gds){ ?>
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                     <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                             <th class="text-center"><?php echo $lang['NO'] ?>.</th>
                                                        <th><?php echo $lang['GD_NUMBER'] ?></th>
                                                        <th><?php echo $lang['AMOUNT'] ?></th>
                                                        <th><?php echo $lang['DATE_CREATED'] ?></th>
                                                        <th><?php echo $lang['STATUS'] ?></th>
                                                         <!-- <th><?php echo $lang['TIME_REMAIN'] ?></th> -->
                                                        <!-- <th><?php echo $lang['TRANSFER'] ?></th> -->
                           </tr>
                        </thead>
                        <tbody>
                           <?php $total = 0; $num = 1; foreach ($gds as $value => $key){ ?>
                           <tr>
                              <td data-title="<?php echo $lang['NO'] ?>." align="center"><?php echo $num ?></td>
                                                        <td data-title="<?php echo $lang['GD_NUMBER'] ?>" >#<?php echo $key['gd_number'] ?></td>
                                                        <td data-title="<?php echo $lang['AMOUNT'] ?>"><?php echo number_format($key['amount']); ?> USD</td>

                                                        <?php if($getLanguage === 'vietnamese'){ ?>
                                                            <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("d/m/Y H:i:s", strtotime($key['date_added'])); ?></td>
                                                        <?php }else{?>
                                                            <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("m/d/Y H:i:A", strtotime($key['date_added'])); ?></td>
                                                        <?php } ?>
                                                        <td data-title="<?php echo $lang['STATUS'] ?>" class="status">
                                                            <?php
                                                            if($getLanguage === 'english'){
                                                                switch ($key['status']) {
                                                                    case 0:
                                                                        echo '<span class="label label-inverse">Waitting</span>';
                                                                        break;
                                                                    case 1:
                                                                        echo '<span class="label label-info">Matched</span>';
                                                                        break;
                                                                    case 2:
                                                                        echo '<span class="label label-success">Finish</span>';
                                                                        break;
                                                                    case 3:
                                                                        echo '<span class="label label-danger">Report</span>';
                                                                        break;
                                                                }
                                                            }
                                                            if($getLanguage === 'vietnamese'){
                                                                switch ($key['status']) {
                                                                    case 0:
                                                                        echo '<span class="label label-inverse">Đang chờ</span>';
                                                                        break;
                                                                    case 1:
                                                                        echo '<span class="label label-info">Khớp lệnh</span>';
                                                                        break;
                                                                    case 2:
                                                                        echo '<span class="label label-success">Kết thúc</span>';
                                                                        break;
                                                                    case 3:
                                                                        echo '<span class="label label-danger">Báo cáo</span>';
                                                                        break;
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                       <!--  <td data-title="<?php echo $lang['TIME_REMAIN'] ?>"> <span style="color:red; font-size:15px;" class="text-danger countdown" data-countdown="<?php echo $key['date_finish']; ?>">
                                                          </span> 
                                                       </td> -->
                                                        <!-- <td data-title="<?php echo $lang['TRANSFER'] ?>"><a class="label label-success" href="<?php echo intval($key['status']) !== 0 ? $self -> url -> link('account/gd/transfer', 'token='.$key["id"].'', 'SSL') : 'javascript:;' ?>"><?php echo $lang['TRANSFER_'] ?></a></td> -->

                           </tr>
                           <?php  $total = $total + intval($key['amount']);  $num++; } ?>
                             <tr>
                                                              <td colspan="3"><div class="text-right">Total: <?php echo number_format($total) ?> USD</div></td>
                                                            </tr>
                        </tbody>
                     </table>
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
<script type="text/javascript">
   $(document).ready(function() {
       $('#datatable').dataTable();
   } );
</script>
<?php echo $self->load->controller('common/footer') ?>