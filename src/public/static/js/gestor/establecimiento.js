/* 
 */
establecimiento = function(){
	
    var jstrap = new jStrap();
	 
    var msgs = {
        email : {
            good : '¡OK!',
            bad : 'No parece ser un correo electrónico válido.',
            def : 'Ingrese e-mail correcto',
            mailValid : 'Email ya registrado.'
        },
        requerido : {
            good : '¡OK!',
            bad : 'Campo Requerido.',
            def : 'Campo Requerido.'
        },
        mensajeOk : {
            good : 'Actualización exitosa.'
        },
        mensajeMail : {
            good : 'La contraseña se ha enviado a su correo.',
            bad  : 'Error al generar la contraseña.'
        }
    };    
    var establecimientojs = {
        modalCamClave : function(a){
            $(a).bind('click', function(){
                var t = $(this);
                var tipo = "";
                var idUsu = t.attr('rel');
    			
                if(t.hasClass('cambioClaveUsu')) {
                    tipo = 'GET';
                } else {
                    tipo = 'POST';
                }
                var contenido = $('#content-winModUsuario');
                contenido.addClass("loading");
                contenido.html("");
                $.ajax({
                    'url' : '/gestor/establecimientos/cambio-clave',
                    'type' : tipo,
                    'dataType' : 'html',
                    'data' : {
                        'idUsu' : idUsu,
                        'csrf' : $("#csrf").text()
                    },
                    'success' : function(msg) {
                        contenido.removeClass("loading");
                        if ( $(msg).attr('data-error') == '-1') {
                            contenido.html('<div class="bad alignC bold f16">' + msgs.mensajeMail.bad + '</div>');
                            establecimientojs.closeWindowTime();
                        }
                        if ($(msg).attr('data-error') == '1') {
                            contenido.html('<div class="good alignC bold f16">' + msgs.mensajeMail.good + '</div>');
                            establecimientojs.closeWindowTime();
                        }
                        if ($(msg).attr('data-error') == '0') {
                            contenido.html(msg);
                        }
                        $('.admincrearcontraseniaBtn').unbind();
                        establecimientojs.modalCamClave('.admincrearcontraseniaBtn');
                    },
                    'error' : function(msg) {
                    }
                });
            });
        },
        modalEditUsu : function(a){
            $(a).bind('click', function(){
                var t = $(this);
                var idUsu = t.attr('rel');
                var contenido = $('#content-winModUsuario');
                contenido.addClass("loading");
                contenido.html("");
                $.ajax({
                    'url' : '/gestor/establecimientos/editar-usuario',
                    'type' : 'GET',
                    'dataType' : 'html',
                    'data' : {
                        'idUsu' : idUsu
                    },
                    'success' : function(res) {
                        contenido.removeClass("loading");
                        contenido.html(res);
                        var obj = {
                            'nombre' : $('#fNom'),
                            'apellido_paterno' : $('#fApellP'),
                            'apellido_materno' : $('#fApellM'),
                            'email' : $('#email'),
                            'submit' : $('.EinfoguardarBtn')
                        };
                        establecimientojs.validaFormEditar(obj);
                        establecimientojs.closeWindow('.EinfocancelarBtn');
                    },
                    'error' : function(res) {
                    }
                });
    			
            });
        },
        validaFormEditar : function(obj){
        	
            obj.submit.bind('click',function(e){
	        		
                if(!obj.nombre.hasClass('ready')) {
                    obj.nombre.next().removeClass('good def').addClass('bad').text(msgs.requerido.bad);
                }
                if(!obj.apellido_paterno.hasClass('ready')) {
                    obj.apellido_paterno.next().removeClass('good def').addClass('bad').text(msgs.requerido.bad);
                }
                if(!obj.apellido_materno.hasClass('ready')) {
                    obj.apellido_materno.next().removeClass('good def').addClass('bad').text(msgs.requerido.bad);
                }
                if(!obj.email.hasClass('ready')) {
                    if (obj.email.val()!= '') {
                        obj.email.next().removeClass('good def').addClass('bad').text(msgs.email.def);
                    } else {
                        obj.email.next().removeClass('good def').addClass('bad').text(msgs.requerido.bad);						
                    }
                }
                if (obj.nombre.hasClass('ready') && obj.apellido_paterno.hasClass('ready') && obj.apellido_materno.hasClass('ready') && obj.email.hasClass('ready')) {
                    establecimientojs.GuardarUsu('.EinfoguardarBtn');
                }
	        		
            });
        	
            obj.nombre.bind('blur',function(){
                var t = $(this);
                if (t.val()!='') {
                    t.addClass('ready');
                    t.next().removeClass('bad def').addClass('good').text(msgs.requerido.good);
                } else {
                    t.removeClass('ready');
                    t.next().removeClass('good def').addClass('bad').text(msgs.requerido.bad);
                }
            });
        	
            obj.apellido_paterno.bind('blur',function(){
                var t = $(this);
                if (t.val()!='') {
                    t.addClass('ready');
                    t.next().removeClass('bad def').addClass('good').text(msgs.requerido.good);
                } else {
                    t.removeClass('ready');
                    t.next().removeClass('good def').addClass('bad').text(msgs.requerido.bad);
                }
            });
        	
            obj.apellido_materno.bind('blur',function(){
                var t = $(this);
                if (t.val()!='') {
                    t.addClass('ready');
                    t.next().removeClass('bad def').addClass('good').text(msgs.requerido.good);
                } else {
                    t.removeClass('ready');
                    t.next().removeClass('good def').addClass('bad').text(msgs.requerido.bad);
                }
            });
        	
            obj.email.bind('blur',function(){
                var t = $(this);
        		
                if (t.val() != '') {
                    if(jstrap.isMail(t.val())){
                        establecimientojs.validarMail(t);
                    }else{
                        t.removeClass('ready');
                        t.next()
                        .text(msgs.email.def)
                        .addClass('bad')
                        .removeClass('good');
                    }
                } else {
                    t.removeClass('ready');
                    t.next()
                    .text(msgs.requerido.bad)
                    .addClass('bad')
                    .removeClass('good');
                }
            });
        	
        },
        validarMail : function (t){
        	
            var value= t.val(), 
            idUsu = t.attr('rel');
            t.addClass('loadingMail');
            t.css({
                'background-position' : '221px 4px'
            });
        	
            $.ajax({
                'url': '/gestor/establecimientos/validar-email',
                'type' : 'POST',
                'dataType' : 'JSON',
                'data' : {
                    'value' : value,
                    'idUsu' : idUsu
                },
                'success' : function (response){
                    if( response == true){
                        t.addClass('ready').removeClass('loadingMail');
                        t.next().removeClass('bad').addClass('good').text(msgs.email.good);
                    }else{
                        t.removeClass('ready').removeClass('loadingMail');
                        t.next().removeClass('good').addClass('bad').text(msgs.email.mailValid);
                    }
                }
            });
        },
        GuardarUsu : function(a){
            //$(a).bind('click', function(){
            var t = $(a);
            var nombres = $('#fNom').val();
            var apellido_paterno = $('#fApellP').val();
            var apellido_materno = $('#fApellM').val();
            var email = $('#email').val();
            var activo = $('#fEstado').val();
            var idUsu = t.attr('rel');
            var contenido = $('#content-winModUsuario');
            contenido.addClass("loading");
            contenido.html("");
            $.ajax({
                'url' : '/gestor/establecimientos/editar-usuario',
                'type' : 'POST',
                'dataType' : 'html',
                'data' : {
                    'idUsu' : idUsu,
                    'nombres' : nombres,
                    'apellido_paterno' : apellido_paterno,
                    'apellido_materno' : apellido_materno,
                    'email' : email,
                    'activo' : activo,
                    'csrf' : $("#csrf").text()
                },
                'success' : function(msg) {
                    contenido.removeClass("loading");
                    if ( $(msg).attr('data-error') == '-1') {
                        contenido.html(msg);
                        var obj = {
                            'nombre' : $('#fNom'),
                            'apellido_paterno' : $('#fApellP'),
                            'apellido_materno' : $('#fApellM'),
                            'email' : $('#email'),
                            'submit' : $('.EinfoguardarBtn')
                        };
                        establecimientojs.validaFormEditar(obj);
                    }
                    if ($(msg).attr('data-error') == '1') {
                        contenido.html('<div class="good alignC bold f16">' + msgs.mensajeOk.good + '</div>');
                        $("#searchbt").click();
                        establecimientojs.closeWindowTime();
                    }
                    establecimientojs.closeWindow('.EinfocancelarBtn');
                        
                },
                'error' : function(res) {
                }
            });
    			
        //});
        },
        closeWindowTime: function (){
            setTimeout(
                function(){
                    $('#winModUsuario .closeWM').click();
                    $('.EinfoguardarBtn').unbind();
                },
                1500
                );
        },
        closeWindow: function (a){
            $(a).bind('click', function (){
                $('#winModUsuario .closeWM').click();
                $('.EinfoguardarBtn').unbind();
            });
            
        }
    };
    
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
    establecimientojs.modalCamClave('.cambioClaveUsu');
    establecimientojs.modalEditUsu('.editarUsu');
    
};