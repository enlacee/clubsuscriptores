/*
Establecimiento 
 */

$(function(){
    // Class
    var jstrap = new jStrap();
    //jstrap.onlyNumDoc('#fNDoc');
    jstrap.maxLenghtDoc('#tipodoc');
    var msjs = {
        validaDni : {
            bad : 'Ingrese el número de Documento',
            good: ''
        },
        validaCupon : {
            bad : 'Ingrese el número de Cupón',
            good: ''
        },
        validaMontoTipo : {
            bad : 'Ingrese el monto de descuento',
            bad2 : 'Seleccione el monto de descuento'
        },
        validaError : {
            error1 : 'No puede Generar Cupón para este suscriptor',
            error2 : 'El suscriptor esta inactivo',
            error3 : 'Stock de Descuentos Agotados',
            success : 'Registro de cupón exitoso'
        }
    };
    //var messageTipB = {};
	
    var redimirBeneficio = {
		   
        validarDNI : function(q) {
            $(q).bind('click', function(e) {
                e.preventDefault();
                var t = $(this);
                var tipo = '', msj = '',value = '',col='',ord='';
                var contenido = $("#adminEstaContentBox");
                contenido.removeClass("hide");
					
                var a = $('#fNDoc');
                var tipodoc = $('#tipodoc').val();
                msj = msjs.validaDni.bad;
                value = a.val();
                tipo = '0';
						
                var error =$("#errorValidarDni");
                var error2 =$("#errorValidarCupon");
                error2.slideUp("fast", function(){
                    error2.addClass("hide");
                });
                if ( $.trim(value) != ''){
                    error.slideUp("fast", function(){
                        error.addClass("hide");
                    });
                    
                    contenido.addClass("loading");
                    contenido.html("");
                    $.ajax({
                        'url' : '/establecimiento/redencion-beneficio/valida-dni-cupon',
                        'type' : 'POST',
                        'dataType' : 'html',
                        'data' : {
                            'tipo' : tipo,
                            'value' : value,
                            'col' : col,
                            'ord' : ord,
                            'tipodoc' : tipodoc
                        },
                        'success' : function(res) {
                            contenido.removeClass("loading");
                            contenido.html(res);
                            $(q).unbind();
                            $('.btn_consumir').unbind();
                            $('a.Btnredimir').unbind();
                            redimirBeneficio.validarDNI(q);
                            redimirBeneficio.detalleRedimirCupon('.btn_consumir');                                                                                    
                        },
                        'error' : function(res) {
                        }
                    });
                } else {
                    error.html(msj);
                    error.slideDown("fast", function(){
                        error.removeClass("hide");
                    });
                }
            });
        },
        validarCupon :function (q) {
            $(q).bind('click', function(e) {
                e.preventDefault();
                var t = $(this);
                var tipo, msj, value, col, ord, a='';
                var contenido = $("#adminEstaContentBox");
                contenido.removeClass("hide");

                a = $('#fNCupon');
                msj = msjs.validaCupon.bad;
                value = a.val();
                tipo = '1';
               
                var error =$("#errorValidarCupon");
                var error2 =$("#errorValidarDni");
                error2.slideUp("fast", function(){
                    error2.addClass("hide");
                });
                if ( $.trim(value) != '') {
                    error.slideUp("fast", function(){
                        error.addClass("hide");
                    });

                    contenido.addClass("loading");
                    contenido.html("");
                    $.ajax({
                        'url' : '/establecimiento/redencion-beneficio/valida-dni-cupon',
                        'type' : 'POST',
                        'dataType' : 'html',
                        'data' : {
                            'tipo'  : tipo,
                            'value' : value,
                            'col'   : col,
                            'ord'   : ord
                        },
                        'success' : function(res) {
                            contenido.removeClass("loading");
                            contenido.html(res);
                            $(q).unbind();
                            $('.btn_consumir').unbind();
                            $('a.Btnredimir').unbind();
                            redimirBeneficio.validarCupon(q);
                            redimirBeneficio.detalleRedimirCupon('.btn_consumir');
                        },
                        'error' : function(res) {
                        }
                    });
               
                } else {
                    error.html(msj);
                    error.slideDown("fast", function(){
                        error.removeClass("hide");
                    });
                }
            });
        },
        detalleRedimirCupon : function(a) {
            $(a).bind("click", function(e) {
                e.preventDefault();
                var a = $(this);
                var idS =a.attr('idS');
                var idB =a.attr('idB');
                var estado =a.attr('est');
                var nroC =a.attr('nroC');
                var tipo = $("#mictBenContent").attr("tipo");
                var contenido = $("#content-winRedimirCupon");
                var csrf = $("#csrf").text();

                contenido.html("");
                contenido.addClass("loading");
					
                $.ajax({
                    type: 'GET',
                    url: "/establecimiento/redencion-beneficio/redimir-cupon/",
                    data: {
                        'idS' : idS,
                        'idB' : idB,
                        'est' : estado,
                        'nroC': nroC,
                        'tipo': tipo,
                        'csrf': csrf
                    },
                    dataType: "html",
                    success: function(msg) {
							
                        contenido.removeClass("loading");
						//contenido.html(msg); //agregado para visualizar los contenidos	
                        if ( $(msg).attr('data-error') == '1' || $(msg).attr('data-error') == '0' ) {
                            contenido.html(msg);
                        }
                        if ($(msg).attr('data-error') == '-1') {
                            contenido.html('<div class="bad">' + $(msg).attr('data') + '</div>');
                            redimirBeneficio.closeWindow();                       
                        }
                        if ($(msg).attr('data-error') == '-2') {
                            contenido.html('<div class="bad">' + msjs.validaError.error1 + '</div>');
                            redimirBeneficio.closeWindow();
                        }
							
                        if ($(msg).attr('data-error') == '-3') {
                            contenido.html('<div class="bad">' + msjs.validaError.error2+ '</div>');
                            redimirBeneficio.closeWindow();
                        }
                        if ($(msg).attr('data-error') == '-4') {
                            contenido.html('<div class="bad">' + msjs.validaError.error3 + '</div>');
                            redimirBeneficio.closeWindow();
                        }
							
                        redimirBeneficio.redimirCupon('.Btnredimir');
                        //Validando campo    
                        jstrap.onlyNum('#fMonto');                                               
                    }
                });
					 
            });
        },
        redimirCupon : function(a){
            $(a).bind("click", function(e) {
                e.preventDefault();
                var a = $(this);
                var idS =a.attr('idS') ;
                var idB =a.attr('idB') ;
                var estado =a.attr('est');
                var nroC =a.attr('nroC');
                var montoE = $('#fMonto').val();
                var boucherE = $('#fBoucher').val();
                var tipo = a.attr("tipo");
                var csrf = $("#csrf"), //input
                    csrf2 = $("#csrf2"); //div
					
                csrf.val(csrf2.html());
                var contenido = $("#content-winRedimirCupon"),
                    frmReCupons = $('#frmRedimirCupon').serialize(),
                    cbotipo1=$('#cbotipo1').val(),
                    monto1=$('#monto1').val(),
                    iMontoTipo=0,
                    eachMontoTipo="",
                    validador=1,
                    msjMontoTipo="";
                
                if(cbotipo1 != undefined) {
                    eachMontoTipo='.cboMntoDescR';
                    msjMontoTipo=msjs.validaMontoTipo.bad2;
                } else if (monto1 != undefined) {
                    eachMontoTipo='.txtMntoDescR';
                    msjMontoTipo=msjs.validaMontoTipo.bad;
                }
                var valor=0;
                $(eachMontoTipo).each(function(i) {
                    ($(this).val()=='')? (iMontoTipo++):'' ;
//                    if(eachMontoTipo=='.txtMntoDescR'){
//                        valor=parseInt($(this).val());
//                        (valor > 0)? '':iMontoTipo++ ;
//                    }
                });                
                (iMontoTipo != 0)? (validador = ""):'' ;
                if(validador == '') {
                    alert(msjMontoTipo);
                    return true;
                } else {
                    contenido.html("");
                    contenido.addClass("loading");
                    
                    $.ajax({
                        type: 'POST',
                        url: "/establecimiento/redencion-beneficio/redimir-cupon/",
                        data: frmReCupons+'&idS='+idS+'&idB='+idB+'&est='+estado+'&nroC='+nroC+'&monto='+montoE
                                +'&numero_cupon='+boucherE+'&tipo='+tipo+'&csrf='+csrf.val()
                        /*{
                            'idS' : idS,
                            'idB' : idB,
                            'est' : estado,
                            'monto' : montoE,
                            'numero_cupon' : boucherE,
                            'tipo' : tipo,
                            'csrf' : csrf.val()
                        }*/,
                        dataType: "html",
                        success: function(msg) {
                            contenido.removeClass("loading");
                            //contenido.html(msg); //agregado para visualizar los contenidos
                            if ( $(msg).attr('data-error') == '-1') {
                                contenido.html(msg);
                            }
                            if ($(msg).attr('data-error') == '1') {
                                contenido.html('<div class="good">' + msjs.validaError.success + '</div>');
                                //redimirBeneficio.reloadDetalle();
                                $("#adminEstaContentBox").html("");
                                redimirBeneficio.closeWindow();
                            }

                            redimirBeneficio.redimirCupon('.Btnredimir');
                            //Validando campo    
                            jstrap.onlyNum('#fMonto');                                               

                        }
                    });
                }
            });
        },
        reloadDetalle : function (){
            var a = $('#mictBenContent');
            var value = a.attr('idbene');
            var tipo = a.attr('tipo');
            var tipodoc = a.attr('tipodoc');
            var col = '';
            var ord = '';
        		
            var contenido = $("#adminEstaContentBox");
            contenido.html('');
            contenido.addClass('loading');
        		
            $.ajax({
                'url' : '/establecimiento/redencion-beneficio/valida-dni-cupon',
                'type' : 'POST',
                'dataType' : 'html',
                'data' : {
                    'tipo' : tipo,
                    'value' : value,
                    'col' : col,
                    'ord' : ord,
                    'tipodoc' : tipodoc
                },
                'success' : function(res) {
                    contenido.removeClass("loading");
                    contenido.html(res);
                    if (tipo==0) {
                        $('#addValidateDni').unbind();
                        $('.btn_consumir').unbind();
                        $('a.Btnredimir').unbind();
                        redimirBeneficio.validarDNI('#addValidateDni');
                        redimirBeneficio.detalleRedimirCupon('.btn_consumir');
                    } else {
                        $('#addValidateCupon').unbind();
                        $('.btn_consumir').unbind();
                        $('a.Btnredimir').unbind();
                        redimirBeneficio.validarCupon('#addValidateCupon');
                        redimirBeneficio.detalleRedimirCupon('.btn_consumir');
                    }
                },
                'error' : function(res) {
                //							cntMsj.removeClass('loading good').addClass('bad').text('Fallo el envio');
                }
            });
        },
        closeWindow: function (){
            setTimeout(
                function(){
                    $('#winRedimirCupon .closeWM').click();
                },
                1500
                );
        },
        paginado : function(){
            $(".itemPag a[href]").live("click", function(e){
                e.preventDefault();
                var pagina = $(this).attr("rel");
                var contenido = $("#adminEstaContentBox");
                var objTable = $("#mictBenContent");
                var ord = objTable.attr("ord");
                var col = objTable.attr("col");
                var idbeneficio = objTable.attr("idbene");
                var tipo = objTable.attr("tipo");
                var tipodoc = objTable.attr("tipodoc");


                contenido.addClass("loading");
                contenido.html("");

                (pagina == undefined) ? (pagina = '') : (pagina = pagina);
                (contenido == undefined) ? (contenido = '') : (contenido = contenido);
                (ord == undefined) ? (ord = '') : (ord = ord);
                (col == undefined) ? (col = '') : (col = col);
                (idbeneficio == undefined) ? (idbeneficio = '') : (idbeneficio = idbeneficio);
                (tipo == undefined) ? (tipo = '') : (tipo = tipo);

                var json = {
                    "page"   : pagina,
                    "ord"    : ord,
                    "col"    : col,
                    "value"  : idbeneficio,
                    "tipo"   : tipo,
                    "tipodoc"   : tipodoc
                };

                $.ajax({
                    data    : json,
                    url     : "/establecimiento/redencion-beneficio/valida-dni-cupon",
                    type    : "POST",
                    dataType: "html",
                    success : function(result){
                        contenido.html(result);
                        contenido.removeClass("loading");
                        var q = "#addValidateDni";
                        $(q).unbind();
                        $('.btn_consumir').unbind();
                        $('a.Btnredimir').unbind();
                        redimirBeneficio.validarDNI(q);
                        redimirBeneficio.detalleRedimirCupon('.btn_consumir');
                    }
                });
            });
        },
        ordenamiento : function(){
            $(".ordenarFecha").live("click", function(e){
                e.preventDefault();
                var contenido = $("#adminEstaContentBox");
                var objTable = $("#mictBenContent");
                var ord = objTable.attr("ord");
                var col = objTable.attr("col");
                var idbeneficio = objTable.attr("idbene");
                var tipo = objTable.attr("tipo");
                var pagina = objTable.attr("page");
                var tipodoc = objTable.attr("tipodoc");

                contenido.addClass("loading");
                contenido.html("");

                (pagina == undefined) ? (pagina = '') : (pagina = pagina);
                (contenido == undefined) ? (contenido = '') : (contenido = contenido);
                (ord == undefined) ? (ord = '') : (ord = ord);
                (col == undefined) ? (col = '') : (col = col);
                (idbeneficio == undefined) ? (idbeneficio = '') : (idbeneficio = idbeneficio);
                (tipo == undefined) ? (tipo = '') : (tipo = tipo);

                if (ord=="" || ord=="DESC") ord="ASC"; else ord="DESC";

                var json = {
                    "page"   : pagina,
                    "ord"    : ord,
                    "col"    : col,
                    "value"  : idbeneficio,
                    "tipo"   : tipo,
                    "tipodoc"   : tipodoc
                };

                $.ajax({
                    data    : json,
                    url     : "/establecimiento/redencion-beneficio/valida-dni-cupon",
                    type    : "POST",
                    dataType: "html",
                    success : function(result){
                        contenido.html(result);
                        contenido.removeClass("loading");
                        var q = "#addValidateDni";
                        $(q).unbind();
                        $('.btn_consumir').unbind();
                        $('a.Btnredimir').unbind();
                        redimirBeneficio.validarDNI(q);
                        redimirBeneficio.detalleRedimirCupon('.btn_consumir');
                    }
                });
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
        redencionLote : function(textbox){
            var txtNroRed = $(textbox),
                aAddCup = $('#aAddMoreCupons');
            
            aAddCup.live('click', function(){
                loadCantCupons();
            });
            //jstrap.onlyNumDoc(textbox);
            txtNroRed.live('keyup',function(e){
                e.preventDefault();
                var key = e.keyCode || e.charCode || e.which || window.e ;
                if(key === 13) { //presionamos enter
                    loadCantCupons();
                }
            });
            
            function loadCantCupons(){
                var contRedLot = $('#idAjaxRedimirLote'),
                    frmRedCup = $('#frmRedimirCupon').serialize();
                contRedLot.html("");
                contRedLot.addClass("loading center mB10");

                $.ajax({
                    data    : frmRedCup,
                    url     : "/establecimiento/redencion-beneficio/redimir-lote",
                    type    : "POST",
                    dataType: "html",
                    success : function(result){
                        contRedLot.html(result);
                        contRedLot.removeClass("loading");
                        //var numb = $('#idNumbLote').val();
                        //console.log(numb);
                        /*if(numb>4){
                            contRedLot.css({
                                'overflow-y':'scroll',
                                'max-height':'290px'
                            });
                        }*/
                    }
                });
            }
            //txtNroRed.live('blur', function(){
            //});
            /* by Paul */
            txtNroRed.live('keypress', function(e){
                var tecla = e.keyCode || e.charCode || e.which || window.e ;//(document.all) ? e.keyCode : e.which;
                //console.log(tecla);
                if (tecla==8) return true;
                if (tecla==0) return true;
                if (tecla==13) return true;
                if (tecla==39 || tecla==37) return true; //teclas izquierda y derecha
                if (tecla==9) return true; //tecla tab (tabulacion)
                var patron =/\d/,
                    te = String.fromCharCode(tecla);
                return patron.test(te);
            });
        },
        validaMntoDcto : function(txt){
            var txtmnto = $(txt),
                punto = 0;
            
            /* by Paul */
            txtmnto.live('keypress', function(e){
                var flag = false;
                var keyascii = e.keyCode || e.charCode || e.which || window.e ;//(event.which)? event.which : event.keyCode;
                var cadena = $(this).val();
                if ((keyascii >= 48 && keyascii <= 57) || (keyascii == 46) || (keyascii == 8) 
                    || (keyascii == 9) || (keyascii==39) || (keyascii==37)) {
                    flag= true;
                    if (keyascii == 46) {
                        punto++;
                        if (punto > 1 && cadena.indexOf(".") >=0) {
                            flag= false;
                        }
                        if(cadena.length==0) flag=false;
                    }
                }
                return flag;
            });
        },
        showMessageCbo_nocierraPuerta : function(cbo){
            var combo = $(cbo),
                msjTipo='';            
            combo.live('change', function(){
                var cbo = $(this),
                    idTipo = cbo.val(),
                    ArrayIdTipo= idTipo.split('-'),
                    valPr=(ArrayIdTipo[2]=='0.00')?'':ArrayIdTipo[2],
                    valPs=(ArrayIdTipo[3]=='0.00')?'':ArrayIdTipo[3],
                    valA=(ArrayIdTipo[1]=='0.00')?'':ArrayIdTipo[1];
                    cbo.parent().next().html(valPr);
                    cbo.parent().next().next().html(valPs);
                    cbo.parent().next().next().next().html(valA);
                    msjTipo = (idTipo==''?'Seleccione Tipo':messageTipB[idTipo]);
                    //console.log(msjTipo);
                    cbo.next().find('span').html(msjTipo);
            });
        },
        descripcionLote : function(cbo){
            var combo = $(cbo),
                msjTipo='';
            combo.live('mouseover', function(){
                var cbo = $(this).prev(),
                    idTipo = cbo.val();                    
                    msjTipo = (idTipo==''?'Seleccione Tipo':messageTipB[idTipo]);
                    //console.log(msjTipo);
                    cbo.next().find('span').html(msjTipo);
            })            
        },
        showMessageCbo_cierraPuerta : function(cbo,txt){
            var combo = $(cbo),
                msjTipo='';
            combo.live('change', function(){
                var cbo = $(this),
                    idTipo = cbo.val(),
                    ArrayIdTipo= idTipo.split('-'),
                    monto=parseFloat(cbo.parent().next().children(txt).val()),
                    ahorro=((monto*ArrayIdTipo[4])/100);
                    ahorro=ahorro.toFixed(2);
                var montoPagar=monto-ahorro;
                    montoPagar=montoPagar.toFixed(2);
                    ahorro=parseFloat(ahorro)?ahorro:'';
                    montoPagar=(montoPagar>0)?montoPagar:'';
                    cbo.parent().next().next().html(ahorro);
                    cbo.parent().next().next().next().html(montoPagar);
                    msjTipo = (idTipo==''?'Seleccione Tipo':messageTipB[idTipo]);
                    //console.log(msjTipo);
                    cbo.next().find('span').html(msjTipo);
            });
            var texto = $(txt);
            texto.live('blur', function(){
                var cbo = $(this).parent().prev().children(cbo),
                    idTipo = cbo.val(),
                    ArrayIdTipo= idTipo.split('-'),
                    monto=parseFloat($(this).val()),
                    ahorro=((monto*ArrayIdTipo[4])/100);
                    ahorro=ahorro.toFixed(2);
                var montoPagar=monto-ahorro;
                    montoPagar=montoPagar.toFixed(2);
                    ahorro=parseFloat(ahorro)?ahorro:'';
                    montoPagar=(montoPagar>0)?montoPagar:'';
                    cbo.parent().next().next().html(ahorro);
                    cbo.parent().next().next().next().html(montoPagar);
            });
        }
    };
   
    // init
    redimirBeneficio.validarDNI('#addValidateDni');
    redimirBeneficio.validarCupon('#addValidateCupon');

    redimirBeneficio.detalleRedimirCupon('.btn_consumir');
    redimirBeneficio.redimirCupon('.Btnredimir');

    redimirBeneficio.paginado();
    redimirBeneficio.ordenamiento();
    redimirBeneficio.maxLenghtN('#tipodoc');
    
    // redencion en lote
    redimirBeneficio.redencionLote('#nrocupones');
    redimirBeneficio.validaMntoDcto('.txtMntoDescR');
    redimirBeneficio.showMessageCbo_nocierraPuerta('.classNoCierraPuerta');
    redimirBeneficio.showMessageCbo_cierraPuerta('.classCierraPuerta','.classCierraPuerta2');
    redimirBeneficio.descripcionLote('.showMessageTipo');
    
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/establecimiento/error/' + jqXHR.status;
    });
});