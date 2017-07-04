$(function() {

	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result).show().css({'width': '100%'});
            }

            reader.readAsDataURL(input.files[0]);
        }else{
            $('#blah').hide();
        }
    }
    $("#file").on('change' , function (env) {
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
        }
    });

	$('.onBack').on('click', function(){
		history.back();
		return false;
	});
	$('#comfim-pd').on('submit', function(){
		$(this).ajaxSubmit({
			beforeSubmit : function(arr, $form, options) { 
                if(!$("#file").val()){
                    $('.error-file').show();
                    return false;
                }               
            },
			success : function(result) {
				result = $.parseJSON(result);
				_.has(result, 'ok') && result.ok === -1 && alert("Error Server! Please try agian.");
				_.has(result, 'ok') && result.ok === 1 && location.reload(true);
			}
		});
		return false;
	});
});