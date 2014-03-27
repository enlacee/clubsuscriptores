$(function(){
    var jstrap = new jStrap();
    var vars = {
        contenedor_opciones: '#adminEstaContentBox',
        pag_class: '.pag',
        form: '#modalOpcion',
        modulo: '#modulo',
        btn_buscar: '#searchbt1',
        edit_class: '.optEdit',
        content_form_edit : '#content-winOpcion',
        btn_guardar : '#btnSubmitED',
        btn_cancelar: '#cancelEditOpcion',
        form_opcion: '#modalOpcion',
        fields : [
        '#nombreop',
        '#descripop',
        '#btnSubmitED',
        '#modulo_id'
        ],
        params : {
            minChar : 1,
            limit : 2
        },
        msjs : {
            defecto : 'Campo requerido.',
            nombre : {
                good : 'El nombre es correcto.',
                def : 'Ingrese un nombre.',
                bad : 'Nombre incorrecto.'
            },
            descripcion : {
                good : 'Descripción correcta.',
                def : 'Ingrese una descripción.',
                bad : 'Descripción incorrecta.'
            },
            modulo : {
                good : 'Módulo seleccionado correctamente.',
                def : 'Seleccione un módulo.',
                bad : 'Debe seleccionar un módulo.'
            }
        }
    };
    var Opciones = {
        edit: function(a) {
            $(vars.edit_class).unbind();
            $(a).bind('click', function() {
                $(vars.content_form_edit).html('');
                $(vars.content_form_edit).addClass('loading');
                var id = $(this).attr('rel');
                $.ajax({
                    url: '/admin/opciones/editar-opcion',
                    type: 'GET',
                    dataType: 'html',
                    data: {
                        id : id
                    },
                    success: function (html) {
                        $(vars.content_form_edit).removeClass('loading');
                        $(vars.content_form_edit).html(html);
                        $(vars.btn_cancelar).bind('click', function() {
                            $('.closeWM').trigger('click', null);
                        })
                        Opciones.validFields();
                    }
                })
            })
        },
        save: function() {
            var datos = $(vars.form_opcion).serialize();
            $(vars.content_form_edit).html('');
            $(vars.content_form_edit).addClass('loading');
            $.ajax({
                'url': '/admin/opciones/editar-opcion',
                'type': 'POST',
                'dataType': 'html',
                'data': datos,
                'success': function (html) {
                    $(vars.content_form_edit).removeClass('loading');
                    $(vars.content_form_edit).html(html);
                    Opciones.listarOpciones($('a.active').attr('rel'), $(vars.modulo).val());
                    Opciones.closeModal();
                }
            })
        },
        closeModal: function() {
            setTimeout(function(){
                $('.closeWM ').trigger('click', null);
            }, 3000)
        },
        hitPage: function(e) {
            $(vars.pag_class).unbind();
            Opciones.listarOpciones($(e).attr('rel'), $(vars.modulo).val());
        },
        find: function() {
            $(vars.pag_class).unbind();
            $(vars.btn_buscar).bind('click', function() {
                Opciones.listarOpciones($('a.active').attr('rel'), $(vars.modulo).val());
            });
        },
        listarOpciones: function(pag, modulo) {
            $(vars.contenedor_opciones).html('');
            $(vars.contenedor_opciones).addClass('loading');
            $.ajax({
                url: '/admin/opciones/listar-opciones',
                type: 'GET',
                dataType: 'html',
                data: {
                    pag: pag,
                    modulo: modulo
                },
                success: function (html){
                    $(vars.contenedor_opciones).removeClass('loading');
                    $(vars.contenedor_opciones).html(html);
                    $(vars.pag_class).bind('click', function() {
                        Opciones.hitPage(this);
                    });
                    Opciones.edit(vars.edit_class);
                }
            });
        },
        validFields : function(){
            //function Valid Text
            function validText(inputTxt, type, msjValid){
                $(inputTxt).blur(function(){
                    var t = $(this),
                    resp = t.parents('.block').find('.response');
                    switch(type){
                        case 'text':
                            if(jstrap.isEmpty(t.val())){
                                t.addClass('ready');
                                resp.text(msjValid.good).addClass('good').removeClass('def bad');
                            }else{
                                t.removeClass('ready');
                                resp.text(msjValid.def).addClass('bad').removeClass('def good');
                            }
                            break;
                        case 'email':
                            if(jstrap.isMail(t.val())){
                                t.addClass('ready');
                                resp.text(msjValid.good).addClass('good').removeClass('def bad');
                            }else{
                                t.removeClass('ready');
                                resp.text(msjValid.bad).addClass('bad').removeClass('def good');
                            }                      
                            break;
                    }
                }).keyup(function(){
                    var t = $(this),                
                    resp = t.parents('.block').find('.response');
                    switch(type){
                        case 'text':
                            if(t.val().length >= vars.params.minChar){
                                t.addClass('ready'); 
                                resp.text(msjValid.def).addClass('def').removeClass('bad good');
                            }else{
                                t.removeClass('ready');
                                resp.text(msjValid.bad).addClass('bad').removeClass('def good');
                            }                      
                            break;
                        case 'email':
                            if(jstrap.isMail(t.val())){
                                t.addClass('ready');
                                resp.text(msjValid.good).addClass('good').removeClass('def bad');
                            }else{
                                t.removeClass('ready');
                                resp.text(msjValid.def).addClass('def').removeClass('bad good');
                            }
                            break;
                    }                     
                });  
            }
            validText(vars.fields[0], 'text', {
                good : vars.msjs.nombre.good,
                def : vars.msjs.nombre.def
            });
            validText(vars.fields[1], 'text', {
                good : vars.msjs.descripcion.good,
                def : vars.msjs.descripcion.def
            });            
            $(vars.fields[2]).bind('click', function(e){
                e.preventDefault();
                var t = $(this);
                var readys = $(vars.form + ' .ready'),
                fields = $(vars.form + ' .field');
                if( (readys.size() >= vars.params.limit) ){
                    Opciones.save();
                }else{
                    fields
                    .not('.ready, :disabled')
                    .removeClass('ready')
                    .parents('.block')
                    .find('.response')
                    .removeClass('def good')
                    .addClass('bad')
                    .text(vars.msjs.defecto);                     
                }
            });
        }
    };
    
    //    Opciones.validFields({
    //        form : '#modalOpcion',        
    //        fields : [
    //        '#nombreop',
    //        '#descripop',
    //        '#btnSubmitED'
    //        ],
    //        params : {
    //            minChar : 1,
    //            limit : 2
    //        },
    //        msjs : {
    //            defecto : 'Campo requerido.',
    //            nombre : {
    //                good : 'El nombre es correcto.',
    //                def : 'Ingrese nombre correcto.'
    //            },
    //            descripcion : {
    //                good : 'Descripción correcta.',
    //                bad : 'Ingrese una descripción.'
    //            }
    //        }
    //    }); 
    Opciones.listarOpciones(1,0);
    Opciones.find();
})