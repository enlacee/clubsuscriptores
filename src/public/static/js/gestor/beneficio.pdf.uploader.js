/*
SuperUploader para Postulante
*/
$(function(){
    var uploadFile = {
        start : function(ctrl, filename, msgerror1, msgerror2, filesize){
            var cont = 0;
            $(ctrl).bind('change',function(e){
                e.preventDefault();
                $("#pdf_msg").addClass("loading");
                var file = $(ctrl);
                var name = $(filename);
                var r =  $('#pdf_msg');//file.parents('.bloqueNbeneficio').find('.response');
                var ext = (file.val().substr(file.val().length-4, file.val().length)).toUpperCase();
                if('.PDF'==ext){
                    var size=1;
                    if( !$.browser.msie ) {
                        size = file[0].files[0].size;
                    }
                    if(size <= filesize){
                        uploadFile.subirFile(cont, file, name, filesize);
                    }else{
                        r.addClass("bad").removeClass("good").text(msgerror1);
                        file.removeClass("ready");
                        $("#pdf_msg").removeClass('loading');
                    }
                }else{
                    r.addClass("bad").removeClass("good").text(msgerror2);
                    file.removeClass("ready");
                    $("#pdf_msg").removeClass('loading');
                }
            });
        },
        subirFile : function(cont, file, name, filesize) {
            var form = $('<form style="display:none;"></form>');
            var iframe = $("<iframe name='frmdescarga"+cont+"' src='javascript:false;'></iframe>");
            var r = $('#pdf_msg');//file.parents('.bloqueNbeneficio').find('.response');
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
                        if($.trim(result[0])!="success"){
                            r.addClass("bad").removeClass("good").text(result[1]);
                            file.removeClass("ready");
                            $("#pdf_msg").removeClass('loading');
                        }else{
                            $('#pdf_msg').css('display','none');
                            //                            file.after(result[1]);
                            name.val(result[1]);
                            $('#pdf_msg').fadeIn(500);
                            form.remove();
                            r.removeClass("bad").addClass("good").text("¡OK!");
                            file.addClass("ready");
                            $('#divDelFot').removeClass('hide');
                            $("#pdf_msg").removeClass('loading');
                        }
                    }, 200);
                });
                var input = file;
                var clone = input.clone(true);
                file.after(clone);
                form.append(input);
                var input_filesize = $("<input type='hidden' name='filesize' value='"+filesize+"' />");
                form.append(input_filesize);
                form.attr('target',iframe.attr('name'));
                form.attr("action",urls.siteUrl + "/gestor/beneficios/carga-pdf");
                form.attr("method","POST");
                form.attr('enctype', 'multipart/form-data');
                form.attr('encoding', 'multipart/form-data');
                form.submit();
                clone.replaceWith(file);
            });
            form.append(iframe).appendTo('body');
        }
    };
    uploadFile.start("#pdf_resultado", "#pdf_name",
        "Tamaño de archivo sobrepasa el limite Permitido.",
        "Archivo debe tener extensión PDF.",
        maxPDFsize);
});
