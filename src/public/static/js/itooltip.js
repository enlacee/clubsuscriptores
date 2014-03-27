$(function() {
   
   var ToolTip = function(){
       this.iTooltip = function(classTooltip){
            var tip;
            $(classTooltip).hover(function(){
                $('body').append('<div id="iTooltip" class="anchoTooltip" style="z-index: 99999;"></div');
                tip = $(this).find('.tip');
                $('#iTooltip').html(tip.html()).show();
            }, function() {
                $("#iTooltip").remove();
            }).mousemove(function(e) {
                var mousex = e.pageX + 15,
                mousey = e.pageY + 15,
                tip = $(this).find('.tip');
                tipWidth = tip.width(),
                tipHeight = tip.height(); 

                var tipVisX = $(window).width() - (mousex + tipWidth),
                tipVisY = $(window).height() - (mousey + tipHeight);						  

                if( tipVisX < 15 ){
                    mousex = e.pageX - tipWidth - 15;
                }
                if( tipVisY < 15 ){
                    mousey = e.pageY - tipHeight - 15;
                }
                $('#iTooltip').css({
                    top: mousey, 
                    left: mousex
                });
            });
        };
        
        this.load = function(){
            var combo = $('.cboMntoDescR');
            combo.change();
        };
   }
   
   var tt = new ToolTip();
   tt.iTooltip('.iTooltip');
   tt.load();
});
