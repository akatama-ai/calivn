<?php 
   $self->document->setTitle($lang['text_dashboard']);
   echo $self->load->controller('common/header'); echo $self->load->controller('common/column_left');
   
   ?>

<div class="wraper container-fluid">

   <div class="page-title">
      <h3 class="title"><?php echo $lang['text_dashboard'] ?></h3>    
   </div>
   <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <div class="widget-panel widget-style-2 white-bg">
            <div class="dash-item">
               <div class="tile-image">
                  <img src="catalog/view/theme/default/img/bouns.png">
               </div>
               <div class="tile-footer">
                  <p class=""><?php echo $lang['c_wallet'] ?></p>
                  <p class="m-0 counter c-wallet" data-link="<?php echo $self->url->link('account/dashboard/getCWallet', '', 'SSL'); ?>">Loading ...</p>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <div class="widget-panel widget-style-2 white-bg">
            <div class="tile-image">
               <img src="catalog/view/theme/default/img/rwallet.png">
            </div>
            <div class="tile-footer">
               <p class=""><?php echo $lang['r_wallet'] ?></p>
               <p class="m-0 counter r-wallet" data-link="<?php echo $self->url->link('account/dashboard/getRWallet', '', 'SSL'); ?>">loading ...</p>
            </div>
         </div>
      </div>
     
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <div class="widget-panel widget-style-2 white-bg">
            <div class="dash-item">
               <div class="tile-image">
                  <img src="catalog/view/theme/default/img/pin.png">
               </div>
               <div class="tile-footer">
                  <p class=""><?php echo $lang['pinBalance']; ?></p>
                  <p class="m-0 counter pin-balence" data-link="<?php echo $self->url->link('account/dashboard/totalpin', '', 'SSL'); ?>">loading ...</p>
               </div>
            </div>
         </div>
      </div>
     <!--  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <div class="widget-panel widget-style-2 white-bg">
            <div class="tile-image">
               <img src="catalog/view/theme/default/img/ph.png">
            </div>
            <div class="tile-footer">
               <p class=""><?php echo $lang['provideDonation']; ?></p>
               <p class="m-0 counter pd-count" data-link="<?php echo $self->url->link('account/dashboard/countPD', '', 'SSL'); ?>">loading ...</p>
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <div class="widget-panel widget-style-2 white-bg">
            <div class="tile-image">
               <img src="catalog/view/theme/default/img/gh.png">
            </div>
            <div class="tile-footer">
               <p class=""><?php echo $lang['getDonation']; ?></p>
               <p class="m-0 counter gd-count" data-link="<?php echo $self->url->link('account/dashboard/countGD', '', 'SSL'); ?>">loading ...</p>
            </div>
         </div>
      </div> -->
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <div class="widget-panel widget-style-2 white-bg">
            <div class="tile-image">
               <img src="catalog/view/theme/default/img/tree.png">
            </div>
            <div class="tile-footer">
               <p class=""><?php echo $lang['downlineTree']; ?></p>
               <p class="m-0 counter downline-tree" data-link="<?php echo $self->url->link('account/dashboard/totaltree', '', 'SSL'); ?>">loading ...</p>
            </div>
         </div>
      </div>
      
   </div>
   <!-- end row -->
   <!-- End row -->
   <div class="row rule">
      <!-- <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title"><?php echo $lang['downlineTreeAnalytics']; ?></h3>
            </div>
            <div class="panel-body">
               
              <table class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th style="border-width:1px" width="50%"><?php echo $lang['level'] ?></th>
                          <th style="border-width:1px" width="50%"><?php echo $lang['total'] ?></th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php for ($i=0; $i < 6; $i++) { ?>
                         <tr>
                              <td><code>Vip <?php echo $i ?></code></td>
                              <td data-level="<?php echo $i + 1 ?>" data-id="<?php echo $self->session -> data['customer_id'] ?>" data-link="<?php echo $self->url->link('account/dashboard/analytics', '', 'SSL'); ?>" class="analytics-tree analytics-tree-loading">loading ...</td>
                          </tr>
                      <?php } ?>
                      
                  </tbody>
              </table>
                
            </div>
         </div>
      </div> -->
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title"><?php echo $lang['introduct']; ?></h3>
            </div>
            <div class="panel-body">
                <div class="media-body innerAnnounce">
                    <h4 class="heading" style="margin-bottom: 0px;"> <i class="fa fa-link">
                      </i>
                      <?php
                          if($language === 'english'){
                              echo 'Invite Link <small>(copy this and share for your friend): </small>';
                          }
                          if($language === 'vietnamese'){
                             echo 'Liên kết giới thiệu <small>(sao chép và chia sẻ cho bạn bè của bạn): </small>';
                          }
                          
                      ?> 
      
                  </h4>
                    <span><u><a target="_blank" href="<?php echo HTTPS_SERVER.'registers&token='.$self->session -> data['customer_id'];  ?>" ><?php echo HTTPS_SERVER.'registers&token='.$self->session -> data['customer_id'];  ?></a></u></span>
                  </div>
                
            </div>
         </div>
      </div>
   </div>
   <div class="row rule" style="margin-top:0;">
      <div class="col-md-12" id="anouncenment">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title"><?php echo $lang['announcement'] ?></h3>
            </div>
            <div class="panel-body" style="min-height:335px;">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <?php foreach ($article_limit as $key => $value): ?>
                     <div class="blog-item">
                        <p class="blog-title"><a href="<?php echo $self->url->link('account/dashboard/viewBlogs', 'token='.$value["simple_blog_article_id"].'', 'SSL') ?>"><?php echo $value['article_title'] ?></a></p>
                        <p><?php echo date("m/d/Y H:i:A", strtotime($value['date_added'])); ?></p>
                        <p><?php echo $value['short_description'] ?></p>
                     </div>
                     <?php endforeach; ?>
                     <?php echo $pagination; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
    
</div>
<!-- Page Content Ends -->
<!--
   <div class="col-md-12">
      <h3 class="panel-title">Invite Link (copy this and share for your friend): </h3>
      <span><u><a target="_blank" href="<?php echo $self -> url -> link('account/registers', 'token='.$self->session -> data['customer_id'].'', 'SSL'); ?>" ><?php echo $self -> url -> link('account/registers', 'token='.$self->session -> data['customer_id'].'', 'SSL'); ?></a></u></span>
   </div>
   -->
</div>
</section>
<script type="text/javascript">
   if (location.hash === '#success') {
      alertify.set('notifier','delay', 100000000);
      alertify.set('notifier','position', 'top-right');
      alertify.success('Create user successfull !!!');
   }
   
</script>
<?php echo $self->load->controller('common/footer') ?>