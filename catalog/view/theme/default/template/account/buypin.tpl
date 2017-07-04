<?php 
   $self -> document -> setTitle($lang['text_buy']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>

   <div class="wraper container-fluid">
      <div class="row none-chart">
      
        
       <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
         <div class="widget-panel widget-style-2 white-bg">
            <div class="dash-item">
               <div class="tile-image">
                  <img src="catalog/view/theme/default/img/pin.png">
               </div>
               <div class="tile-footer">
                  <p class="">PIN Current</p>
                  <p class="m-0 counter"><?php echo $pin; ?></p>
               </div>
            </div>
         </div>
      </div>
   </div>
      <div class="row ">
         <div class="col-md-12">
            <div class="panel panel-default">
            
               <div class="panel-heading">
               <h3 class="panel-title pull-left">Buy PIN</h3>
               <div class="options pull-right">
                 
               </div>
               <div class="clearfix"></div>
            </div>
              <div class="card-body table-responsive">
                  <div class="col-md-12">
                         <form id="frmBuyPin" action="<?php echo $self -> url -> link('account/token/buySubmit', '', 'SSL') ?>" method="POST">
                     <div class="col-md-4">
                        <h3><?php echo $lang['text_package_amount'] ?></h3>
                        <br/>
                        <div class="form-group">
                           <!-- <h4>PIN Package:</h4> -->
                           <div style="font-size:15px;"><i class="fa fa-check-square-o"></i> 1 PIN = 5 USD</div>
                        </div>
                         <select class="form-control valid" id="pin_price" name="pin_price">
                            <option value="">--Please Select--</option>
                              <option value="2">2 PIN</option>
                              <option value="4">4 PIN</option>
                              <option value="8">8 PIN</option>
                              <option value="16">16 PIN</option>
                              <option value="32">32 PIN</option>
                              <option value="64">64 PIN</option>
                          </select>
                        
                        <span id="pin_price-error" class="field-validation-error" style="display: none;">
                        <span></span>
                        </span>
                        <br/>
                        <button id="btnBuy" type="submit" autocomplete="off" class="btn btn-primary" >Buy</button>
                     </div>
                  </form>
                        <br>
                        <br>
                     </div>
                  </div>
            </div>
            <div class="card">
             <div class="card-header bgm-bluegray">
                   <h2>History
                   </h2>
               </div>
              <div class="card-body table-responsive" id="no-more-tables">
                  <div class="col-md-12">
                        <table id="" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                   <th class="text-center">No.</th>
                                                          <th>Date Create</th>
                                                          <th>Amount Pin</th>
                                                          <th>Amount BTC</th>
                                                          <th>Received (BTC)</th>
                                                          <th>Status</th>
                                                          <th>Wallet</th>
                                                          <th>QR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                         
                     <?php for ($i=0; $i < count($invoice); $i++) { ?> 
      
                                                            <tr>
                                                               <td data-title="No." align="center"><?php echo $i+1 ?></td>
                                                                <td data-title="Date Create"><?php echo date("Y-m-d H:i:A", strtotime($invoice[$i]['date_created'])); ?></td>
                                                                <td data-title="Amount Token"><?php echo $invoice[$i]['pin'] ?></td>
                                                                <td data-title="Amount BTC"><?php echo (intval($invoice[$i]['amount']) / 100000000) ?></td>
                                                                <td data-title="Received (BTC)">
                                                                    <?php echo (intval($invoice[$i]['received']) / 100000000) ?>
                                                                </td>
                                                                <td data-title="Status">
                                                                   <span class="label <?php echo intval($invoice[$i]['confirmations']) === 0 ? "label-warning" : 'label-success' ?>"><?php echo intval($invoice[$i]['confirmations']) === 0 ? "Pending" : 'Finish' ?></span>
                                                                </td>
                                                                <td data-title="Wallet">
                                                                    <?php echo $invoice[$i]['input_address'] ?>
                                                                </td>
                                                                <td data-title="QR"><img style="width: 80px;" src="https://chart.googleapis.com/chart?chs=150x150&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $invoice[$i]['input_address'] ?>?amount=<?php echo (intval($invoice[$i]['amount']) / 100000000) ?>"/></td>
                                                            </tr>
                                                       
   
                   
                     <?php } ?>
                        </tbody>
                                        </table>
                        <br>
                        <br>
                     </div>
                  </div>
            </div>
         </div>
      </div>
   </div>


<?php echo $self->load->controller('common/footer') ?>