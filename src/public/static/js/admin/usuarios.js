
$(function(){
    var jstrap = new jStrap(); 
    var tipo_documento='#tipo_documento',
        minValCEX=3;
    var Usuario = {
        form : '#formUsuarioA',   
        fields : [
        '#nombres',
        '#apellido_paterno',
        '#numero_documento',
        '#email',
        '#rol',
        '#establecimiento',
        '#saveBtnUsuario',
        '#apellido_materno',
        '#subRol',
        ],
        params : {
            minChar : 1,
            limit : 7
        },
        msjs : {
            defecto : 'Campo requerido.',
            nombre : {
                good : 'El nombre es correcto.',
                def : 'Ingrese nombre correcto.',
                bad : 'Se requieren tus Nombres.'
            },
            apellido : {
                good : 'El apellido es correcto.',
                def : 'Ingrese apellido correcto.',
                bad : 'Se requieren tus Apellidos.'
            },
            nDoc : {
                good : 'El n# de documento es correcto.',
                def : 'Ingrese n# de documento correcto.',
                'bad' : 'Ingrese nro de documento válido'
            },
            email : {
                good : 'El email es correcto.',
                def : 'Ingrese email correcto.',
                bad : 'No parece ser un correo electrónico válido'
            },
            perfil : {
                good : 'El perfil es correcto.',
                def : 'Seleccione un perfil válido.',
                bad : 'No parece ser un perfil válido'
            }
        },
        filtrosUsuario: function(a, b) {
            var actual = $(a);
            actual.bind("change", function(){
                $(b).submit();
            });
        },
        filtrosEstablecimiento: function(a,b) {
            var A = $(a), B = $(b);
            if (A.val()==2) {
                B.removeAttr('disabled');
            } else {
                B.val(0).attr('disabled', 'disabled');
            }
        },
        frmSubmitFiltrosUsuarios : function(a) {
            var actual = $(a);
            actual.bind("submit", function(e){
                e.preventDefault();
                var action = $(this).attr("action");
                var establecimiento = $("#idEstablecimiento").val();
                var tipo = $("#tipo").val();
                var subTipo = $("#subTipo").val();
                var estado = $("#estado").val();
                var query = $("#query").val();

                if (establecimiento!="") {
                    action=action+"/establecimiento/"+establecimiento;
                }
                if (tipo!="") {
                    action=action+"/tipo/"+tipo;
                }
                if (tipo==2 && subTipo!="") {
                    action=action+"/subTipo/"+subTipo;
                }
                if (estado!="") {
                    action=action+"/estado/"+estado;
                }
                if (query!="") {
                    action=action+"/query/"+query;
                }
                $(this).attr("action", action);
                document.location.href =action;
            });
        },
        start: function(a, b) {
            Usuario.filtrosUsuario(a, b);
            Usuario.filtrosEstablecimiento('#tipo','#subTipo');
            Usuario.frmSubmitFiltrosUsuarios(b);
        },
        date: function() {
        //            var rangemin = urls.fYearCurrent-60;
        //            var rangemax = urls.fYearCurrent-18;
        //            var range = rangemin + ":" + rangemax;
        //            var vigencia = $(".icoCalendar").datepicker({
        //                changeMonth: true,
        //                changeYear: true,
        //                showMonthAfterYear: false,
        //                maxDate: -0,
        //                yearRange:  range
        //            });
        },
        validFields : function(){
            //function Valid Text
            function validText(inputTxt, type, msjValid){
                var valTipoDoc=$(tipo_documento);
                                
                $(inputTxt).blur(function(){
                    var initClass = ((valTipoDoc.val()).split('#'))[0];
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
                                .text(msjValid.bad)
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
                                .text(msjValid.bad)
                                .addClass('bad')
                                .removeClass('def good');
                            }                      
                            break;
                        case 'documento':
                            if( t.val().length >= parseInt(t.attr('maxlength')) ||
                              (initClass=='CEX' && t.val().length >= minValCEX)){
                                t.addClass('ready');
                                resp.text(msjValid.good).addClass('good').removeClass('def bad');
                            }else{
                                t.removeClass('ready');
                                resp.text(msjValid.bad).addClass('bad').removeClass('def good');
                            }
                            break;
                    }
                }).keyup(function(){
                    var initClass = ((valTipoDoc.val()).split('#'))[0];
                    var t = $(this),                
                    resp = t.parents('.block').find('.response');
                    
                    switch(type){
                        case 'text':
                            if(t.val().length >= Usuario.params.minChar){
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
                        case 'documento':
                            if( t.val().length >= parseInt(t.attr('maxlength')) ||
                              (initClass=='CEX' && t.val().length >= minValCEX)){
                                t.addClass('ready'); 
                                resp.text(msjValid.def).addClass('def').removeClass('bad good');
                            }else{
                                t.removeClass('ready');
                                resp.text(msjValid.bad).addClass('bad').removeClass('def good');
                            }                      
                            break;                        
                    }                     
 
                });  
            } 
            
            //Nombre                        
            validText(Usuario.fields[0], 'text', {
                good : Usuario.msjs.nombre.good,
                def : Usuario.msjs.nombre.def,
                bad : Usuario.msjs.nombre.bad
            });
            //Apellidos
            validText(Usuario.fields[1], 'text', {
                good : Usuario.msjs.apellido.good,
                def : Usuario.msjs.apellido.def,
                bad : Usuario.msjs.apellido.bad
            });
            validText(Usuario.fields[7], 'text', {
                good : Usuario.msjs.apellido.good,
                def : Usuario.msjs.apellido.def,
                bad : Usuario.msjs.apellido.bad
            });
            //N documento
            jstrap.onlyNumDoc(Usuario.fields[2]);
            jstrap.maxLenghtDoc('#tipo_documento', Usuario.fields[2]);   
            validText(Usuario.fields[2], 'documento', {
                good : Usuario.msjs.nDoc.good,
                def : Usuario.msjs.nDoc.def,
                bad : Usuario.msjs.nDoc.bad
            });                                          
            //Mail
            validText(Usuario.fields[3], 'email', {
                good : Usuario.msjs.email.good,
                def : Usuario.msjs.email.def,
                bad : Usuario.msjs.email.bad
            });
            //Rol
            var rolPerfil = $(Usuario.fields[4]),
            subRolEstablecimiento = $(Usuario.fields[8]),
            rolEstablecimiento = $(Usuario.fields[5]),
            txtValidar = '2', //'establecimiento' //change;
            txtValidarmaster = '7'; //'establecimiento master''

            $.getJSON(
                "/admin/usuarios/get-modulo",
                { id: rolPerfil.val() },
                function(json) {
                    //console.log(json);
                    if ( $.trim(json) != txtValidar) {
                        rolEstablecimiento.attr('disabled', 'disabled');
                        subRolEstablecimiento.attr('disabled', 'disabled');
                    } else {
                        if (subRolEstablecimiento.val() == txtValidarmaster) {
                            rolEstablecimiento.attr('disabled', 'disabled');
                        }
                    }
                }
            );
            
            rolPerfil.bind('change', function(){
                var t = $(this);
                var resp = t.parents('.block').find('.response');
                var subRolEstablecimiento = $(Usuario.fields[8]);
                if(t.val() == 0) {
                    t.removeClass('ready');
                    resp.text(Usuario.msjs.perfil.bad).addClass('bad').removeClass('def good');
                } else {
                    t.addClass('ready');
                    resp.text(Usuario.msjs.perfil.good).addClass('good').removeClass('bad');
                }
                
                /*$.ajax({
                    'url' : '/admin/usuarios/get-modulo',
                    'type' : 'POST',
                    'dataType' : 'JSON',
                    'data' : { 'id' : t.val() },
                    'success' : function(data) {
                    	alert(data.toSource());
                    },
                    'error' : function(data) {
                    }
                });*/
                $.getJSON(
                    "/admin/usuarios/get-modulo",
                    { id: t.val() },
                    function(json) {
                        //console.log(json);
                        if(json == txtValidar){
                            rolEstablecimiento.removeAttr('disabled');
                            subRolEstablecimiento.removeAttr('disabled');
                        }else{
                            rolEstablecimiento.val(0).attr('disabled', 'disabled');
                            subRolEstablecimiento.val(0).attr('disabled', 'disabled');
                        }
                    }
                );
            });
            
            subRolEstablecimiento.bind('change', function(){
                var t = $(this);
                if(t.val() == 7){
                    rolEstablecimiento.val(0).attr('disabled', 'disabled');
                }else{
                    rolEstablecimiento.removeAttr('disabled');
                }
            });
            
            //Reset click
            $('a.GUagregarusuarioBtn, #lboxEusuarioeditar .closeWM').bind('click', function(){                
                $(Usuario.fields[0] + ', ' + Usuario.fields[1] + ', ' + Usuario.fields[7] + ', ' + Usuario.fields[2] + ', ' + Usuario.fields[3], Usuario.form)
                .removeClass('ready')
                .val('')
                .parents('.block')
                .find('.response')
                .text('')
                .removeClass('bad def good');
            });
            //Submit
            $(Usuario.fields[6]).bind('click', function(e){
                e.preventDefault();
                var t = $(this),
                readys = $(Usuario.form + ' .ready'),
                fields = $(Usuario.form + ' .fields');
                if( (readys.size() >= Usuario.params.limit) ){
                    $(Usuario.form).submit();
                }else{
                    //errors
                    fields
                    .not('.ready, :disabled')
                    .removeClass('ready')
                    .parents('.block')
                    .find('.response')
                    .removeClass('def good')
                    .addClass('bad')
                    .text(Usuario.msjs.defecto);
                }
            });
        },
        
        modalCamClave : function(a) {
            $(a).bind('click', function() {
                var t = $(this);
                var tipo = "";
                var idUsu = t.attr('rel');
                var csrf = $("#csrf").text();
    			
                if(t.hasClass('cambiarClaveListaUsuario')) {
                    tipo = 'GET';
                } else {
                    tipo = 'POST';
                }
                var contenido = $('#content-winModClaveAdm');
                contenido.addClass("loading");
                contenido.html("");
                $.ajax({
                    'url' : '/admin/usuarios/cambio-clave',
                    'type' : tipo,
                    'dataType' : 'html',
                    'data' : {
                        'idUsu' : idUsu,
                        'csrf' : csrf
                    },
                    'success' : function(msg) {
                        contenido.removeClass("loading");
                        if ( $(msg).attr('data-error') == '-1') {
                            contenido.html('<div class="bad"> Error al generar la contraseña. </div>');
                            Usuario.closeWindowTime();
                        }
                        if ($(msg).attr('data-error') == '1') {
                            contenido.html('<div class="good"> La contraseña se ha enviado a su correo. </div>');
                            Usuario.closeWindowTime();
                        }
                        if ($(msg).attr('data-error') == '0') {
                            contenido.html(msg);
                        }
                        $('.admincrearcontraseniaBtn').unbind();
                        Usuario.modalCamClave('.admincrearcontraseniaBtn');
                    },
                    'error' : function(msg) {
                    }
                });
            });
        },
        modalEditarUsuario : function(a){
            $(a).bind('click', function(){
                var t = $(this);
                var tipo = "";
                var idUsu = t.attr('rel'), tip = t.attr('tip');
                if(t.hasClass('editarListaUsuario')) {
                    tipo = 'GET';
                } else {
                    tipo = 'POST';
                }
                var contenidoDelete = $('#content-winNuevoUsuarioAdm');
                var contenido = $('#content-winEditarUsuarioAdm');
                contenido.addClass("loading");
                contenido.html("");            
                var rangemin = urls.fYearCurrent-60;
                var rangemax = urls.fYearCurrent-18;
                var range = rangemin + ":" + rangemax;
                $.ajax({
                    'url' : '/admin/usuarios/editar-usuario',
                    'type' : tipo,
                    'dataType' : 'html',
                    'data' : {
                        'id' : idUsu,
                        'tip': tip
                    },
                    'success' : function(msg) {
                        contenidoDelete.html('');
                        contenido.removeClass("loading");
                        if ( $(msg).attr('data-error') == '-1') {
                            contenido.html('<div class="bad"> Error al editar el usuario. </div>');
                            Usuario.closeWindowTime();
                        }
                        if ($(msg).attr('data-error') == '0') {
                            contenido.html(msg);
                            var fnval = $("#fecha_nacimiento").val().split('/', null);
                            var dd = ''
                            if(fnval.length == 3) {
                                dd = dd + fnval[2] + '-' + fnval[1] + '-' + fnval[0];
                            } else {
                                dd = null;
                            }
                            if($(".no-datepicker").html()==null){
                                $("#fecha_nacimiento").datepicker({
                                    changeMonth: true,
                                    changeYear: true,
                                    showMonthAfterYear: false,
                                    defaultDate: dd,
                                    maxDate: '-18y',
                                    yearRange:  range
                                });
                            }
                            Usuario.validFields();
                        }
                    },
                    'error' : function(msg) {
                    }
                });
            });
        },
        modalNuevoUsuario : function(a){
            $(a).bind('click', function(){
                var t = $(this);
                var tipo = "";
                var contenidoDelete = $('#content-winEditarUsuarioAdm');
                var contenido = $('#content-winNuevoUsuarioAdm');
                contenido.addClass("loading");
                contenido.html("");         
                $.ajax({
                    'url' : '/admin/usuarios/nuevo-usuario',
                    'type' : 'GET',
                    'dataType' : 'html',
                    'success' : function(msg) {
                        contenidoDelete.html('');
                        contenido.removeClass("loading");
                        if ( $(msg).attr('data-error') == '-1') {
                            contenido.html('<div class="bad"> Error. </div>');
                            Usuario.closeWindowTime();
                        }
                        if ($(msg).attr('data-error') == '0') {
                            contenido.html(msg);
                            var rangemin = urls.fYearCurrent-60;
                            var rangemax = urls.fYearCurrent-18;
                            var range = rangemin + ":" + rangemax;
                            $("#fecha_nacimiento").datepicker({
                                changeMonth: true,
                                changeYear: true,
                                showMonthAfterYear: false,
                                maxDate: '-18y',
                                yearRange:  range
                            });
                            Usuario.validFields();
                        }
                    },
                    'error' : function(msg) {
                    }
                });
            });
        },
        closeWindowTime: function (){
            setTimeout(
                function(){
                    $('#winModClaveAdm .closeWM').click();
                },
                1500
                );
        },
        modalReasignaClaveUser : function(a){
            $(a).bind('click', function(){
                Usuario.operacion('GET');
            });
        },
        operacion : function(type){
            var tipo = "";
            var contenido = $('#content-lboxReasigClaveUser');
            contenido.addClass("loading");
            contenido.html("");
            $.ajax({
                'url' : '/admin/usuarios/reasigna-clave',
                'type' : type,
                'dataType' : 'html',
                'success' : function(data) {
                    contenido.removeClass("loading");
                    contenido.html(data);
                }
            });
        }
    };
    
    Usuario.modalCamClave(".cambiarClaveListaUsuario");
    Usuario.modalEditarUsuario(".editarListaUsuario");
    Usuario.modalNuevoUsuario("#GUagregarusuarioBtn");
    Usuario.modalReasignaClaveUser("#GUreasignaclaveBtn");
    
    Usuario.start(".filtroListarUsuarios, #searchbt", "#formAdmFields");
});
