       <script src="catalog/view/javascript/jquery.app.js"></script>
	<script>
		
		$('.packet-invest').on('submit', function(){
			
   		var self = $(this);
   		alertify.confirm('<p class="text-center" style="font-size:16px;color: black;height: 20px">Make sure your choice is correct !</p>',
		  function(){
		    window.funLazyLoad.start();
	   		setTimeout(function(){
				self.ajaxSubmit({
					success : function(result) {
						if (result == "no_6"){
							var xhtml = '<p class="text-center" style="font-size:25px;color: black;text-transform: uppercase;height: 20px">Smaller your weak team 50 BTC !</p>';
							alertify.alert(xhtml, function(){
							    location.reload(true);
							  });
							window.funLazyLoad.reset();
							return false;
						}
						if (result == "no_7"){
							var xhtml = '<p class="text-center" style="font-size:25px;color: black;text-transform: uppercase;height: 20px">Smaller your weak team 100 BTC !</p>';
							alertify.alert(xhtml, function(){
							    location.reload(true);
							  });
							window.funLazyLoad.reset();
							return false;
						}
						if (result == "no_complete"){
							var xhtml = '<p class="text-center" style="font-size:25px;color: black;text-transform: uppercase;height: 20px">Please pay your investment package!</p>';
							alertify.alert(xhtml, function(){
							    location.reload(true);
							  });
							window.funLazyLoad.reset();
							return false;
						}
						if (result == 1){
							var xhtml = '<p class="text-center" style="font-size:25px;color: black;text-transform: uppercase;height: 50px">PLEASE UPDATE THE INFORMATION IN ORDER TO ENJOY THE BENEFITS OF PLAYERS.</p>';
							alertify.alert(xhtml, function(){
							    //location.reload(true);
							  });
							window.funLazyLoad.reset();
							return false;
						}
						result = $.parseJSON(result);
						if (result.pin == -1){
							var xhtml = '<p class="text-center" style="font-size:25px;color: black;text-transform: uppercase;height: 50px">You do not have Pin</p>';
							alertify.alert(xhtml, function(){
							    
							  });
							window.funLazyLoad.reset();
							return false;
							
						}
						console.log(result);
						var pin = result.pin;
						var package = result.package;
						var total = package + pin;
						var amount_btc = result.amount_btc/100000000;
						var xhtml = '<div class="col-md-12">Please send '+amount_btc+' BTC to this address.</div><div class="col-md-6"><img style="margin-left:-10px" src="https://chart.googleapis.com/chart?chs=225x225&chld=L|0&cht=qr&chl=bitcoin:'+result.input_address+'?amount='+amount_btc+'"/><p>'+result.input_address+'</p></div><div class="col-md-6"><p>Your Packet: '+package+' USD</p><p>Pin: '+pin+'</p><p>Total: '+ amount_btc +' BTC</p></div>'
						alertify.alert(xhtml, function(){
						    location.reload(true);
						  });
						
					}
				});
				//check_payment();
			}, 200);
		  },
		  function(){
		});
   		return false;
   	});

   	$('.packet-invoide').on('submit', function(){
   		var self = $(this);
	    // window.funLazyLoad.start();
   		setTimeout(function(){
			self.ajaxSubmit({
				success : function(result) {
					result = $.parseJSON(result);
					console.log(result);
					if(_.has(result, 'success') && result['success'] === 1){
						var xhtml = '<div class="col-md-12 text-center"><h3>You have to activate this package! please select another package!</h3></div>'
					}else{
						var amount = result.amount / 100000000;
						var pin = result.pin / 100000000;
						var package = result.package / 100000000
						var total = package + pin;
						var received = result.received / 100000000;
						var xhtml = '<div class="col-md-12">Please send '+amount+' BTC to this address.</div><div class="col-md-6"><img style="margin-left:-10px" src="https://chart.googleapis.com/chart?chs=225x225&chld=L|0&cht=qr&chl=bitcoin:'+result.input_address+'?amount='+amount+'"/><p>'+result.input_address+'</p></div><div class="col-md-6"><p>Your Packet: '+package+' BTC</p><p>Pin: '+pin+' BTC</p><p>Total: '+ total +' BTC</p><p></p>Paid amount: '+received+' BTC</div>'
					}
					
					alertify.alert(xhtml, function(){
					    location.reload(true);
					  });
				}
			});
			//check_payment();
		}, 200);
   		return false;
   	});
	 /*function check_payment(){
	 	$.ajax({
	        url : "<?php //echo $check_payment ?>",
	        type : "post",
	        dataType:"text",
	        data : {
	           
	        },
	        success : function (result){
	            if (result == "3"){
	            	$('.ajs-btn.ajs-ok').trigger('click');
	            }
	            if (result == "0")
	            {
	            	setTimeout(function(){ check_payment(); }, 1500);
	            }

	        }
	    });
	 }*/
	</script>
     <!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'OGmqsibjQ5';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->  
   </body>
</html>