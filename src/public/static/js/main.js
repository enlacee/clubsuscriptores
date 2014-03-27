/*
JStrap  :: Funciones de arranque
*/
var jStrap = function(){
    //Mail
    this.isMail = function(sVal){
        var ep = /^(([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+)?$/g;
        return ( ep.test(sVal) && (sVal != '') );
    };
    //Vacio
    this.isEmpty = function(sVal){
        var ep = /\s+$/;
        return ( (sVal.length != 0) || ep.test(sVal) );
    };
    //letras - Ander
    this.onlyLetters = function(oField){
        return $(oField).each( function(){
            var t = $(this);
            t.keypress( function(e){
                var key = e.keyCode || e.charCode || e.which || window.e ;
                if (key==0 || key==8|| key==32  || key==241 ||key==209) return true;
                var patron =/[A-Za-z\s]/;
                var te = String.fromCharCode(key);
                return patron.test(te);
//                if (tecla==0 || tecla==8 DELL|| tecla==32 SPACE || tecla==46 PUNTO Y SUPR || tecla==241 ||tecla==209) return true;
            });
            t.bind('paste', function(){
                setTimeout(function(){
                    var value = t.val(), newValue;
                    newValue = value.replace(/[^a-zA-Z]/g,'');
                    t.val(newValue);
                }, 0);
            });				
        });       
    };
    //eliminar todo espacio - Ander
    this.removeAllSpaces = function(oField){
        return $(oField).each( function(){
            var t = $(this);
            t.bind('blur', function(){
                setTimeout(function(){
                    var cadena=t.val();
                    //var textosalida = cadena.replace(/(á|é|í|ó|ú|ñ|ä|ë|ï|ö|ü)/gi,'');
                    var textosalida = cadena.replace(/( )/gi,'');
                    t.val(textosalida);
                }, 0);
            });				
        });       
    };
    //mas de dos espacios - Ander
    this.removeSpacesUnnecessary = function(oField){
        return $(oField).each( function(){
            var t = $(this);
            t.bind('blur', function(){
                setTimeout(function(){
                    var JStemp = t.val(),
                        JSobj = /^(\s*)([\W\w]*)(\b\s*$)/;
                    //Elimina los espacios de delante y detrás
                    if (JSobj.test(JStemp)) {JStemp = JStemp.replace(JSobj, '$2');}
                    //Elimina los espacios duplicados
                    var JSobj = / +/g;
                    JStemp = JStemp.replace(JSobj, " ");
                    if (JStemp == " ") {JStemp = "";}
                    t.val(JStemp);
                }, 0);
            });				
        });       
    };
    //Num Tlf
    this.onlyNumTlf = function(oField){
        return $(oField).each( function(){
            var t = $(this),
            isShift = false;
            t.keypress( function(e){
                var key = e.keyCode || e.charCode || e.which || window.e ;						
                if(key == 16) isShift = true;							
                return ( key == 8 || key == 9 || key == 32 ||
                    key == 37 || key == 39 ||
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
                setTimeout(function(){
                    var value = t.val();
                    var newValue = value.replace(/[^0-9-#-*-(-)--]/g,'');
                    t.val(newValue);
                }, 0);
            });				
        });       
    };
    //Num Tipo de documento
    this.onlyNumDoc = function(a){
        return $(a).each( function(){
            var t = $(this),
            isShift = false,
            reg1Letter = /\D{2}/;

            t.keypress( function(e){				
                var key = e.keyCode || e.charCode || e.which || window.e ;					
                if(key == 16) isShift = true;
                if(t.hasClass('PAS')){
                	//1 Letra minimo en PAS       	
//                	reg1Letter = /\D{2}/;
//                    if(t.val().indexOf("-") == -1 && key == 45){ // signo -
////                        console.log(t.val().indexOf("-"));  
//                    } else if(reg1Letter.test($.trim(t.val())) == false){
//                        return ( key == 8 || key == 9 || key == 32 ||
//	                        ( 65 <= key && key <=90 || 
//	                            97 <= key && key <=122 || 
//	                            48<= key && key <=57 ));
//                	}else{
//                        return ( key == 8 || key == 9 || key == 32 ||
//                                 key == 37 || key == 39 ||
//                                ( key == 48 && isShift == false ) ||
//                                ( key == 49 && isShift == false ) ||
//                                ( key == 50 && isShift == false ) ||
//                                ( key == 51 && isShift == false ) ||
//                                ( key == 52 && isShift == false ) ||
//                                ( key == 53 && isShift == false ) ||
//                                ( key == 54 && isShift == false ) ||
//                                ( key == 55 && isShift == false ) ||
//                                ( key == 56 && isShift == false ) ||
//                                ( key == 57 && isShift == false ) );               		
//                	}                    
                }else if(t.hasClass('CEX')){
//                    return ( key == 8 || key == 9 || key == 32 ||
//	                        ( 65 <= key && key <=90 || 
//	                            97 <= key && key <=122 || 
//	                            48<= key && key <=57 ));                	
                }else{                	
                    return ( key == 8 || key == 9 || key == 32 ||
                        key == 37 || key == 39 ||
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
                }                     
            });
            t.bind('paste', function(){
                setTimeout(function() {
                    var value = t.val(),
                    newValue;
//                    if(t.hasClass('CEX') || t.hasClass('PAS')){
                    if(t.hasClass('PAS')){
                        newValue = value.replace(/[^a-zA-Z0-9]/g,'');            
                    }else{
                        newValue = value.replace(/[^0-9]/g,'');                        
                    }            
                    t.val(newValue);
                }, 0);
            });
            
        });
    };
    //Num Tipo de documento
    this.onlyNum = function(a){
        return $(a).each( function(){
            var t = $(this),
            isShift = false;
            t.keypress( function(e){				
                var key = e.keyCode || e.charCode || e.which || window.e ;
                if(key == 16) isShift = true;						
                return ( 
                    ( key == 8 ) || ( key == 9 ) || ( key == 13 ) ||    
                    ( key == 37 ) || ( key == 39 ) ||    
                    ( key == 46 && isShift == false ) ||
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
    };
    //maxlenght Doc, Carnet Extranjeria
    this.maxLenghtDoc = function(selectN, input){
        var oSelect = $(selectN), oInput;
        if(input == undefined) {
            oInput = oSelect.next();
        } else {
            oInput = $(input);
        }
        //init DOM
        var initClass = ((oSelect.val()).split('#'))[0];
        //Carnet Extranjería

        switch(initClass){
            case 'CEX':
                oInput.addClass('CEX').removeClass('DNI PAS RUC');
                break;
            case 'PAS':
                oInput.addClass('PAS').removeClass('DNI CEX RUC');
                break;
            case 'RUC':
                oInput.addClass('RUC').removeClass('DNI CEX PAS');
                break;  
            case 'DNI':
                oInput.addClass('DNI').removeClass('RUC CEX PAS');
                break;            
            default:
                oInput.removeClass('RUC CEX PAS DNI').addClass('DNI');
        }        
        
        oSelect.bind('change', function(){
            var t = $(this),
            string = (t.val()).split('#'),
            numMax = string[1],
            inputVal = oInput.val();
            oInput.removeAttr('maxlength').attr('maxlength', numMax);
            oInput.val(inputVal);
            oInput.focus();
  
            switch(string[0]){
                case 'CEX':
                    oInput.addClass('CEX').removeClass('DNI PAS RUC');
                    break;
                case 'PAS':
                    oInput.addClass('PAS').removeClass('DNI CEX RUC');                  
                    break;
                case 'RUC':
                    oInput.addClass('RUC').removeClass('DNI CEX PAS');
                    break;  
                case 'DNI':
                    oInput.addClass('DNI').removeClass('RUC CEX PAS');
                    break;            
                default:
                    oInput.removeClass('RUC CEX PAS DNI').addClass('DNI');
            } 

            var value = oInput.val(),
            newValue;
//            if(oInput.hasClass('CEX') ){
//                newValue = value.replace(/[^a-zA-Z0-9]/g,'');            
//            }else 
            if( oInput.hasClass('PAS') ){
            	newValue = value.replace(/[a-zA-Z]{2}/g,''); 
            }else{
                newValue = value.replace(/[^0-9]/g,'');                        
            }

            oInput.val(newValue);

        });				
        oInput.bind('keyup click blur focus change paste', function(e){
            var t = $(this),
            string;
            if(input == undefined) {
                string = (t.siblings('select').val()).split('#');
            } else {
                string = (oSelect.val()).split('#');
            }            
            var numMax = parseInt(string[1]),
            valueArea;
            var key = e.keyCode || e.charCode || e.which || window.e ;
            var length = t.val().length;
            if( length > numMax ) {
                valueArea = t.val().substring(numMax, '') ;
                oInput.val(valueArea);
            }
        });						
    };
    this.validateRuc = function(selectN){
        var t = selectN;
        var r = t.next();
        var valor = false;
        var valueRuc = $.trim(t.val());
        var val2Dig = parseInt(valueRuc.substr(0,2)),
        ultimoDig = parseInt(valueRuc.substr(10,1)),
        factor = '5432765432',
        diviRuc = 0,
        restoFinal = 0;
        //SE CAMBIO DE PRUEBA datamaxlength X maxlength
        if(valueRuc != '' &&
            valueRuc.length == $.trim(t.attr('maxlength')) &&
            (val2Dig == 10 ||
                val2Dig == 20 ||
                val2Dig == 17 ||
                val2Dig == 15)
            ) {
				
            var arrValueRuc = valueRuc.split('',10),
            arrFactor = factor.split('',10),
            sumaTotalRuc = 0;
				
            for(var i = 0; i < 10; i++ ){
                sumaTotalRuc += parseInt(arrValueRuc[i]) * parseInt(arrFactor[i]);
            };

            diviRuc = (sumaTotalRuc%11);
				
            restoFinal = 11 - diviRuc;
            if(restoFinal == 10){
                restoFinal = 0;
            }else if(restoFinal == 11){
                restoFinal = 1;
            }
            if(restoFinal <= 10 && restoFinal == ultimoDig ){
                valor = true;
            //registroEmp._fRucValid(a, t, r);
            }else{
                valor = false;
            }
        } 
        return valor;
    };
    //select value requirdo
    this.selectReq = function(sVal){    
        return ( sVal != '' );
    };
    //clear form    
    this.clearForm = function(obj){
        var defaults = {
            form : 'form',
            clases : 'ready good bad',
            classResponse : 'response'
        };
        var objNew = $.extend({}, defaults, obj ); 
        $(objNew.form).get(0).reset();
        $('input, select, textarea', objNew.form).removeClass(objNew.clases);
        $('input.placeH', objNew.form).addClass('cGray');
        $(objNew.form + ' .' + objNew.classResponse).removeClass(objNew.clases).text('');
    }
    //textarea Maxlenght
    this.pasteMaxlength = function(sFiled){   
        $(sFiled).bind('keyup click blur focus change paste', function(e){
            var t = $(this);
            setTimeout(function(){                
                var chars = t.val(), 
                charsSize = t.val().length,
                size = parseInt(t.attr('maxlength'));
                if( charsSize > size ){
                    valField = chars.substring(size, 0);
                    t.val(valField);
                }
            },0);
        });
    }      
//end
};
/* 
init 
*/
$(function() {
    var msgs = {
        cDef : {
            good :'¡Genial!',
            bad : 'Campo Requerido',
            def :'Opcional'
        },
        cPass : {
            good : '¡Genial!',
            bad : 'No parece ser una contraseña válido.',
            def : 'Ingresa tu contraseña'
        },
        cEmail : {
            good : '¡OK!',
            bad : 'No parece ser un mail válido.',
            def : 'Ingrese un email correcto'
        },
        passForgot : {
            good : 'Su nueva contraseña fue enviada.',
            mailInvalid : 'El e-mail ingresado no existe.',
            bad : 'Error al enviar su nueva contraseña.'
        }
    };
    //class 
    var CS = function(opts){  
        //window modal and alert
        this.winModal = function(){
            var a = $('.winModal'),
            m = $('#mask'),
            w = $('.window'),
            c = $('.closeWM'),
            s = 'fast',
            o = 0.50; 
            var jash = $.trim(window.location.hash).split("-");
            var url = location.href.substring(7,location.href.length).split("/");
            if(jash.length > 0 && jash[0]!="" && url[1].substring(0,url[1].length-1)!="suscripciones") {
                if($('body').find('"' + jash[0] + '"').size() > 0){
                    var mH = $(document).height(),
                    mW = $(window).width();
                    $('html, body').animate({
                        scrollTop:0
                    }, s);
                    m.css({
                        'height':mH
                    });		
                    m.fadeTo(s,o);				
                    $(jash[0]).fadeIn(s);
                    $(document).keyup(function(e){
                        if(e.keyCode === 27) {
                            m.hide();
                            w.hide();
                        }
                    });						
                }
                if(jash.length==2) {
                    $(jash[0]+" input[name=return]").val(Base64.decode(jash[1]));
                }
            }
            a.live('click',function(e){
                e.preventDefault();
                var t = $(this);
                
                if(!(t.hasClass('disabledModal'))){
                    
                    var i = t.attr('href');                  
                    if( $.browser.msie && $.browser.version.substr(0,8) < 8 ){
                        //IE7, IE6 url en href
                        i = '#' + i.split('#')[1];
                    }
                    var mH = $(document).height(),
                    mW = $(window).width();					
                    if(!(t.hasClass('noScrollTop')) ){
                        {
                            $('html, body').animate({
                                scrollTop:0
                            }, s);
                        }
                    }
                    if( $.browser.msie && $.browser.version.substr(0,8) < 7 ){
                        $('html, body').animate({
                            scrollTop:0
                        }, s);                    
                    }							
                    m.css({
                        'height':mH
                    });			
                    m.fadeTo(s,o);	
                    $(i).fadeIn(s);
                    //Modal dialogo
                    if(t.hasClass('modalConfirmacion') || t.hasClass('modalDialogo')){
                        var msjDialog = t.next().html();
                        $(i).find('.infoDialog').html(msjDialog);
                    }
                    if(t.hasClass('modalConfirmacion')){
                        $(i).find('.btnMsjModal').removeClass('hide');    
                    }
                    
                    $(document).keyup(function(e){
                        if(e.keyCode === 27) {
                            m.hide();
                            w.hide();
                        }
                    });	
                    /* url aplicada */				
                    var oRedirect = t.attr('return');
                    if(oRedirect){
                        var receptorUrl = $('#return');
                        receptorUrl.val(oRedirect);
                    }

                }
                
														 
            });
            
            c.click(function(e){
                e.preventDefault();
                var linkCloseX = $(this),
                content = linkCloseX.parent();
                m.hide();
                w.hide();		
                if(linkCloseX.hasClass('closeRegiFast')){
                    var inputsNRP = content.find('input.inputRpm');
                    //reset
                    $.each(inputsNRP, function(i, val){
                        var inptRPEA = inputsNRP.eq(i);
                        if($.trim(inptRPEA.val()) != ''){
                            inptRPEA.val('').removeClass('ready bienRegFast malRegFast').parents('.placeHRel').find('.txtPlaceHR').removeClass('hide');
                        }			
                    });
                    var inputsNSM = $('input#wmPMail'),
                    altIpt = inputsNSM.attr('alt');
                    inputsNSM.removeClass('readyLogin').addClass('cGray').val(altIpt);
                    $('#wmPPass').removeClass('readyLogin').val('');
                    content.find('.respW').removeClass('bad good').text('');	
                    $('#textForgotPReg').val('');	

                    $('#cntRegisterWM').css('display','block');
                    $('#cntForgotPReg').css('display','none');	
                }else if(linkCloseX.hasClass('closeResLogin')){
                    //reset Login
                    var mailRT = $('#wmMail'),
                    passRT = $('#wmPass'),
                    reMail = $('#textForgotP');
                    mailRT.val(mailRT.attr('alt')).removeClass('readyLogin').addClass('cGray').next().removeClass('bad good').text('');				
                    passRT.val('').removeClass('readyLogin').next().removeClass('bad good').text('');
                    reMail.val('');
                }			
            });		
            m.click(function(e){
                $(this).hide();
                w.hide();
            });			
        };

        this.placeholder = function(){
            var tr = $('input.placeH, textarea.placeH');
            tr.focus(function(){
                var t = $(this);
                if(t.val() == t.attr('alt')){
                    t.val('').removeClass('cGray');
                }
            });		 
            tr.blur(function(){
                var t = $(this);			
                if(t.val() == ''){
                    t.val(t.attr('alt')).addClass('cGray');
                }
            });
        };
        
        this.forgotPass = function(){	
            var A = $('#forgotPass'),
            B = $('#cntLoginWM'),
            C = $('#mainContentrecuperar'),
            D = $('#backLogWM'),
            SF = $('#cambiarBtn'),
            form = $('#fForgotPass'),
            email = $('#textForgotP'),
            resp = $('#responseFP'),
            loading = $('#loadingCFP'),
            errorCmp = $('#errorCmp'),
            so = 'fast';						
            A.click(function(e){		
                e.preventDefault();			
                B.slideUp(so, function(){
                    C.slideDown();
                    var jstrap = new jStrap(); 
                    //clear form login
                    jstrap.clearForm({
                        form : '#loguearme',
                        classResponse : 'respW'                  
                    });                        
                });					
            });		
            D.click(function(e){
                e.preventDefault();
                C.slideUp(so, function(){
                    B.slideDown();
                    form.removeClass('hide');
                    errorCmp.text('');
                    resp.text('');
                    email.val('');				
                });
            });
		
            fMail('#cambiarBtn' , msgs.cEmail.good , msgs.cEmail.bad , msgs.cEmail.def );
				
            function fMail(a,good,bad,def){
                $(a).bind('click', function(e){
                    e.preventDefault();			
                    loading.removeClass('hide').addClass('loading');
                    form.addClass('hide');
                    var t = $(this),
                    trigger = $('#textForgotP');
                    r = $('#responseFP'),
                    okL = 'okGo',
                    ep = /^(([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+)?$/g;
                    if(ep.test(trigger.val())&& trigger.val()!=''){
                        //form.submit();
                        var emailFP = $.trim(email.val());
                        var tipo = $('#tipo').val(); 
                        var idEmail = emailFP,
                        authToken = $.trim($('#recuperar_token').val()) ;
                        $.ajax({
                            'url' : '/auth/recuperar-clave/',
                            'type' : 'POST',
                            'dataType' : 'JSON',
                            'data' : {
                                'email' : idEmail,
                                'recuperar_token' : authToken,
                                'rol' : tipo
                            },
                            'success' : function(res){								
                                if(res.status == 'error'){
                                    loading.addClass('hide');	
                                    errorCmp.removeClass('hide').addClass('bad').text(msgs.passForgot.bad);
                                    email.val('');									
                                }else if(res.status == 'mailinvalid'){
                                    loading.addClass('hide');	
                                    errorCmp.removeClass('hide').addClass('bad').text(msgs.passForgot.mailInvalid);
                                    email.val('');									
                                }else{
                                    loading.addClass('hide');																
                                    errorCmp.removeClass('hide').addClass('good').text(msgs.passForgot.good);
                                    email.val('');																								
                                }
                            },
                            'error' : function(res){
                                loading.addClass('hide');	
                                errorCmp.removeClass('hide').addClass('bad').text(msgs.passForgot.bad);
                                email.val('');							
                            }
                        });				
                    }else{		
                        loading.addClass('hide');		
                        form.removeClass('hide');				
                        r.removeClass('good hide').addClass('bad').text(bad);
                        t.removeClass(okL);
                    }
                });
            }
			
        };
        this.flashMsg = function(){
            var mensajes = $('.flash-message'),
            h = 0,
            s = 'middle',
            interval = '3000';
            $.each(mensajes, function(k, v){
                h = 1000 * (k);
                setTimeout(function(){
                    $(v).fadeIn(s, h, function(){
                        setTimeout(function(){
                            $(v).fadeOut(s);
                        }, h + interval);
                    });
                },h);
            });		
        };	
        this.placeholderRel = function(){
            var tr = $('input.placeHRel, textarea.placeHRel'),
            trText = $('.txtPlaceHR');
            tr.bind('focus', function(){
                var t = $(this),
                txtPlaceH = t.parents('.placeHRel').find('.txtPlaceHR'),
                textP = txtPlaceH.text();
                if($.trim(t.val()) == ''){ 
                    txtPlaceH.addClass('hide'); 
                }
            });		 
            tr.bind('blur', function(){
                var t = $(this),
                txtPlaceH = t.parents('.placeHRel').find('.txtPlaceHR'),
                textP = txtPlaceH.text();						
                if($.trim(t.val()) == ''){ 
                    txtPlaceH.removeClass('hide').text(textP); 
                }
            });
            trText.bind('click', function(){
                var t = $(this),
                inputPlaceH = t.parents('.placeHRel').find('.placeHRel'),
                textP = t.text();						
                if($.trim(inputPlaceH.val()) == ''){ 
                    t.addClass('hide');
                    inputPlaceH.focus(); 
                }
            });		
        };			
        this.iTooltip = function(classTooltip){				
            var tip;
            $(classTooltip).hover(function(){
                $('body').append('<div id="iTooltip"></div');	
                tip = $(this).find('.tip');
                $('#iTooltip').html(tip.html()).show();
            }, function() {
                $("#iTooltip").remove();			 
            }).mousemove(function(e) {
                var mousex = e.pageX + 15,
                mousey = e.pageY + 15,
                tip = $(this).find('.tip');
                tipWidth = tip.width(),
                tipHeight = tip.height(); 

                var tipVisX = $(window).width() - (mousex + tipWidth),
                tipVisY = $(window).height() - (mousey + tipHeight);						  

                if( tipVisX < 15 ){
                    mousex = e.pageX - tipWidth - 15;
                }
                if( tipVisY < 15 ){
                    mousey = e.pageY - tipHeight - 15;
                }
                $('#iTooltip').css({
                    top: mousey, 
                    left: mousex
                });
            });
        };		
        this.login = function(){
            var A = $('.btnLoginEPA'),
            okL = 'readyLogin',
            resp = '.respW',
            cntMsjA = $('.msgLRAll');

            function inputReq(a,good,bad,def){
                var A = $(a);
                A.blur(function(e){
                    cntMsjA.removeClass('bad good').text('');
                    var t=$(this),
                    r=t.next(resp);
                    if($.trim(t.val())!=='' && t.val() !== t.attr('alt') ){
                        t.addClass(okL);
                        r.addClass('good').removeClass('bad hide def').text(good);
                    }else{
                        t.removeClass(okL).next(resp).addClass('bad').removeClass('good hide def').text(bad);
                    }		
                }).keypress(function(){
                    var t = $(this),
                    r=t.next(resp);
                    if(t.val().length===0 && t.val() !== t.attr('alt') ){
                        t.removeClass(okL);
                    //r.removeClass('good hide def').addClass('bad').text(bad);
                    }else{
                        t.addClass(okL);
                        r.removeClass('bad good hide').addClass('def').text(def);
                    }					
                });				
            }
            inputReq('#wmPass' , msgs.cPass.good , msgs.cPass.bad , msgs.cPass.def );
            inputReq('#wmPPass' , msgs.cPass.good , msgs.cPass.bad , msgs.cPass.def );
            function fMail(a,good,bad,def){
                $(a).bind('blur', function(){
                    cntMsjA.removeClass('bad good').text('');
                    var t = $(this),vValor=t.val(),
                    r = t.next(resp),
                    ep = /^(([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+)?$/g;
                    //limpiar todo espacio Ander
                        var cadena=t.val();
                        var textosalida = cadena.replace(/( )/gi,'');
                        t.val(textosalida);
                    if(ep.test(t.val())&& t.val()!=''){
                        r.removeClass('bad hide').addClass('good').text(good);
                        t.addClass(okL);
                    }else{
                        if(vValor==t.attr("alt"))t.val(t.attr("alt"));
                        r.removeClass('good hide').addClass('bad').text(bad);
                        t.removeClass(okL);
                    }
                });
            }		
            fMail('#wmMail' , msgs.cEmail.good , msgs.cEmail.bad , msgs.cEmail.def );	
            fMail('#wmPMail' , msgs.cEmail.good , msgs.cEmail.bad , msgs.cEmail.def );					
            A.bind('click', function(e){
                e.preventDefault();			
                var t = $(this);

                // Login Normal
                var	M = $('#wmMail'),
                MValue = M.val(),
                P = $('#wmPass'),
                PValue = P.val(),
                rM = M.next(resp),
                rP = P.next(resp),
                _form = '#loguearme',
                formFlag = $(_form),
                tipoAB = 'input#tipo',
                iptToken = 'input#auth_token',
                urlReturn = 'input#return',
                cntMsj = $('#loginModalW .msgLRAll'),
                id_tarifa = '';													
                if( M.hasClass(okL) && P.hasClass(okL) ){
                    //postulante
                    formFlag.submit();
                }else{
                    cntMsj.removeClass('bad good').text('');
                    if( M.hasClass(okL) ){
                        rM.removeClass('hide def bad').addClass('good').text(msgs.cEmail.good);
                    }else{
                        rM.removeClass('hide def good').addClass('bad').text(msgs.cEmail.bad);
                    }		
                    if( P.hasClass(okL) ){
                        rP.removeClass('hide def bad').addClass('good').text(msgs.cPass.good);							
                    }else{
                        rP.removeClass('hide def good').addClass('bad').text(msgs.cPass.bad);
                    }												
                }															
            });	
            if( $.browser.msie && $.browser.version.substr(0,1) < 9 ) {		
                $('#loguearme input').keydown(function(e){
                    var keyC = e.keyCode || e.charCode || e.which || window.e ;
                    if (keyC == 13) {
                        $('#loguearme #logueobtn').trigger('click');
                        return false;
                    }
                });		
            }
            //clear Form            
            var jstrap = new jStrap();
            var linkCloseL = $('#loginModalW .closeWM');    
            if( linkCloseL.size() >0 && (modulo_actual == 'suscriptor') ){
                linkCloseL.bind('click', function(){
                    //clear form login
                    jstrap.clearForm({
                        form : '#loguearme',
                        classResponse : 'respW'                  
                    });
                    //clear form receuprar clave
                    jstrap.clearForm({
                        form : '#recuperar',
                        classResponse : 'respRC'
                    });
                    $('#mainContentrecuperar').addClass('hide').removeAttr('style');
                    $('#cntLoginWM').removeAttr('style');
                });
            }
        };
        this.viewencuesta = function(){
            var A = $('.encuestaVer'), 
            id = $('#encuesta_id');
            A.bind('click', function(e){
                e.preventDefault();			
                var cntMsj = $('#content-winVerEncuesta');
                cntMsj.text('')
                .removeClass('hide bad good')
                .css({
                    'height':'400px'
                })
                .addClass('loading')
                .addClass("center");
                $.ajax({
                    async : true,
                    url : '/suscriptor/home/ver-resultado/',
                    type : 'POST',
                    dataType : 'html',
                    contentType: 'application/x-www-form-urlencoded',
                    data : 'id='+id.val(),
                    success : function(res){
                        //cntMsj.removeClass('loading bad').addClass('good').text(res.parametro);
                        //cntMsj.removeClass('loading good').addClass('bad').text(res);
                        cntMsj.removeAttr('style').removeClass('loading good').addClass('good').html(res);
                    },
                    error : function(res){
                        //cntMsj.removeClass('loading good').addClass('bad').text(res.mensaje);
                        cntMsj.removeAttr('style').removeClass('loading good').addClass('bad').html(res);
                    }
                });								
            });		
        };
        this.votaencuesta = function(){
            var btnvota = $('#btnVotar'), 
            frmencuesta = $('#formencuesta'),
            A = $('.encuestaVer');
            btnvota.bind('click',function(e){
                e.preventDefault();
                var radio = $("input[name='rbtnop']");
                var content = $('#idcontent_vota');
                if(radio.is(':checked')){
                    btnvota.attr('disabled',true).css('opacity','0.5');
                    $.ajax({
                        url : '/suscriptor/home/json/case/grabavoto',
                        type : 'POST',
                        dataType : 'JSON',
                        data : frmencuesta.serialize(),
                        success : function(res){
                            if(/grabado/.test(res.mensaje)){
                                //btnvota.fadeOut('slow',function(){content.html('');});
                                btnvota.attr('disabled',false).css('opacity','1');
                                A.click();
                            }
                        },
                        error : function(res){
                        }
                    });
                }
            });
        };
        this.quierolapromo = function(){
            var btnquiero = $('#Qpromobtn'),
            frmquiero = $('#frmquieropromo'),
            awindow = $('#idaconfirmcupo');
                
            btnquiero.bind('click',function(e){    			
                var frmconfir = $('#frmconfirmcupones'),
                hdcantidad = $('#hdcantidad'),
                hdbeneficio_id = $('#hdbeneficio_id'),
                hdslug = $('#hdslug'),
                spnro = $('#sp_cantidad'),
                winlogin = $('#idIngresoPortal'),
                aWinMessage = $('#idmessagecupo'),
                messageView = $('#content-winMessages'),
                titleView = $('#title-winMessages'),
                classbtn = $('#idclassbtnqp'),
                btnqp = $(this);
    			
                //alert(classbtn.val()+' - '+classbtn.attr('alt'));//return;
                btnqp.attr('disabled', true)
                .removeClass(classbtn.val())
                .addClass(classbtn.attr('alt'));
                e.preventDefault();
                $.ajax({
                    url : '/suscriptor/beneficio/json/case/quierolapromo/',
                    type : 'POST',
                    dataType : 'JSON',
                    data : frmquiero.serialize(),
                    success : function(res){
                        if(res.login){
                            if(res.popup){
                                if(res.mensaje){
                                    messageView.html(res.message);
                                    titleView.html(res.title);
                                    aWinMessage.click();
                                }else{
                                    frmconfir.attr('action',res.url);
                                    hdcantidad.val(res.nropedido_enviado);
                                    hdbeneficio_id.val(res.id);
                                    hdslug.val(res.slug);
                                    spnro.html(res.nropedido_enviado);
                                    awindow.click();
                                }
                                btnqp.attr('disabled', false)
                                .removeClass(classbtn.attr('alt'))
                                .addClass(classbtn.val());
                            }else{
                                frmquiero.attr('action',res.url);
                                frmquiero.submit();
                            }
                        }else{
                            winlogin.click();
                            btnqp.attr('disabled', false)
                            .removeClass(classbtn.attr('alt'))
                            .addClass(classbtn.val());
                        }
                    },
                    error : function(res){
                    }
                });    			
            });
        };
        this.misconsumos = function(){
            //var paginator = $(".consumopaginator"),
            var paginator = $("#btnMisConsumos, .aLinkSusTable"),
            pager = $('.consumopaginator');
            
            paginator.live('click',function(e){
                e.preventDefault();
                consumopaginacion(this);
            });
            pager.live('click',function(){
                var op = $(this).val(),
                all = $("input:checkbox[name='chktipobenef[]'][value='0']");
                switch(op){
                    case '0':
                        $("input:checkbox[name='chktipobenef[]']").attr('checked',all.is(':checked'));
                        return true;
                        break;
                    default:
                        var sizecheck = $(".optionalA").size();
                        all.attr('checked',false);
                        var checks = $(".optionalA:checked").size();
                        if(sizecheck==checks) all.attr('checked',true);                        
                        break;
                }
            });
            
            function consumopaginacion(page){
                var chktipo = $("input:checked[name='chktipobenef[]']"),
                types = '',
                pagina = $(page).attr('rel'),
                cntMsj = $('#mictaConsTable'),
                cboMes = $('#cboMes').val(),
                cboAnio = $('#cboAnio').val(),
                msj = $('#responseMictaFiltroDate'),
                ord = '',
                col = ($(page).attr('col')!=undefined)?$(page).attr('col'):'',
                formsFiltro=$('#formFiltroDate'),
                checks = $("input:checkbox[name='chktipobenef[]']:checked").size()
                ;
                if (cboMes==0) {
                    cntMsj.addClass('bad').html("Seleccione el Mes.");
                    //cntMsj.html('');
                } else if (cboAnio==0) {
                    cntMsj.addClass('bad').html("Seleccione el Año.");
                    //cntMsj.html('');
                } else if (checks<1) {
                    cntMsj.addClass('bad').html("Debe seleccionar al menos una Tipo de beneficio.");
                    //cntMsj.html('');
                }else {
                    cntMsj.removeClass('bad').html('');
                    
                    if ($(page).hasClass('flechasOrdenASC')){
                        ord='DESC';
                    } else if ($(page).hasClass('flechasOrdenDSC')){
                        ord='ASC';
                    }
                    
                    $('#ord').val(ord);
                    $('#col').val(col);
                    
                    
                    chktipo.each(function(){
                        types+=$(this).val()+',';
                    });
                    cntMsj.html('').css({
                        'height':'390px'
                    }).addClass('loading');
                    paginator.attr('disabled',true);
                    
                    formsFiltro.submit();
//                    $.ajax({
//                        async : true,
//                        url : '/suscriptor/mi-cuenta/mis-consumos-tipo/',
//                        type : 'POST',
//                        dataType : 'html',
//                        contentType: 'application/x-www-form-urlencoded',
//                        data : 'tipo='+types+'&idMes='+cboMes+'&anio='+cboAnio+'&ord='+ord+'&col='+col,
//                        success : function(res){
//                            cntMsj.removeAttr('style').removeClass('loading good').addClass('good').html(res);
//                            paginator.attr('disabled',false);
//                        },
//                        error : function(res){
//                        }
//                    });
                }
            }
        };
        this.efectSearch = function(){
            var txtsearch = $('#search'),
            strcompara =  'Busca por descuento, precio, oferta';
            txtsearch.focus(
                function(){
                    if(strcompara == txtsearch.val()){
                        $(this).val('').css({
                            'color':'#000'
                        });
                    }
                });
            txtsearch.blur(
                function(){
                    if(txtsearch.val()==''){
                        $(this).val(strcompara).css({
                            'color':'#DFDFDF'
                        });
                    }
                }
                );
        };
        this.animateField = function(field, nbsp, spped){
            var fieldA = $(field),
            widthF = fieldA.innerWidth();
            fieldA.focus(function(){
                fieldA.animate({
                    'width':widthF + nbsp
                },spped);
            }).blur(function(){
                fieldA.animate({
                    'width':widthF
                },spped);
            });
        };
        this.previewBenef = function() {
            var result = $('#content-previewBenefS'),
            aPreview = $('.aPreviewBS');
            aPreview.live('click',function(){
                var data = 'id=' + $(this).attr('rel');
                result.empty();
                result.addClass('loading');
                $.ajax({
                    url : '/suscriptor/mi-cuenta/vista-previa-beneficio',
                    type : 'POST',
                    dataType : 'html',
                    data : data,
                    success : function(html){
                        result.removeClass("loading");
                        result.html(html);
                    }
                });
            });
        };
    };
    // init
    var cs = new CS();
    cs.winModal();
    cs.placeholder();
    cs.placeholderRel();
    cs.login();
    cs.flashMsg();
    cs.forgotPass();
    cs.viewencuesta();
    cs.votaencuesta();
    cs.quierolapromo();
    cs.misconsumos();
    //cs.efectSearch();
    cs.animateField('#search', 30, 250);
    cs.iTooltip('.iTooltip');
    cs.previewBenef();
});


