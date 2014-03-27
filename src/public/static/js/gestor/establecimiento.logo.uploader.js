/*
SuperUploader para Postulante
*/
$(function(){
    var previewPhoto = {
        start : function(validadores,msgerror1,msgerror2,filesize){
            var cont = 0;
            $('#path_imagen').bind('change',function(e){
                e.preventDefault();
                $("#img_avatar").find('img').remove();
                $("#img_avatar").addClass("loading");
                var foto = $('#path_imagen');
                var ext = (foto.val().substr(foto.val().length-4, foto.val().length)).toUpperCase();
                 
                var va = validadores.split("|");
                if(va[0].toUpperCase()==ext || va[1].toUpperCase()==ext || va[2].toUpperCase()==ext || va[3].toUpperCase()==ext){
                    var size=1;
                    if( $.browser.msie ) {
                        size=1;
                    }else{
                        size = foto[0].files[0].size;
                    }
                    if(size<=filesize){
                        previewPhoto.subirFile(cont, foto,filesize);
                    }else{
                    	$('.divBenN').next().addClass("bad").text(msgerror1);
                    	$("#img_avatar").next().next().removeClass('ready');
                        $("#img_avatar")
                        .html("<img width='128' height='87' src='"+urls.mediaUrl+"/images/photoDefault.png' alt='Logo del Establecimiento' />")
                        .removeClass('loading');
                    }
                }else{
                	$('.divBenN').next().addClass("bad").text(msgerror2);
                	$("#img_avatar").next().next().removeClass('ready');
                    $("#img_avatar")
                    .html("<img width='128' height='87' src='"+urls.mediaUrl+"/images/photoDefault.png' alt='Logo del Establecimiento' />");
                    $("#img_avatar").removeClass('loading');
                }
            });
        },
        subirFile : function(cont,foto,filesize){
            var form = $('<form style="display:none;"></form>');
            var iframe = $("<iframe name='frmdescarga"+cont+"' src='javascript:false;'></iframe>");
            iframe.bind('load', function(){
                iframe.unbind('load').bind('load', function () {
                    setTimeout(function(){
                        var response=undefined;
                        if( $.browser.msie && $.browser.version.substr(0,1) < 8 ) {
                            response = window.frames['frmdescarga'+cont].document.body.innerHTML;
                        }else{
                            response = $(iframe)[0].contentDocument.body.innerHTML;
                        }
                        var result = response.split("|");
                        var inputPath = $('#path_imagen');
                        if($.trim(result[0])!="success"){
                            $('.divBenN').next().addClass("bad").text(result[1]);
                            $("#img_avatar")
                            .html("<img width='128' height='87' src='"+urls.mediaUrl+"/images/photoDefault.png"+"' alt='Logo del Establecimiento' />")
                            .removeClass('loading');
                            $("#path_imagen").removeClass('ready'); 
                        }else{
                            $('#img_avatar').css('display','none');
                            $('#img_avatar').html(result[1]);
                            $('#img_avatar').fadeIn(500);
                                        
                            form.remove();
                            $('.divBenN').next().removeClass("bad").text("");
                            $('#divDelFot').removeClass('hide');
                            $("#img_avatar").removeClass('loading');
                            $("#path_imagen").addClass('ready'); 
                        }
                    }, 200);
                });
                var input = foto;
                var clone = input.clone(true);
                foto.after(clone);
                form.append(input);
                var input_filesize = $("<input type='hidden' name='filesize' value='"+filesize+"' />");
                form.append(input_filesize);
                form.attr('target',iframe.attr('name'));
                form.attr("action",urls.siteUrl + "/gestor/establecimientos/cargafoto");
                form.attr("method","POST");
                form.attr('enctype', 'multipart/form-data');
                form.attr('encoding', 'multipart/form-data');
                form.submit();
                clone.replaceWith(foto);
            });
            form.append(iframe).appendTo('body');
        }
    };
    previewPhoto.start(".jpg|.png|jpeg|.gif|wbmp",
        "Tamaño de archivo sobrepasa el limite Permitido.",
        "Archivo debe tener extensiones .jpg, .png, .jpeg, .gif",
        524288);
    
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
});
