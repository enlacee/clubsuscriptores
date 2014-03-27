/*
Control:    Upload Photo Preview Extend Drag and Drop.
Autor:      Solman Vaisman Gonzalez.
Email:      Solman28@gmail.com
Todos los derechos reservados. :P
*/

$(function(){
    var txtImage = "#txtImage";
    var imgLoader = "#preloader";
    var idBtnCerrar = ".admiGeCrossLittle";
    var idBtnEdit = "#admi-edit";
    var idBtnStar = "#admi-star";
    var firstImage = "#FirstImagen";
    var secondImage = "#SecondImagen";
    var contImg = 0;
    var comportamiento = 0; // 0->insertar, 1->editar

    var imagelist = new Array();
    var desclist = new Array();
    //Maxlenght IE
    jq.pasteMaxlength('#contenido');
    //end
    var previewUPE = {

        carga : function(){
            var objImgs = $("#contentImgs").find("li");
            if (objImgs.size()>0) comportamiento = 1; else comportamiento = 0;
            
            $.each(objImgs, function(index, item){
                var valor = $(item).find("img").attr("valor");
                imagelist.push(valor);
            });

            $.each(objImgs, function(index, item){
                var valor2 = $(item).find(".image-description").html();
                desclist.push(valor2);
            });


            if (comportamiento==1) {
                previewUPE.mostrarImagenPrincipal();
            }
            previewUPE.insertarImagenesEnFormulario("#listaimagenes_content","#listadesc_content");
            //previewUPE.insertarDescEnFormulario("#listadesc_content");
            previewUPE.editLeyenda();
            previewUPE.saveChangesBt2();

        },
        start : function(validadores,msgerror1,msgerror2,filesize) {
            var cont = 0;
            $('#path_logo_preview').bind('change',function(e) {
                $(txtImage).val($(this).val());
                e.preventDefault();
                /*
                comento el cargador
                $(imgLoader).removeClass("hide").removeClass("bad").removeClass("response").html("");
                $(imgLoader).addClass("loading");
                */
                var foto = $('#path_logo_preview');
                var ext = (foto.val().substr(foto.val().length-4, foto.val().length)).toUpperCase();               
                var va = validadores.split("|");

                if(va[0].toUpperCase()==ext || va[1].toUpperCase()==ext || va[2].toUpperCase()==ext || va[3].toUpperCase()==ext){
                    var size=1;
                    if( $.browser.msie ) { //para internet explorer carga la foto
                        size=1;
                    }else{
                        size = foto[0].files[0].size;
                    }
                    if(size<=filesize){
                        //previewUPE.subirFile(cont, foto,filesize, msgerror1);
                        //console.log("archivo seleccionado cont="+cont);

                    }else{
                        //$(imgLoader).addClass("bad").addClass("response").html(msgerror1);
                        //$(imgLoader).removeClass('loading');
                    }
                }else{
                    $(imgLoader).addClass("bad").addClass("response").html(msgerror2);
                    $(imgLoader).removeClass('loading');
                }
            });
            

            /*carga real*/
            $('#upload-data').bind('click',function(e) {
                $(txtImage).val($(this).val());
                e.preventDefault();

                $(imgLoader).removeClass("hide").removeClass("bad").removeClass("response").html("");
                $(imgLoader).addClass("loading");                

                var foto = $('#path_logo_preview');
                var ext = (foto.val().substr(foto.val().length-4, foto.val().length)).toUpperCase();
                 
                var va = validadores.split("|");
                if(va[0].toUpperCase()==ext || va[1].toUpperCase()==ext || va[2].toUpperCase()==ext || va[3].toUpperCase()==ext){
                    var size=1;
                    if( $.browser.msie ) { //para internet explorer carga la foto
                        size=1;
                    }else{
                        size = foto[0].files[0].size;
                    }
                    if(size<=filesize){
                        previewUPE.subirFile(cont, foto,filesize, msgerror1);
                        
                    }else{
                        $(imgLoader).addClass("bad").addClass("response").html(msgerror1);
                        $(imgLoader).removeClass('loading');
                    }
                }else{
                    $(imgLoader).addClass("bad").addClass("response").html(msgerror2);
                    $(imgLoader).removeClass('loading');
                }
            });
            
            //escuchamos a las acciones
            previewUPE.carga();
            previewUPE.btnCerrar();
            previewUPE.dragAndDrop();
            previewUPE.establecerImagenPrincipal();
            previewUPE.establecerPrincipal();
        },
        subirFile : function(cont,foto,filesize, msgerror) {
            var form = $('<form style="display:none;"></form>');
            var iframe = $("<iframe name='frmdescarga"+cont+"' src='javascript:false;'></iframe>");
            iframe.bind('load', function(){
                iframe.unbind('load').bind('load', function () {
                   previewUPE.timeReadIframe(iframe, 200, 0, function(result) {
                        if($.trim(result[0])!="success"){
                            //foto.parents("#eIPhoto").next().addClass("bad").text(result[1]);
                            $(imgLoader).addClass("bad").addClass("response").html(msgerror).removeClass('loading');
                        } else {

                            //console.log(result[2]);

                            imagelist.push(result[1]);
                            desclist.push(result[2]);

                            //console.log(desclist);

                            previewUPE.mostrarImagen("#contentImgs", result, function(){
                                previewUPE.mostrarImagenPrincipal();
                            });
                            form.remove();
                            //foto.parents("#eIPhoto").next().removeClass("bad").text("");
                            $(imgLoader).removeClass('loading');
                            //jan sanchez
                            $('#txtImageDescription').val('');
                            //console.log('loading complete, cont='+cont);
                            $('#path_logo_preview').val('');
                            foto='';
                        }
                   });
                });
                var input = foto;
                var clone = input.clone(true);
                foto.after(clone);
                form.append(input);
                var input_filesize = $("<input type='hidden' name='filesize' value='"+filesize+"' />"),
                input_desc = $("<input type='hidden' name='input-desc' value='"+$('#txtImageDescription').val()+"' />");
                form.append(input_filesize);
                form.append(input_desc);
                form.attr('target',iframe.attr('name'));
                form.attr("action",urls.siteUrl + "/gestor/beneficios/cargafoto2");
                form.attr("method","POST");
                form.attr('enctype', 'multipart/form-data');
                form.attr('encoding', 'multipart/form-data');
                form.submit();
                clone.replaceWith(foto);
            });
            form.append(iframe).appendTo('body');
        },
        timeReadIframe : function(iframe, time, cont, callback) {
            setTimeout(function() {
                var response=undefined;
                if( $.browser.msie && $.browser.version.substr(0,1) < 8 ) {
                    response = window.frames[iframe.attr("name")].document.body.innerHTML;
                } else {
                    response = $(iframe)[0].contentDocument.body.innerHTML;
                }
                var result = response.split("|");
                if(cont>3) {
                    callback.call(this, result);
                } else {
                    if($.trim(result[0])!="success"){
                        previewUPE.timeReadIframe(iframe, time, cont+1, callback);
                    } else {
                        callback.call(this, result);
                    }
                }
            }, time);
        },
        mostrarImagenes : function(content) {
            $(content).html("");
            $.each(imagelist, function(index, value){
                var img = "<img style='width:125px; height:70px;' valor='"+value+"' src='"+urls.siteUrl+"/"+value+"' class='imgPrev' />";
                var li = "<li class='aGesImgLittle'> "+img+" <div class='admiGeCrossLittle iTooltip'>"+
                          "<span class='tip hide'>Eliminar Imagen</span>  </div>"+
                          "<div id='admi-edit'><a href='#edit-picture' class='winModal'>&nbsp;</a></div>"+
                          "<div id='admi-star'></div>"+
                          "<div class='image-description'>"+desclist[index]+"</div>"+
                          //"<div class='admiGeCrossLittle2'>Establecer principal</div>"+
                          "</li>";
                $(content).append(li);
            });

            previewUPE.insertarImagenesEnFormulario("#listaimagenes_content","#listadesc_content");
            //previewUPE.insertarDescEnFormulario("#listadesc_content");
        },
        mostrarImagen : function(content, imagen, callback) {
            var img = "<img style='width:125px; height:70px;' valor='"+imagen[1]+"' src='"+urls.siteUrl+"/"+imagen[1]+"' class='imgPrev hide' />";
            var li = "<li class='aGesImgLittle'> "+img+" <div class='admiGeCrossLittle iTooltip'>"+
                      "<span class='tip hide'>Eliminar Imagen</span>  </div>"+
                        "<div id='admi-edit'><a href='#edit-picture' class='winModal'>&nbsp;</a></div>"+
                        "<div id='admi-star'></div>"+
                      "<div class='image-description'>"+imagen[2]+"</div>"+
                      "</li>";
            
            $(content).append(li);
            $(".imgPrev:last").fadeIn("slow", function(){
                $(this).removeClass("hide");
            });
            callback.call(this);
            previewUPE.insertarImagenesEnFormulario("#listaimagenes_content","#listadesc_content");
        },
        mostrarImagenPrincipal : function(){

            if(imagelist.length==0) {
                $(firstImage).html("");
                $(secondImage).html("");
            } else {
                var img = "<img style='width:173px; height:97px;' src='"+urls.siteUrl+"/"+imagelist[0]+"' class='imgPrev hide' />";
                $(firstImage).html(img);
                $(secondImage).html(desclist[0]);

                if(imagelist.length<2) {
                    $(firstImage+" .imgPrev").fadeIn("slow", function(){
                       $(this).removeClass("hide");
                    });
                    $(secondImage+" .imgPrev").fadeIn("slow", function(){
                       $(this).removeClass("hide");
                    });
                } else {
                    $(firstImage+" .imgPrev").removeClass("hide");
                    $(secondImage+" .imgPrev").removeClass("hide");                    
                }
            }
        },
        btnCerrar : function() {
            $(idBtnCerrar).live("click", function(){
                var a = $(this).parent();
                a.find(".imgPrev").fadeOut("fast", function(){
                    var valor = $(this).attr("valor");
                    var indice = previewUPE.indexOfArray(valor);
                    if (indice!=-1) {
                      $.ajax({
                          'url'      : '/gestor/beneficios/eliminarfoto',
                          'type'     : 'POST',
                          'dataType' : 'JSON',
                          'data' : {
                              'img' : valor
                          },
                          success: function(){
                            
                          }
                      });
                      delete imagelist[indice];
                      delete desclist[indice];
                      
                      previewUPE.updateArray();
                      previewUPE.mostrarImagenPrincipal();
                      a.html("");
                      a.animate({
                          "width":"0px"
                      }, "fast", function(){a.remove();});
                      previewUPE.insertarImagenesEnFormulario("#listaimagenes_content","#listadesc_content");
                      //previewUPE.insertarDescEnFormulario("#listadesc_content");
                    } else {
                        $(this).fadeIn("fast", function(){alert("ERROR: No se pudo eliminar imagen")});
                    }
                });
            });
        },
        indexOfArray : function(valor) {
            r = -1;
            $.each(imagelist, function(index, value) {
                if($.trim(value)==$.trim(valor)) r = index;
            });
            return r;
        },
        indexOfArray2 : function(valor) {
            r = -1;
            $.each(desclist, function(index, value) {
                if($.trim(value)==$.trim(valor)) r = index;
            });
            return r;
        },
        updateArray : function() {
            var a = new Array();
            var b = new Array();
            $.each(imagelist, function(index, value) {
                if (value!=undefined) a.push(value);
            });
            $.each(desclist, function(index, value) {
                if (value!=undefined) b.push(value);
            });
            imagelist = a;
            desclist = b;
        },
        dragAndDrop : function() {
            var capa = $(".aGesImgLittle").find(".imgPrev");
            capa.live("mousedown", function(ex){
                
                var actual = $(this).parent();
                actual.find(".imgPrev").css("cursor","move");
                actual.parent().css("z-index","8");
                
                actual.bind("mousemove",function(e){
                    actual.css("position","absolute");
                    actual.css("z-index","5");
                    var x = parseInt(e.pageX/1);
                    var y = parseInt(e.pageY/1);
                    actual.css("left", x*1-$(this).width()/2-10+"px");
                    actual.css("top", y*1-$(this).height()/2-10+"px");

                    e.preventDefault();
                    e.stopPropagation();
                });

                actual.bind("mouseup", function(e){
                    actual.unbind("mousemove");
                    actual.unbind("mouseup");
                    actual.css("z-index","1");

                    var posx = parseInt((e.pageX - $("#galeria").position().left - 70)/125);
                    var posy = parseInt((e.pageY - $("#galeria").position().top - 40 )/70);
                    if (posx>=0 && posy>=0) {
                        var offset = posy*3+ posx;
                        
                        //console.log(offset);

                        if (offset<imagelist.length) {

                            var temp =imagelist[offset];
                            var temp2 =desclist[offset];

                            var valor = actual.find(".imgPrev").attr("valor");
                            var valor2 = actual.find(".image-description").html();
                            

                            var offset2 = previewUPE.indexOfArray(valor);

                            imagelist[offset2] = temp;
                            desclist[offset2] = temp2;

                            imagelist[offset] = valor;
                            desclist[offset] = valor2;

                   

                            if (imagelist.length>2){
                                
                              
                                
                                imagelist[offset2] = imagelist[1];    
                                desclist[offset2] = desclist[1];

                                imagelist[1] = temp;
                                desclist[1] = temp2;
                                
                            }

                            previewUPE.mostrarImagenes("#contentImgs");
                            previewUPE.mostrarImagenPrincipal();
                        }
                    }
                    actual.removeAttr("style");
                });

                ex.preventDefault();
                ex.stopPropagation();
            });
        },
        establecerImagenPrincipal : function() {
            $("#admi-star").live("click", function(){

                var actual = $(this);
                var padre = $(this).parent();
                var valor_img = padre.find(".imgPrev").attr("valor");
                var valor_desc = padre.find(".image-description").html();
                
                //console.log(valor_desc);

                if (imagelist.length>0){
                    var valor0 = imagelist[0];
                    var valordesc = desclist[0];

                    var posx = previewUPE.indexOfArray(valor_img);
                    var posx2 = previewUPE.indexOfArray2(valor_desc);

                    imagelist[0] = valor_img;
                    imagelist[posx] = valor0;// el principal deja de serlo y pasa a ocupa

                    desclist[0] = valor_desc;
                    desclist[posx2] = valordesc;

                    if (imagelist.length>2){
                        imagelist[posx] = imagelist[1];
                        imagelist[1] = valor0;
                            
                        desclist[posx2] = desclist[1];
                        desclist[1] = valordesc;
                    }

                    $("#FirstImagen").fadeOut("fast", function(){
                       $(this).fadeIn("fast");
                    });
                    $("#SecondImage").fadeOut("fast", function(){
                       $(this).fadeIn("fast");
                    });
                    
                    padre.fadeOut("fast", function(){
                        previewUPE.mostrarImagenes("#contentImgs");
                        previewUPE.mostrarImagenPrincipal();
                    });
                }
            });
        },
        editLeyenda : function() {

            $("#admi-edit").live("click", function(){
                var actual = $(this);
                var padre = $(this).parent();
                var valor_img = padre.find(".imgPrev").attr("valor");
                var valor_desc = padre.find(".image-description").html();
                $('#edit-image-desc').val(valor_desc);

                $('#error_sinstructions').fadeOut('fast');

                //$('img.editImageonLive').attr('src', siteUrl+'/'+valor_img);
                
                if (imagelist.length>0){
                    var posx2 = previewUPE.indexOfArray2(valor_desc);

                    $('#edit-id-image').val(posx2);
                }

            });
        },
        saveChangesBt2 : function() {

            $("#saveChangesBt2").live("click", function(){

            if($('#edit-image-desc').val()==''){
                desclist[$('#edit-id-image').val()]=' ';
            }else{
                desclist[$('#edit-id-image').val()]=$('#edit-image-desc').val();
            }

                $('#idCloseChangeE').trigger('click');
                previewUPE.mostrarImagenes("#contentImgs");
                previewUPE.mostrarImagenPrincipal();

            });
        },



        establecerPrincipal: function() {
            $(".aGesImgLittle").live("mouseover", function(){
                //$(this).find("#admi-star").css("top", "63px");
                /*$(this).find("#admi-star").animate({
                    "top":"63px"
                },100, function(){  });*/

               /* $(".aGesImgLittle").not($(this).parent()).
                    find("#admi-star").css("top", "90px");*/
            }).live("mouseleave", function(){
                //$(this).find("#admi-star").css("top", "90px");
                /*$(this).find("#admi-star").animate({
                    "top":"90px"
                },80, function(){  });*/
            });
        },
        insertarImagenesEnFormulario: function(c,d){
            var campo = "";
            $.each(imagelist, function(index, value) {
                campo=campo+$.trim(value)+"|";
            });
            campo = campo.substring(0, campo.length-1);
            $(c).html("<input type='hidden' value='"+campo+"' name='listaimagenes' />");

            var campo2 = "";
            $.each(desclist, function(index2, value2) {
            campo2=campo2+$.trim(value2)+"|";
            });
            campo2 = campo2.substring(0, campo2.length-1);
            $(d).html("<input type='hidden' value='"+campo2+"' name='descimagenes' />");


        },
        insertarDescEnFormulario: function(c){
            /*
            var campo = "";
            $.each(desclist, function(index, value) {
                campo=campo+$.trim(value)+"|";
            });
            campo = campo.substring(0, campo.length-1);
            $(c).html("<input type='hidden' value='"+campo+"' name='descimagenes' />");
            */
        }
    };
    previewUPE.start(".jpg|.png|jpeg|.gif|wbmp",
        "Tamaño de archivo sobrepasa el limite Permitido.",
        "Archivo debe tener extensiones .jpg, .png, .jpeg, .gif",
        524288);

});

