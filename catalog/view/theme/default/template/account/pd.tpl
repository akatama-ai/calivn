<?php 
   $self -> document -> setTitle($lang['heading_title']); 
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
               <h3 class="panel-title pull-left"><?php echo $lang['text_register_user'] ?></h3>
              
               <div class="options pull-right">
                  <div class="btn-toolbar">
                  <?php if (count($pds)<=0) {
                   ?>
               
                     <a href="create-deposit" class="btn btn-default"><i class="fa fa-fw fa-plus"></i><?php echo $lang['text_button_create'] ?></a>

                     <?php   } ?>
                  </div>
               </div>
                <!-- <?php if ($countPd == 0 ){ ?> -->
               <!-- <?php } ?> -->
               <div class="clearfix"></div>
            </div>
            <?php if(count($pds) > 0){ ?>
            <div class="panel-body">
               <div class="row" id="no-more-tables">
                  <table id="datatable" class="table table-striped table-bordered">
                     <thead>
                        <tr>
                           <th class="text-center"><?php echo $lang['NO'] ?>.</th>
                           <th><?php echo $lang['ACCOUNT'] ?></th>
                           <th><?php echo $lang['DATE_CREATED'] ?></th>
                           <th><?php echo $lang['PD_NUMBER'] ?></th>
                           <th><?php echo $lang['FILLED'] ?></th>
                           <!-- <th><?php echo $lang['MAX_PROFIT'] ?></th> -->
                           <th><?php echo $lang['STATUS'] ?></th>
                           <th><?php echo $lang['TIME_REMAIN'] ?></th>
                           <!-- <th><?php echo $lang['TRANSFER'] ?></th> -->
                        </tr>
                     </thead>
                     <tbody>
                        <?php $num = 1; foreach ($pds as $value => $key){ ?>
                        <tr>
                           <td data-title="<?php echo $lang['NO'] ?>" align="center"><?php echo $num ?></td>
                           <td data-title="<?php echo $lang['ACCOUNT'] ?>"><?php echo $key['username'] ?></td>
                           <?php if($getLanguage === 'vietnamese'){ ?>
                           <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("d/m/Y H:i:s", strtotime($key['date_added'])); ?></td>
                           <?php }else{?>
                           <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("m/d/Y H:i:A", strtotime($key['date_added'])); ?></td>
                           <?php } ?>
                           <td data-title="<?php echo $lang['PD_NUMBER'] ?>">#<?php echo $key['pd_number'] ?></td>
                           <td data-title="<?php echo $lang['FILLED'] ?>"><?php echo number_format($key['filled']); ?> USD</td>
                           <!-- <td data-title="<?php echo $lang['MAX_PROFIT'] ?>"><?php echo number_format($key['max_profit']); ?> USD</td> -->
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
                           <td data-title="<?php echo $lang['TIME_REMAIN'] ?>"> <span style="color:red; font-size:15px;" class="text-danger countdown" data-countdown="<?php echo intval($key['status']) == 0 ? $key['date_finish_forAdmin'] : $key['date_finish']; ?>">
                              </span> 
                           </td>
                         <!--   <td data-title="<?php echo ($lang['TRANSFER']) ?>">
                              <!-- && intval($key['status']) !== 3 -->
                             <!-- <a href="<?php echo intval($key['status']) !== 5 ? $self -> url -> link('account/pd/transfer', 'token='.$key["id"].'', 'SSL') : 'javascript:;' ?>"><?php echo $lang['TRANSFER'] ?></a>
                           </td> -->
                        </tr>
                        <?php $num++; } ?>
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
<?php echo $self->load->controller('common/footer') ?>