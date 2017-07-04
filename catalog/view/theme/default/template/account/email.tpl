<?php 
$self->document->setTitle('Email');
echo $self->load->controller('common/header'); echo $self->load->controller('common/column_left');

?>

<div class="container">
    <div id="wrapper">
        <div id="layout-static">
            <div class="static-content-wrapper">
                <div class="static-content">
                    <div class="page-content">
                        <ol class="breadcrumb">
                            <li>
                                <a href="<?php echo $self->url->link('account/dashboard', '', 'SSL'); ?>">Home</a>
                            </li>
                            <li style="padding:0">
                               &gt;
                            </li>
                            <li>
                                <a href="<?php echo $self->url->link('account/email', '', 'SSL'); ?>">Email</a>
                            </li>
                            <li style="padding:0">
                               &gt;
                            </li>
                            <li class="active">
                                <a href="javascript:void(0)">Create Email</a>
                            </li>
                        </ol>
                        <div class="page-heading mb0" style="margin-top:0px;">
                            <h1>Create Email</h1>
                        </div>
                        
                        <div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-edit-account" style="display:none">
                                        <i class="fa fa-check"></i> Send email successfull
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="frmCreateEmail" action="<?php echo $self->url->link('account/email/submit', '', 'SSL'); ?>" class="form-horizontal margin-none" method="post" novalidate="novalidate">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="firstname">
                                						Subject:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" id="Subject" name="Subject" type="text">
                                                        <span id="Subject-error" class="field-validation-error">
                                                            <span></span>
                                                        </span>
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="firstname">
						                                Message:
						                            </label>
                                                    <div class="col-md-8">
                                                        <textarea style="height:350px;" class="form-control" cols="20" id="Description" name="description" ></textarea>
                                                        <span id="Description-error" class="field-validation-error">
                                                            <span></span>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="firstname">
						                                
						                            </label>
						                            
						                            <div class="col-md-6">
						                            	<div class="loading"></div>
                                                    	<button type="submit" class="btn btn-primary">Send</button>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- #page-content -->
                </div>
            </div>
        </div>
    </div>
</div>