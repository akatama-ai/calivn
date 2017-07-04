$( document ).ready(function() {

function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result).show().css({'width': '150px','height':'150px'});
               
                $('#btn_save').show();
                 $('#old_img').hide();
            }

            reader.readAsDataURL(input.files[0]);
        }else{
            $('#blah').hide();
        }
    }
    $("#file").on('change' , function (env) {

         $('#old_img').hide();
        readURL(this);
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            if($("#file").val()){
               $('.error-file').show(); 
           }else{
                $('.error-file').hide(); 
           }
            $('#comfim-pd').resetForm();
        }else{
            $('.error-file').hide();
             $('#old_img').hide();
        }
    });
function readURLs(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blahs').attr('src', e.target.result).show().css({'width': '250px','height':'250px','display':'block'});
               
                $('#btn_saves').show();
                 $('#old_imgs').hide();
            }

            reader.readAsDataURL(input.files[0]);
        }else{
            $('#blahs').hide();
        }
    }
    $("#files").on('change' , function (env) {

         $('#old_imgs').hide();
        readURLs(this);
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            if($("#files").val()){
               $('.error-file').show(); 
           }else{
                $('.error-file').hide(); 
           }
            $('#comfim-pds').resetForm();
        }else{
            $('.error-file').hide();
             $('#old_imgs').hide();
        }
    });
    
  $('#reset_passwdTran').on('click', function(){
      $('.alert-edit-account').hide();
          var self = $(this);
          window.funLazyLoad.start();
          window.funLazyLoad.show();

          setTimeout(function(){
              $.ajax({
                  url: self.data('link'),
                  type: 'GET',
                  data: {
                      'id': self.data('id')
                  },
                  async: false,
                  success: function(result) {
                      result = $.parseJSON(result);
                      _.has(result, 'ok') && result.ok === 1 && window.funLazyLoad.reset();
                      alert('One email for reset has been sent to your email. Please check your email.');

                  }
              });
          }, 1500);
  });
    window.funLazyLoad.start();
    $.fn.existsWithValue = function() {
        return this.length && this.val().length;
    };
    var emailPass = null;
    var phonePass = null;

    $('div.page-tabs ul li a').on('click', function() {
        $('.alert-edit-account').hide();
        return true;
    });

    var parseQueryString = function() {

        var str = window.location.search;
        var objURL = {};

        str.replace(
            new RegExp("([^?=&]+)(=([^&]*))?", "g"),
            function($0, $1, $2, $3) {
                objURL[$1] = $3;
            }
        );
        return objURL;
    };

    var paramt = parseQueryString();

    switch (paramt['success']) {
        case 'account':
            $('.alert-edit-account').show().html('<i class="fa fa-check"></i>'+ window.text_success_account);
            break;
        case 'password':
            $('.page-tabs ul li a[href="#ChangePassword"]').trigger('click')
            $('.alert-edit-account').show().html('<i class="fa fa-check"></i>'+ window.text_success_password );
            break;
        case 'transaction':
            $('.page-tabs ul li a[href="#ChangePassword2"]').trigger('click')
            $('.alert-edit-account').show().html('<i class="fa fa-check"></i>' + window.text_success_tranpassword);
            break;
    }

    $('input#Phone').keydown(function(event) {
        if (event.keyCode === 13) {
            return true;
        }
        if (!(event.keyCode == 8 || event.keyCode == 46 || (event.keyCode >= 35 && event.keyCode <= 40) || (event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105))) {
            event.preventDefault();
        }
    });
    var funSetting = {
        errorReset: function() {
            $('#Email-error span').hide().html('');
            $('#Email').parent().removeClass('has-error');
            $('#Phone-error span').hide().html('');
            $('#Phone').parent().removeClass('has-error');
            $('#transaction_password').parent().removeClass('has-error');
            $('#transaction_password-error span').hide().html('');
        },

        isValidEmailAddress: function(email, callback) {
            var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
            callback(pattern.test(email));
        },
        loadingBanks: function(callback) {
            $.ajax({
                url: $('#Banksinfo').data('link'),
                type: 'GET',
                data: {
                    'id': $('#Banksinfo').data('id')
                },
                async: false,
                success: function(result) {

                    callback($.parseJSON(result));
                }
            });
        },
        loadingAccount: function(callback) {
            $.ajax({
                url: $('#EditProfile').data('link'),
                type: 'GET',
                data: {
                    'id': $('#EditProfile').data('id')
                },
                async: false,
                success: function(result) {
                    callback($.parseJSON(result));
                }
            });
        },
        account_edit_check_email: function(callback) {
            if ($('#Email').existsWithValue() !== 0) {
                window.funLazyLoad.show();
                $.ajax({
                    url: $('#Email').data('link'),
                    type: 'GET',
                    data: {
                        'email': $('#Email').val()
                    },
                    async: false,
                    success: function(result) {
                        result = $.parseJSON(result);
                        callback(result.success === 0);
                    }
                });
            }
        },
        checkPhoneExit: function(callback) {
            if ($('#Phone').existsWithValue() !== 0) {
                window.funLazyLoad.show();
                $.ajax({
                    url: $('#Phone').data('link'),
                    type: 'GET',
                    data: {
                        'phone': $('#Phone').val()
                    },
                    async: false,
                    success: function(result) {
                        result = $.parseJSON(result);
                        callback(result.success === 0);
                    }
                });
            }
        },
        checkPasswdTran: function(callback) {
            if ($('#transaction_password').existsWithValue() !== 0) {
                window.funLazyLoad.show();
                $.ajax({
                    url: $('#transaction_password').data('link'),
                    type: 'GET',
                    data: {
                        'pwtranction': $('#transaction_password').val()
                    },
                    async: false,
                    success: function(result) {
                        result = $.parseJSON(result);
                        callback(parseInt(result) === 1);
                    }
                });
            }
        }
    };

    var frChangePassword = {
        errorReset: function() {
            $('#OldPassword-error span').hide().html('');
            $('#OldPassword').parent().removeClass('has-error');
            $('#Password-error span').hide().html('');
            $('#Password').parent().removeClass('has-error');
        },
        checkOldPasswd : function(callback){
            if ($('#OldPassword').existsWithValue() !== 0) {
                window.funLazyLoad.show();
                $.ajax({
                    url: $('#OldPassword').data('link'),
                    type: 'GET',
                    data: {
                        'passwd': $('#OldPassword').val()
                    },
                    async: false,
                    success: function(result) {
                        result = $.parseJSON(result);
                        callback(parseInt(result) === 1);
                    }
                });
            }
        },
    };



    var frChangePasswordTransaction= {
        errorReset: function() {
            $('#TranoldPassword-error span').hide().html('');
            $('#TranoldPassword').parent().removeClass('has-error');
            $('#Tranpassword_New-error span').hide().html('');
            $('#Tranpassword_New').parent().removeClass('has-error');
            $('#TranConfirmPassword-error span').hide().html('');
            $('#TranConfirmPassword').parent().removeClass('has-error');
        },
        checkTranoldPassword : function(callback){
            if ($('#TranoldPassword').existsWithValue() !== 0) {
                window.funLazyLoad.show();
                $.ajax({
                    url: $('#TranoldPassword').data('link'),
                    type: 'GET',
                    data: {
                        'pwtranction': $('#TranoldPassword').val()
                    },
                    async: false,
                    success: function(result) {
                        result = $.parseJSON(result);
                        callback(parseInt(result) === 1);
                    }
                });
            }
        },
    };


    $('#changePasswdTransaction').on('submit', function() {
        frChangePasswordTransaction.errorReset();
        if (!$('#TranoldPassword').existsWithValue()) {
            $('#TranoldPassword').parent().addClass('has-error');

            $('#TranoldPassword-error span').show().html(window.text_error_old_password);
            return false;
        };

        $('#TranoldPassword').parent().addClass('has-success');

        if (!$('#Tranpassword_New').existsWithValue()) {
            $('#Tranpassword_New').parent().addClass('has-error');
            $('#Tranpassword_New-error span').show().html(window.text_error_new_password);
            return false;
        };

        if($('#Tranpassword_New').val() !== $('#TranConfirmPassword').val()){
            $('#TranConfirmPassword').parent().addClass('has-error');
            $('#TranConfirmPassword-error span').show().html(window.text_error_confirm_password);
            return false;
        }else{
            $('#Tranpassword_New').parent().addClass('has-success');
            $('#TranConfirmPassword').parent().addClass('has-success');
        }
       

        var checkPasswdTran = null;

        //check passwd transaction
        frChangePasswordTransaction.checkTranoldPassword(function(callback) {
            window.funLazyLoad.reset();
            checkPasswdTran = callback;
        });
        if (!checkPasswdTran) {
            $('#TranoldPassword').parent().addClass('has-error');
            $('#TranoldPassword-error span').show().html(window.error_tranpassword_re);
            return false;
        } else {
            $('#TranoldPassword').parent().addClass('has-success');
        }

        if(checkPasswdTran){
            window.funLazyLoad.reset();
            return true;
        }


        return false;
    });


    $('#frmChangePassword').on('submit', function() {
        frChangePassword.errorReset();
        if (!$('#OldPassword').existsWithValue()) {
            $('#OldPassword').parent().addClass('has-error');
            $('#OldPassword-error span').show().html(window.text_error_old_password);
            return false;
        };

        $('#OldPassword').parent().addClass('has-success');

        if (!$('#Password').existsWithValue()) {
            $('#Password').parent().addClass('has-error');
            $('#Password-error span').show().html(window.text_error_new_password);
            return false;
        };

        if($('#Password').val() !== $('#ConfirmPassword').val()){
            $('#ConfirmPassword').parent().addClass('has-error');
            $('#ConfirmPassword-error span').show().html(window.text_error_confirm_password);
            return false;
        }else{
            $('#Password').parent().addClass('has-success');
            $('#ConfirmPassword').parent().addClass('has-success');
        }
        var _checkOldPasswd = null;
        frChangePassword.checkOldPasswd(function(callback){
            window.funLazyLoad.reset();
            _checkOldPasswd = callback;
        });

        if(!_checkOldPasswd){
            $('#OldPassword').parent().addClass('has-error');
            $('#OldPassword-error span').show().html(window.error_old_tranpassword);
            return false;
        }else{
            $('#OldPassword').parent().addClass('has-success');
        }
        window.funLazyLoad.reset();
        if(_checkOldPasswd){
            return true;
        }
        return false;
    });


    $('#account_edit').on('submit', function() {

        funSetting.errorReset();
        if (!$('#Email').existsWithValue()) {
            $('#Email').parent().addClass('has-error');
            $('#Email-error span').show().html('Please enter email address');
            return false;
        };
        var checkIsEmail = null;
        funSetting.isValidEmailAddress($('#Email').val(), function(callback) {
            checkIsEmail = callback;
        })
        if (!checkIsEmail) {
            $('#Email').parent().addClass('has-error');
            $('#Email-error span').show().html('Please enter email address');
            return false;
        };


        if (!$('#Phone').existsWithValue()) {
            $('#Phone').parent().addClass('has-error');
            $('#Phone-error span').show().html('Please enter phone number');
            return false;
        };
        var checkPasswdTran = null;
        if (!$('#transaction_password').existsWithValue()) {
            $('#transaction_password').parent().addClass('has-error');
            $('#transaction_password-error span').show().html(window.error_tranpassword);
            return false;
        } else {
            //check passwd transaction
            funSetting.checkPasswdTran(function(callback) {
                window.funLazyLoad.reset();
                checkPasswdTran = callback;
            });
            if (!checkPasswdTran) {
                $('#transaction_password').parent().addClass('has-error');
                $('#transaction_password-error span').show().html(window.error_tranpassword_re);
                return false;
            } else {
                $('#transaction_password').parent().addClass('has-success');
            }
        }

        var checkEmailExit = null;
        var checkPhone = null;

        if (emailPass !== $('#Email').val()) {
            funSetting.account_edit_check_email(function(callback) {
                checkEmailExit = callback;
            });
        } else {
            checkEmailExit = true;
        }


        if (!checkEmailExit) {
            $('#Email').parent().addClass('has-error');
            $('#Email-error span').show().html('This email address is already exists');
            window.funLazyLoad.reset();
            return false;
        } else {
            $('#Email').parent().addClass('has-success');
            if (phonePass !== $('#Phone').val()) {
                funSetting.checkPhoneExit(function(callback) {
                    checkPhone = callback;
                });
            } else {
                checkPhone = true;
            }
            if (!checkPhone) {
                $('#Phone').parent().addClass('has-error');
                $('#Phone-error span').show().html('This phone is already exists');
                window.funLazyLoad.reset();
                return false;
            } else {
                $('#Phone').parent().addClass('has-success');
            }
        }
        window.funLazyLoad.reset();
        if (checkEmailExit && checkPhone && checkPasswdTran) {
            window.funLazyLoad.show();
            return true;
        }
        return false;
    });

    funSetting.loadingAccount(function(result) {
        _.has(result, 'username') && $('#UserName').val(result.username);
        _.has(result, 'email') && $('#Email').val(result.email);
        _.has(result, 'telephone') && $('#Phone').val(result.telephone);
        //wallet of user start
        if(_.has(result, 'wallet')){
            $('#BitcoinWalletAddress').val(result.wallet);
            var img_ = $('#bitcoin-image').data('img');
            img_ = img_ + result.wallet;
            $('#bitcoin-image img').attr('src', img_).parent().parent().show();
        }
        result.wallet === '' && $('#bitcoin-image').removeAttr('data-img').empty().hide();
        //wallet of user end
        emailPass = result.email;
        phonePass = result.telephone;

        if (_.has(result, 'level')) {
            result.level = parseInt(result.level);
            switch (result.level) {
                case 1:
                    $('#Level').html('Vip 0');
                    break;
                case 2:
                    $('#Level').html('Vip 1');
                    break;
                case 3:
                    $('#Level').html('Vip 2');
                    break;
                case 4:
                    $('#Level').html('Vip 3');
                    break;
                case 5:
                    $('#Level').html('Vip 4');
                    break;
                case 6:
                    $('#Level').html('Vip 5');
                    break;
            }
        };
        _.each($('#EditProfile').data(), function(v, e) {
            $('#EditProfile').removeAttr('data-' + e);
        });
        window.funLazyLoad.reset();
    });


    var walletCurrent = $('#BitcoinWalletAddress').val();

    $('#updateWallet').on('submit', function(){
        $('#BitcoinWalletAddress-error span').hide().html('');
        $('#BitcoinWalletAddress').parent().removeClass('has-error');
        $('#Password2-error span').hide().html('');
        $('#Password2').parent().removeClass('has-error');
        $('.account-wallet').hide();

        if (!$('#BitcoinWalletAddress').existsWithValue()) {
            $('#BitcoinWalletAddress').parent().addClass('has-error');
            $('#BitcoinWalletAddress-error span').show().html('Please enter your bitcoin wallet address');
            return false;
        }else{
            $('#BitcoinWalletAddress').parent().addClass('has-success');
        }
        if (!$('#Password2').existsWithValue()) {
            $('#Password2-error span').show().html(window.error_tranpassword);
            return false;
        }
        $(this).ajaxSubmit({
            type : 'GET',
            beforeSubmit : function(arr, $form, options) {
                $('#updateWallet button').hide();
                $('#updateWallet div.loading').show();
                if(arr[0].value === walletCurrent){
                    $('#updateWallet button').show();
                    $('#updateWallet div.loading').hide();
                    $('#BitcoinWalletAddress-error span').hide().html('');
                    $('#BitcoinWalletAddress').parent().removeClass('has-success');
                    $('#Password2-error span').hide().html('');
                    $('#Password2').parent().removeClass('has-success');
                    $('#Password2').val('');
                    return false;
                }

            },
            success : function(result){
                result = $.parseJSON(result);
                if(result.login === -1){
                    location.reload(true);
                }else{
                    if(result.password === -1){
                        $('#Password2').parent().addClass('has-error');
                        $('#Password2-error span').show().html(window.error_tranpassword_re);
                        $('#updateWallet button').show();
                        $('#updateWallet div.loading').hide();
                    }else{
                        $('#Password2').parent().addClass('has-success');
                    }
                    if (result.wallet === -1) {
                         $('#BitcoinWalletAddress').parent().addClass('has-error');
                        $('#walletbtc-error span').show().html('Wrong address for Bitcoin Wallet!');
                        $('#updateWallet button').show();
                        $('#updateWallet div.loading').hide();
                    }
                    else{
                        $('#BitcoinWalletAddress').parent().addClass('has-success');
                    }
                    if(result.ok === 1){
                        $('#updateWallet button').show();
                        $('#updateWallet div.loading').hide();
                        $('.account-wallet').show();
                        $('#BitcoinWalletAddress-error span').hide().html('');
                        $('#BitcoinWalletAddress').parent().removeClass('has-success');
                        $('#Password2-error span').hide().html('');
                        $('#Password2').parent().removeClass('has-success');
                        var img_ = $('#bitcoin-image').data('img');
                        img_ = img_ + $('#BitcoinWalletAddress').val();
                        $('#bitcoin-image img').attr('src', img_).parent().parent().show();
                        $('#Password2').val('');
                    }
                }
            }
        });
        return false;
    });

    var account_hoders = $('#Accountholders').val();
    var bank_name = $('#Bankname').val();
    var account_number = $('#Accountnumber').val();
    var branch_bank = $('#Branchbank').val();

    $('#updateBanks').on('submit', function(){

        $('#Accountholders-error span').hide().html('');
        $('#Accountholders').parent().removeClass('has-error');
        $('#Bankname-error span').hide().html('');
        $('#Bankname').parent().removeClass('has-error');
        $('#Accountnumber-error span').hide().html('');
        $('#Accountnumber').parent().removeClass('has-error');
        $('#Branchbank-error span').hide().html('');
        $('#Branchbank').parent().removeClass('has-error');

        $('.account-bank').hide();

        if (!$('#Accountholders').existsWithValue()) {
            $('#Accountholders').parent().addClass('has-error');
            $('#Accountholders-error span').show().html( window.text_error_account_holder);
            return false;
        }else{
            $('#Accountholders').parent().addClass('has-success');
        }

        if (!$('#Bankname').existsWithValue()) {
            $('#Bankname').parent().addClass('has-error');
            $('#Bankname-error span').show().html(window.text_error_bank_name);
            return false;
        }else{
            $('#Bankname').parent().addClass('has-success');
        }

        if (!$('#Accountnumber').existsWithValue()) {
            $('#Accountnumber').parent().addClass('has-error');
            $('#Accountnumber-error span').show().html(window.text_error_account_number);
            return false;
        }else{
            $('#Accountnumber').parent().addClass('has-success');
        }

        if (!$('#Branchbank').existsWithValue()) {
            $('#Branchbank').parent().addClass('has-error');
            $('#Branchbank-error span').show().html(window.text_error_branch);
            return false;
        }else{
            $('#Branchbank').parent().addClass('has-success');
        }


        $(this).ajaxSubmit({
            type : 'GET',
            beforeSubmit : function(arr, $form, options) {
                $('#updateBanks button').hide();
                $('#updateBanks div.loading').show();
                if($.isNumeric($('#Accountnumber').val()) === false){
                    $('#Accountnumber').parent().addClass('has-error');
                    $('#Accountnumber-error span').show().html(window.text_error_account_number);
                    $('#updateBanks button').show();
                    $('#updateBanks div.loading').hide();
                    return false;
                }else{
                    return true;
                }

            },
            success : function(result){
                result = $.parseJSON(result);
                if(result.login === -1){
                    location.reload(true);
                }else{
                    if (result.checkAccountNumber === -1) {
                        $('#Accountnumber').parent().addClass('has-error');
                        $('#Accountnumber-error span').show().html('This account number is already exists');
                        window.funLazyLoad.reset();
                        return false;
                    } else {
                        $('#Accountnumber').parent().addClass('has-success');
                    }
                    if(result.ok === 1){
                        $('#updateBanks button').show();
                        $('#updateBanks div.loading').hide();
                        $('.account-bank').show();
                        $('#Accountholders-error span').hide().html('');
                        $('#Accountholders').parent().removeClass('has-success');
                        $('#Bankname-error span').hide().html('');
                        $('#Bankname').parent().removeClass('has-success');
                        $('#Accountnumber-error span').hide().html('');
                        $('#Accountnumber').parent().removeClass('has-success');
                        $('#Branchbank-error span').hide().html('');
                        $('#Branchbank').parent().removeClass('has-success');
                    }
                }
            }
        });
        return false;
    });
});
