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
});
$(function() {
	$('#frmTransferCredit').on('submit', function() {
		$(this).ajaxSubmit({
			type : 'GET',
			beforeSubmit : function(arr, $form, options) {
				window.funLazyLoad.start();
				window.funLazyLoad.show();
				var n = arr.length;
				if (arr[0].value === "") {
					$('#MemberUserName-error').show().html('Please enter Account received.').parent().addClass('has-error') && window.funLazyLoad.reset();
					return false;
				} else {
					$('#MemberUserName-error').hide().parent().addClass('has-success');
				}
				if(n < 4){
					window.funLazyLoad.reset();
					$('#fromWallet-error').show().html('Please choose Wallet.');
					return false;
				}else{
					$('#fromWallet-error').hide();
				}
				if(arr[2].value == "") {
					window.funLazyLoad.reset();
					$('#amount-error').show().html('Please enter amount!');
					return false;
				}
				else {
					$('#amount-error').hide().parent().addClass('has-success');
				}
				if(arr[3].value == "") {
					window.funLazyLoad.reset();
					$('#TransferPassword-error').show().html('Please enter Password!');
					return false;
				}
				else {
					$('#TransferPassword-error').hide().parent().addClass('has-success');
				}
				n == 4 && arr[0].value !== "" && arr[2].value !== "" && arr[3].value !== ""  && $('#frmTransferCredit button').hide();
				
			},
			success : function(result) {
				
				result = $.parseJSON(result);
				_.has(result, 'customer') && result['customer'] === -1 && $('#MemberUserName-error').show().html('Wrong Account received. Please enter Account received!') && window.funLazyLoad.reset();
				_.has(result, 'amount') && result['amount'] === -1 && $('#amount-error').show().html('Wrong amount. Please enter amount!') && window.funLazyLoad.reset();
				_.has(result, 'password') && result['password'] === -1 && $('#TransferPassword-error').show().html('Wrong password. Please enter Password!') && window.funLazyLoad.reset();
				if (_.has(result, 'customer') && result['customer'] === -1 || _.has(result, 'amount') && result['amount'] === -1 || _.has(result, 'password') && result['password'] === -1){
					$('#frmTransferCredit button').show();
				}
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