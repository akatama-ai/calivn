$(function(){
	 $.fn.existsWithValue = function() {
        return this.length && this.val().length;
    };
	$('#frmCreateEmail').on('submit', function(){
		$(this).ajaxSubmit({
            type : 'GET',
            beforeSubmit : function(arr, $form, options) { 
                $('Subject-error span').hide().html('Please enter the subject').parent().parent().removeClass('has-error');
                $('#Description-error span').hide().html('Please enter the Message').parent().parent().removeClass('has-error');
                if(!$('#Subject').existsWithValue()){
                	$('#Subject-error span').show().html('Please enter the subject').parent().parent().addClass('has-error');
                	return false;
                }else{
                	$('#Subject-error span').hide().html('Please enter the subject').parent().parent().addClass('has-success');
                }

                if(!$('#Description').existsWithValue()){
                	$('#Description-error span').show().html('Please enter the Message').parent().parent().addClass('has-error');
                	return false;
                }else{
                	$('#Description-error span').hide().html('Please enter the Message').parent().parent().addClass('has-success');
                }
                $('#frmCreateEmail button').hide();
                $('#frmCreateEmail .loading').show();
                
            },
            success : function(result){
                result = $.parseJSON(result);
                if(result.login === -1){
                    location.reload(true);
                }else{
                	result.ok === 1 && $('#frmCreateEmail button').show() && $('#frmCreateEmail .loading').hide();
                	result.ok === 1 && $('#Subject').val('') && $('#Description').val('') && $('#Subject-error span').hide().html('Please enter the subject').parent().parent().removeClass('has-success') && $('#Description-error span').hide().html('Please enter the subject').parent().parent().removeClass('has-success') ;
                }
            }
        });
		return false;
	});
});