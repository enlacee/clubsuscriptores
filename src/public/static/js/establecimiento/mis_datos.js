$(function(){
    var jstrap = new jStrap();
    var vars = {
        contenedor_usuarios: '#adEsDatosTabConten',
        pag_class: '.pag',
        form: '#establecimiento'
    };
    var MisDatos = {
        listarUsuarios: function(pag) {
            $.ajax({
                url: '/establecimiento/mis-datos/listar-usuarios',
                type: 'GET',
                dataType: 'html',
                data: {
                    pag: pag
                },
                success: function (html){
                    $(vars.contenedor_usuarios).html(html);
                    $(vars.pag_class).bind('click', function() {
                        MisDatos.hitPage(this)
                    });
                }
            });
        },
        hitPage: function(e) {
            $(vars.pag_class).unbind();
            MisDatos.listarUsuarios($(e).attr('rel'));
        },
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
                    }                     
                });  
            }
            
            validText(obj.fields[1], 'text', {
                good : obj.msjs.nombre.good,
                def : obj.msjs.nombre.def
            });
            validText(obj.fields[4], 'text', {
                good : obj.msjs.direccion.good,
                def : obj.msjs.direccion.def
            });            
            validText(obj.fields[2], 'email', {
                good : obj.msjs.email.good,
                def : obj.msjs.email.def
            }); 
            validText(obj.fields[3], 'text', {
                good : obj.msjs.tlf.good,
                def : obj.msjs.tlf.def
            });            
            jstrap.onlyNumDoc(obj.fields[3]);
            $(obj.fields[5]).bind('click', function(e){
                e.preventDefault();
                var t = $(this);
                var readys = $(obj.form + ' .ready'),
                fields = $(obj.form + ' .field');
                var pathLogo = $('#path_imagen'),
                flagLogo = false;
                if(pathLogo.val() == '' && pathLogo.attr('rel')==undefined ) {
                    pathLogo.parents('.block').find(vars.rs).addClass('bad').text(obj.msjs.defecto);
                    flagLogo = false ;
                } else {
                    //pathLogo.addClass(vars.okR);
                    pathLogo.parents('.block').find(vars.rs).removeClass('bad').text('');
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
                    .parents('.block')
                    .find('.response')
                    .removeClass('def good')
                    .addClass('bad')
                    .text(obj.msjs.defecto);                     
                }
            });
        }
    };
    
    MisDatos.validFields({
        form : '#establecimiento',        
        fields : [
        '#path_imagen',
        '#fContacto',
        '#fEmail',
        '#fTelefono',
        '#direccion',
        '#adminSaveBtnbene'
        ],
        params : {
            minChar : 1,
            limit : 5
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
    MisDatos.listarUsuarios(1);
})