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
                                <h3 class="panel-title"><?php echo $lang['t_title'] ?></h3>                 
                        
                            </div>

                             <?php if ($transferList) { ?>
                            <div class="panel-body">
                                
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                                        <table id="datatable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                      <th><?php echo $lang['NO'] ?>.</th>
                                                            <th><?php echo $lang['t_titleTRANSACTION'] ?></th>
                                                            <th><?php echo $lang['DATE_CREATED'] ?></th>
                                                            <th><?php echo $lang['t_titleACCOUNT'] ?></th>
                                                            <th><?php echo $lang['amount'] ?></th>
                                                            <th><?php echo ($lang['t_titlePD']) ?></th>
                                                            <th><?php echo ($lang['t_titleGD']) ?></th>
                                                            <th><?php echo ($lang['t_titleTIME']) ?></th>
                                                            <th><?php echo ($lang['t_titleConfirm']) ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $num = 1;foreach ($transferList as $key => $value) { ?>
                                                            <td data-title="<?php echo $lang['NO'] ?>.">1</td>
                                                            <td data-title="<?php echo $lang['t_titleTRANSACTION'] ?>">TC<?php echo $value['transfer_code']  ?></td>

                                                           

                                                            <?php if($getLanguage === 'vietnamese'){ ?>
                                                                <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("d/m/Y H:i:s", strtotime($value['date_added'])); ?></td>
                                                            <?php }else{?>
                                                                <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("m/d/Y H:i A", strtotime($value['date_added'])); ?></td>
                                                            <?php } ?>
                                                            <td data-title="<?php echo $lang['t_titleACCOUNT'] ?>"><?php echo $value['username'] ?></td>
                                                            <td data-title="<?php echo $lang['amount'] ?>"><?php echo number_format($value['amount']); ?> USD</td>
                                                            <td data-title="<?php echo ($lang['t_titlePD']) ?>" class="status">
                                                            <?php
                                                            if($getLanguage === 'english'){
                                                                switch (intval($value['pd_satatus'])) {
                                                                    case 0:
                                                                        echo '<span class="label label-inverse">Waitting</span>';
                                                                        break;
                                                                    case 1:
                                                                        echo '<span class="label label-success">Finished</span>';
                                                                        break;
                                                                    case 2:
                                                                        echo '<span class="label label-danger">Report</span>';
                                                                        break;
                                                                }
                                                            }else{
                                                                switch (intval($value['pd_satatus'])) {
                                                                case 0:
                                                                    echo '<span class="label label-inverse">Đang chờ</span>';
                                                                    break;
                                                                case 1:
                                                                    echo '<span class="label label-success">Kết thúc</span>';
                                                                    break;
                                                                case 2:
                                                                    echo '<span class="label label-danger">Báo cáo</span>';
                                                                    break;
                                                                }
                                                            }
                                                            ?>
                                                            </td>
                                                            <td data-title="<?php echo ($lang['t_titleGD']) ?>" class="status">
                                                            <?php
                                                            if($getLanguage === 'english'){
                                                                switch (intval($value['gd_status'])) {
                                                                    case 0:
                                                                        echo '<span class="label label-inverse">Waitting</span>';
                                                                        break;
                                                                    case 1:
                                                                        echo '<span class="label label-success">Accepted</span>';
                                                                        break;
                                                                    case 2:
                                                                        echo '<span class="label label-danger">Report</span>';
                                                                        break;
                                                                }
                                                            }else{
                                                                switch (intval($value['pd_satatus'])) {
                                                                case 0:
                                                                    echo '<span class="label label-inverse">Đang chờ</span>';
                                                                    break;
                                                                case 1:
                                                                    echo '<span class="label label-success">Kết thúc</span>';
                                                                    break;
                                                                case 2:
                                                                    echo '<span class="label label-danger">Báo cáo</span>';
                                                                    break;
                                                                }
                                                            }
                                                            ?>
                                                            </td>
                                                            <td data-title="<?php echo ($lang['t_titleTIME']) ?>" class="countdown text-danger" data-countdown="<?php echo $value['date_finish'] ?>">
                                                            </td>
                                                            <td ata-title="<?php echo ($lang['t_titleConfirm']) ?>">
                                                                <a md-ink-ripple="" class="btn btn-sm btn-success" href="<?php echo $self -> url -> link('account/pd/confirm', 'token='.$value['id'].'', 'SSL'); ?>"><?php echo $lang['t_titleConfirm_'] ?></a>
                                                            </td>
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
                    
                </div> <!-- End Row -->
   <!-- End row -->
</div>
  <script type="text/javascript">
            $(document).ready(function() {
                $('#datatable').dataTable();
            } );
        </script>

<?php echo $self->load->controller('common/footer') ?>