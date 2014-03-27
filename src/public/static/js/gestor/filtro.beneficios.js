/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    var form = '#formBox';
    var selects = '#formBox select';
    var trigger = '#searchbt';
    var result = '#result';
    var pag = '.pag';
    var chk = '#chk_all';
    //    var chk_item = '.chk_item';
    var vnc = '.venc';
    var vncl = '#vencOk';
    var filtro = {
        init: function () {
            $(trigger).bind('click', function(){
                filtro.cleanEvents();
                var data = $(form).serialize();
                filtro.find(data, result);
            });
            $(selects).bind('change', function(){
                filtro.cleanEvents();
                var data = $(form).serialize();
                filtro.find(data, result);
            });
            $(form).submit( function () {
                return false;
            });
        },
        paginacion : function(){
            filtro.cleanEvents();
            var data = $(form).serialize();
            data = data + '&page=' + $(this).attr('rel');
            filtro.find(data, result);
            return false;
        },
        closeModal : function (refrescar) {
            setTimeout(function(){
                filtro.cleanEvents();
                $('.window').fadeOut('fast', null);
                $('#mask').fadeOut('slow', null);
                if(refrescar) {
                    var data2 = $(form).serialize();
                    filtro.find(data2);
                } else {
                    $(pag).bind('click', filtro.paginacion);
                    $(chk).bind('change', filtro.seleccion);
                    $(vnc).bind('click', filtro.vencimiento);
                    $(vncl).bind('click', filtro.vencerxlote);
                }
            }, 3000);
        },
        vencimiento : function() {
            var data = {
                id : $(this).attr('rel'),
                csrf: $("#csrf").text()
            };
            filtro.cleanEvents();
            $(pag).bind('click', filtro.paginacion);
            $(chk).bind('change', filtro.seleccion);
            $(vnc).bind('click', filtro.vencimiento);
            $(vncl).bind('click', filtro.vencerxlote);
            $('#vcont').html('<span class="Trebuchet14 gray">¿Estás seguro que deseas pasar a no vigente este beneficio?</span>');
            $('#aceptVenBtn').bind('click', function(){
                filtro.cleanEvents();
                $('#vcont').addClass('loading').text('Procesando');
                $.ajax({
                    url : '/gestor/beneficios/vencer-beneficio',
                    type : 'POST',
                    dataType : 'json',
                    data : data,
                    success : function (response) {
                        filtro.cleanEvents();
                        $('#vcont').removeClass('loading').text(response.message);
                        filtro.closeModal(true);
                    }
                })
            })
        //            filtro.closeModal(false);
        },
        vencerxlote : function () {
            //            filtro.cleanEvents();
            $('#diagContent').text('¿Estás seguro que deseas vencer estos beneficios?');
            var benef = '';
            $('input:checkbox.chk_item:checked').not(':disabled').each(function(){
                benef = benef + '&beneficio[]=' + $(this).val();
            })
            benef = benef + '&csrf=' + $("#csrf").text();
            if(benef != '') {
                $('#diagContent').addClass('loading').text('Procesando');
                $.ajax({
                    'url' : '/gestor/beneficios/vencer-lote-beneficios',
                    'type' : 'POST',
                    'dataType' : 'json',
                    'data' : benef,
                    'success' : function (response) {
                        $('#diagContent').removeClass('loading').text(response.message);
                        filtro.closeModal(true);
                    }
                })
            }else {
                $('#diagContent').removeClass('loading').text('Debe seleccionar al menos un beneficio');
                filtro.closeModal(false);
            }
        },
        find: function(data) {
            filtro.cleanEvents();
            $(result).html('');
            $(result).addClass("loading");
            $.ajax({
                url : '/gestor/beneficios/buscar-beneficios',
                type : 'GET',
                dataType : 'html',
                data : data,
                success : function (html) {
                    filtro.cleanEvents();
                    $(result).append(html);
                    $(result).removeClass("loading hide");
                    $(pag).bind('click', filtro.paginacion);
                    $(chk).bind('change', filtro.seleccion);
                    $(vnc).bind('click', filtro.vencimiento);
                    $(vncl).bind('click', filtro.vencerxlote);
                }
            })
        },
        seleccion : function  () {
            $('input:checkbox.chk_item').not(':disabled').each(function() {
                $(this).attr('checked', $(chk).is(':checked'));
            })
            $('input:checkbox.chk_item').not(':disabled').bind('change', function() {
                $(chk).attr('checked', false);
            })
        },
        cleanEvents : function () {
            $(vnc).unbind();
            $(vncl).unbind();
            $(pag).unbind();
            $(chk).unbind();
            $('#aceptVenBtn').unbind();
        },
        darBajaBenef : function(){
            var linkDarBaja = $('.aDarBajaBenef'),
                divLoadBaja = $('#content-darBajaBeneficio');
                
            linkDarBaja.live('click', function(){
                divLoadBaja.html('');
                divLoadBaja.addClass('loading center');
                $.ajax({
                    url : '/gestor/beneficios/dar-baja-beneficio',
                    type : 'GET',
                    dataType : 'html',
                    data : { id : $(this).attr('rel') },
                    success : function (result) {
                        divLoadBaja.removeClass("loading hide center");
                        divLoadBaja.html(result);
                    }
                });
            });
            
            var btnAceptaBaja = $('#aceptBajaBenefBtn'),
                btnCloseBaja = $('#aCloseBajaB'),
                aCloseBenef = $('#aCloseBajaBenef');
            
            btnCloseBaja.live('click', function(){
                aCloseBenef.trigger('click');
            });
            
            btnAceptaBaja.live('click', function(){
                var cntMsj3 = $('#content-darBajaBeneficio'),
                    idBene = $('#txtidBenef').val();

                cntMsj3.text('').removeClass('hide bad good')
                      .addClass('loading center');
                $.ajax({
                    url : '/gestor/beneficios/dar-baja-beneficio',
                    type : 'POST',
                    dataType : 'JSON',
                    data : { id : idBene },
                    success : function(res){
                        cntMsj3.html(res.mensaje).addClass('good')
                        .removeClass('loading');
                        if(res.success){
                            setTimeout(function(){
                                cntMsj3.removeClass('good');
                                aCloseBenef.trigger('click');
                            },1000);
                            filtro.find('');
                        }
                    },
                    error : function(res){
                        cntMsj3.html('Error').addClass('bad');
                    }
                });
            });
        }
    }
    filtro.cleanEvents();
    filtro.init();
    filtro.darBajaBenef();
    
    $(trigger).trigger('click');
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        filtro.cleanEvents();
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
});

