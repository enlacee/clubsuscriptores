/* 
 */
$(function(){
    var form = '#formBox2';
    var trigger = '#searchbt1';
    var result = '#adminEstaContentBox';
    var pag = '.pag';
    var chk = '#chk_all';
    var chk_item = '.chk_item';

    var filtro = {
        init: function () {
            $(trigger).bind('click', function(){
                var data = $(form).serialize();
                $('.listarUsuarios').die();
                filtro.findEsta(data, result);
            });
            $(form).submit( function () {
                return false;
            });
        },
        paginacion : function(){
            $('.listarUsuarios').die();
            $('.elimina').die();
            var data = $(form).serialize();
            data = data + '&page=' + $(this).attr('rel');
            filtro.findEsta(data, result);
            return false;
        },
        findEsta: function(data) {
            $(result).html('');
            $(result).addClass("loading");
            $.ajax({
                'url' : '/gestor/establecimientos/buscar-establecimientos',
                'type' : 'GET',
                'dataType' : 'html',
                'data' : data,
                'success' : function (html) {
                    $('.listarUsuarios').die();
                    $('.elimina').die();
                    $(result).append(html);
                    $(result).removeClass("loading hide");
                    $(pag).bind('click', filtro.paginacion);
                    $(chk).bind('change', filtro.seleccion);
                    filtro.listarUsuario('.listarUsuarios');
                    filtro.eliminarEstablecimiento('.elimina');
                    
                }
            });
        },
        seleccion : function  () {
            $('input:checkbox.chk_item').each(function() {
                $(this).attr('checked', $(chk).is(':checked'));
            });
            $('input:checkbox.chk_item').bind('change', function() {
                $(chk).attr('checked', false);
            });
        },
        listarUsuario: function (a){
            $(a).live('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                var ajaxCnt = $('#ajax-loading');
                $a = $(this);
                $('#contEstablecimiento').slideUp('slow', function(){
                    ajaxCnt.slideDown('slow').removeClass('hide');
                    $.ajax({
                        type: 'GET',
                        url: '/gestor/establecimientos/listar-usuarios-establecimientos',
                        data: {
                            id: $a.attr('rel')
                        },
                        success: function(response) {
                            //ajaxCnt.slideUp('slow');
                            $('.listarUsuarios').die();
                            ajaxCnt.slideUp('fast').addClass('hide');
                            $('#contentGrid').append('<div id="ContenedorListarUsuario" class="all"></div>');
                            $('#ContenedorListarUsuario').html(response);
                            filtroEstablecimiento();
                            filtro.backToProcess();
                        },
                        dataType: 'html'
                    });
                });

            });
        },
        backToProcess : function () {
            $('#backToProcess').live('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var ajaxCnt = $('#ajax-loading');
                var cntPerfilC = $('#ContenedorListarUsuario');
                cntPerfilC.slideUp('slow', function() {
                    cntPerfilC.remove();
                    $('#contEstablecimiento').slideDown('slow');
                });
                $('.elimina').die();
                $('.listarUsuarios').die();
                filtro.listarUsuario('.listarUsuarios');
                filtro.eliminarEstablecimiento('.elimina');
            });
        },
        eliminarEstablecimiento : function (a) {
            $(a).live('click', function(e){
                var rel = $(this).attr('rel');
                var btnSave = $('.btnEliminar');
        		
                btnSave.bind('click',function (e){
                    e.preventDefault();
                    var contenedor= $('.contenedor');
                    var contenedorMensaje = $('.elimEstablecimiento');
                    contenedorMensaje.addClass('hide');
                    contenedor.addClass('loading');
                    $.ajax({
                        'url' : '/gestor/establecimientos/eliminar-establecimiento',
                        'type' : 'POST',
                        'dataType' : 'JSON',
                        'data' : {
                            'idEst': rel,
                            'csrf' : $("#csrf_base").text()
                        },
                        'success' : function (html) {
                            contenedor.removeClass('loading');
                            if (html == 1) {
                                contenedor.append('<span class="good alignC bold f16"> Eliminación exitoso </span>');
                                setTimeout(function(){
                                    $('#winAlertEstablecimiento .closeWM').click();
                                    location.reload();
                                }, 1500);
                            } else {
                                contenedor.append('<span class="bad alignC bold f16"> Error al eliminar. </span>');
                                contenedorMensaje.next().remove();
                            }
                            $('.elimina').die();
                            $('.btnEliminar').unbind('click');
                        	
                        	
                            filtro.eliminarEstablecimiento('.elimina');
                        },
                        'error' : function(html) {
                            $('.elimina').die();
                            $('.btnEliminar').unbind('click');
                            contenedor.removeClass('loading');
                            contenedor.append('<span class="bad alignC bold f16"> Error al eliminar. </span>');
                            setTimeout(function(){
                                $('#winAlertEstablecimiento .closeWM').click();
                                contenedorMensaje.removeClass('hide');
                                contenedorMensaje.next().remove();
                            }, 1500);
                            filtro.eliminarEstablecimiento('.elimina');
                        }
                    });
                });
            });
        }
    };
    
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
    
    $('.listarUsuarios').die();
    $('.elimina').die();
    filtro.init();
    $(trigger).trigger('click');
});

