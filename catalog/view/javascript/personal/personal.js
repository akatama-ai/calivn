$(document).ready(function() {       
    $('#Floor').change(function(){
        $('#floorOne').hide();
        var Floor = $('#Floor').val();
        for (var i = 1; i <= window.sumFloor; i++) {
            if (Floor == 'floor'+i) {
                var floors = $('#customerFloor'+i);
            };
            
        };
        
        $.ajax({
             url : $('#customerFloor').data('link'),
             type : 'GET',
             data : {floor : $('#Floor').val()},
             async : false,
             success : function(result) {
                 result = $.parseJSON(result);
                
                 for (var i = 1; i <= window.sumFloor; i++) {

                    if (Floor == 'floor'+i) {
                        var appends = _.values(result)[0];
                    }
                }
               
                $('.resetFloor').html('');
              
                floors.append(appends);
                 next();
                 prev();
             }
         });
       
     
    });
    
    function next(){

        $("#Next").click(function(){
          //   window.funLazyLoad.start();
          // window.funLazyLoad.show();
            // $('#next_page').val(parseInt($('#next_page').val())+10);
            // alert($('#next_page').val());
         $.ajax({
             url : $('#customerFloor').data('link'),
             type : 'GET',
             data : {next : parseInt($('#next_page').val()), floor : $('#Floor').val()},
             async : false,
             success : function(result) {
                result = $.parseJSON(result);
                console.log(result);
                if (_.has(result, 'total') && _.has(result, 'total') >= parseInt($('#next_page').val()) ) { 
                    window.funLazyLoad.reset();
                    return false;
                }
                $('.resetFloor').html('');
                setTimeout(function(){ window.funLazyLoad.reset(); }, 500);
                for (var i = 1; i <= window.sumFloor; i++) {
                    if (_.has(result, 'fl'+i)) {                      
                        $('#customerFloor'+i).append(_.values(result)[0]);
                       next();
                        prev();
                        
                        return true;
                    } 
                    
                };

             }
         });
        });
    }

    function prev(){

        $("#Prev").click(function(){
            window.funLazyLoad.start();
            window.funLazyLoad.show();
            // $('#next_page').val(parseInt($('#next_page').val())+10);
            // alert($('#next_page').val());
         $.ajax({
             url : $('#customerFloor').data('link'),
             type : 'GET',
             data : {prev : parseInt($('#next_page').val()), floor : $('#Floor').val()},
             async : false,
             success : function(result) {
                 result = $.parseJSON(result);
                console.log(result);
                if ( 10 == parseInt($('#next_page').val()) ) { 
                    window.funLazyLoad.reset();
                    return false;
                }
                $('.resetFloor').html('');
                setTimeout(function(){ window.funLazyLoad.reset(); }, 500);
                for (var i = 1; i <= window.sumFloor; i++) {
                    if (_.has(result, 'fl'+i)) {                      
                        $('#customerFloor'+i).append(_.values(result)[0]);
                       next();
                        prev();
                        
                        return true;
                    } 
                    
                };


                
                
                
             }
         });
        });
    }
    
});