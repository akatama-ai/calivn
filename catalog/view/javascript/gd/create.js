$(function() {

	location.hash === '#success' && $('#sucess-alert').show();

	window.choise = null;
	window.value = null;
	var c_wallet_current = $('#c-wallet').data('value');
	var r_wallet_current = $('#r-wallet').data('value');

	
	$('#createGD input[type=radio][name=FromWallet]').change(function(){
		$('#amount').val('');
		var valRadio = parseInt($(this).val());
		var valBTC = null;
		$('#createGD button').attr('disabled', false);
		valRadio === 1 ? (
			window.choise = 1,
			window.value =  parseFloat($('#c-wallet').data('value')),

			$('#amount').val(parseFloat(window.value) + ' USD')
		) : (
			window.choise = 2,
			window.value =  parseFloat($('#r-wallet').data('value')),
			$('#amount').val(parseFloat(window.value) + ' USD')
		);
	});


	$('#createGD').on('submit', function() {
		$(this).ajaxSubmit({
			beforeSubmit : function(arr, $form, options) {

				$('#Password2-error').hide();
				$('#err-c-wallet').hide();
				$('#sucess-alert').hide();
				$('#err-pin').hide();
				
				$('#createGD button').attr('disabled', true);

				if(window.choise === 1 && window.value < 10){
					window.funLazyLoad.reset();
					$('#err-c-wallet').show();
					return false;
				}

				if(window.choise === 2 && window.value === 0){
					window.funLazyLoad.reset();
					return false;
				}
				if(!$('#Password2').val()){
					$('#Password2-error').show();
					return false;
				}
				window.funLazyLoad.start();
				window.funLazyLoad.show();

				$('.alert').hide();
			},
			type : 'GET',
			data : {
				'amount' : window.value
			},
			success : function(result) {
				$('#createGD button').attr('disabled', false);
				
				result = $.parseJSON(result);
				_.has(result, 'password') && result['password'] === -1 && $('#err-passs').show() && window.funLazyLoad.reset();
				_.has(result, 'checkConfirmPD') && result['checkConfirmPD'] === -1 && $('#err-checkConfirmPD').show() && window.funLazyLoad.reset();
				_.has(result, 'pin') && result['pin'] === -1 && $('#err-pin').show() && window.funLazyLoad.reset();

				if(_.has(result, 'ok') && result['ok'] === 1){
					if(location.hash === ''){
						location.href = location.href+'#success';
					}
					location.reload(true);
				}  
				
			}
		});
		return false;
	});

});