$(function(){

    $('#edit-image-desc').keyup(function(e){
        var currentText=$(this).val(),
        totalChars= 120,
        inputTextChars= currentText.length,
        remainingChars=(totalChars-inputTextChars),
        outputContent= '#error_sinstructions',
        outputText= '#me_sinstructions',
        youAre='Te quedan',
        charAvailable='caracteres disponibles',
        andUseThe='Has usado los',
        charAllowed='caracteres permitidos';
        if(remainingChars>=0){
            remainingChars=totalChars-inputTextChars;            
            $(outputContent).fadeIn();
            $(outputText).html(' '+youAre+' '+remainingChars+' '+charAvailable+'.');

            if(remainingChars<=0){
                $(outputContent).fadeIn();
                $(outputText).html(' '+andUseThe+' '+totalChars+' '+charAllowed+'.'); 
            }
        }else{
            if(remainingChars<=0){
                $(outputContent).fadeIn();
                $(outputText).html(' '+andUseThe+' '+totalChars+' '+charAllowed+'.');  
            }       
            $(this).val(currentText.substring(0,totalChars));
        }        
    });

    $('#edit-image-desc').blur(function(e){
        var outputContent= '#error_sinstructions';
        $(outputContent).fadeOut();
    });

    $('#txtImageDescription').keyup(function(e){
        var currentText=$(this).val(),
        totalChars= 120,
        inputTextChars= currentText.length,
        remainingChars=(totalChars-inputTextChars),
        outputContent= '#error_sinstructions2',
        outputText= '#me_sinstructions2',
        youAre='Te quedan',
        charAvailable='caracteres disponibles',
        andUseThe='Has usado los',
        charAllowed='caracteres permitidos';
        if(remainingChars>=0){
            remainingChars=totalChars-inputTextChars;
            $(outputContent).fadeIn();
            $(outputText).html(' '+youAre+' '+remainingChars+' '+charAvailable+'.');
            
            if(remainingChars<=0){
                $(outputContent).fadeIn();
                $(outputText).html(' '+andUseThe+' '+totalChars+' '+charAllowed+'.'); 
            }
        }else{
            if(remainingChars<=0){
                $(outputContent).fadeIn();
                $(outputText).html(' '+andUseThe+' '+totalChars+' '+charAllowed+'.');  
            }       
            $(this).val(currentText.substring(0,totalChars));
        }
    });

    $('#txtImageDescription').blur(function(e){
        var outputContent= '#error_sinstructions2';
        $(outputContent).fadeOut();
    });    


});