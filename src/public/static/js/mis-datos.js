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
            mailValid : 'Email ya registrado.'
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
        cTlfNum : {
            good : '!OK¡',
            bad : 'Incorrecto',
            def : 'Ingrese Número Fijo ó Celular'
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
        fMail : function(a,good,bad,def) {

            $(a).bind('blur', function() {
                var t = $(this),
                r =  t.parents('.block').find(vars.rs),
                ep = /^(([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+)?$/g;
                if( ep.test(t.val()) || t.val() == '' ) {
                    if(!t.val() == '')
                        r.removeClass('bad').addClass('good').text(good);
                    t.addClass(vars.okR);
                } else {					
                    r.removeClass('good').addClass('bad').text(bad);
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
            var td = $('#fSelDoc').val().split('#');
            var $ndoc = t, tdoc = td[0];
            r.text('');
            $ndoc.addClass('loadingNumDoc');
            $.ajax({
                url: '/registro/validar-dni/',
                type: 'post',
                data: {
                    ndoc: $ndoc.val(),
                    tdoc: tdoc
                },
                dataType: 'json',
                success: function(response){
                    
                    if( response.status == 'registrado' && ddoc == $ndoc.val() ){
                        $ndoc.addClass('ready').removeClass('loadingNumDoc');
                        r.removeClass('bad def').addClass('good').text(msgs.cDocNum.good);
                        t.addClass(vars.okR);
                    }else{
                        if (ddoc != $ndoc.val() && response.status == 'registrado'){
                            $ndoc.removeClass('ready').removeClass('loadingNumDoc');
                            r.removeClass('good def').addClass('bad').text(msgs.cDocNum.docNumValid);
                            t.removeClass(vars.okR);
                        }else {
                            $ndoc.addClass('ready').removeClass('loadingNumDoc');
                            r.removeClass('bad def').addClass('good').text(msgs.cDocNum.good);
                            t.addClass(vars.okR);
                        }
                    }
                },
                error : function(response){
                    $ndoc.removeClass('ready').removeClass('loadingNumDoc');
                    r.removeClass('good def').addClass('bad').text(msgs.cDocNum.docNumValid);
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
        },    
        fSubmit : function(a,b,c,f1,f2,f3,f4,f5,f6,f7,f8,f9,f10,f11,f12) {
            var A=$(a), B=$(b), c = 7, d = 3;
            F1 = $(f1), F2 = $(f2), F3 = $(f3), F4 = $(f4),
            F5 = $(f5), F6 = $(f6), F7 = $(f7), F8 = $(f8),
            F9 = $(f9), F10 = $(f10),
            F11 = $(f11), F12 = $(f12);
            A.bind('click', function(e) {
                e.preventDefault();
                
                if(!($(this).hasClass('inactive'))){
                    if(B.find('.' +vars.okR).size() >= c) {
                        B.submit();
                    } else {
                        $('#mis-datos .required').not('.ready').removeClass('ready').parents('.block').find('.response').removeClass('def good').addClass('bad').text(msgs.cDef.bad);
                    }
                }
                
            });   
        }
    };
    
    var jstrap = new jStrap();
    jstrap.onlyLetters('#fNames');
    jstrap.removeSpacesUnnecessary('#fNames');
    jstrap.onlyLetters('#fSurnameP');
    jstrap.removeSpacesUnnecessary('#fSurnameP');
    jstrap.onlyLetters('#fSurnameM');
    jstrap.removeSpacesUnnecessary('#fSurnameM');
    
    formRegistro.fMail('#fEmailContacto', msgs.cEmail.good, msgs.cEmail.bad, msgs.cEmail.def);
    formRegistro.fInput('#fNames', msgs.cName.good, msgs.cName.bad, msgs.cName.def);
    formRegistro.fInput('#fSurnameP', msgs.cApell.good, msgs.cApell.bad, msgs.cApell.def);
    formRegistro.fInput('#fSurnameM', msgs.cApell.good, msgs.cApell.bad, msgs.cApell.def);
    formRegistro.fIDate('#fBirthDate', msgs.cBirth.good, msgs.cBirth.bad, msgs.cBirth.def);
    formRegistro.fOnlyNumTlf('#fTlfFC');
    formRegistro.fOnlyNumDoc('#fNDoc');
    formRegistro.fDni('#fNDoc', msgs.cDocNum.good, msgs.cDocNum.bad, msgs.cDocNum.def);
    formRegistro.fInput('#fTlfFC',msgs.cTlfNum.good, msgs.cTlfNum.bad, msgs.cTlfNum.def);
    formRegistro.fSubmit('#saveBt','#mis-datos',6,'#fEmail','#fNames','#fSurname','#fBirthDate','#fTlfFC','#fNDoc');
    formRegistro.maxLenghtN('select.maxLenghtN');
    formRegistro.jFunctions();	
    formRegistro.deleteMesLoad();
});