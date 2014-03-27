/*
	 Registro
*/
$( function() {
    var estado = '';
    var jstrap = new jStrap(); 
    jstrap.onlyNumDoc('#documento_suscriptor');
    jstrap.maxLenghtDoc('#tipo_documento_suscriptor');   
    //jstrap.onlyNumDoc('#fNDoc');
    jstrap.maxLenghtDoc('#fSelDoc');
    jstrap.onlyLetters('#fNames');
    jstrap.removeSpacesUnnecessary('#fNames');
    jstrap.onlyLetters('#fSurnameP');
    jstrap.removeSpacesUnnecessary('#fSurnameP');
    jstrap.onlyLetters('#fSurnameM');
    jstrap.removeSpacesUnnecessary('#fSurnameM');
    
    var msgs = {
        cDef : {
            good :'Bien',
            bad : 'Campo Requerido',
            def :'Opcional'
        },
        cEmail : {
            good : '¡OK!',
            bad : 'No parece ser un correo electrónico válido.',
            def : 'Ingrese e-mail correcto',
            mailValid : 'Email ya registrado.'
        },
        cCodigoSuscriptor : {
            good : '¡OK!',
            bad : 'No parece ser un código de suscriptor válido.',
            def : 'Ingresa un código de suscriptor válido',
            codRegistrado : 'Código de suscriptor ya registrado.',
            codInvalid: 'Código ingresado inválido'
        },
        cPass : {
            good : '¡OK! Verifica la seguridad de tu clave',
            bad : '¡Usa de 6 a 32 caracteres!',
            def : '¡Usa de 6 a 32 caracteres! Sé ingenioso.',
            sec : {
                msgDef : 'Nivel de seguridad',
                msg1 : 'Demasiado corta',
                msg2 : 'Débil',
                msg3 : 'Fuerte',
                msg4 : 'Muy fuerte'
            }
        },
        cRePass : {
            good : '¡OK!',
            bad : 'Las contraseñas introducidas no coinciden. Vuelve a intentarlo.',
            def : 'Tienen que ser iguales'
        },
        cName : {
            good : 'El nombre se ve genial.',
            bad : '¡Se requiere tu nombre!',
            def : 'Ingrese nombre correcto'
        },
        cApell : {
            good : 'El Apellido se ve genial.',
            bad : '¡Se requiere tu apellido!',
            def : 'Ingrese apellido correcto'
        },
        cBirth : {
            good : '!OK¡',
            bad : '¡Se requiere su fecha de nacimiento!',
            def : 'Ingrese su fecha de nacimiento completa.',
            exed : 'Incorrecto!. La fecha de nacimiento seleccionada es mayor a la fecha actual'
        },
        cSexo : {
            good : '!OK¡',
            bad : '¿Femenino o Masculino?',
            def : 'Defina su sexo'
        },
        cTlfNum : {
            good : '!OK¡',
            bad : 'Incorrecto',
            def : 'Ingrese Número Fijo ó Celular',
            min : 'Debe ingresar un minimo de 6 dígitos'
        },
        cSDoc : {
            good : '!OK¡',
            bad : '',
            def : '¡OK!'
        },
        cDocNum : {
            good : '!OK¡',
            bad : 'Incorrecto',
            def : 'Ingrese número de Documento',
            docNumValid : 'Numero de Doc. ya registrado.',
            docNumSuscriptor : 'Usted puede registrase como suscriptor.'
        },
        cPais : {
            good : '!OK¡',
            bad : 'Selecciona país',
            def : '!OK¡'
        },
        cDepa : {
            good : '!OK¡',
            bad : 'Selecciona Departamento',
            def : '!OK¡'
        },
        cDist : {
            good : '!OK¡',
            bad : 'Selecciona Distrito',
            def : '!OK¡'
        }
    }
    var vars = {
        rs : '.response',
        okR :'ready',
        sendFlag : 'sendN',
        loading : '<div class="loading"></div>'
    }
    var formRegistro = {
        fCodigoSuscriptor : function(a,good,bad,def) {
            $(a).bind('blur', function() {
                var t = $(this),
                r =  t.parents('.block').find(vars.rs);
                if(!$('#chkCodSuscriptor').is(':checked')) {
                    return true;
                }
                //TODO determinar y validar cual es el tamaño máximo del codigo de suscriptor
                if(t.val()!='') {
                    formRegistro._fValidaCodigoSuscriptor(a, t, r);	
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
                return true;
            });
            $(a).keyup( function() {
                var t=$(this);
                if(t.val().length>0) {
                    $('#chkCodSuscriptor').attr('checked', true);
                    $('#registrarse .required').not('.s_usuario').attr('disabled', true);
                    formRegistro.fSetDisableNotRequired(true);
                }else {
                    $('#chkCodSuscriptor').attr('checked', false);
                    $('#registrarse .required').attr('disabled', false);
                    formRegistro.fSetDisableNotRequired(false);
                }
            });
        },
        fChkCodSuscriptor : function(a) {
            $(a).change(function() {
                if($('#chkCodSuscriptor').is(':checked')) {
                    $('#registrarse .required').not('.s_usuario').attr('disabled', true);
                    formRegistro.fSetDisableNotRequired(true);
                }else {
                    $('#registrarse .required').attr('disabled', false);
                    formRegistro.fSetDisableNotRequired(false);
                    $('#registrarse .required').val('').removeAttr('checked').removeAttr('selected');
                }
            })
        },
        fSetDisableNotRequired : function (value){
            $('#dayjFunctions').attr('disabled', value);
            $('#monthjFunctions').attr('disabled', value);
            $('#yearjFunctions').attr('disabled', value);
            $('#sexoMF-M').attr('disabled', value);
            $('#sexoMF-F').attr('disabled', value);  
            $('#fSelDoc').attr('disabled', value);  
        },
        _fValidaCodigoSuscriptor : function(a, t, r) {
            var $codigo = t;
            r.text('');	
            $codigo.addClass('loadingMail');	
            $.ajax({
                url: '/registro/validar-codigo-suscriptor/',
                type: 'post',
                data: {
                    codigo_suscriptor: $codigo.val()
                },
                dataType: 'json',	    				
                success: function(response){
                    if( response.status == 'suscrito'){
                        $codigo.addClass('ready').removeClass('loadingMail');
                        r.removeClass('bad def').addClass('good').text(msgs.cCodigoSuscriptor.good);
                    }else{
                        $codigo.removeClass('ready').removeClass('loadingMail');
                        var tip = msgs.cCodigoSuscriptor.codRegistrado;
                        if(response.status == 'no_existe')
                            tip = msgs.cCodigoSuscriptor.codInvalid;
                        r.removeClass('good def').addClass('bad').text(tip);
                        t.removeClass(vars.okR);	
                    }
                },
                error : function(response){
                    $codigo.removeClass('ready').removeClass('loadingMail');
                    r.removeClass('good def').addClass('bad').text(msgs.cCodigoSuscriptor.codValid);
                    t.removeClass(vars.okR);	  					
                }
            });
        },
        fMail : function(a,good,bad,def) {

            $(a).bind('blur', function() {
                var t = $(this),
                r =  t.parents('.block').find(vars.rs),
                ep = /^(([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+)?$/g;
                if( ep.test(t.val()) && t.val() != '' ) {
                    formRegistro._fMailValid(a, t, r);
                } else {					
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);					
                }
            });

        },
        _fMailValid : function(a, t, r){

            var $email = t;
            r.text('');	
            $email.addClass('loadingMail');	
            $.ajax({
                url: '/registro/validar-email/',
                type: 'post',
                data: {
                    email: $email.val() 
                },
                dataType: 'json',	    				
                success: function(response){
                    if( response.status == true){
                        $email.addClass('ready').removeClass('loadingMail');
                        r.removeClass('bad def').addClass('good').text(msgs.cEmail.good);
                    }else{
                        $email.removeClass('ready').removeClass('loadingMail');
                        r.removeClass('good def').addClass('bad').text(msgs.cEmail.mailValid);
                        t.removeClass(vars.okR);	
                    }
                },
                error : function(response){
                    $email.removeClass('ready').removeClass('loadingMail');
                    r.removeClass('good def').addClass('bad').text(msgs.cEmail.mailValid);
                    t.removeClass(vars.okR);	  					
                }
            });

        },
        fDni : function(a, b ,good, bad, def){
            $(a).bind('blur', function() {
                var n = $(this),
                r = n.parents('.block').find(vars.rs);
                if(n.val()!='' && n.val().length==n.attr('maxlength')) {
                    formRegistro._fDniValid(n, b, r);
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    n.removeClass(vars.okR);
                }
            });
        },
        minLenght : function(a, d){
            $(a).bind('blur', function() {
                var n = $(this),
                r = n.parents('.block').find(vars.rs);
                if(n.val().length>=d) {
                    r.removeClass('bad').addClass('good').text(msgs.cTlfNum.good);
                    n.addClass(vars.okR);
                } else {
                    n.removeClass(vars.okR);
                    if (n.val().length<d && n.val().length >0) {
                        r.removeClass('good').addClass('bad').text(msgs.cTlfNum.min);
                    }else{
                        r.removeClass('good').addClass('bad').text(msgs.cTlfNum.bad);
                    }
                }
            });
        },
        _fDniValid : function(n, t, r){
            var td = $(t).val().split('#');
            var ndoc = n, tdoc = td[0];
            r.text('');
            ndoc.addClass('loadingNumDoc');
            estado = '';
            $.ajax({
                url: '/registro/validar-documento/',
                type: 'post',
                data: {
                    ndoc: ndoc.val(),
                    tdoc: tdoc
                },
                dataType: 'json',
                success: function(response){
                    switch(response.status) {
                        case 'valido':
                            ndoc.addClass('ready').removeClass('loadingNumDoc suscriptor');
                            r.removeClass('bad def').addClass('good').text(msgs.cDocNum.good);
                            $('#registrarse .required').attr('disabled', false);
                            formRegistro.fSetDisableNotRequired(false);
                            $('#registrarse .required').removeAttr('checked').removeAttr('selected');
                            break;
                        case 'registrado':
                            ndoc.removeClass('ready').removeClass('loadingNumDoc suscriptor');
                            r.removeClass('good def').addClass('bad').text(msgs.cDocNum.docNumValid);
                            $(t).removeClass(vars.okR);
                            $('#registrarse .required').attr('disabled', false);
                            formRegistro.fSetDisableNotRequired(false);
                            $('#registrarse .required').removeAttr('checked').removeAttr('selected');
                            break;
                        case 'suscrito':
                            ndoc.addClass('ready suscriptor').removeClass('loadingNumDoc');
                            r.removeClass('bad def').addClass('good').text(msgs.cDocNum.docNumSuscriptor);
                            $('#registrarse .required').not('.s_usuario').attr('disabled', true);
                            formRegistro.fSetDisableNotRequired(true);
                            $(t).attr('disabled', false);
                            ndoc.attr('disabled', false);
                            break;
                        case 'invitado':
                            estado = 'invitado';
                            r.removeClass('bad def').addClass('good').text('');
                            $('#registrarse .required').not('.s_usuario').attr('disabled', true);
                            $('#fSelDoc').attr('disabled', false);
                            $('#fNDoc').attr('disabled', false);
                            formRegistro.fSetDisableNotRequired(true);
                            ndoc.addClass('ready suscriptor').removeClass('loadingNumDoc');
                            $(t).attr('disabled', false);
                            ndoc.attr('disabled', false);
                            
                            $('span.response').removeClass('good def bad loading').text('');
                            r.addClass('good').removeClass('bad def').text(response.message);
                            $('#fNames').val(response.suscriptor.nombres);
                            $('#fSurnameP').val(response.suscriptor.apellido_paterno);
                            $('#fSurnameM').val(response.suscriptor.apellido_materno);
                            $('#fSelDoc').val(response.suscriptor.tipo_documento);
                            $('#fEmail').val(response.suscriptor.email_contacto);
                            $('#fNDoc').val(response.suscriptor.numero_documento);
                            $('#sexoMF-' + response.suscriptor.sexo).attr('checked', true);
                            $('#fTlfFC').val(response.suscriptor.telefono);
                            $('#es_suscriptor').val(1);
                            if(response.suscriptor.email_contacto != '' && response.suscriptor.email_contacto != null)
                                $('#fEmail').trigger('blur', null);
                            var fn;
                            if (response.suscriptor.fecha_nacimiento != null)
                                fn = response.suscriptor.fecha_nacimiento.split('-')
                            
                            if(!(response.suscriptor.fecha_nacimiento == null || fn[0] == '0000')) {
                                $('#dayjFunctions').val(parseInt(fn[2], 10));
                                $('#monthjFunctions').val(parseInt(fn[1], 10));
                                $('#yearjFunctions').val(fn[0]);
                            }
                            break;
                    };
                },
                error : function(response){
                    ndoc.removeClass('ready').removeClass('loadingNumDoc');
                    r.removeClass('good def').addClass('bad').text(msgs.cDocNum.docNumValid);
                    $(t).removeClass(vars.okR);
                }
            });
        },
        fPass : function(a,b,c) {
            var good = msgs.cPass.good,
            bad = msgs.cPass.bad,
            def = msgs.cPass.def,
            msgDef = msgs.cPass.sec.msgDef,
            msg1 = msgs.cPass.sec.msg1,
            msg2 = msgs.cPass.sec.msg2,
            msg3 = msgs.cPass.sec.msg3,
            msg4 = msgs.cPass.sec.msg4,
            pf1 = $('#pf1'),
            pf2 = $('#pf2'),
            pf3 = $('#pf3'),
            pf4 = $('#pf4'),
            msg = $('#txtPass'),
            ep = /[a-z]|[A-Z]|\d|[^A-Za-z0-9]/,
            epMin = /[a-z]/,
            epMay = /[A-Z]/,
            epNum = /\d/,
            epEsp = /[^A-Za-z0-9]/,
            epMinC = /[a-z]+[A-Z]+|[A-Z]+[a-z]+|[a-z]+\d+|\d+[a-z]+|[a-z]+[^A-z0-9]+|[^A-z0-9]+[a-z]+/,
            epMayC = /[A-Z]+\d+|\d+[A-Z]+|[A-Z]+[^A-z0-9]+|[^A-z0-9]+[A-Z]+/,
            epEspC = /[^A-z0-9]+\d+|\d+[^A-z0-9]+/;
            $(a).keyup( function() {
                var t = $(this),
                v = $(this).val(),
                r = t.parents('.block').find(vars.rs);
                if(v.length>=(b)) {
                    r.removeClass('bad').addClass('good').text(good);
                    if(ep.test(t.val())) {
                        pf1.removeClass('bgRed bgGreen').addClass('bgYellow');
                        pf2.removeClass('bgRed bgGreen').addClass('bgYellow');
                        pf3.removeClass('bgRed bgGreen');
                        pf4.removeClass('bgGreen');
                        msg.text(msg2);

                        if( epMinC.test(t.val()) || epMayC.test(t.val()) || epEspC.test(t.val()) ) {
                            pf1.removeClass('bgRed bgYellow').addClass('bgGreen');
                            pf2.removeClass('bgRed bgYellow').addClass('bgGreen');
                            pf3.removeClass('bgYellow').addClass('bgGreen');
                            pf4.removeClass('bgGreen');
                            msg.text(msg3);
                        }
                        if(epMay.test(t.val()) && epNum.test(t.val()) && epEsp.test(t.val())) {
                            pf1.removeClass('bgRed bgYellow').addClass('bgGreen');
                            pf2.removeClass('bgRed bgYellow').addClass('bgGreen');
                            pf3.removeClass('bgYellow').addClass('bgGreen');
                            pf4.addClass('bgGreen');
                            msg.text(msg4);
                        }
                    }
                    t.addClass(vars.okR);
                } else {
                    r.removeClass('good bad').text(def);
                    pf1.addClass('bgRed').removeClass('bgYellow bgGreen');
                    pf2.removeClass('bgYellow bgGreen');
                    pf3.removeClass('bgGreen');
                    pf4.removeClass('bgGreen');
                    msg.text(msg1);
                    t.removeClass(vars.okR);
                }
                if(v.length==0) {
                    pf1.removeClass('bgRed bgYellow');
                    pf2.removeClass('bgRed bgYellow');
                    msg.text(msgDef);
                }
                var cc = $(c);
                if(cc.val().length>0) {
                    rr = cc.next(vars.rs);
                    if(cc.val()!==t.val()) {
                        rr.removeClass('god bad').text(msgs.cRePass.def);
                    }
                    else {
                        rr.removeClass('bad').addClass('good').text(msgs.cRePass.good);
                    }
                }
            }).blur( function() {
                var t = $(this), 
                r = t.parents('.block').find(vars.rs);
                if(t.val().length>=b) {
                    r.removeClass('bad').addClass('good').text(good);
                    t.addClass(vars.okR);
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
            });
        },
        fRePass : function(a,b,c) {
            var good = msgs.cRePass.good,
            bad = msgs.cRePass.bad,
            def = msgs.cRePass.def,
            r = $(a).parents('.block').find(vars.rs);
            $(a).keyup( function() {
                var t=$(this);
                if(t.val().length>=c) {
                    if(t.val()===$(b).val()) {
                        r.removeClass('bad').addClass('good').text(good);
                        t.addClass(vars.okR);
                    } else {
                        r.removeClass('good bad').text(def);
                        t.removeClass(vars.okR);
                    }
                } else {
                    r.removeClass('good bad').text(def);
                    t.removeClass(vars.okR);
                }
            }).blur( function() {
                var t=$(this);
                if(t.val().length>=c) {
                    if(t.val()!==$(b).val()) {
                        r.removeClass('good').addClass('bad').text(bad);
                        t.removeClass(vars.okR);
                    } else {
                        r.removeClass('bad').addClass('good').text(good);
                        t.addClass(vars.okR);
                    }
                }
                else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
            });
        },
        fInput : function(a,good,bad,def) {
            var A = $(a),
            r = A.parents('.block').find(vars.rs);
            A.blur( function() {
                var t = $(this);
                if(t.val().length>0) {
                    r.removeClass('bad').addClass('good').text(good);
                    t.addClass(vars.okR);
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
            }).keypress( function() {
                var t = $(this);
                if(t.val().length===0) {
                    //r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                } else {
                    r.removeClass('good bad').text(def);
                    t.addClass(vars.okR);
                }
            });
        },
        fIDate : function(a,good,bad,def) {
            var A = $(a),
            r = A.parents('.block').find(vars.rs);
            A.change( function() {
                var t=$(this);
                r.removeClass('bad').addClass('good').text(good);
                t.addClass(vars.okR);
            });
        },
        fRadius : function(a,b,good,bad,def) {
            var A = $(a);
            A.bind('change', function() {
                var t = $(this);
                A.removeClass(vars.okR);
                if(t.is(':checked')) {
                    t.addClass(vars.okR);
                }
                t.parents(b).next(vars.rs).removeClass('def bad').addClass('good').text(good);
            });
        },
        fINum : function(a) {
            return $(a).each( function() {
                var t = $(this);
                t.keydown( function(e) {
                    var key = e.keyCode || e.charCode || e.which || window.e ;
                    return (key == 8 || key == 9 || key == 32 ||
                        (key >= 48 && key <= 57)||
                        (key >= 96 && key <= 106)||
                        key==109 || key==116 );
                });
            });
        },
        fOnlyNumTlf : function(a) {
            return $(a).each( function() {
                var t = $(this),
                isShift = false;
                t.keypress( function(e) {
				
                    var key = e.keyCode || e.charCode || e.which || window.e ;
						
                    if(key == 16) isShift = true;
							
                    return ( key == 8 || key == 9 || key == 32 || 
                        key == 40 || key == 41 || key == 42 || 
                        key == 45 || key == 35 ||
                        ( key == 48 && isShift == false ) ||
                        ( key == 49 && isShift == false ) ||
                        ( key == 50 && isShift == false ) ||
                        ( key == 51 && isShift == false ) ||
                        ( key == 52 && isShift == false ) ||
                        ( key == 53 && isShift == false ) ||
                        ( key == 54 && isShift == false ) ||
                        ( key == 55 && isShift == false ) ||
                        ( key == 56 && isShift == false ) ||
                        ( key == 57 && isShift == false ) 
						
                        );		
					
                });
                t.bind('paste', function(){
                    setTimeout(function() {
                        var value = t.val();
                        var newValue = value.replace(/[^0-9-#-*-(-)--]/g,'');
                        t.val(newValue);
                    }, 0);
                });				

            });
        },
        fOnlyNumDoc : function(a) {
            return $(a).each( function() {
                var t = $(this),
                isShift = false;
                t.keypress( function(e) {
				
                    var key = e.keyCode || e.charCode || e.which || window.e ;
						
                    if(key == 16) isShift = true;
							
                    return ( key == 8 || key == 9 || key == 32 || 
                        ( key == 48 && isShift == false ) ||
                        ( key == 49 && isShift == false ) ||
                        ( key == 50 && isShift == false ) ||
                        ( key == 51 && isShift == false ) ||
                        ( key == 52 && isShift == false ) ||
                        ( key == 53 && isShift == false ) ||
                        ( key == 54 && isShift == false ) ||
                        ( key == 55 && isShift == false ) ||
                        ( key == 56 && isShift == false ) ||
                        ( key == 57 && isShift == false ) 
						
                        );		
					
                });
                t.bind('paste', function(){
                    setTimeout(function() {
                        var value = t.val();
                        var newValue = value.replace(/[^0-9]/g,'');
                        t.val(newValue);
                    }, 0);
                });		

            });
        },		
        fWordsOnly : function(a) {
            return $(a).each( function() {
                var t = $(this);
                t.keydown( function(e) {
                    var key = e.keyCode || e.charCode || e.which || window.e ;
                    return(	key == 8 || key == 9 || key == 13 || key == 32 ||
                        key > 64 && key < 91 ||
                        key == 192 );
                });
            });
        },
        fUbi : function(a,b,c){
            var A = $(a),
            B = $(b),
            C = $(c),
            r = $.trim(A.attr('rel')),
            rB = $.trim(B.attr('rel'));							
            var paisCargado =  $.trim($(a + ' option:selected' ).val());
            var ciudadCargado =  $.trim($(b + ' option:selected' ).val());				
            if( paisCargado != r && paisCargado != 'none' ){
                A.addClass(vars.sendFlag);
                B.attr('disabled','disabled').val('none').focus().siblings('label').addClass('noReq').children('span').html('&nbsp;');
                C.attr('disabled','disabled').val('none').focus();
                B.removeClass(vars.okR).next().html('&nbsp;').removeClass('bad good'); 
                C.next().html('&nbsp;').removeClass('bad good'); 	
            }else if( paisCargado == r && (ciudadCargado != rB && ciudadCargado != 'none') ){
                C.attr('disabled','disabled').val('none').focus().next(vars.rs).removeClass('god bad').text('');			
            }else if( paisCargado == r ){
                A.addClass(vars.okR);
                B.addClass(vars.okR);					
            }
            A.bind('change',function(){
                var t = $(this);
                if(t.val() == r){
                    t.removeClass(vars.sendFlag);
                    B.removeAttr('disabled');
                    B.siblings('label').removeClass('noReq').children('span').html('* ');       		  	    		
                }else{
                    t.addClass(vars.sendFlag);
                    B.attr('disabled','disabled').val('none').focus().siblings('label').addClass('noReq').children('span').html('&nbsp;');
                    C.attr('disabled','disabled').val('none').focus().siblings('label').addClass('noReq').children('span').html('&nbsp;');
                    B.removeClass(vars.okR).next().html('&nbsp;').removeClass('bad good'); 
                    C.removeClass(vars.okR).next().html('&nbsp;').removeClass('bad good');
                }
                if(t.val() == 'none'){
                    A.removeClass(vars.okR); 		
                }else{
                    A.addClass(vars.okR);
                } 	
            });		
            B.bind('change',function(){
                var t = $(this);
                if(t.val() == rB){
                    C.removeAttr('disabled');
                    C.siblings('label').removeClass('noReq').children('span').html('* ');
                }else{
                    C.attr('disabled','disabled').val('none').focus().siblings('label').addClass('noReq').children('span').html('&nbsp;');
                    C.removeClass(vars.okR).next().html('&nbsp;').removeClass('bad good'); 
                    B.next().html('&nbsp;').removeClass('bad good'); 	
                }		  			  			  	   
            });								
        },		
        fSelect : function(a,good,bad,def) {
            var A = $(a),
            r = A.next(vars.rs);
            A.bind('change', function() {
                var t = $(this);
                if(t.val() == 'none') {
                    A.removeClass(vars.okR);
                    r.removeClass('good def').addClass('bad').text(bad);
                } else {
                    r.addClass('good').removeClass('bad def').text(good);
                    A.addClass(vars.okR);
                }
            });
        },
        fOldPass : function(a){
            var trigger = $(a),
            res = trigger.siblings('.response');
            if( ($('body').is('#myAccount')) ) {
                trigger.keyup( function(){
                    res.removeClass('bad good').addClass('def').text('');
                });
            }				
        },		
        maxLenghtN : function(trigger){
            var select = $(trigger),
            input = select.next();
            select.bind('change', function(){
                var t = $(this),
                string = (t.val()).split('#'),
                numMax = string[1],
                inputVal = input.val();
                input.removeAttr('maxlength').attr('maxlength', numMax);
                input.val(inputVal);
                input.focus();

            });				
            input.bind('keyup click blur focus change paste', function(e) {
                var t = $(this),
                string = (t.siblings('select').val()).split('#'),
                numMax = parseInt(string[1]),
                valueArea;
                var key = e.which;
                var length = t.val().length;
                if( length > numMax ) {
                    valueArea = t.val().substring(numMax, '') ;
                    input.val(valueArea);
                }
            });						
        },
        fDistritoValidacion : function(a,b,good,bad,def) {
            var dist = $(b),
            A = $(a),
            r = dist.next(vars.rs);
            if(A.val()==3926 && dist.val()=="none") {
                dist.removeClass(vars.okR);
                r.removeClass('good def').addClass('bad').text(bad);
            } else {
                r.addClass('good').removeClass('bad def').text(good);
                dist.addClass(vars.okR);
            }
        },
        deleteMesLoad : function(){
            var selectAll = $('#monthjFunctions'),
            countOpt, anioOpt, bucle = 12 - vars.vMonthCurrent;
            $.each(selectAll, function(i, v){	
                countOpt = $(selectAll[i]).children('option').size(),
                anioOpt = $(selectAll[i]).next().val(); 
                if( Number(anioOpt) == vars.vYearCurrent ){
                    for( x = 0; x <= bucle; x++){
                        $(selectAll[i]).children('option').eq(12-x).remove().end();
                    }	
                }
            });			
        },	    
        jFunctions : function(object){			
            var inputValue = $('#fBirthDate');
			
            var mesNombres = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            //inicioAnio = 1910,
            inicioAnio = urls.fMinDate,
            finResto = 15,      
            //finAnio = 2011,
            finAnio = urls.fYearCurrent - finResto,
            separador = '/',
            //inicial = '21' + separador + '10' + separador + '1985',
            inicial = inputValue.val(),
            inicialPart = inicial.split(separador),
            txtDia = '-- Día --',
            txtMes = '-- Mes --',
            txtAnio = '-- Año --';
			
            var dia = $('#dayjFunctions'),
            mes = $('#monthjFunctions'),
            anio = $('#yearjFunctions');

            anio.one('click', function(){
                if(anio.val() == '0'){
                    anio.val(finAnio);	
                }				
            });						
            //años
            var anioI = inicioAnio,
            anioE = finAnio,
            iterador = anioE - anioI;
            anio.append('<option value="0">' + txtAnio  + '</option>');
            for (i = 0 ; i <= iterador; i++){
                anio.append('<option value="' + (anioI + i) + '">' + (anioI + i) + '</option>');
            }
            //meses
            var longitudMeses = mesNombres.length;	
            mes.append('<option value="0">' + txtMes  + '</option>');	
            for (j = 0 ; j < longitudMeses; j++){
                mes.append('<option value="' + (j+1) + '">' + mesNombres[j] + '</option>');
            }
            //dias
            var longitudDias,
            valorDia = Number(inicialPart[0]),
            valorMes = Number(inicialPart[1]),
            valorAnio = Number(inicialPart[2]);
			
            // Valores por defecto para valores desconocidos
            if(isNaN(valorDia)){
                valorDia = 0;
            }
            if(isNaN(valorMes)){
                valorMes = 0;
            }
            if(isNaN(valorAnio)){
                valorDia = 0;
            }						
			
            //bisiesto
            function anioBisiesto(anioF){
                var checkAnioF = (((anioF % 4 == 0) && (anioF % 100 != 0)) || (anioF % 400 == 0)) ? 1 : 0;
                if ( !checkAnioF )  
                    return false;
                else 
                    return true;
            }			
            var checkAnio = anioBisiesto(valorAnio);
					
            if( valorMes == 1 || valorMes == 3 || valorMes == 5 || valorMes == 7 || valorMes == 8 || valorMes == 10 || valorMes == 12 ){
                longitudDias = 31;
            }else if( valorMes == 2 ){
                if(checkAnio == true){
                    longitudDias = 29;
                }else{
                    longitudDias = 28;
                }
            }else if( valorMes == 4 || valorMes == 6 || valorMes == 9 || valorMes == 11 ){
                longitudDias = 30;
            }else{
                // Para valores desconocidos como = 0
                longitudDias = 31;
            }
            // longitudDias = cantidad de dias por mes y anio bisiesto	
            dia.append('<option value="0">' + txtDia  + '</option>');			
            function loopDias(longitudDias){
                for (k = 0 ; k < longitudDias; k++){
                    dia.append('<option value="' + (k+1) + '">' + (k+1) + '</option>');
                }		
            }
            loopDias(longitudDias);			
            //focus init valor select			
            if( $.browser.msie && $.browser.version.substr(0,1) < 7 ) {
                // IE 6 Fuck
                setTimeout(function(){
                    //focus init valor select			
                    dia.val(valorDia);
                    mes.val(valorMes);
                    anio.val(valorAnio);				
                }, 1000);	
            }else{
                //focus init valor select	
                dia.val(valorDia);
                mes.val(valorMes);
                anio.val(valorAnio);	
            }                      		
            //change dia
            dia.bind('change', function(){
                //escrbiendo fecha en input
                fechaDefault(dia, mes, anio);				
            });
            //change mes
            mes.live('change', function(){
                var t = $(this),
                longitudChangeDias,
                nMes = parseInt(t.val()),
                nAnioM = parseInt(anio.val());
                checkMesAnioBisiesto = anioBisiesto(nAnioM);
                if( nMes ==  2){
                    if(checkMesAnioBisiesto == true){
                        longitudChangeDias = 29;
                    }else{
                        longitudChangeDias = 28;
                    }
                }else if( nMes == 1 || nMes == 3 || nMes == 5 || nMes == 7 || nMes == 8 || nMes == 10 || nMes == 12 ){
                    longitudChangeDias = 31;
                }else if( nMes == 4 || nMes == 6 || nMes == 9 || nMes == 11 ){
                    longitudChangeDias = 30;
                }
				
                // loop dias
                var diaActualizado = parseInt(dia.children('option:last').val());
                actualizandoDias(dia, diaActualizado, longitudChangeDias); 					
				
                //escrbiendo fecha en input
                fechaDefault(dia, mes, anio);										
            });
            //change año
            anio.live('change', function(){
                var t = $(this),
                longitudChangeAnioDias,
                nAnio = parseInt(t.val()),
                anioBisiestoChange = anioBisiesto(nAnio),
                nMesY = parseInt(mes.val());
                // loop dias Febrero
                if( nMesY == 2 ){
                    if(anioBisiestoChange == true){
                        longitudChangeAnioDias = 29;
                    }else{
                        longitudChangeAnioDias = 28;
                    }
                    //loop dias
                    var diaActualizadoAnio = parseInt(dia.children('option:last').val());
                    actualizandoDias(dia, diaActualizadoAnio, longitudChangeAnioDias);					
                }				
                //escrbiendo fecha en input
                fechaDefault(dia, mes, anio);
											
            });	
						
            //cambio de fecha
            function fechaDefault(dia, mes, anio){
                if( inputValue.size() == 1 ){
                    inputValue.val(dia.val() + separador + mes.val() + separador + anio.val());
                    // add ready // 0 valor a buscar
                    var splitStr = inputValue.val().split('/'),	
                    str1 = splitStr[0],
                    str2 = splitStr[1],
                    str3 = splitStr[2];
					
                    if ((str1.split(''))[0].indexOf('0') == -1 && (str2.split(''))[0].indexOf('0') == -1 && (str3.split(''))[0].indexOf('0') == -1){
						
                        if(Number(str3) == urls.fYearCurrent && 
                            Number(str2) == urls.fMonthCurrent &&
                            Number(str1) > urls.fDayCurrent){
						  
                            //Esta mal si marcan mayor a la fecha actual -- El dia Mal
                            inputValue.removeClass('ready');
                            inputValue.parents(".block").find(".response").removeClass('good').addClass('bad').text(msgs.cBirth.exed);
                        //esta mal							
						
                        }else if(Number(str3) == urls.fYearCurrent && 
                            Number(str2) > urls.fMonthCurrent){
						
                            //Esta mal si marcan mayor a la fecha actual -- El mes Mal
                            inputValue.removeClass('ready');
                            inputValue.parents(".block").find(".response").removeClass('good').addClass('bad').text(msgs.cBirth.exed);
                        //esta mal														
						
                        }else{
                            inputValue.addClass('ready');
                            inputValue.parents(".block").find(".response").removeClass('bad').addClass('good').text(msgs.cBirth.good);
                        //esta ok							
                        }          
            
                    }else{

                        inputValue.removeClass('ready');
                        inputValue.parents(".block").find(".response").removeClass('good bad').addClass('def').text(msgs.cBirth.def);
                    //esta mal           

                    }
                //fin add ready																				 
                }
            }
			
            //actualizando los dias
            function actualizandoDias(dia, diaActualizado, longitudChangeDias){
                if(diaActualizado > longitudChangeDias){
                    for( x = diaActualizado; x > longitudChangeDias; x--){
                        dia.children('option').eq(x).remove();
                    }	

                }
                if(diaActualizado < longitudChangeDias){
                    for( x = diaActualizado; x < longitudChangeDias; x++){
                        dia.append('<option value="' + (x + 1) + '">' + (x + 1) + '</option>');
                    }

                } 						
            }
        //fin			
        },    
        fSubmit : function(a,b,c,f1,f2,f3,f4,f5,f6,f7,f8,f9,f10,f11,f12) {
            var A=$(a), B=$(b), c = 9, d = 3;
            F1 = $(f1), F2 = $(f2), F3 = $(f3), F4 = $(f4),
            F5 = $(f5), F6 = $(f6), F7 = $(f7), F8 = $(f8),
            F9 = $(f9), F10 = $(f10),
            F11 = $(f11), F12 = $(f12);
            A.bind('click', function(e) {
                var comboscategoria = $(".categoriax").hasClass("unready");
                e.preventDefault();
                if($('#es_suscriptor').val() == 1) {
                    if(estado == 'invitado') {
                        if(B.find('.' +vars.okR + '.s_usuario').size() >= 3 && !comboscategoria) {
                            B.submit();
                        } else {
                            $('#registrarse .s_usuario').not('.ready').not('#fCodigo').removeClass('ready').parents('.block').find('.response').removeClass('def good').addClass('bad').text(msgs.cDef.bad);
                        }
                    }else {
                        if(B.find('.' +vars.okR + '.s_usuario').size() >= 4 && !comboscategoria) {
                            B.submit();
                        } else {
                            $('#registrarse .s_usuario').not('.ready').not('#fCodigo').removeClass('ready').parents('.block').find('.response').removeClass('def good').addClass('bad').text(msgs.cDef.bad);
                        }
                    }
                }else {
                    if(B.find('.' +vars.okR).size() >= c && !comboscategoria) {
                        B.submit();
                    } else {
                        $('#registrarse .required').not('.ready').removeClass('ready').parents('.block').find('.response').removeClass('def good').addClass('bad').text(msgs.cDef.bad);
                    }
                }
            });
        },
        MultiSelectValidator : function(s) {
            $(s).bind("change", function(){
                var actual = $(this);
                $.each($(s), function(index, value){
                    var error = false;
                    $.each($(s), function(index2, value2){
                        if ($(value).val()==$(value2).val() &&
                            $(value).attr("id")!=$(value2).attr("id") &&
                            $(value).val()>-1 ) {
                            error = true;
                        }
                    });
                    if (error) {
                        $(value).parent().find(".response").addClass("bad").html("Categorias deben ser diferentes.");
                        $(value).addClass("unready");
                    } else {
                        $(value).parent().find(".response").removeClass("bad").html("");
                        $(value).removeClass("unready");
                    }
                });
            });
        },
        validarSuscriptor: function(b) {
            $(b).bind('click', function(e) {
                e.preventDefault();
                var documento = $('#documento_suscriptor');
                var tdoc = $('#tipo_documento_suscriptor');
                var td = $(tdoc).val().split('#');
                var tipo_documento = td[0];
                var distrito = $('#distrito_entrega');
                var r = distrito.parents('.block').find(vars.rs);
                r.removeClass('good bad def').addClass('loading').text('');
                $.ajax({
                    url: '/registro/verificar-suscriptor/',
                    type: 'GET',
                    data: {
                        documento       : documento.val(),
                        tipo_documento  : tipo_documento,
                        distrito        : distrito.val()
                    },
                    dataType: 'json',
                    success: function(response){
                        if( response.status == true){
                            $('#registrarse .span_suscriptorForm').css("display", "block");
                            $('#registrarse .required').not('.s_usuario').attr('disabled', true);
                            formRegistro.fSetDisableNotRequired(true);
                            $('span.response').removeClass('good def bad loading').text('');
                            r.addClass('good').removeClass('bad def').text(response.message);
                            $('#fNames').val(response.suscriptor.nombres);
                            $('#fSurnameP').val(response.suscriptor.apellido_paterno);
                            $('#fSurnameM').val(response.suscriptor.apellido_materno);
                            $('#fSelDoc').val(response.suscriptor.tipo_documento);
                            $('#fEmail').val(response.suscriptor.email_contacto);
                            $('#fNDoc').val(response.suscriptor.numero_documento);
                            $('#sexoMF-' + response.suscriptor.sexo).attr('checked', true);
                            $('#fTlfFC').val(response.suscriptor.telefono);
                            $('#es_suscriptor').val(1);
                            if(response.suscriptor.email_contacto != '' && response.suscriptor.email_contacto != null)
                                $('#fEmail').trigger('blur', null);
                            
                            documento.addClass('ready');
                            var fn;
                            if (response.suscriptor.fecha_nacimiento != null)
                                fn = response.suscriptor.fecha_nacimiento.split('-')
                            
                            if(!(response.suscriptor.fecha_nacimiento == null || fn[0] == '0000')) {
                                $('#dayjFunctions').val(parseInt(fn[2], 10));
                                $('#monthjFunctions').val(parseInt(fn[1], 10));
                                $('#yearjFunctions').val(fn[0]);
                            }
                        }else{
                            //                            $('#es_suscriptor').val(0);
                            formRegistro.triggerReset();
                            r.removeClass('good def').addClass('bad').text(response.message);
                        }
                    }
                });
                return false;
            });
        },
        reset: function(e) {
            $(e).bind('change', function() {
                if($('#es_suscriptor').val() == 1) {
                    formRegistro.triggerReset();
                }
            })
        }, 
        triggerReset: function(){
            $('#registrarse .span_suscriptorForm').css("display","none");
            //var r = $('#es_suscriptor').parents('.block').find(vars.rs);
            $('#fNames').val('');
            $('#fSurnameP').val('');
            $('#fSurnameM').val('');
            $('#sexoMF-M').attr('checked', true);
            $('#fTlfFC').val('');
            $('#fEmail').val('');
            $('#es_suscriptor').val(0);
            $('#dayjFunctions').val(0);
            $('#monthjFunctions').val(0);
            $('#yearjFunctions').val(0);
            $('#fSelDoc').val('DNI#8');
            $('#fNDoc').val('');
            $('#registrarse .required').attr('disabled', false);
            $('#documento_suscriptor').removeClass('.ready');
            $('#fEmail').removeClass('.ready');
            $('#fEmail').removeClass('.bad');
            formRegistro.fSetDisableNotRequired(false);
            $('span.response').removeClass('good def bad loading').text('');
        }
    };
    
    formRegistro.fMail('#fEmail', msgs.cEmail.good, msgs.cEmail.bad, msgs.cEmail.def);
    formRegistro.fPass('#fClave',6,'#fRClave');
    formRegistro.fRePass('#fRClave','#fClave',6);
    formRegistro.fInput('#fNames', msgs.cName.good, msgs.cName.bad, msgs.cName.def);
    formRegistro.fInput('#fSurnameP', msgs.cApell.good, msgs.cApell.bad, msgs.cApell.def);
    formRegistro.fInput('#fSurnameM', msgs.cApell.good, msgs.cApell.bad, msgs.cApell.def);
    formRegistro.fIDate('#fBirthDate', msgs.cBirth.good, msgs.cBirth.bad, msgs.cBirth.def);
    formRegistro.fOnlyNumTlf('#fTlfFC');
    formRegistro.minLenght('#fTlfFC',6);
    //formRegistro.fOnlyNumDoc('#fNDoc');
    formRegistro.fDni('#fNDoc','#fSelDoc' , msgs.cDocNum.good, msgs.cDocNum.bad, msgs.cDocNum.def);
    formRegistro.fOldPass('#fACtns');
    formRegistro.fSubmit('#btnSuscribe','#registrarse',8,'#fEmail','#fClave','#fRClave','#fNames','#fSurname','#fBirthDate','#efSex','#fTlfFC','#fPais','#fDepart','#fNDoc','#fDistri');
    formRegistro.maxLenghtN('select.maxLenghtN');
    formRegistro.jFunctions();	
    formRegistro.deleteMesLoad();
    formRegistro.MultiSelectValidator(".categoriax");
    
    //formRegistro.fOnlyNumDoc('#documento_suscriptor');
    //formRegistro.maxLenghtN('select#tipo_documento_suscriptor');
    formRegistro.validarSuscriptor('#searchRCS');
    formRegistro.reset('#distrito_entrega');
    formRegistro.reset('#tipo_documento_suscriptor');
    formRegistro.reset('#documento_suscriptor');

});