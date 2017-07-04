$(function() {
	$('#submitPD').on('submit', function() {
		$('#amount-error').hide().parent().removeClass('has-error').removeClass('has-success');
		$('#Password2-error').hide().parent().removeClass('has-error').removeClass('has-success');
		$(this).ajaxSubmit({
			type : 'GET',
			beforeSubmit : function(arr, $form, options) {
				$('.alert-edit-account').hide();
				$('.alert-dismissable').hide();
				if (arr[0].value === "") {
					$('#amount-error').show().parent().addClass('has-error');
					return false;
				} else {
					$('#amount-error').parent().addClass('has-success');
				}
				if (arr[1].value === "") {
					$('#Password2-error').show().parent().addClass('has-error');
					$('#Password2-error span').show().html(window.err_password);
					return false;
				} else {
					$('#Password2-error').parent().addClass('has-success');
				}
				arr[0].value !== "" && arr[1].value !== ""  && $('#submitPD button').hide();
				
			},
			success : function(result) {
				result = $.parseJSON(result); 
				console.log(result);
				if (_.has(result, 'login') && result.login === -1) {
					location.reload(true);
				} else {
					//$('.loading').hide() && $('#submitPD button').show();
					
					if (_.has(result, 'checkCountDay') && result.checkCountDay === -1) {						
						$('#checkPD-error').show();
						$('#checkPD-error').show().html(window.err_pd);
						return true;
					} else {
						$('#checkPD').parent().addClass('has-success');
					}

					if (_.has(result, 'account_number') && result.account_number === -1) {						
						$('#checkPD-error').show();
						$('#checkPD-error').show().html(window.err_account);
						return true;
					} else {
						$('#checkPD').parent().addClass('has-success');
					}
//
					if (_.has(result, 'checkWaiting') && result.checkWaiting === -1) {						
						$('#checkWaiting-error').show();
						$('#checkWaiting-error').show().html(window.err_pd);
						//window.location.href = 'index.php?route=account/pd';
						return true;
					} else {
						$('#checkWaiting').parent().addClass('has-success');
					}
					if (_.has(result, 'pin') && result.pin === -1) {						
						$('#checkPD-error').show();
						$('#checkPD-error').show().html(window.err_pin);
						return true;
					} else {
						$('#checkPD').parent().addClass('has-success');
					}
					
					if (_.has(result, 'password') && result.password === -1) {
						$('#Password2').parent().addClass('has-error');
						$('#Password2-error').show();
						$('#Password2-error span').show().html(window.err_password_2);
						return true;
					} else {
						$('#Password2').parent().addClass('has-success');
					}

					if (_.has(result, 'pin')  && result.pin === -1 && _.has(result, 'password') && result.password === 1) {
						$('.alert-dismissable').show();
						$('#Password2').parent().removeClass('has-error').removeClass('has-success');
						$('#Password2-error span').hide().html(window.err_password_2);
						$('#amount-error').hide().parent().removeClass('has-error').removeClass('has-success');
						$('#amount').val('');
						$('#Password2').val('');
		
					}
					if (_.has(result, 'ok') && result.ok === 1 && _.has(result, 'password') && result.password === 1 && _.has(result, 'pin')  && result.pin === 1 && _.has(result, 'data_link') ) {
				
						$('#Password2').parent().removeClass('has-error').removeClass('has-success');
						$('#Password2-error span').hide().html(window.err_password_2);
						$('#amount-error').hide().parent().removeClass('has-error').removeClass('has-success');
						$('#amount').val('');
						$('#Password2').val('');
						$('.alert-edit-account').show();
						$('.alert-dismissable').hide();
						console.log('ok');
						window.location.href=result.data_link;
									
					}

					if(_.has(result, 'ok') && result.ok === -1 && _.has(result, 'password') && result.password === 1 && _.has(result, 'pin')  && result.pin === 1){
						$('#Password2').parent().removeClass('has-error').removeClass('has-success');
						$('#Password2-error span').hide().html(window.err_password_2);
						$('#amount-error').hide().parent().removeClass('has-error').removeClass('has-success');
						$('#amount').val('');
						$('#Password2').val('');
						$('.alert-edit-account').hide();
						$('.alert-dismissable').show();
					
						
					}
					
				}
			}
		});
		return false;
	});
}); 