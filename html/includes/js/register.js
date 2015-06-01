var flagAddCenter=true;

$( document ).ready(function() {
    $(".teacherInput").hide();
    $("input[name='role']").click(function(){
        
        $(".teacherInput").hide();
        $(".addCenterButton").html("<a><i class='fa fa-plus'></i></a>");
        $(".addCenterCamp").prop('disabled', false);
        flagAddCenter=true;

        if($(this).val()=="teacher"){
        	$(".orderInput").show();
            $(".addCenterButton").show();
            $(".text#centers").addClass("addCenterCamp");
            
        } else {
            $(".addCenterButton").hide();
            $(".text#centers").removeClass("addCenterCamp");
        }

        $(".second").css({
            "margin-left": "0",
            "z-index":"0"
        });
    });
    
    $(".addCenterButton").click(function(){
        if(flagAddCenter){
            $(".addCenterButton").html("<a><i class='fa fa-chevron-down'></i></a>");
            $(".addCenterCamp").val('startOption');
            $(".teacherInput").show();
            $(".addCenterCamp").prop('disabled', true);
            flagAddCenter=false;
        } else {
            $(".addCenterButton").html("<a><i class='fa fa-plus'></i></a>");
            $(".teacherInput").hide();
            $(".orderInput").show();
            $(".addCenterCamp").prop('disabled', false);
            flagAddCenter=true;
        }
        
    });

});

jQuery('#datetimepicker').datetimepicker({
    timepicker:false,
    format:'d/m/Y',
    lang:'ca'
});