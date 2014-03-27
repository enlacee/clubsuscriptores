/*
validacion form contacto
*/
$(function(){
    var jstrap = new jStrap();        
    var formContact = {
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
                        case 'email':
                            if(jstrap.isMail(t.val())){
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
                        case 'documento':
                            if((t.val().length >= parseInt(t.attr('maxlength')) && !t.hasClass('CEX')) 
                                || (t.hasClass('CEX') && t.val().length >= parseInt(11) 
                                    && t.val().length <= parseInt(t.attr('maxlength')))){
                                if(t.hasClass('PAS')){
                                    //1 Letra minimo en PAS                        		
                                    var reg1Letter = /\D/;
                                    if(reg1Letter.test($.trim(t.val()))){
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
                                }else{
                                    t.addClass('ready');                            
                                    resp
                                    .text(msjValid.good)
                                    .addClass('good')
                                    .removeClass('def bad');                        		                        		
                                }                            
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
                        case 'email':
                            if(jstrap.isMail(t.val())){
                                t.addClass('ready');
                                resp
                                .text(msjValid.good)
                                .addClass('good')
                                .removeClass('def bad');
                            }else{
                                t.removeClass('ready');
                                resp
                                .text(msjValid.def)
                                .addClass('def')
                                .removeClass('bad good');
                            }                        
                            break;
                        case 'documento':
//                            if( t.val().length >= parseInt(t.attr('maxlength')) ){
                            if((t.val().length >= parseInt(t.attr('maxlength')) && !t.hasClass('CEX')) 
                                || (t.hasClass('CEX') && t.val().length >= parseInt(11) 
                                    && t.val().length <= parseInt(t.attr('maxlength')))){
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
            //Nombre                        
            validText(obj.fields[0], 'text', {
                good : obj.msjs.nombre.good,
                def : obj.msjs.nombre.def
            });
            //Apellidos
            validText(obj.fields[1], 'text', {
                good : obj.msjs.apellido.good,
                def : obj.msjs.apellido.def
            });
            //Tlf
            jstrap.onlyNumTlf(obj.fields[3]);
            validText(obj.fields[3], 'text', {
                good : obj.msjs.tlf.good,
                def : obj.msjs.tlf.def
            });
            //N documento
            jstrap.maxLenghtDoc($(obj.fields[2]).prev());
            jstrap.onlyNumDoc(obj.fields[2]);
            validText(obj.fields[2], 'documento', {
                good : obj.msjs.nDoc.good,
                def : obj.msjs.nDoc.def
            });            
            //Mensaje
            validText(obj.fields[6], 'text', {
                good : obj.msjs.mensaje.good,
                def : obj.msjs.mensaje.def
            });           
            //Mail
            validText(obj.fields[4], 'email', {
                good : obj.msjs.email.good,
                def : obj.msjs.email.def
            });            
            //Submit
            $(obj.fields[8]).bind('click', function(e){
                e.preventDefault();
                var t = $(this),
                readys = $(obj.form + ' .ready'),
                fields = $(obj.form + ' .fields'),
                iptCaptcha = $(obj.fields[7]),
                validCaptcha = false;
                if( iptCaptcha.size()>0 ){
                    validCaptcha = jstrap.isEmpty(iptCaptcha.val());
                }else{
                    validCaptcha = true;  
                }
                
                
                //field Captcha clear
                $('#recaptcha_table .response').remove();
                //if    
                if( (readys.size() >= obj.params.limit) &&
                    validCaptcha == true ){
                    //submir
                    $(obj.form).submit();
                }else{
                    //errors
                    fields
                    .not('.ready, :disabled')
                    .removeClass('ready')
                    .parents('.block')
                    .find('.response')
                    .removeClass('def good')
                    .addClass('bad')
                    .text(obj.msjs.defecto);                     
                    //captcha
                    if(iptCaptcha.size()>0){
                        if( jstrap.isEmpty(iptCaptcha.val()) == false ){
                            $('#recaptcha_table .recaptcha_input_area')
                            .append('<span class="response bad">' + obj.msjs.defecto + '</span>');
                        }
                    }
                }          
            });            
        }
    };
    formContact.validFields({
        form : '#formContact',   
        fields : [
        '#nombres',
        '#apellidos',
        '#nro_documento',
        '#telefonos',
        '#email',
        '#categoria',
        '#mensaje',
        '#recaptcha_response_field',
        '#MictaSendbtn'
        ],
        params : {
            minChar : 1,
            limit : 7
        },
        msjs : {
            defecto : 'Campo requerido.',
            nombre : {
                good : 'El nombre es correcto.',
                def : 'Ingrese nombre correcto.'
            },
            apellido : {
                good : 'El apellido es correcto.',
                def : 'Ingrese apellido correcto.'
            },
            nDoc : {
                good : 'El n# de documento es correcto.',
                def : 'Ingrese n# de documento correcto.'
            },
            tlf : {
                good : 'El n# de teléfono es correcto.',
                def : 'Ingrese n# de teléfono correcto.'
            },
            email : {
                good : 'El email es correcto.',
                def : 'Ingrese email correcto.'
            },
            mensaje : {
                good : 'El mensaje es correcto.',
                def : 'Ingrese mensaje correcto.'
            }
        }
    });
    jstrap.pasteMaxlength('#mensaje');
});