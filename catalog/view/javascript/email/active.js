$(function(){
	$('#activeSubmit').on('submit', function(){
		$(this).ajaxSubmit({
            type : 'GET',
            beforeSubmit : function(arr, $form, options) { 
            	if($('#input-email').val() === ''){
            		alert('Please Enter account');
            		return false;
	           	}
	           	if($('#input-password').val() === ''){
	           		alert('Please Enter password');
	           		return false;
	           	}
	           	window.funLazyLoad.start();
                window.funLazyLoad.show();

            },
            success : function(result){
                result = $.parseJSON(result);
                if(_.has(result, 'req') && parseInt(result.req) === 0){
                     alertify.set('notifier','position', 'top-right');
                     alertify
                        .alert("Login Error.", function(){
                        alertify.warning('OK');
                      });
                }
                if(_.has(result, 'ok') && result.ok === '1'){
                    
                	location.reload(true);
                }
                if(_.has(result, 'ok') && result.ok !== '1'){
                    alertify
                        .alert(result.ok, function(){
                        alertify.warning('OK');
                      });
                }
                window.funLazyLoad.reset();
            }
        });
		return false;
	});
});