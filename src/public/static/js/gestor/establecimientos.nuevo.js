/* 
 */
$(function(){
	
    var jstrap = new jStrap();
	
    var vars = {
        rs : '.response',
        okR :'ready',
        sendFlag : 'sendN',
        loading : '<div class="loading"></div>'
    };
	
    var formContact = {
        validFields : function(obj){
            //function Valid Text
            function validText(inputTxt, type, msjValid){
                $(inputTxt).blur(function(){
                    var t = $(this),
                    resp = t.parents('.bloqueNbeneficio').find('.response');
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
                            if( t.val().length >= parseInt(t.attr('maxlength')) ){
                                t.addClass('ready');
                                resp
                                .text(msjValid.good)
                                .addClass('good')
                                .removeClass('def bad');
                            }else{
                                t.removeClass('ready');
                                resp
                                .text('Ingrese número válido.')
                                .addClass('bad')
                                .removeClass('def good');
                            }
                            break;
                    }
                }).keyup(function(){
                    var t = $(this),                
                    resp = t.parents('.bloqueNbeneficio').find('.response');
	                    
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
                            if( t.val().length >= parseInt(t.attr('maxlength')) ){
                                t.addClass('ready'); 
                                resp
                                .text(msjValid.def)
                                .addClass('def')
                                .removeClass('bad good');
                            }else{
                                t.removeClass('ready');
                                resp
                                .text('Ingrese número válido.')
                                .addClass('bad')
                                .removeClass('def good');
                            }                      
                            break;                        
                    }                     
                });  
            }
            function validSelect(combo, msjValid){
                $(combo).bind('change', function(){
                    var t = $(this),
                    resp = t.parents('.bloqueNbeneficio').find('.response');
                    if(jstrap.selectReq(t.val())){
                        t.addClass('ready');
                        resp
                        .text(msjValid.good)
                        .addClass('good')
                        .removeClass('def bad');                                    
                    }else{
                        t.removeClass('ready');
                        resp
                        .text(msjValid.bad)
                        .addClass('bad')
                        .removeClass('def good');                        
                    }
                });
            }
            
            
            
	            
            //Nombre
            validText(obj.fields[1], 'text', {
                good : obj.msjs.nombre.good,
                def : obj.msjs.nombre.def
            });
	            
            //N documento
            jstrap.onlyNumDoc(obj.fields[2]);
            $(obj.fields[2]).bind('blur',function(){
                var valor = jstrap.validateRuc($(obj.fields[2]));
                var a = $(obj.fields[2]);
                var r = a.next(vars.rs);
                if (valor == true ){
                    formContact._fRucValid(a, r, obj);
                } else  {
                	a.removeClass('ready');
                    r.removeClass('good').addClass('bad').text(obj.msjs.nRuc.incorrect);
                }
            });
	            
            //tipoEst 
            validSelect(obj.fields[3],{
                good : obj.msjs.tipoEst.good,
                bad : obj.msjs.tipoEst.bad
            });
	            
            //Nombre Contacto
            validText(obj.fields[4], 'text', {
                good : obj.msjs.nombre.good,
                def : obj.msjs.nombre.def
            });
	            
            //Direccion 
            validText(obj.fields[9], 'text', {
                good : obj.msjs.direccion.good,
                def : obj.msjs.direccion.def
            });
            //Mail
            validText(obj.fields[5], 'email', {
                good : obj.msjs.email.good,
                def : obj.msjs.email.def
            }); 
            //Tlf
            jstrap.onlyNumTlf(obj.fields[6]);
            validText(obj.fields[6], 'text', {
                good : obj.msjs.tlf.good,
                def : obj.msjs.tlf.def
            });
	            
            $(obj.fields[8]).bind('click', function(e){
                e.preventDefault();
                var t = $(this);
                var readys = $(obj.form + ' .ready'),
                fields = $(obj.form + ' .field');
                var pathLogo = $('#path_imagen'),
                flagLogo = false;
                if(pathLogo.val() == '' && pathLogo.attr('rel')==undefined ) {
                    pathLogo.parents('.bloqueNbeneficio').find(vars.rs).addClass('bad').text(obj.msjs.defecto);
                    flagLogo = false ;
                } else {
                    //pathLogo.addClass(vars.okR);
                    pathLogo.parents('.bloqueNbeneficio').find(vars.rs).removeClass('bad').text('');
                    flagLogo = true ;
                }
                if( (readys.size() >= obj.params.limit) && (flagLogo == true) ){
                    //submir
                    $(obj.form).submit();
                }else{
                    //errors 	
                    fields
                    .not('.ready, :disabled')
                    .removeClass('ready')
                    .parents('.bloqueNbeneficio')
                    .find('.response')
                    .removeClass('def good')
                    .addClass('bad')
                    .text(obj.msjs.defecto);                     
                }
            });
        },
        _fRucValid : function(a, r, obj){
            var $ndoc = a;
	            
            $ndoc.addClass('loadingMail');
            var idEmp = $ndoc.attr('rel');
            $.ajax({
                url: '/gestor/establecimientos/validar-ruc/',
                type: 'post',
                data: {
                    ndoc: $ndoc.val(),
                    idEmp: idEmp
                },
                dataType: 'json',
                success: function(response){
                    if( response.status == true){
                        $ndoc.addClass('ready').removeClass('loadingMail');
                        r.removeClass('bad def').addClass('good').text(obj.msjs.nRuc.good);
                    }else{
                        $ndoc.removeClass('ready').removeClass('loadingMail');
                        r.removeClass('good def').addClass('bad').text(obj.msjs.nRuc.rucValid);
                        a.removeClass(vars.okR);
                    }
                }
            });
        }
    };
	
    formContact.validFields({
        form : '#formNueEst',   
        fields : [
        '#path_imagen',
        '#fnombre',
        '#fRuc',
        '#fTipoEstabl',
        '#fContacto',
        '#fEmail',
        '#fTelefono',
        '#fEstado',
        '#adminSaveBtnbene',
        '#direccion'
        ],
        params : {
            minChar : 1,
            limit : 9
        },
        msjs : {
            defecto : 'Campo requerido.',
            nombre : {
                good : 'El nombre es correcto.',
                def : 'Ingrese nombre correcto.'
            },
            tipoEst : {
                good : 'Tipo correcto.',
                bad : 'Seleccione un tipo.'
            },
            nRuc : {
                good : 'Su RUC es correcto',
                bad : '¡Se requiere su RUC!',
                def : 'Ingrese su RUC correcta',
                rucValid : 'Ruc ya registrado.',
                incorrect : '¡Ruc incorrecto!'
            },
            tlf : {
                good : 'El n# de telefono es correcto.',
                def : 'Ingrese n# de telefono correcto.'
            },
            email : {
                good : 'El email es correcto.',
                def : 'Ingrese email correcto.'
            },
            mensaje : {
                good : 'El mensaje es correcto.',
                def : 'Ingrese mensaje correcto.'
            },
            direccion : {
                good : 'La dirección es correcta.',
                def : 'Ingrese la dirección correctamente.'
            }
        }
    }); 
});

