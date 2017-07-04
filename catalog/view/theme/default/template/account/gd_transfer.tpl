<?php 
   $self -> document -> setTitle($lang['transferTitle']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['transferTitle'] ?></h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo $lang['transferTitle'] ?></h3>                 
                        
                            </div>
                             <?php if ($transferList) { ?>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                                        <table id="datatable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $lang['NO'] ?>.</th>
                                                            <th><?php echo $lang['TRANSACTION'] ?></th>
                                                            <th><?php echo $lang['DATE_CREATED'] ?></th>
                                                            <th><?php echo $lang['ACCOUNT_TRANSFER'] ?></th>
                                                            <th><?php echo $lang['AMOUNT'] ?></th>
                                                            <th><?php echo $lang['transferPDStatus'] ?></th>
                                                            <th><?php echo $lang['transferGDStatus'] ?></th>
                                                            <th><?php echo $lang['transferTime'] ?></th>
                                                            <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $num = 1; foreach ($transferList as $key => $value) {?>
                                                        
                                                            <tr>
                                                                <td data-title="<?php echo $lang['NO'] ?>." ><?php echo $num ?></td>
                                                                <td data-title="<?php echo $lang['TRANSACTION'] ?>">TC<?php echo $value['transfer_code'] ?></td>
                                                                <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("m/d/Y H:i A", strtotime($value['date_added'])); ?></td>
                                                                <td data-title="<?php echo $lang['ACCOUNT_TRANSFER'] ?>"><?php echo $value['username'] ?></td>
                                                                <td data-title="<?php echo $lang['AMOUNT'] ?>"><?php echo number_format($value['amount']); ?> USD</td>
                                                                <td data-title="<?php echo $lang['transferPDStatus'] ?>" class="status">
                                                                    <?php
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
                                                                    ?>
                                                                </td>
                                                                <td data-title="<?php echo $lang['transferGDStatus'] ?>" class="status">
                                                                <?php
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
                                                                ?>
                                                                </td>
                                                                <td data-title="<?php echo $lang['transferTime'] ?>" class="countdown" data-countdown="<?php echo $value['date_finish'] ?>">
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-success" href="<?php echo $self -> url -> link('account/gd/confirm', 'token='.$value['id'].'', 'SSL'); ?>">Info</a>
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