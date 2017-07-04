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
               <h3 class="panel-title pull-left"><?php echo $lang['heading_title'] ?></h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                    <!--  <a href="<?php echo $self->url->link('account/register', '', 'SSL'); ?>" class="btn btn-success">
                     <i class="fa fa-fw fa-plus"></i> Register New Member
                     </a> -->
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                   <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"><?php echo $lang['NO'] ?>.</th>
                                                        <th><?php echo $lang['USERNAME'] ?></th>
                                                        <!-- <th><?php echo $lang['LEVEL'] ?></th> -->
                                                        <!-- <th><?php echo $lang['DOWNLINE'] ?></th> -->
                                                        <th>PHONE NUMBER</th>
                                                        <th><?php echo $lang['EMAIL'] ?></th>
                                                        <th>PROVINCE</th>
                                                        <th><?php echo $lang['DATE'] ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $count = 1; foreach ($refferals as $key => $value) { ?>
                                                       <tr>
                                                        <td data-title="<?php echo $lang['USERNAME'] ?>." align="center"><?php echo $count ?></td>
                                                        <td data-title="<?php echo $lang['USERNAME'] ?>"><?php echo $value['username'] ?></td>
                                                      <!--   <td data-title="<?php echo $lang['LEVEL'] ?>">
                                                            <?php echo "Vip".(intval($value['level']) - 1) ?>
                                                        </td> -->
                                                      <!--   <td data-title="<?php echo $lang['DOWNLINE'] ?>" class="static-parent" data-link="<?php echo $self->url->link('account/refferal/getlevel', '', 'SSL'); ?>" >
                                                  
                                                            <div data-parent-id="<?php echo $value['customer_id'] ?>" class="static-tree">
                                                                <?php for ($i=0; $i < 6; $i++) { ?>
                                                                    Vip<?php echo $i ?> [<span class="z-<?php echo $i ?>"></span>] <?php echo $i < 5 ? '-' : '' ?>
                                                                <?php } ?>
                                                            </div>
                                                        </td> -->
                                                        <td data-title="PHONE NUMBER"><?php echo $value['telephone'] ?></td>
                                                        <td data-title="<?php echo $lang['EMAIL'] ?>"><?php echo $value['email'] ?></td>

                                                        <?php $zone = $self -> getZone($value['address_id']) ?>
                                                      
                                                         <td data-title="PROVINCE"><?php echo !empty($zone['name']) ? $zone['name'] : ''; ?></td>
                                                        <?php if($language === 'vietnamese'){ ?>
                                                            <td data-title="<?php echo $lang['DATE'] ?>"><?php echo date("d/m/Y ", strtotime($value['date_added'])); ?></td>
                                                        <?php }else{?>
                                                            <td data-title="<?php echo $lang['DATE'] ?>"><?php echo date("m/d/Y ", strtotime($value['date_added'])); ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php $count++; } ?>
                                                    
                                                </tbody>
                                            </table>

                     <?php echo $pagination; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- End Row -->
   <!-- End row -->
</div>
<?php echo $self->load->controller('common/footer') ?>