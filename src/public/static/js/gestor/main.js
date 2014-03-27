/* 
Main Gestor
 */
var jq = { 
    selectTrue : function(sVal) {
        return ( sVal != '0' );
    },
    pasteMaxlength : function(sFiled){   
        $(sFiled).bind('keyup click blur focus change paste', function(e){
            var t = $(this);
            setTimeout(function(){                
                var chars = t.val(), 
                charsSize = t.val().length,
                size = parseInt(t.attr('maxlength'));
                if( charsSize > size ){
                    valField = chars.substring(size, 0);
                    t.val(valField);
                }
            },0);
        });
    }        
}
$(function(){
   // Class
   var Gestor = function(){
        this.loginVM = function(){
                var linkOlvidoC = $('#linkLoginMLS');
                if(linkOlvidoC.size()>0){
                        var cntPass = $('#contentForgotMLS'),
                        cntLog = $('#contentLoginMLS'),
                        linkPass = $('#backLogMLS');
                        linkOlvidoC.bind('click', function(e){
                                e.preventDefault();										
                                cntLog.slideUp('fast',function(){
                                        cntPass.slideDown();		
                                });												
                        });
                        linkPass.bind('click', function(e){
                                e.preventDefault();										
                                cntPass.slideUp('fast',function(){
                                        cntLog.slideDown();		
                                });												
                        });					
                }
        }
        this.iTooltip = function(classTooltip){				
            var tip;
            $(classTooltip).hover(function(){
                    $('body').append('<div id="iTooltip"></div');	
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
                    $('#iTooltip').css({  top: mousey, left: mousex });
            });
        };
   };
   
   // init
   var objest = new Gestor();
   objest.loginVM();
   objest.iTooltip('.iTooltip');
});