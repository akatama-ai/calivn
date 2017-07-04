<?php 
   if(!$notCreate) $self -> document -> setTitle($lang['text_blockchain_confirm'] . ($bitcoin / 100000000).' BTC');
   else $self -> document -> setTitle("You can not create more orders !!!!");
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>

   <div class="wraper container-fluid">
      <div class="row ">
         <div class="col-md-12">
            <div class="card">
             <div class="card-header bgm-bluegray">
                   <h2><?php echo $lang['text_blockchain_confirm'] ?>: <?php echo ($bitcoin / 100000000) ?> BTC
                   </h2>
               </div>
              <div class="card-body table-responsive" id="no-more-tables">
                  <div class="col-md-12">
                        <h3><?php echo $lang['text_blockchain'] ?></h3>
                        <br/>
                        <img src="https://chart.googleapis.com/chart?chs=200x200&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $wallet ?>?amount=<?php echo ($bitcoin / 100000000) ?>"/>
                        <br/>
                        <h3><?php echo $lang['text_blockchain_wallet'] ?></h3>
                        <br/>
                        <code><?php echo $wallet ?></code>
                        <br/>
                        <br/>
                        <code id="websocket"><?php echo $lang['text_blockchain_received'] ?>: 0 BTC</code>
                        <br>
                        <br>
                     </div>
                  </div>
            </div>
         </div>
      </div>
   </div>



<?php echo $self->load->controller('common/footer') ?>

<?php if(!$notCreate) { ?>
<script type="text/javascript">
   var str = 'We are waiting for 3 confirmation from <a style="color: #f0ad4e;" href="https://blockchain.info/" target="_blank">blockchain.info</a>';
   $('#websocket').html(str);
</script>
<?php }?>