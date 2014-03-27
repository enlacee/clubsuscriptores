var minVal=1;
/* 
beneficio
 */
$(function(){
    var today = (urls.fDayCurrent<10? ("0" + urls.fDayCurrent):urls.fDayCurrent)+"/"+(urls.fMonthCurrent<10? ("0" + urls.fMonthCurrent):urls.fMonthCurrent)+"/"+urls.fYearCurrent;
    var exp = /^[0-9]{1}[x][0-9]{1}$|^[0-9]{1,2}[%]$/;
    var altoHTML = apmaximo;
    var msg = {
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
        fecha_inicio : {
            good : '¡OK!',
            bad : 'Fecha Requerida.',
            def : 'Debe ingresar una fecha de inicio.'
        },
        fecha_fin : {
            good : '¡OK!',
            bad : 'Fecha Requerida.',
            def : 'Debe ingresar una fecha de finalizacion.'
        },
        categorias : {
            good : '¡OK!',
            bad : 'Debe seleccionar al menos una categoria.',
            def : 'Seleccione las categorias.'
        },
        establecimiento: {
            good : '¡OK!',
            bad : 'Debe seleccionar un establecimiento',
            def : 'Seleccione un establecimiento.'
        },
        chapita: {
            good : '¡OK!',
            bad : 'Debe seleccionar el color de la chapita',
            def : 'Seleccione un color para la chapita.'
        },
        anunciante: {
            good : '¡OK!',
            bad : 'Debe seleccionar un Anunciante',
            def : 'Seleccione Anunciante.'
        },
        tipo_beneficio: {
            good : '¡OK!',
            bad : 'Debe seleccionar el tipo de beneficio',
            def : 'Seleccione el tipo de beneficio.'
        },
        minCupones :{
            bad : 'El Stock no puede ser menor que el Límite cupones.'
        },
        altoHTMLMax : {
            bad : 'El alto del contenido HTML(heigth) es mayor al permitido por página en el catálogo.'
        },
        pdf : {
        	bad : 'Debe subir el archivo PDF.'
        },
        stock: {
        	bad : 'Debe ingresar mas valor que los cupones generados.'
        },
        maximo_por_subscriptor: {
        	bad : 'Debe ingresar mas valor que los cupones generados por suscriptor.'
        }
    };
    var vars = {
        rs : '.response',
        okR : 'ready',
        req : 'requerido',
        sendFlag : 'sendN',
        loading : '<div class="loading"></div>'
    };
    //maxLenght textarea IE
    jq.pasteMaxlength('#descripcion');
    jq.pasteMaxlength('#descripcion_corta');
    jq.pasteMaxlength('#valor');
    jq.pasteMaxlength('#cuando');
    jq.pasteMaxlength('#como');
    jq.pasteMaxlength('#descripcion_cupon');
    jq.pasteMaxlength('#terminos_condiciones_web');
    jq.pasteMaxlength('#terminos_condiciones_cupon');
    jq.pasteMaxlength('#direccion');
    //jq.pasteMaxlength('#informacion_adicional');    
    var beneficio = {
        init : function(){
            var vigencia = $("#fecha_inicio_vigencia, #fecha_fin_vigencia").datepicker({
                changeMonth: true,
                changeYear: true,
                minDate: -0,
                showMonthAfterYear: false,
                onSelect: function( selectedDate ) {
                    var option = this.id == "fecha_inicio_vigencia" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    dateIF = $.datepicker.parseDate(
                        instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                    vigencia.not( this ).datepicker( "option", option, dateIF );
                    
//                    var opc='',fecha1='',fecha11='',fecha2='',fecha22='',domCambio='';
//                    if (this.id == "fecha_inicio_vigencia") {
//                        opc=1;
//                        if ($("#fecha_inicio_publicacion").val() != '') {
//                            domCambio=$("#fecha_inicio_publicacion");
//                            fecha1=selectedDate.split('/');
//                            fecha2=domCambio.val().split('/');
//                            compararD(opc,domCambio,fecha1,fecha2);
//                        }                        
//                        if ($("#fecha_fin_publicacion").val() != '') {
//                            domCambio=$("#fecha_fin_publicacion");
//                            fecha1=selectedDate.split('/');
//                            fecha2=domCambio.val().split('/');
//                            compararD(opc,domCambio,fecha1,fecha2);
//                        }
//                    } else {
//                        opc=2;
//                        if ($("#fecha_inicio_publicacion").val() != '') {
//                            domCambio=$("#fecha_inicio_publicacion");
//                            fecha1=selectedDate.split('/');
//                            fecha2=domCambio.val().split('/');
//                            compararD(opc,domCambio,fecha1,fecha2);
//                        }                        
//                        if ($("#fecha_fin_publicacion").val() != '') {
//                            domCambio=$("#fecha_fin_publicacion");
//                            fecha1=selectedDate.split('/');
//                            fecha2=domCambio.val().split('/');
//                            compararD(opc,domCambio,fecha1,fecha2);
//                        }
//                    }
//                    
//                    function compararD(opc,domCambio,fecha1,fecha2) {
//                        var fecha11='',fecha22='';
//                        if (fecha1 != '' && fecha2 != '') {
//                            fecha11=fecha1[2]+'/'+fecha1[1]+'/'+fecha1[0];
//                            fecha22=fecha2[2]+'/'+fecha2[1]+'/'+fecha2[0];                            
//                            if(opc==1 && fecha11 > fecha22 ) domCambio.val(selectedDate);
//                            else if (opc==2 && fecha11 < fecha22 ) domCambio.val(selectedDate);
//                        }
//                    }
                    
                    var tf = $('#fecha_fin_vigencia');
                    var rf =  tf.parents('.bloqueNbeneficio').find(vars.rs);
//                    if($('#fecha_inicio_vigencia').val() == today) {
//                        $('#estado').val(3);
//                    }
                    if($('#fecha_fin_vigencia').val() != '') {
                        //var tf = $('#fecha_fin_vigencia');
                        //var rf =  tf.parents('.bloqueNbeneficio').find(vars.rs);
                        rf.removeClass('bad').addClass('good').text(msg.fecha_fin.good);
                        tf.addClass(vars.okR);
                    } else {
                        //var tf = $('#fecha_fin_vigencia');
                        //var rf =  tf.parents('.bloqueNbeneficio').find(vars.rs);
                        rf.removeClass('good').addClass('bad').text(msg.fecha_fin.bad);
                        tf.removeClass(vars.okR);
                    }
                    var t = $('#fecha_inicio_vigencia');
                    var r =  t.parents('.bloqueNbeneficio').find(vars.rs);
                    if($('#fecha_inicio_vigencia').val() != '') {
                        r.removeClass('bad').addClass('good').text(msg.fecha_inicio.good);
                        t.addClass(vars.okR);
                    } else {
                        //var tf = $('#fecha_inicio_vigencia');
                        //var rf =  tf.parents('.bloqueNbeneficio').find(vars.rs);
                        r.removeClass('good').addClass('bad').text(msg.fecha_fin.bad);
                        t.removeClass(vars.okR);
                    }
                }
            });
            
            var publicacion = $("#fecha_inicio_publicacion, #fecha_fin_publicacion").datepicker({                
//                onClose: function(dateText, inst){
//                    inst.input.val('');
//                },
                beforeShow: function(input, inst){
//                    var instanceMin = $("#fecha_inicio_vigencia").data("datepicker"),
//                    instanceMax = $("#fecha_fin_vigencia").data("datepicker"),
//                    minDate = $.datepicker.parseDate(
//                        instanceMin.settings.dateFormat || $.datepicker._defaults.dateFormat,
//                    instanceMin.input.val(), instanceMin.settings ),
//                    maxDate = $.datepicker.parseDate(
//                        instanceMax.settings.dateFormat || $.datepicker._defaults.dateFormat,
//                    instanceMax.input.val(), instanceMax.settings );
//                    $("#fecha_inicio_publicacion").datepicker( "option", "minDate", minDate)
//                     .datepicker( "option", "maxDate", maxDate);
//                    $("#fecha_fin_publicacion").datepicker( "option", "maxDate", maxDate);
                 },    
                changeMonth: true,
                changeYear: true,
                minDate: -0,
                showMonthAfterYear: false,            
                onSelect: function( selectedDate ) {
//                    if($("#fecha_inicio_vigencia").val()=='' || $("#fecha_fin_vigencia").val()==''){
//                        alert("Ingrese fecha de inicio de vigencia y fin de vigencia.");
//                        $(this).val(''); 
//                    } else {
                        var option = this.id == "fecha_inicio_publicacion" ? "minDate" : "maxDate",
                        instance = $(this).data("datepicker"),
                        date = $.datepicker.parseDate(
                            instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                            selectedDate, instance.settings );
                        publicacion.not( this ).datepicker( "option", option, date );
                        var tf = $('#fecha_fin_publicacion');
                        var rf =  tf.parents('.bloqueNbeneficio').find(vars.rs);
                        if($('#fecha_inicio_publicacion').val() == today) {
                            $('#estado').val(3);
                        }
                        if($('#fecha_fin_publicacion').val() != '') {
                            rf.removeClass('bad').addClass('good').text(msg.fecha_fin.good);
                            tf.addClass(vars.okR);
                        } else {
                            rf.removeClass('good').addClass('bad').text(msg.fecha_fin.bad);
                            tf.removeClass(vars.okR);
                        }
                        var t = $('#fecha_inicio_publicacion');
                        var r =  t.parents('.bloqueNbeneficio').find(vars.rs);
                        if($('#fecha_inicio_publicacion').val() != '') {                            
                            r.removeClass('bad').addClass('good').text(msg.fecha_inicio.good);
                            t.addClass(vars.okR);
                        } else {
                            r.removeClass('good').addClass('bad').text(msg.fecha_fin.bad);
                            t.removeClass(vars.okR);
                        }
//                    }
                }
            });
            
            //Remueve el atributo selected de los items en las 
            //listas de categorias disponibles o seleccionadas
            $('#cat_disponibles option').each(function(i) {
                $(this).removeAttr("selected");
            });
            $('#cat_seleccionadas option').each(function(i) {
                $(this).removeAttr("selected");
            });
            $('#establecimiento_id').bind('change', function (i) {
                var r =  $(this).parents('.bloqueNbeneficio').find(vars.rs);
                $('#email_info').val('');
                $('#email_info').attr('disabled', false);
                $('#telefono_info').val('');
                $('#telefono_info').attr('disabled', false);
                if($('#email_info_establecimiento').is(':checked')) {
                    $('#email_info_establecimiento').attr('checked', !$('#email_info_establecimiento').is(':checked'));
                    $('#email_info_establecimiento').trigger('click', null);
                    
                }
                if($('#telefono_info_establecimiento').is(':checked')) {
                    $('#telefono_info_establecimiento').attr('checked', !$('#email_info_establecimiento').is(':checked'));
                    $('#telefono_info_establecimiento').trigger('click', null);
                }
                if($('#establecimiento_id').val() == 0 ) {
                    r.removeClass('good').addClass('bad').text(msg.establecimiento.bad);
                } else {
                    r.removeClass('bad').addClass('good').text(msg.establecimiento.good);
                    $(this).addClass(vars.okR);
                }
            });
            $('#estado').bind('change', function(i){
                if($(this).val() == 3) {
                    $('#fecha_inicio_publicacion').val(today);
                    $('#fecha_fin_publicacion').val('').removeClass('ready');
                    var r =  $('#fecha_fin_publicacion').parents('.bloqueNbeneficio').find(vars.rs);
                    r.removeClass('good').addClass('bad').text(msg.fecha_fin.bad);
                }
            });
        },
        categorias : function(trigger, from, to, inp, good, bad, def) {
            $(trigger).click(function(){
                var sel = $(from + ' option:selected');
                sel.remove().appendTo(to).removeAttr("selected");
                var r =  $(this).parents('.bloqueNbeneficio').find(vars.rs);

                //Pdf lista de ganadores
                var pdfInput = $('#pdf_resultado'),
                cntPdf = $('#resultado'),
                cntDataPdf = $('#dataUpPdf'),
                msgPdfTxt = $('#pdf_msg'),
                flagItemSel = false,
                flagItemCatSortDispo = false;
                
                //Recorriendo los seleccionados
                $.each(sel, function(i,v){
                	if( parseInt($.trim($(v).val())) == idSorteoResultado ){
                		flagItemSel = true;
                	}
                    else if( parseInt($.trim($(v).val())) == idSorteoDisponibles ){
                        flagItemCatSortDispo = true;
                	} 
                });
                
                beneficio.detacado_banner(from,inp,flagItemCatSortDispo,false,'#tipo_beneficio_id');
                
                if(flagItemSel){
                    if(from == inp) {                    	
                    	pdfInput.removeClass('requerido ready');
                        //pdf
                        cntPdf.slideUp('fast');                        
                    } else {
                    	pdfInput.addClass('requerido');
                        //pdf                        
                        cntPdf.slideDown('fast');
                        pdfInput.val('');
                        cntDataPdf.text('');
                        msgPdfTxt.text('').removeClass('good bad');
                    }
                }

                //change data file
                pdfInput.bind('change', function(){
                	var t = $(this),
                	valT = t.val();
                	cntDataPdf.text(valT);
                });
                
                //other
                
                if ($('select' + inp + ' option').size() > 0) {
                    r.removeClass('bad').addClass('good').text(msg.categorias.good);
                    $(inp).addClass(vars.okR);
                } else {
                    r.removeClass('good').addClass('bad').text(msg.categorias.bad);                    
                    $(inp).removeClass(vars.okR);
                }

            });
        },
        inputPdfInfo : function(trigger,dataMostrar) {
            //Pdf lista de ganadores
            var pdfInput = $(trigger),
            cntDataPdf = $(dataMostrar);

            //change data file
            pdfInput.bind('change', function(){
                var t = $(this),
                valT = t.val();
                cntDataPdf.text(valT);
            });
        },
        detacado_banner : function(from,inp,flagItemCatSortDispo,flagTipConcurso,idBusq) {
            var cntChechBanner = $('#bannerSorteoDisponible'),tercerVal=false;
            if((flagItemCatSortDispo && from == inp && !flagTipConcurso) || 
                (!flagTipConcurso && !flagItemCatSortDispo )){
                if(idBusq=='#cat_seleccionadas'){
                    $('#cat_seleccionadas option').each(function () {
                        if( parseInt($.trim($(this).val())) == idSorteoDisponibles ){
                            tercerVal = true;
                        } 
                    });
                }else if(idBusq=='#tipo_beneficio_id') {
                    if( $(idBusq).val() == tipBen_idConcurso ){
                        tercerVal = true;
                    }
                }
                    
            } else {
                tercerVal=true;
            }
            if(tercerVal){
                cntChechBanner.slideDown('fast');
            } else {
                cntChechBanner.slideUp('fast');
                $('#es_destacado_banner').attr('checked', false);
            }
            
        },
		_validarStockBase: function(){
			var responseSE = {},
				dom = {
				stockCupos : '#stock',
				LimiteCupos : '#maximo_por_subscriptor',
				sinStock : 'input[name="sin_stock"]:checked',
				controlTriggerChildrenStockDetalle : '#tipoPromocion',
				ChildrenStockDetalle : '.tipProMaximoSusc',
				ChildrenStockDetalleId : '#inputValidTD',
				radioIlimitado: 'input[name=sin_limite_por_suscriptor]:nth(0)',
				radioGeneral: 'input[name=sin_limite_por_suscriptor]:nth(2)',
				radioDetalle : 'input[name=sin_limite_por_suscriptor]:nth(1)',
				radioStockIlimitado: 'input[name=sin_stock]:nth(0)',
                radioStockDetalle: 'input[name=sin_stock]:nth(2)'
			},
			result = parseInt($(dom.LimiteCupos).val()) >= 1
                && parseInt($(dom.stockCupos).val()) >= parseInt($(dom.LimiteCupos).val()) ? 1: 0;
            if($(dom.radioIlimitado).is(":checked")){
                //alert(1);
                if($(dom.radioStockIlimitado).is(":checked")){
                    responseSE.valid = 1;
                }else{
                    if(parseInt($(dom.stockCupos).val())>=1){
                        responseSE.valid = 1;
                    }
                }				
				responseSE.target = dom.stockCupos;
				responseSE.good = "¡OK!";
				responseSE.bad = "Campo requerido";
			}
			//if($(dom.radioDetalle).is(":checked") && !$(dom.ChildrenStockDetalle).length){
            if($(dom.radioDetalle).is(":checked")){
                //alert(2);
                if($(dom.sinStock).val()==1 && parseInt($(dom.LimiteCupos).val())>=minVal){
                    responseSE.valid = 1;
                } else {
                    responseSE.valid = result;
                }
				responseSE.target = dom.LimiteCupos;
				responseSE.good = "¡OK!";
				responseSE.bad = "Límite de stock debe ser igual o menor al stock de cupos y mayor que "+minVal;
			}
			if($(dom.radioGeneral).is(":checked")){
                //alert(3);
				if($(dom.ChildrenStockDetalle).length && !$(dom.ChildrenStockDetalle).is(":disabled")){
                    var resultStockMaxDetalle = this._validarStockMaxDetalle(dom);
                    if(resultStockMaxDetalle.valid){
                        if($(dom.sinStock).val()==1 && parseInt($(dom.LimiteCupos).val())>=minVal){
                            responseSE.valid = 1;
                        } else {
                            if(result){
                                responseSE.valid = result;
                                responseSE.target = dom.LimiteCupos;
                                responseSE.good = "¡OK!";
                                responseSE.bad = "";
                            } else {
                                responseSE.valid = result;
                                responseSE.target = dom.LimiteCupos;
                                responseSE.good = "¡OK!";
                                responseSE.bad = "Límite de stock debe ser igual o menor al stock de cupos y mayor que "+minVal;
                            }
                        }
                    } else {
                        responseSE.valid = resultStockMaxDetalle.valid;
                        responseSE.target = resultStockMaxDetalle.target;
                        responseSE.good = "¡OK!";
                        responseSE.bad = "La Sumatoria de detalles de Max S. debe ser mayor o igual al Limite de cupos por suscriptor";
                    }
                    
                        
                    
//                    } else {
//                        responseSE.valid = result;
//                        responseSE.target = dom.LimiteCupos;
//                        responseSE.good = "¡OK!";
//                        responseSE.bad = "Límite de stock debe ser igual o menor al stock de cupos y mayor que "+minVal;
//                    }
				}else{
					responseSE.target = dom.ChildrenStockDetalleId;
					responseSE.valid = result;
					responseSE.good = "¡OK!";
					responseSE.bad = "Debería agregar un detalle de descuento";
				}
			}
            
            if($(dom.radioStockDetalle).is(":checked")){
                if($(dom.ChildrenStockDetalle).length == 0){
					responseSE.target = dom.ChildrenStockDetalleId;
					responseSE.valid = result;
					responseSE.good = "¡OK!";
					responseSE.bad = "Debería agregar un detalle de descuento";
				}
			}
            
			responseSE.dom = dom;
			this._showError(responseSE);
			return responseSE; 
		},
		_validarStockMaxDetalle: function(dom){
			var responseSED = {},
				count = 0;                
//            if($(dom.sinStock).val()==1){
//                responseSED.valid = 1;
//            } else {
                for(var i = 0; i< $(dom.ChildrenStockDetalle).length; i++){
                    count += parseInt($($(dom.ChildrenStockDetalle)[i]).val());		
                }
                //responseSED.valid = ($(dom.stockCupos).val() >= count) ? 1: 0;
                responseSED.valid = ($(dom.LimiteCupos).val() <= count) ? 1: 0;
//            }
			responseSED.target = dom.ChildrenStockDetalleId;
			return responseSED;
		},
		_showError: function(responseSE){
			var A = $(responseSE.target);
			var r = A.parents('.bloqueNbeneficio').find(vars.rs);
			if(responseSE.valid){
                r.removeClass('bad').addClass('good').text(responseSE.good);
                A.addClass(vars.okR);
			} else {
                r.removeClass('good').addClass('bad').text(responseSE.bad);
                A.removeClass(vars.okR);
			}
		},
        submit : function() {
            var this_ =  this;
			$('#btnUpdate').bind('click', function(e) {
				 
				 e.preventDefault();
                $('#cat_disponibles option').each(function(i) {
                    $(this).attr("selected", "selected");
                });
                $('#cat_seleccionadas option').each(function(i) {
                    $(this).attr("selected", "selected");
                });
                
                //verifica campos tipos descuentos
                var olListIpt = $('#dataOLRes'),
                isShow = (olListIpt.find('li')).size(),
                isAllInput = true,
                iptValidTD = $('#inputValidTD'),
                responseOLM = $('#resListNMB');
                
                if(isShow>0){
                	var isInputs = olListIpt.find('input[type="text"]'),valSuma=0;
                    $.each(isInputs, function(i,v){
                        valSuma=0;
                    	if( ($.trim($(v).val()) == $.trim($(v).attr('alt')) 
                    	   || $.trim($(v).val()) == '') && $.trim($(v).attr('noValidar')) =='' 
                            && $(v).attr('disabled') !='disabled' && $(v).attr('readonly') !='readonly'
                            ){
                    		isAllInput = false;valSuma++;
                    		iptValidTD.removeClass('ready').addClass('requerido');
                    		responseOLM.text(msg.requerido.bad).
                    		removeClass('good').addClass('bad');                    		
                    		return false;
                    	}else{
                    		isAllInput = true;
                    		iptValidTD.addClass('ready');
                    		responseOLM.text(msg.requerido.good).
                    		removeClass('bad').addClass('good');
                    	}
                    });
//                    if(valSuma > 0){
//                        isAllInput = false;
//                        iptValidTD.removeClass('ready').addClass('requerido');
//                        responseOLM.text(msg.requerido.bad).
//                        removeClass('good').addClass('bad');
//                    } else {
//                        isAllInput = true;
//                        iptValidTD.addClass('ready');
//                        responseOLM.text(msg.requerido.good).
//                        removeClass('bad').addClass('good');
//                    }
//                } else {
//                    iptValidTD.addClass('ready');
                }
                //fin
                
                var chapita = $('#chapita'),
                chVal = chapita.val(),
                chRes = chapita.parents('.bloqueNbeneficio').find('.response'),
                chFlag = (chVal.length > 1 && exp.test(chVal)),
                chTxt = 'Ingrese el contenido correcto de la chapita. Ejm: 2x1, 45%',
                frm = $('#formBeneficio'),
                respEditor = $('#respEditor');
                
                //nbfields
                respEditor.removeClass('bad').text('');
                
                //alert( $('#formBeneficio .' + vars.okR).size()+'---'+$('#formBeneficio .requerido').size());                
                if(!isAllInput){
                    e.preventDefault();
                } else {
                    var result = this_._validarStockBase();
                    if(!result.valid){
                        e.preventDefault();
                    }else if($('#formBeneficio .' + vars.okR).size() < $('#formBeneficio .requerido').size()) {
                        $('#formBeneficio .requerido')
                        .not('.ready')
                        .removeClass('ready')
                        .parents('.bloqueNbeneficio')
                        .find('.response')
                        .removeClass('def good')
                        .addClass('bad')
                        .text(msg.requerido.bad);

                        if($.trim(chVal) != ''){
                            if(chFlag){
                                chRes
                                .text('')
                                .removeClass('good bad');                          
                            }else{
                                chRes
                                .text(chTxt)
                                .addClass('bad')
                                .removeClass('good');                          
                            }
                        }
                        //Midiendo Alto del Editor
                        beneficio._heightIframe();                                        
                    }else {
                        if(chVal != ''){
                            if( chFlag ){
                                //Midiendo Alto del Editor
                                beneficio._heightIframe();
                                //Submit
                                setTimeout(function(){                                

                                    if( parseInt($('#iframeH').val()) <= altoHTML){

                                        frm.submit();

                                    }else{
                                        $('html, body').animate({
                                            scrollTop:respEditor.offset().top - 100
                                        }, 'slow');
                                        respEditor.addClass('bad').text(msg.altoHTMLMax.bad);
                                    }

                                },0);
                            }else{
                                chRes
                                .text(chTxt)
                                .addClass('bad')
                                .removeClass('good');  
                            }                        
                        }else{
                            //Midiendo Alto del Editor
                            beneficio._heightIframe();
                            //Submit
                            setTimeout(function(){
                                if( parseInt($('#iframeH').val()) <= altoHTML){

                                    frm.submit();

                                }else{
                                    $('html, body').animate({
                                        scrollTop:respEditor.offset().top - 270
                                    }, 'slow');
                                    respEditor.addClass('bad').text(msg.altoHTMLMax.bad);
                                }
                            },0);
                        }
                    }
                }
            });
            
            
            
        },
        _heightIframe : function(){
            $('#dataLoad2').remove();
            var htmlData = $('#iframeCEL').contents().find('body').html(),
            cntH = '';                        
            $('body').append('<div id="dataLoad2"></div>');
            setTimeout(function(){
                cntH = ($('#dataLoad2').html(htmlData)).height();                
                $('input#iframeH').val(cntH + 10);
            },0);
        },
        logo : function() {
            $('#path_logo').bind('change', function() {
                $('#img_avatar').html('').addClass('loading');
            });
        },
        fMail : function(a,good,bad,def) {
            $(a).bind('blur', function() {
                var t = $(this),
                r =  t.parents('.bloqueNbeneficio').find(vars.rs),
                ep = /^(([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+)?$/g;
                if( ep.test(t.val()) && t.val() != '') {
                    r.removeClass('bad').addClass('good').text(good);
                    t.addClass(vars.okR);
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
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
        fOnlyNum : function(a) {
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
		fOnlyNumMoreThanCero: function (a,good,bad,def){
			var A = $(a),
            r = A.parents('.bloqueNbeneficio').find(vars.rs);
            A.blur(function() {
					var t = $(this),
					valor = t.val(),
					val2;
					if(valor > 0) {
                        if( a == '#stock' && $(a).attr("formu")=='edit'){
                            beneficio.validStockMin(t,r,null);
                        } else if( a == '#maximo_por_subscriptor' && $(a).attr("formu")=='edit'){
                            beneficio.validCantMinCuponSuscriptor(t,r,null);
                        } else {
                            beneficio.clearMsgStockLimit();
                            r.removeClass('bad').addClass('good').text(good);
                            t.addClass(vars.okR);                    
                        }
						
					} else {						
                        r.removeClass('good').addClass('bad').text(bad);
						t.removeClass(vars.okR);
					}}
			).keyup(function(){
                    var t = $(this);
					if(parseInt(t.val()) > 0){
						r.removeClass('good').addClass('bad').text(bad);
						t.removeClass(vars.okR);                                       
					}else{
                        r.removeClass('good bad').text(def);
                        t.addClass(vars.okR);						
					}
			});
		},		
        fInput : function(a,good,bad,def,obliga) {
            var A = $(a),
            r = A.parents('.bloqueNbeneficio').find(vars.rs);
            A.blur( function() {
                var t = $(this),
                valor = t.val(),
                val2;
                if (obliga==true) {
                    if(valor.length>0) {
                        r.removeClass('bad').addClass('good').text(good);
                        t.addClass(vars.okR);                    
                    } else {                    
                        r.removeClass('good').addClass('bad').text(bad);
                        t.removeClass(vars.okR);   
                    }
                } else {
                    r.removeClass('good bad').text('');
                }
            }).keyup( function() {
                var t = $(this);
                if (obliga==true) {
                    if(parseInt(t.val()).length == 0){                                      
                        r.removeClass('good').addClass('bad').text(bad);
                        t.removeClass(vars.okR);                                       
                    }else{
                        r.removeClass('good bad').text(def);
                        t.addClass(vars.okR);                    
                    }
                } else {
                    r.removeClass('good bad').text(def);
                }
            });
        },
        fInputSumarBeneficio : function(a,b,c) {
            var A = $(a),B = $(b);

            A.blur( function() {
            //A.live("blur", function(){
                var t=$(this);
                if(!parseInt(t.val())){
                    t.val(t.attr("alt")).addClass("cGray");
                } else {
                    t.removeClass("cGray")
                }
                beneficio.ASumarTipoDescuentos(a,b,c)
            });
            
        },
        ASumarTipoDescuentos : function(a,b,c) {
            var A = $(a),B = $(b),sum=0,C = $(c);
            if(C.val()==2) {                
                A.each(function(i) {
                    if(parseInt($(this).val())){
                        sum= sum + parseInt($(this).val());
                    }              
                });
                B.val(sum);
            }                
        },
        showInfo : function(field){
            var oField = $(field),
            r = oField.parents('.bloqueNbeneficio').find(vars.rs);
            r.removeClass('bad good');
            oField.blur( function() {                 
                r.text('');  
            }).keyup( function() {
                var t = $(this),
                max = t.attr('maxlength');
                if( (t.val()).length == 0 ){                                      
                    r.text('');
                }else{
                    if($.trim(max) != ''){
                        r.text('Máximo ' + max + ' Caracteres.');
                    }
                }                 
            });    
        },
        validCantMinCuponSuscriptor : function(t,r,idTipDes){
            var logica,msgCant,det=$("#resListNMB");            
            if(parseInt(t.val())){
                if(idTipDes ==null){
                    var data = {
                        idBen: $("#idBen").val(),
                        cant: t.val()
                    };
                }else{
                    var data = {
                        idBen: $("#idBen").val(),
                        cant: t.val(),
                        idTipDesc: idTipDes.replace("d_cantidad[","").replace("]","")
                    };
                }
                $.ajax({
                    'url' : '/gestor/beneficios/obtener-cupon-min-suscriptor',
                    'type' : 'GET',
                    'dataType' : 'json',
                    'data' : data,
                    async: false,
                    'success' : function (info) {
                        minVal= (parseInt(info.cant)>0)?info.cant:1;
                        logica=info.valor;
                        msgCant=" (actualmente en "+info.cant+")";
                    }
                });
                
                if(!logica){
                    t.removeClass(vars.okR).val("");
                    r.removeClass('good').addClass('bad').text(msg.stock.bad+msgCant);             
                }else{
                    t.addClass(vars.okR);
                    r.removeClass('bad').addClass('good').text(msg.requerido.good);
                    det.html("");
                }
            }else{
                t.removeClass(vars.okR);
                r.removeClass('good').addClass('bad').text(msg.requerido.bad);
            }                            
        },
        validSinLimite : function(a,b,tip){
            var A = $(a),B = $(b),C=$("#inputValidTD");
            
            var lnk_add = $("#tipoPromocion"),
                chRes = A.parents('.bloqueNbeneficio').find('.response'),
                alt="Max S.",
                attrVal="data",
                valTip='.tipProMaximoSusc';
            
            if (tip==2) {
                alt="Stock";
                attrVal="dataStock";
                valTip='.tipProSinStock';
            }
            
            var classTipProMaximoSusc=$(valTip);
            
            A.bind('click', function(){
                var valS=$(this).val();
                var valName=$(this).attr("name");
                beneficio.clearMsgStockLimit();
                switch (valS) {
                    case '1':{
                         minVal=1;
                         B.val("").addClass("ready").attr('disabled',true);
                         C.addClass("ready").addClass("requerido");
                         lnk_add.attr(attrVal, "1");
                         classTipProMaximoSusc.addClass('cGray').val(alt).attr('disabled',true);
                         chRes.text('');
                         break;
                    }
                    case '0':{
                         B.val("").removeClass('ready').attr('disabled',false);
                         C.addClass("ready").addClass("requerido");
                         lnk_add.attr(attrVal, "1");
                         classTipProMaximoSusc.addClass('cGray').val(alt).attr('disabled',true);
                         chRes.text('');
                         break;
                    }
                    case '2':{
                         if (valName=="sin_limite_por_suscriptor") {
                             B.val("").removeClass('ready').attr('disabled',false);
                         } else {                             
                             B.val("").addClass("requerido ready").attr('disabled',true);   
                         }                         
                         C.removeClass("ready");
                         lnk_add.attr(attrVal, "0");
                         classTipProMaximoSusc.addClass('cGray').val(alt).attr('disabled',false);
                         beneficio.valTipProMaximoSuscNOTnull(valTip);
                         chRes.text('');
                         break;
                    }
                }
            });
        },
        valTipProMaximoSuscNOTnull : function (a){
            var A = $(a);
            A.blur( function() {
                var t=$(this);
                if(parseInt(t.val())<1){
                    t.val("");
                }
            });
        },
        validCantMaxPorSuscriptor : function(a){
            var A = $(a);
            var r = A.parents('.bloqueNbeneficio').find(vars.rs);
            var logica,msgCant,data;
                        
            A.blur( function() {
                var t=$(this);
                var idTipDes=t.attr("name");
                if(parseInt(t.val())){
                    if(t.val()>0){
                        data = {
                            idBen: $("#idBen").val(),
                            cant: t.val(),
                            idTipDesc: idTipDes.replace("detTipDesc[","").replace("][d_maximo_por_suscriptor]","")
                        };
                        $.ajax({
                            'url' : '/gestor/beneficios/obtener-cupon-max-suscriptor-tipdesc',
                            'type' : 'GET',
                            'dataType' : 'json',
                            'data' : data,
                            async: false,
                            'success' : function (info) {
                                logica=info.valor;
                                msgCant=" (actualmente en "+info.cant+")";
                            }
                        });
                        if(!logica){
                            t.removeClass(vars.okR);
                            r.removeClass('good').addClass('bad').text(msg.stock.bad+msgCant);
                            $(this).val("");
                        }else{
                            var cond=0;
                            $(".maxPorSusc_tipDesc").each(function(i,v){
                                if (parseInt($(v).val()) < 0 || $(v).val()=='') {
                                    cond++;
                                }
                            });
                            if(cond==0){
                                t.addClass(vars.okR);
                                r.removeClass('bad').addClass('good').text(msg.requerido.good);
                            } else {
                                msgCant='';
                                t.removeClass(vars.okR);
                                r.removeClass('good').addClass('bad').text(msg.requerido.bad);                        
                            }

                        }
                    } else {
                        t.removeClass(vars.okR);
                        r.removeClass('good').addClass('bad').text(msg.requerido.bad); 
                    }
                }else{
                    t.val(t.attr("alt"));
                    t.removeClass(vars.okR);
                    r.removeClass('good').addClass('bad').text(msg.requerido.bad); 
                }                
                                
            })
                            
        },
        validStockMin : function(t,r,idTipDes){
            var logica,msgCant;
            if(parseInt(t.val())){
                if(idTipDes ==null){
                    var data = {
                        idBen: $("#idBen").val(),
                        cant: t.val()
                    };
                }else{
                    var data = {
                        idBen: $("#idBen").val(),
                        cant: t.val(),
                        idTipDesc: idTipDes.replace("d_cantidad[","").replace("]","")
                    };
                }
                $.ajax({
                    'url' : '/gestor/beneficios/obtener-stock-minimo',
                    'type' : 'GET',
                    'dataType' : 'json',
                    'data' : data,
                    async: false,
                    'success' : function (info) {
                        logica=info.valor;
                        msgCant=" (actualmente en "+info.cant+")";
                    }
                });
            }else{
                logica=false;
            }
            if(!logica){
                t.removeClass(vars.okR);
                r.removeClass('good').addClass('bad').text(msg.stock.bad+msgCant);               
            }else{
                t.addClass(vars.okR);
                r.removeClass('bad').addClass('good').text(msg.requerido.good);
            }
        },
        validStockLimite : function(t,r,other){
            var val2 = $(other),
            logica;

            if(other == '#stock'){
                logica = parseInt(t.val()) > parseInt(val2.val());

                if (t.val()=='' || t.val()=='0') {
                    logica = true;
                };
                

            }
            if(other == '#maximo_por_subscriptor'){

                logica = parseInt(t.val()) < parseInt(val2.val());
   
                if (t.val()=='' || t.val()=='0') {
                    logica = true;
                };

            }

            if( logica ){
                t.removeClass(vars.okR);
                r.removeClass('good').addClass('bad').text(msg.minCupones.bad);
                val2.addClass(vars.okR);
                val2.siblings('.response').addClass('good').removeClass('bad').text(msg.requerido.good);               
            }else{
                t.addClass(vars.okR);
                r.removeClass('bad').addClass('good').text(msg.requerido.good);
                val2.addClass(vars.okR);
                val2.siblings('.response').addClass('good').removeClass('bad').text(msg.requerido.good);                 
            }            
        },
        fCheckInputGenerarCupon : function(chk,descripCupon, good, bad , def) {
            $(chk).bind('change', function(e) {
                e.preventDefault();
                var r =  $(descripCupon).parents('.bloqueNbeneficio').find(vars.rs);
                var parentCheck = $('#div_descripcionCupon'),
                    arentCheck2 = $('#div_terminoCondicionCupon');
                if($(this).is(':checked')){
//                    $(descripCupon).removeClass('ready').addClass('requerido');
//                    r.removeClass('good').addClass('bad').text(msg.requerido.bad);
                	parentCheck.slideDown('fast');   
                	arentCheck2.slideDown('fast');                    
                } else {
//                    $(descripCupon).removeClass('requerido ready').val("");
//                    r.removeClass('bad').addClass('good').text(msg.requerido.good);
                    parentCheck.slideUp('fast');
                    arentCheck2.slideUp('fast');
                }
            });
        },
        fCheckInput : function(chk, inp, good, bad , def, source, getInfo, field, smsg) {
            //'#sin_limite_por_suscriptor', '#maximo_por_subscriptor'
            $(chk).bind('change', function(e) {
                e.preventDefault();
                var i = $(inp);
                var r =  $(this).parents('.bloqueNbeneficio').find(vars.rs);

                var that = this, contadorbeneficio2=0;

                if(inp=='#maximo_por_subscriptor'){
                    $.each($('ol#dataOLRes li.rowCont input'), function(index, value) {
                        if($(value).attr('rel')=='mxs'){
                            contadorbeneficio2++;
                            if($(that).is(':checked')==true){
                                $(value).val('0');                               
                            }else{                        
                                $(value).val('');                        
                            }
                            $(value).attr('disabled', $(that).is(':checked'));
                       }
                    });
                }
                //edit by jan sanchez

                if(getInfo){
                    if($(source).val() != 0) {
                        i.attr('disabled', $(this).is(':checked'));

                        if($(this).is(':checked')) {
                            r.removeClass('bad').addClass('good').text(good);
                            i.addClass(vars.okR);
                            beneficio.fGetContactoEstablecimiento(source, field, inp);
                        } else {
                            r.removeClass('good').addClass('bad').text(bad);
                            i.removeClass(vars.okR);
                            i.val('');
                        }
                    } else {
                        $(this).attr('checked', false);
                        location.hash = source;                        
                        var rs =  $(source).parents('.bloqueNbeneficio').find(vars.rs);
                        rs.text('');
                        rs.removeClass('good').addClass('bad').text(smsg.bad);
                        $('html, body').animate({
                            scrollTop:180
                        }, 'slow');                         
                    }
                }else {
                    i.attr('disabled', $(this).is(':checked'));
                    if($(this).is(':checked')) {
                        r.removeClass('bad').addClass('good').text(good);
                        i.addClass(vars.okR);
                    } else {

                        if (contadorbeneficio2>0) {
                            
                        }else{
                            r.removeClass('good').addClass('bad').text(bad);
                            i.removeClass(vars.okR);
                        }

                        

                    }
                    i.val('');
                }

                if (contadorbeneficio2>0) {
                    $('#maximo_por_subscriptor').attr('disabled', true);
                }else{
                    //$('#maximo_por_subscriptor').attr('disabled', false);
                }


            });
        },
        fCheckDate : function(chk, inp, good, bad, def) {
            $(chk).change(function(e) {
                e.preventDefault();
                var r =  $(this).parents('.bloqueNbeneficio').find(vars.rs);
                var i = $(inp);
                if($(this).is(':checked')){
                    i.val(today);
                    r.removeClass('bad').addClass('good').text(good);
                    i.addClass(vars.okR);
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    i.removeClass(vars.okR);
                    i.val('');
                }
            });
        },
        fGetContactoEstablecimiento : function (source, field, to) {
            var data = {
                id: $(source).val()
            };
            $.ajax({
                'url' : '/gestor/beneficios/obtener-establecimiento-info',
                'type' : 'GET',
                'dataType' : 'json',
                'data' : data,
                'success' : function (info) {
                    if(field == 'email') {
                        $(to).val(info.email);
                    } else if(field == 'telefono') {
                        $(to).val(info.telefono);
                    }
                }
            });
        },
        fSelect : function (source, msgs) {
            $(source).bind('change', function(e) {
                var t = $(this),   
                r =  $(this).parents('.bloqueNbeneficio').find(vars.rs);
                if($(source).val() == 0 ) {
                    r.removeClass('good').addClass('bad').text(msgs.bad);
                    $(source).removeClass(vars.okR);
                } else {
                    var flagTipConcurso = false;
                    if(source=='#tipo_beneficio_id' && $(source).val()==tipBen_idConcurso) {
                        flagTipConcurso = true;
//                        beneficio.act_tipoRedencion('#tipo_beneficio_id');
                    } 
//                    else if(source=='#tipo_beneficio_id') {
//                        beneficio.act_tipoRedencion('#tipo_beneficio_id');
//                    }
                    beneficio.detacado_banner(1,2,flagTipConcurso,false,"#cat_seleccionadas");                    
                    r.removeClass('bad').addClass('good').text(msgs.good);
                    $(source).addClass(vars.okR);
                }
            });
        },
        chapa : function(chapa, colores, inputColor, good, bad, def){
            var iChapa = $(chapa),
            preview = iChapa.parent(),
            iInputColor = $(inputColor),
            iColores = $(colores);
            iColores.bind('click', function(){
                var t = $(this),
                color = t.attr('rel');
                iInputColor.val('');
                if(!((t.parent()).hasClass('active'))){
                    iColores.parent().removeClass('active');
                    preview.removeAttr('class').addClass(color);
                    t.parent().addClass('active');
                    iInputColor.val(color);
                }
            });
            
            var ep = exp;
            var response = iChapa.parents('.bloqueNbeneficio').find('.response');
            iChapa.keypress( function(e){				
                var key = e.keyCode || e.charCode || e.which || window.e ,
                isShift = false ;
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
                    ( key == 57 && isShift == false ) ||
                    ( key == 88 ) || ( key == 120 )
                    );							
            }).keyup(function(e){
                var t = $(this),
                valor = t.val();
                if( valor.length > 0 ){
                    response.text(def).removeClass('bad good');
                    //t.removeClass(vars.okR);
                    if( valor.length > 1 && ep.test(valor) ){
                        response.text(good).removeClass('bad').addClass('good');
                    //t.addClass(vars.okR);
                    } 
                }else{
                    response.text('').removeClass('bad good');
                //response.text(bad).removeClass('good').addClass('bad');
                //t.removeClass(vars.okR);
                }
            });

            iChapa.bind('paste', function(){
                return false;           
            });	            

            iChapa.parent().mouseleave(function(){                
                if(iChapa.val().length > 0){
                    iChapa.addClass('shadowOut'); 
                }else{
                    iChapa.removeClass('shadowOut');  
                }                
            }).mouseenter(function(){
                if(iChapa.val().length > 0){ 
                    iChapa.removeClass('shadowOut');  
                }else{
                    iChapa.removeClass('shadowOut');  
                }
            });        
        },
        deleteDetaPromocion : function(del){
            $(del).bind('click', function(e){
                e.preventDefault();
                //eliminar del delete

                var row = $(this).parents('.rowCont');
                var eol = row.next('br');
                var iptStock = $('#stock');
                var labelStock = $('#labelTipoDsct'),
            	iptValidTD = $('#inputValidTD'),
                cntTmonedaAW = $('#cntTmonedaAW'), 
            	responseLNMB = $('#resListNMB');
                row.remove();
                eol.remove();
                //Revisa el bloque de stock
                var cntStock = $('#cntStockAW'),
                    cntStockSize = $('#dataOLRes .rowCont').size();
                if ( cntStockSize == 1 ) {
                    $("#dataOLRes > p").remove();
                    responseLNMB.text('').removeClass('bad good');
                }
                if( cntStockSize > 1){
                	cntStock.slideDown('fast');
                    cntTmonedaAW.slideDown('fast');
//                    iptStock.addClass('requerido');
                    labelStock.find('span').remove();
                    iptValidTD.addClass('requerido');
                    responseLNMB.text('').removeClass('bad good');
                }else{
                	cntStock.slideUp('fast');
                	iptValidTD.removeClass('requerido').removeClass('ready');
                }
//                beneficio.ASumarTipoDescuentos('.tipProMaximoSusc','#maximo_por_subscriptor','input[name="sin_limite_por_suscriptor"]:checked');
                beneficio.ASumarTipoDescuentos('.tipProSinStock','#stock','input[name="sin_stock"]:checked');
            });
        },
        placeholder : function(){
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
        },
        tipoPromocion : function(trigger) {
            
            $(trigger).bind('click', function(e) {
                e.preventDefault();
                //verify del radio
                
                var rand = Math.floor(Math.random()*1000001);
                var id = 'del' + rand;
                var flag=beneficio.getObtenerTipoBeneficio();
                beneficio.getTituloTipoBeneficios();
                var subForm = $("<li class='rowCont'>" + 
                    beneficio.getInput_tiposdescuento(flag, rand,trigger) +
                    "<a href='#' class='deleteTD' id='" + id +"'>Eliminar</a>" + 
                    "</li>");
                var container = $('#dataOLRes');
                container.append(subForm);
                beneficio.deleteDetaPromocion('#' + id);
                beneficio.placeholder();
                beneficio.validaMntoDcto('.money');
                beneficio.sumaDesc();
                beneficio.validSinLimite('input[name="sin_limite_por_suscriptor"]','#maximo_por_subscriptor');
                beneficio.validSinLimite('input[name="sin_stock"]','#stock','2');
//                beneficio.fInputSumarBeneficio('.tipProMaximoSusc','#maximo_por_subscriptor','input[name="sin_limite_por_suscriptor"]:checked');
                beneficio.fInputSumarBeneficio('.tipProSinStock','#stock','input[name="sin_stock"]:checked');
            });
        },
        getObtenerTipoBeneficio: function(){
            var A=$('input[name="tipo_redencion"]:checked').val();
            return A;
        },
        inputTipoRedencion: function(a){
            var A=$(a),B=$('#dataOLRes');
            A.bind('change', function (i) {
                B.html("");
            });
        },
        getTituloTipoBeneficios: function(){
            var flag=beneficio.getObtenerTipoBeneficio();
            var list =  $('#dataOLRes .rowCont'),
                container = $('#dataOLRes');
        	if(list.size() < 1){
                var titulo="",subForm="<p class='rowCont'>";
                if (flag==1) {
                    titulo="Código"
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; % desc."
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Stock"
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Max S."
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Descripción"
                } else {
                    titulo="Código"
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; P. Regul"
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;P. Susc."
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ahorro"
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stock"
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Max S."
                    titulo+="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Descripción"
                }
                subForm = subForm+titulo+"</p>";
                container.append(subForm);
            }            
        },
        getInput_tiposdescuento: function(flag, rand,trigger){
            var html='';
            if(flag==1){
                html="<input alt='Código' maxlength='100' value='Código' class='placeH inputNbeneStock cGray' type='text' name='detTipDesc[d"+rand+"][d_codigo]'/> " +
                     "<input alt='% desc.' maxlength='2' value='% desc.' class='money placeH inputNbeneStock cGray' type='text' name='detTipDesc[d"+rand+"][d_porcentaje_descuento]'/> % " +
                     beneficio.getInputDetalle($(trigger).attr("dataStock"), rand,'2') + 
                     beneficio.getInputDetalle($(trigger).attr("data"), rand) + 
                     "<input alt='Descripción' maxlength='250' value='Descripción' size='14' class='placeH cGray' type='text' name='detTipDesc[d"+rand+"][d_descripcion]'/>" ;
            } else {
                html="<input alt='Código' maxlength='100' value='Código' class='placeH inputNbeneStock cGray' type='text' name='detTipDesc[d"+rand+"][d_codigo]'/> " +
                     "<input id='pr"+rand+"' alt='P Regul' maxlength='8' value='P Regul' class='cInputPrecioRegul money placeH inputNbeneStock cGray' type='text' name='detTipDesc[d"+rand+"][d_precio_regular]'/> " +
                     "<input id='ps"+rand+"' alt='P Susc' maxlength='8' value='P Susc' class='cInputPrecioSusc money placeH inputNbeneStock cGray' type='text' name='detTipDesc[d"+rand+"][d_precio_suscriptor]'/> " +
                     "<input alt='Ahorro' maxlength='8' value='Ahorro' class='money placeH inputNbeneStock cGray' type='text' readonly='readonly' name='detTipDesc[d"+rand+"][d_ahorro]'/> " +
                     beneficio.getInputDetalle($(trigger).attr("dataStock"), rand,'2') + 
                     beneficio.getInputDetalle($(trigger).attr("data"), rand) + 
                     "<input alt='Descripción' maxlength='250' value='Descripción' class='placeH cGray' type='text' name='detTipDesc[d"+rand+"][d_descripcion]' size='14'/> " ;
            }
            return html;
        },
        getInputDetalle: function(flag, rand , tip){
            var adisabled = "",tipPro='.tipProSinStock',retorna='';
            if (flag==1) {adisabled = "disabled='disabled'";} 
            else { /*beneficio.valTipProMaximoSuscNOTnull(tipPro);*/ }
            
            retorna= "<input alt='Max S.' maxlength='4' value='Max S.' class='money placeH inputNbeneStock cGray tipProMaximoSusc' type='text' name='detTipDesc[d"+rand+"][d_maximo_por_suscriptor]' rel='mxs' "+adisabled+"/> ";
            
            if(tip==2) retorna= "<input alt='Stock' maxlength='4' value='Stock' class='money placeH inputNbeneStock cGray tipProSinStock' type='text' name='detTipDesc[d"+rand+"][d_cantidad]' rel='mxs' "+adisabled+"/> ";
            
            return retorna
        },
        calcAhorro: function(pr, ps){
            A=$(pr),B=$(ps);
            A.live("blur", function(){
                if($(this).attr("id")!='' && $(this).attr('readonly')!='readonly' ){
                    beneficio.calcAhorro_operacion(this,'pr');
                }
            });
            B.live("blur", function(){
                if($(this).attr("id")!='' && $(this).attr('readonly')!='readonly'){
                    beneficio.calcAhorro_operacion(this,'ps');
                }
            });
        },
        calcAhorro_operacion: function(input,opc){
            var I = $(input),
                C = (opc=='pr')?I.next().next():I.next(),
                valA = (opc=='pr')?I.val():I.prev().val(),
                valB = (opc=='ps')?I.val():I.next().val()
                ,ahorroAB='';
            valA= parseInt($.trim(valA))?valA:0;
            valB= parseInt($.trim(valB))?valB:0;
            if(valA != '' && valB != '' && parseInt(valA) > parseInt(valB)){
                ahorroAB=valA-valB;
                C.val(ahorroAB.toFixed(2));
            } else {
                C.val('');
            }
        },
        sumaDesc : function(){	
            var list =  $('#dataOLRes .rowCont'),
            iptStock =  $('#stock'),
            iptSinStock =  $('#sin_stock'),
            cntTmonedaAW =  $('#cntTmonedaAW'),           
        	labelStock =  $('#labelTipoDsct'),
        	iptValidTD =  $('#inputValidTD');
        	
        	if(list.size()>0){
                cntTmonedaAW.slideDown('fast');
        		iptValidTD.addClass('requerido');
//        		iptStock.removeClass('requerido ready').removeAttr('disabled');
        		iptSinStock.removeAttr('disabled');
        		//iptSinStock.removeAttr('checked');
        		labelStock.find('span').remove();
        		labelStock.prepend('<span class="req">*</span>');        		
        	}else{
        		cntTmonedaAW.slideUp('fast');
        		labelStock.find('span').remove();
        		iptValidTD.removeClass('requerido');
        	}        	
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
        clearMsgStockLimit : function(){
            var A=$("#maximo_por_subscriptor").parents('.bloqueNbeneficio').find('.response').text(''),
                B=$("#stock").parents('.bloqueNbeneficio').find('.response').text(''),
                C=$("#resListNMB").text('');
        }
    };
    
    beneficio.init();
    beneficio.logo();
    beneficio.submit();
    beneficio.categorias('#cat_right','#cat_disponibles','#cat_seleccionadas','#cat_seleccionadas',msg.categorias.good, msg.categorias.bad, msg.categorias.def);
    beneficio.categorias('#cat_left','#cat_seleccionadas','#cat_disponibles','#cat_seleccionadas',msg.categorias.good, msg.categorias.bad, msg.categorias.def);
    beneficio.inputPdfInfo('#pdf_info','#dataUpPdfInfo');
    beneficio.fInput('#descripcion', msg.requerido.good, msg.requerido.bad, 
        'Ingrese la descripción del beneficio. Máximo 500 caracteres.',true);
    beneficio.fInput('#titulo', msg.requerido.good, msg.requerido.bad, 'Ingrese el nombre del beneficio',true);
    beneficio.fInput('#descripcion_corta', msg.requerido.good, msg.requerido.bad, 
        'Ingrese un resumen de la descripción del beneficio. Máximo 120 caracteres.',true);
    beneficio.fInput('#descripcion_cupon', msg.requerido.good, msg.requerido.bad, 
        'Ingrese un resumen de la descripción para el cupón. Máximo 1000 caracteres.',false);
    beneficio.fInput('#terminos_condiciones_cupon', msg.requerido.good, msg.requerido.bad, 
        'Ingrese un resumen de la Término y condiciones. Máximo 1500 caracteres.',false);

    beneficio.fOnlyNumMoreThanCero('#maximo_por_subscriptor', msg.requerido.good, msg.requerido.bad, msg.requerido.def);
    beneficio.fOnlyNumMoreThanCero('#stock', msg.requerido.good, msg.requerido.bad, msg.requerido.def);
//    beneficio.fInputSumarBeneficio('.tipProMaximoSusc','#maximo_por_subscriptor','input[name="sin_limite_por_suscriptor"]:checked');
    beneficio.fInputSumarBeneficio('.tipProSinStock','#stock','input[name="sin_stock"]:checked');
//    beneficio.fInputTipDesc('.tipDescuento', msg.requerido.good, msg.requerido.bad, msg.requerido.def);
    beneficio.fInput('#telefono_info', msg.requerido.good, msg.requerido.bad, msg.requerido.def,true);
    beneficio.fMail('#email_info', msg.email.good, msg.email.bad, msg.email.def);
    beneficio.fOnlyNumTlf('#telefono_info');
    beneficio.fOnlyNum('#stock');
    beneficio.fOnlyNum('#maximo_por_subscriptor');

    beneficio.fCheckInput('#sin_stock', '#stock', msg.requerido.good, msg.requerido.bad, msg.requerido.def, '', false, '', null);
    beneficio.fCheckInput('#email_info_establecimiento', '#email_info', msg.requerido.good, msg.requerido.bad, msg.requerido.def, '#establecimiento_id', true, 'email', msg.establecimiento);
    beneficio.fCheckInput('#telefono_info_establecimiento', '#telefono_info', msg.requerido.good, msg.requerido.bad, msg.requerido.def, '#establecimiento_id', true, 'telefono', msg.establecimiento);
//    beneficio.fCheckInput('#sin_limite_por_suscriptor', '#maximo_por_subscriptor', msg.requerido.good, msg.requerido.bad, msg.requerido.def, '', false, '', null);

    beneficio.fCheckInputGenerarCupon('#generar_cupon','#descripcion_cupon', msg.requerido.good, msg.requerido.bad, msg.requerido.def);
    beneficio.fSelect('#tipo_beneficio_id', msg.tipo_beneficio);
    beneficio.fSelect('#anunciante_id', msg.anunciante);
    beneficio.fSelect('#chapita_color', msg.chapita);
    beneficio.chapa('#chapita','.aColorCh', '#chapita_color', msg.requerido.good, msg.requerido.bad, 'Ingrese el contenido correcto de la chapita. Ejm: 2x1, 45%');
    beneficio.showInfo('#valor');
    beneficio.showInfo('#cuando');
    beneficio.showInfo('#direccion');
    beneficio.showInfo('#informacion_adicional');
    beneficio.showInfo('#como');
    beneficio.tipoPromocion('#tipoPromocion');
    beneficio.validaMntoDcto('.money');
    beneficio.deleteDetaPromocion('.delD');
    beneficio.validCantMaxPorSuscriptor('.maxPorSusc_tipDesc');
    beneficio.validSinLimite('input[name="sin_limite_por_suscriptor"]','#maximo_por_subscriptor');
    beneficio.validSinLimite('input[name="sin_stock"]','#stock','2');
    //->nuevos
    beneficio.calcAhorro('.cInputPrecioRegul','.cInputPrecioSusc');
    beneficio.inputTipoRedencion('input[name="tipo_redencion"]');
});