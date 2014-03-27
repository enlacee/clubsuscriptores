/*
validacion form contacto cuenta
*/
$(function(){
    var jstrap = new jStrap();        
    var formContactCta = {
        validFields : function(obj){
            //function Valid Text
            function validText(inputTxt, type, msjValid){
                $(inputTxt).blur(function(){
                    var t = $(this),
                    resp = t.parents('.block').find('.response');                    
                    switch(type){
                    case 'text':
                        if(jstrap.isEmpty(t.val())){
                            t.addClass('ready');
                            resp
                            .text(msjValid.good)
                            .addClass('good')
                            .removeClass('def bad');
                        }else{
                            t.removeClass('ready');
                            resp
                            .text(t.attr('errmsg'))
                            .addClass('bad')
                            .removeClass('def good');
                        }
                        break;                       
                    }                                       
                }).keyup(function(){
                    var t = $(this),                
                    resp = t.parents('.block').find('.response');
                    
                    switch(type){
                    case 'text':
                        if(t.val().length >= obj.params.minChar){
                            t.addClass('ready'); 
                            resp
                            .text(msjValid.def)
                            .addClass('def')
                            .removeClass('bad good');
                        }else{
                            t.removeClass('ready');
                            resp
                            .text(t.attr('errmsg'))
                            .addClass('bad')
                            .removeClass('def good');
                        }                      
                        break;                        
                    }                     
 
                });  
            } 
            //funcion valid select
            function validSelect(selectReq, msjValid){
                $(selectReq).bind('change', function(){
                    var t = $(this),
                    resp = t.parents('.block').find('.response'); 
                    if(jstrap.selectReq(t.val())){
                        t.addClass('ready'); 
                        resp
                        .text(msjValid.good)
                        .addClass('good')
                        .removeClass('bad def');                        
                    }else{
                        t.removeClass('ready');
                        resp
                        .text(t.attr('errmsg'))
                        .addClass('bad')
                        .removeClass('def good');                        
                    }
                });                
            }

            //categoria                        
            validSelect(obj.fields[0], {
                good : obj.msjs.categoria.good,
                def : obj.msjs.categoria.def
            });   
            //Titulo                        
            validText(obj.fields[1], 'text', {
                good : obj.msjs.tituloConsulta.good,
                def : obj.msjs.tituloConsulta.def
            });            
            //Mensaje
            validText(obj.fields[2], 'text', {
                good : obj.msjs.mensaje.good,
                def : obj.msjs.mensaje.def
            });                      
            //Submit
            $(obj.fields[4]).bind('click', function(e){
                e.preventDefault();
                var t = $(this),
                readys = $(obj.form + ' .ready'),
                fields = $(obj.form + ' .fields'),
                checkValid =  $(obj.fields[5]).is(':checked');
                //field Captcha clear
                 $('#recaptcha_table .response').remove();
                //if                    
                var iptCaptcha = $(obj.fields[3]),
                validCaptcha = false;
                if( iptCaptcha.size()>0 ){
                 validCaptcha = jstrap.isEmpty(iptCaptcha.val());    
                }else{
                  validCaptcha = true;  
                }
                if( (readys.size() >= obj.params.limit) &&
                validCaptcha == true &&
                checkValid ){
                    //submir
                    $(obj.form).submit();
                }else{
                    //errors
                    fields
                    .not('.ready, :disabled')
                    .removeClass('ready')
                    .next()
                    .removeClass('def good').
                    addClass('bad')
                    .text(obj.msjs.defecto);                    
                    //captcha
                    if(iptCaptcha.size()>0){
                        if( jstrap.isEmpty($(obj.fields[3]).val()) == false ){
                            $('#recaptcha_table .recaptcha_input_area')
                            .append('<span class="response bad">' + obj.msjs.defecto + '</span>');
                        }                        
                    }
                    //check terminos
                    if( checkValid == false ){
                      $(obj.fields[5])
                      .parents('.block')
                      .find('.response')
                      .text(obj.msjs.checkTerminos.bad);
                    }                    

                }          
            });            
        }
    }
    formContactCta.validFields({
     form : '#formContactCta',   
     fields : [
         '#categoria',
         '#titulo_consulta',
         '#mensaje',
         '#recaptcha_response_field',
         '#MictaSendbtn',
         '#chkcondi'
     ],
     params : {
        minChar : 1,
        limit : 3
     },
     msjs : {
         defecto : 'Campo requerido.',
         tituloConsulta : {
             good : 'El titulo de consulta es correcto.',
             def : 'Ingrese titulo de consulta.'
         },
         categoria : {
             good : 'La categoría es correcta.',
             def : 'Ingrese categoría.'
         },         
         mensaje : {
             good : 'El mensaje es correcto.',
             def : 'Ingrese mensaje.'
         },
         checkTerminos : {
             bad : 'Debe Aceptar las condiciones.'
         }
     }
    });
    jstrap.pasteMaxlength('#mensaje');
});


