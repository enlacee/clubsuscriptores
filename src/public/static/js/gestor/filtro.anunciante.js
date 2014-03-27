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
                filtro.findEsta(data, result);
            });
            $(form).submit( function () {
                return false;
            });
            filtro.darBajaBenef();
        },
        paginacion : function(){
            var data = $(form).serialize();
            data = data + '&page=' + $(this).attr('rel');
            filtro.findEsta(data, result);
            return false;
        },
        darBajaBenef : function(){
            var linkDarBaja = $('.aEliminarAnunciante'),
                divLoadBaja = $('#content-eliminarAnunciante');
                
            linkDarBaja.live('click', function(){
                divLoadBaja.html('');
                divLoadBaja.addClass('loading center');
                $.ajax({
                    url : '/gestor/anunciante/eliminar-anunciante',
                    type : 'GET',
                    dataType : 'html',
                    data : { id : $(this).attr('rel') },
                    success : function (result) {
                        divLoadBaja.removeClass("loading hide center");
                        divLoadBaja.html(result);
                    }
                });
            });
            
            var btnAceptaBaja = $('#aceptEliminarAnuncianteBtn'),
                btnCloseBaja = $('#aCloseAnuncianteB'),
                aCloseBenef = $('#aCloseAnunciante');
            
            btnCloseBaja.live('click', function(){
                aCloseBenef.trigger('click');
            });
            
            btnAceptaBaja.live('click', function(){
                var cntMsj3 = $('#content-eliminarAnunciante'),
                    idBene = $('#txtidBenef').val();

                cntMsj3.text('').removeClass('hide bad good')
                      .addClass('loading center');
                $.ajax({
                    url : '/gestor/anunciante/eliminar-anunciante',
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
                            filtro.findEsta('');
                        }
                    },
                    error : function(res){
                        cntMsj3.html('Error').addClass('bad');
                    }
                });
            });
        },
        findEsta: function(data) {
            $(result).html('');
            $(result).addClass("loading");
            $.ajax({
                'url' : '/gestor/anunciante/buscar-anunciante',
                'type' : 'GET',
                'dataType' : 'html',
                'data' : data,
                'success' : function (html) {
                    $('.elimina').die();
                    $(result).append(html);
                    $(result).removeClass("loading hide");
                    $(pag).bind('click', filtro.paginacion);
                    $(chk).bind('change', filtro.seleccion);
                    
                }
            });
        }
    };
    
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
    
    filtro.init();
    $(trigger).trigger('click');
});

