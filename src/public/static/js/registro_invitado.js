/*
	 Registro
*/
$( function() {
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
            mailValid : 'Email ya registrado como invitado o suscriptor.'
        },
        cSDoc : {
            good : '!OK¡',
            bad : '',
            def : '¡OK!'
        },
        cBirth : {
            good : '!OK¡',
            bad : '¡Se requiere su fecha de nacimiento!',
            def : 'Ingrese su fecha de nacimiento completa.',
            exed : 'Incorrecto!. La fecha de nacimiento seleccionada es mayor a la fecha actual'
        },
        cDocNum : {
            good : '!OK¡',
            bad : 'Incorrecto',
            def : 'Ingrese número de Documento',
            docNumValid : 'Numero de Doc. ya registrado.'
        }
    }
    var vars = {
        rs : '.response',
        okR :'ready',
        sendFlag : 'sendN',
        loading : '<div class="loading"></div>'
    }
    var formRegistro = {
        winModal : function(){
            $('.window').appendTo('#boxes');
            var a = $('.winModal'),
            m = $('#mask'),
            w = $('.window'),
            c = $('.closeWM'),
            s = 'fast',
            o = 0.50; 					
            a.live('click',function(e){
                e.preventDefault();
                var t = $(this),
                i = t.attr('href'),
                mH = $(document).height(),
                mW = $(window).width();					
                if(!(t.hasClass('noScrollTop'))){
                    $('html, body').animate({
                        scrollTop:0
                    }, s);
                }						
                // cadena solo # 
                if( $.browser.msie && $.browser.version.substr(0,1) < 8 ) {
                    var strI = i.split('#'),
                    strId = strI[1];
                    i = '#' + strId;   
                }							
                m.css({
                    'height':mH
                });			
                m.fadeTo(s,o);	
                $(i).fadeIn(s);			
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
                url: '/mi-cuenta/validar-invitado/',
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
        fDni : function(a, good, bad, def){
            $(a).bind('blur', function() {
                var t = $(this),
                r = t.parents('.block').find(vars.rs);
                if(t.val()!='' && t.val().length==t.attr('maxlength')) {
                    formRegistro._fDniValid(a, t, r);
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
            });
        },
        _fDniValid : function(a, t, r){
            var $ndoc = t;
            r.text('');
            $ndoc.addClass('loadingNumDoc');
            $.ajax({
                url: '/registro/validar-dni/',
                type: 'post',
                data: {
                    ndoc: $ndoc.val()
                },
                dataType: 'json',
                success: function(response){
                    if( response.status == true){
                        $ndoc.addClass('ready').removeClass('loadingNumDoc');
                        r.removeClass('bad def').addClass('good').text(msgs.cDocNum.good);
                    }else{
                        $ndoc.removeClass('ready').removeClass('loadingNumDoc');
                        r.removeClass('good def').addClass('bad').text(msgs.cDocNum.docNumValid);
                        t.removeClass(vars.okR);
                    }
                },
                error : function(response){
                    $ndoc.removeClass('ready').removeClass('loadingNumDoc');
                    r.removeClass('good def').addClass('bad').text(msgs.cDocNum.docNumValid);
                    t.removeClass(vars.okR);
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
	
            if(inputValue.size()>0){
        
                var mesNombres = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                //inicioAnio = 1910,
                inicioAnio = urls.fMinDate,
                //finAnio = 2011,
                finAnio = urls.fYearCurrent,
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
                dia.val(valorDia);
                mes.val(valorMes);
                anio.val(valorAnio);
		
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
                        //inputValue.parents(".block").find(".response").removeClass('good').addClass('bad').text(msgs.cBirth.bad);
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
            }
        },    
        fSubmit : function(a,b,c,f1 ) {
            var A=$(a), B=$(b), c = 1, d = 3;
            F1 = $(f1);
            A.bind('click', function(e) {
                e.preventDefault();
                if(B.find('.' +vars.okR).size() >= c) {
                    B.submit();
                } else {
                    $('#registrarInvitado .required').not('.ready').removeClass('ready').parents('.block').find('.response').removeClass('def good').addClass('bad').text(msgs.cDef.bad);
                }
            });
        }
    };
    
    formRegistro.fMail('#fmail', msgs.cEmail.good, msgs.cEmail.bad, msgs.cEmail.def);
    formRegistro.fIDate('#fBirthDate', msgs.cBirth.good, msgs.cBirth.bad, msgs.cBirth.def);
    formRegistro.fOnlyNumDoc('#fNDoc');
    formRegistro.fSubmit('#btnRegistroInvitado','#registrarInvitado',1,'#fmail');
    formRegistro.maxLenghtN('select.maxLenghtN');
    formRegistro.jFunctions();
    formRegistro.deleteMesLoad();
    formRegistro.winModal();
});