$( document ).ready(function() {
    $.fn.existsWithValue = function() {
        return this.length && this.val().length;
    };
    $('input#Quantity').keydown(function(event) {
        if (event.keyCode === 13) {
            return true;
        }
        if (!(event.keyCode == 8 || event.keyCode == 46 || (event.keyCode >= 35 && event.keyCode <= 40) || (event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105))) {
            event.preventDefault();
        }
    });


    var transfer = {
        errorReset : function(){
            $('#MemberUserName').parent().removeClass('has-error');
            $('#MemberUserName-error span').hide().html('');
            $('#Quantity').parent().removeClass('has-error');
            $('#Quantity-error span').hide().html('');
            $('#TransferPassword').parent().removeClass('has-error');
            $('#TransferPassword-error span').hide().html('');
        },
    }


    $('#frmCreatePin').on('submit', function(){
        transfer.errorReset();
        if (!$('#MemberUserName').existsWithValue()) {
            $('#MemberUserName').parent().addClass('has-error');
            $('#MemberUserName-error span').show().html(window.err_text_account);
            return false;
        }else{
            $('#MemberUserName').parent().addClass('has-success');
        }

        if (!$('#Quantity').existsWithValue()) {
            $('#Quantity').parent().addClass('has-error');
            $('#Quantity-error span').show().html(window.err_text_amount);
            return false;
        }else{
            $('#Quantity').parent().addClass('has-success');
        }
        if (!$('#TransferPassword').existsWithValue()) {
            $('#TransferPassword').parent().addClass('has-error');
            $('#TransferPassword-error span').show().html(window.err_text_passwd);
            return false;
        }else{
            $('#TransferPassword').parent().addClass('has-success');
        }

        $('#frmCreatePin').ajaxSubmit({
            type : 'GET',
            beforeSubmit : function(arr, $form, options) { 
                window.funLazyLoad.start();
                window.funLazyLoad.show();
            },
            success : function(result){
                result = $.parseJSON(result);
                if(result.login === 0){
                    location.reload(true);
                }else{
                    if(result.customer === -1){
                        $('#MemberUserName').parent().addClass('has-error');
                        $('#MemberUserName-error span').show().html(window.err_text_account_notexit);
                        
                    }else{
                        $('#MemberUserName').parent().addClass('has-success');
                    }

                    if(result.password === -1){
                        $('#TransferPassword').parent().addClass('has-error');
                        $('#TransferPassword-error span').show().html(window.err_text_account_passwd);
                    }else{
                        $('#TransferPassword').parent().addClass('has-success');
                    }
                    if(result.pin === -1){
                        $('#Quantity').parent().addClass('has-error');
                        $('#Quantity-error span').show().html(window.err_text_pin);
                    }else{
                        $('#Quantity').parent().addClass('has-success');
                    }
                    if(result.ok === -1){
                        $('.alert-edit-account').hide();
                    }else{
                        $('.alert-edit-account').show();
                        $('#MemberUserName').val('').parent().removeClass('has-success');
                        $('#Quantity').val('').parent().removeClass('has-success');
                        $('#TransferPassword').val('').parent().removeClass('has-success');
                        $('#Description').val('').parent().removeClass('has-success');
                    }

                    window.funLazyLoad.reset();
                }
            }
        })

        return false;
    });
});