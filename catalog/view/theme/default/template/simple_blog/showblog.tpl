<?php 
$self->document->setTitle('BLog');
echo $self->load->controller('common/header'); 
echo $self->load->controller('common/column_left');
?>
<div class="container">
    <div id="wrapper">
        <div id="layout-static">
            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">  
                               
 							<?php foreach ($detail_articles as $key => $value) { ?>	             
	                            <div class="detail_articles text-center" data-url="/Home/Announcement?id=3">
	                                <h3>
	                                	<?php echo html_entity_decode($value['article_title'], ENT_QUOTES, 'UTF-8')?>
	                                </h3>
	                                
	                                <p>
	                                    <?php echo html_entity_decode($value['description'], ENT_QUOTES, 'UTF-8')  ?>
	                                </p>
	                            </div>
	                        <?php  } ?>
                    </div>
                    <!-- #page-content -->
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .logo > a {margin: auto !important;}
</style>
<?php echo $self->load->controller('common/footer') ?>