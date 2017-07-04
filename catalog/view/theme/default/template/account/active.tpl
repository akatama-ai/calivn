<?php 
$self->document->setTitle('Active');
echo $self->load->controller('common/header'); echo $self->load->controller('common/column_left');

?>

<div id="wordwrap">
    <div class="container">
        <div class="bg-content">
            <div class="row">
                <div id="content" class="col-sm-12 login-index">
                    <div class="row row_login">
                        <div class="col-md-9 col-login-index">
                            <div class="header-login-logo"></div>
                            <?php if(intval($status) === 3) { ?>
                            <form id="activeSubmit" action="<?php echo $self->url->link('account/email/activesubmit', '', 'SSL'); ?>" method="post">
                                <div class="well w_login">
                                    <h2 class="text-center">Login</h2>
                                    <div class="input-group form-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </span>
                                        <input type="text" name="email" value="" placeholder="User name" id="input-email" class="form-control"/>
                                    </div>
                                    <div class="input-group form-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-key"></i>
                                        </span>
                                        <input type="password" name="password" value="" placeholder="Password" id="input-password" class="form-control"/>
                                    </div>

                                    <input type="hidden" name="token" value="<?php echo $token ?>" />
                                                     
                                    <div class="input-group form-group">
                                        <input type="submit" value="Active" class="btn-login"/>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>