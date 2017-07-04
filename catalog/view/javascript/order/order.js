$(function(){
	$.fn.existsWithValue = function() {
        return this.length && this.val().length;
    };
	
	var totalMoney = null;
    $('#Quantity').on('change', function(){
    	if($.isNumeric($('#Quantity').val()) !== false){
    		var amount_ = $(this).val() * 20;
    		$('#transfer_usd').html(amount_);
    		totalMoney = amount_;
    	}
    });

    $('#frmCreateOrderPin').on('submit', function(){
    	$('#Quantity-error span').hide().html('').parent().parent().removeClass('has-error').removeClass('has-success');
    	if(!$('#Quantity').existsWithValue()){
    		$('#Quantity-error span').show().html('Please enter amount').parent().parent().addClass('has-error');
    		return false;
    	}

    	if($.isNumeric($('#Quantity').val()) === false){
    		$('#Quantity-error span').show().html('Please enter amount').parent().parent().addClass('has-error');
    		$('#Quantity-error span').show().html('Please enter amount is number');
    		return false;
    	}
    	$('#Quantity-error span').hide().html('').parent().parent().addClass('has-success');

    	if($('#Quantity').existsWithValue() && $.isNumeric($('#Quantity').val()) === true){
    		$(this).ajaxSubmit({
	            type : 'GET',
	            data : {
	            	'amount' : $('#Quantity').val(),
	            	'totalMoney' : totalMoney
	            },
	            beforeSubmit : function(arr, $form, options) { 
	                window.funLazyLoad.start();
	                window.funLazyLoad.show();
	            },
	            success : function(result){
	                result = $.parseJSON(result);
	                if(result.login === 0){
	                    location.reload(true);
	                }else{
	                	$('#Quantity-error span').hide().html('').parent().parent().removeClass('has-error').removeClass('has-success');
	                	$('#Quantity').val('');
	                	$('#transfer_usd').html('0');
	                	window.funLazyLoad.reset();
	                	location.reload(true);
	                }
	            }
	        });
    	}

		
    	return false;
    });
});