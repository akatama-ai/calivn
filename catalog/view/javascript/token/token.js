$(function(){
	$('#frmBuyPin').on('submit', function(envt) {
		$(this).find('#pin_price').parent().removeClass('has-error');
		var inpunt = $(this).find('#pin_price').val();
		var self = $(this);
		if (!inpunt) { return false};

		if((val = $('#pin_price').val(), $.isNumeric(val) && Math.floor(val) == val)){
			$(this).find('#btnBuy').addClass('not-active').text('Loading ...');
			
			$(this).ajaxSubmit({
				beforeSubmit :  function(arr, $form, options) { window.funLazyLoad.start() },
				type : 'GET',
				success : function(result){
					result = $.parseJSON(result);
					if(_.has(result, 'error')){
						var error = _.values(result)[0];
						if(error){
							self.find('#pin_price').val('').attr('placeholder',_.values(result)[1]).parent().addClass('has-error');
							self.find('#btnBuy').removeClass('not-active').text('Submit');
							window.funLazyLoad.reset();
						}else{
							location.href = _.values(result)[1];
						}
					}
				}
			});
		}else{
			$(this).find('#pin_price').val('').attr('placeholder','Please enter amount is numeric').parent().addClass('has-error');
		}
		return false;
	});
});