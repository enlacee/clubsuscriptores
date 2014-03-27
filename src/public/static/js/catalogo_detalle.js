var iTCL=0;
$( function() {
    var frmCatalogoDetalle = {
        paginadorCombo : function(a) {
            var actual = $(a);
            actual.bind("change", function(){
                var valor=$(this).val();
                var mostrandode=$(this).attr("mostrandode");
                var action = $("#frmnpaginadodetalle").attr("action");
                location.href = action+valor+"-"+mostrandode+"#ac";
            });
        },
        start : function() {
            frmCatalogoDetalle.paginadorCombo("#npaginadodetalle");
            frmCatalogoDetalle.aCheckArefTerminosCondiciones("#alinkTerminoCondicion","#mjsTerCondLegal");
        },
        aCheckArefTerminosCondiciones : function(objChk,objMsj) {
            $(objChk).bind('click', function(e) {
                e.preventDefault();
                var cntChechOutPut = $(objMsj),resultImg;
                var srcImg=$(this).children("img").attr("src");
                if (iTCL==0) {
                    resultImg=srcImg.replace("flechaA","flechaB");                  
                    cntChechOutPut.slideDown('fast');iTCL=1;
                } else {
                    resultImg=srcImg.replace("flechaB","flechaA"); 
                    cntChechOutPut.slideUp('fast');iTCL=0;
                }
                $(this).children("img").attr("src", resultImg)
            });
        },
        iframeHeight : function(frame){ 
         var iFrame = $(frame);
         
         if(iFrame.size()>0){

             var dataR = $('#loadIf'),
             addInfo = $('#idinfo'),
             urlFrame = iFrame.attr('src');            
                //var url = document.location.href,
                var url = urlFrame,
                domain = url.lastIndexOf(document.location.host) + document.location.host.length + 1,
                params = url.substring(domain),
                splitUrl = params.split('?');
                
                if(iFrame.attr('height') == '100%'){

                addInfo.addClass('loading').css({'height':'100px'});
                    $.ajax({
                            url : '/beneficio/ver-html-generado',
                            type : 'post',
                            dataType : 'html',
                            data : {
                                ruta:params
                            },
                            success : function(res){                      
                                dataR.html(res);                        
                                setTimeout(function(){
                                  var h = $("#loadIf").height();  
                                  addInfo.animate({
                                      'height': h 
                                      }, function(){
                                      iFrame.removeClass('hide').css('height',h);
                                      dataR.remove();
                                      addInfo.removeClass('loading');
                                  });   
                                },500);                        
                            },
                            error : function(res){
                                addInfo.removeClass('loading');
                                iFrame.css('height','auto');
                            }
                    });  
                }        	 
        	 
         }
         

        }
    };
    frmCatalogoDetalle.start();
    frmCatalogoDetalle.iframeHeight('#iframeHTML');
});




