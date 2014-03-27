$(function() {
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
        tipo_beneficio: {
            good : '¡OK!',
            bad : 'Debe seleccionar el tipo de beneficio',
            def : 'Seleccione el tipo de beneficio.'
        }
    }   
    var vars = {
        rs : '.response',
        okR : 'ready',
        sendFlag : 'sendN',
        loading : '<div class="loading"></div>'
    }
    var today = urls.fYearCurrent+"/"+(urls.fMonthCurrent)+"/"+urls.fDayCurrent;
    var cs = {
        //window modal and alert
        winModal : function(){
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
                mH = $(document).height();
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
                if(t.attr('href') == '#editBene') {
                    cs.callback(t);
                }else if (t.attr('href') == '#previewBene') {
                    cs.callbackPreview(t);                    
                } else if(t.attr('href') == '#modalInfo') {
                    if($('input:checkbox.chk_item:checked').not(':disabled').size() == 0) {
                        $('#diagContent').removeClass('loading').text('Debe seleccionar al menos un beneficio');
                        cs.closeModal();
                    } else {
                        $('#diagContent').text('¿Estás seguro que deseas vencer estos beneficios?');
                    }
                }
            });
            c.click(function(e){
                e.preventDefault();
                m.hide();
                w.hide();
            });		
            m.click(function(e){
                $(this).hide();
                w.hide();
            });			
        },
        closeModal : function () {
            setTimeout(function(){
                filtro.cleanEvents();
                $('.window').fadeOut('fast', null);
                $('#mask').fadeOut('slow', null);
            }, 3000);
        },
        callback : function(t) {
            //var result = '#editBene';
            var result = $('#iEditBene');
            var data = 'id=' + t.attr('rel');
            result.empty();
            result.removeClass('good bad').addClass('loading');
            $.ajax({
                url : '/gestor/beneficios/obtener-beneficio',
                type : 'GET',
                dataType : 'html',
                data : data,
                success : function(html){                    
                    result.removeClass("loading");
                    result.html(html);
                    cs.initEditForm();
                }
            })
        },
        callbackPreview : function(t) {
            //var result = '#editBene';
            var result = $('#iVistaPrevia');
            var data = 'id=' + t.attr('rel');
            result.empty();
            result.addClass('loading');
            $.ajax({
                url : '/gestor/beneficios/vista-previa-beneficio',
                type : 'GET',
                dataType : 'html',
                data : data,
                success : function(html){                    
                    result.removeClass("loading");
                    result.html(html);
                    cs.initEditForm();
                }
            })
        },
        initDPRnge : function (r) {
            if(r){
                var vigencia = $("#fecha_inicio_vigencia, #fecha_fin_vigencia").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    minDate: -0,
                    showMonthAfterYear: false,
                    showOn: "button",
                    buttonImage: dt.logo,
                    buttonImageOnly: true,
                    onSelect: function( selectedDate ) {
                        var option = this.id == "fecha_inicio_vigencia" ? "minDate" : "maxDate",
                        instance = $(this).data("datepicker"),
                        date = $.datepicker.parseDate(
                            instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                            selectedDate, instance.settings );
                        vigencia.not( this ).datepicker( "option", option, date );
                        if($('#fecha_inicio_vigencia').val() == today) {
                            $('#publicar').attr('checked', true);
                        }
                        if($('#fecha_fin_vigencia').val() != '') {
                            var tf = $('#fecha_fin_vigencia');
                            var rf =  tf.parents('.bloqueNbeneficio').find(vars.rs)
                            rf.removeClass('bad').addClass('good').text(msg.fecha_fin.good);
                            tf.addClass(vars.okR);
                        }
                        if($('#fecha_inicio_vigencia').val() != '') {
                            var t = $('#fecha_inicio_vigencia');
                            var r =  t.parents('.bloqueNbeneficio').find(vars.rs)
                            r.removeClass('bad').addClass('good').text(msg.fecha_inicio.good);
                            t.addClass(vars.okR);
                        }
                    }
                });
            }else {
                var fin = $( "#fecha_fin_vigencia" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOn: "button",
                    buttonImage: dt.logo,
                    buttonImageOnly: true,
                    minDate : -0
                });
            }
        },
        categorias : function(trigger, from, to, inp, good, bad, def) {
            $(trigger).click(function(){
                $(from + ' option:selected').remove().appendTo(to).removeAttr("selected");
                var r =  $(this).parents('.bloqueNbeneficio').find(vars.rs);
                if ($('select' + inp + ' option').size() > 0) {
                    r.removeClass('bad').addClass('good').text(msg.categorias.good);
                    $(inp).addClass(vars.okR);
                } else {
                    r.removeClass('good').addClass('bad').text(msg.categorias.bad);
                    $(inp).removeClass(vars.okR);
                }
            });
        },
        initEditForm : function() {
            var flag = $('#vigente').val();
            if(flag == 1) {
                cs.initDPRnge(false);
                cs.submitForm('#formBeneficioVersion', '#btnUpdate');
            } else {
                cs.initDPRnge(true);
                cs.categorias('#cat_right','#cat_disponibles','#cat_seleccionadas','#cat_seleccionadas',msg.categorias.good, msg.categorias.bad, msg.categorias.def);
                cs.categorias('#cat_left','#cat_seleccionadas','#cat_disponibles','#cat_seleccionadas',msg.categorias.good, msg.categorias.bad, msg.categorias.def);
                cs.submitForm('#formBeneficio', '#btnUpdate');
            }
            $('.closeWM').click(function(e){
                e.preventDefault();
                $('#mask').hide();
                $('.window').hide();
            });
        },
        closeModal : function () {
            setTimeout(function(){
                $('.window').fadeOut('fast', null);
                $('#mask').fadeOut('slow', null);
            }, 2100);
        },
        submitForm : function(form, btn) {
            $(btn).bind('click', function () {
                var divResult = $('#iEditBene');
                if($('#vigente').val() == 1) {
                    var data = '';
                    if($('#v_stock').val() != $('#stock').val() || $('#v_fin').val() != $('#fecha_fin_vigencia').val()) {
                        data = 'nv=0&';
                    } else {
                        data = 'nv=1&';
                    }
                    data = data + '&csrf=' + $("#csrf").text();
                    var valcsrf = $("input[name=csrf]").val();
                    $.ajax({
                        url: '/gestor/beneficios/editar-beneficio-ajax',
                        type: 'POST',
                        dataType: 'json',
                        data: $(form).serialize() + '&csrf=' + valcsrf,
                        beforeSend: function(){
                            divResult.html('').addClass('loading');
                        },
                        success: function(response) {
                            divResult.removeAttr('style')
                                .removeClass('loading');
                            if(response.result) {
                                var data = $('#formBox').serialize();
                                data = data + '&pag=' + $('.linkPag.active').attr('rel');
                                $('#searchbt').trigger('click', null);
                                divResult.addClass('good');
                            } else {
                                divResult.addClass('bad');
                                //divResult.html('Hubo un error en la edicion del beneficio, inténtelo mas tarde');
                            }
                            divResult.html(response.message);
                        }
                    });
                } else {
                    var data = '';
                    if($('#v_stock').val() != $('#stock').val() 
                        || $('#v_fin').val() != $('#fecha_fin_vigencia').val()
                        || $('#v_inicio').val() != $('#fecha_inicio_vigencia').val()) {
                        data = 'nv=1&';
                    } else {
                        data = 'nv=0&';
                    }
                    $('#cat_seleccionadas option').each(function(i) {
                        $(this).attr("selected", "selected");
                    });
                    data = data + $('#formBeneficio').serialize();
                    data = data + '&csrf=' + $("#csrf").text();
                    $.ajax({
                        url: '/gestor/beneficios/editar-beneficio-ajax',
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                        success: function(response) {
                            if(response.result) {
                                var data = $('#formBox').serialize();
                                data = data + '&pag=' + $('.linkPag.active').attr('rel');
                                $('#searchbt').trigger('click', null);
                            } else {
                                //TODO mensaje de error en actualizacion
                            }
                        }
                    });
                }
                cs.closeModal();
            });
        }
    };
    cs.winModal();    
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });    
});
