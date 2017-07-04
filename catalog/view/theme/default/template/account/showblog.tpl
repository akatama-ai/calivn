<?php 
   $self -> document -> setTitle(html_entity_decode($detail_articles['article_title'], ENT_QUOTES, 'UTF-8')); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title">View Blog</h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default" style="min-height:600px;">
            <div class="panel-heading">
               <h3 class="panel-title pull-left"><?php echo html_entity_decode($detail_articles['article_title'], ENT_QUOTES, 'UTF-8')?></h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                     <a href="<?php echo $self->url->link('account/dashboard', '', 'SSL'); ?>" class="btn btn-warning"><i class="fa fa-fw fa-undo"></i>Back</a>
                   
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <div class="panel-body panel-custom">
              <div class="col-md-12">
                 <div class="blog-detail">
                    <?php echo html_entity_decode($detail_articles['description'], ENT_QUOTES, 'UTF-8')  ?>
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