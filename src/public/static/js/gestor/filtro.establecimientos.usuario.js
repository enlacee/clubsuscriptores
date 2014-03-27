/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var filtroEstablecimiento = function(){
    var form = '#formBoxUsu';
    var trigger = '#searchbt';
    var result = '#listaUsuContentBox';
   // var page = '.pag';
    var chk = '#chk_all';
    var chk_item = '.chk_item';
    var findUsuario = {
        init: function () {
            $(trigger).bind('click', function(){
                var data = $(form).serialize();
                var t = $('.pagUsu');
                if (t.size()>0) {
                	data = data + '&col=' + t.attr('col') + '&ord=' + t.attr('ord') + '&page=' + t.attr('pag');
                }
                findUsuario.find(data, result);
            });
            $(form).submit( function () {
                return false;
            });
        },
        paginacion : function(){
            var data = $(form).serialize();
            var t = $('.pagUsu');
            data = data + '&col=' + t.attr('col') + '&ord=' + t.attr('ord') + '&page=' + $(this).attr('rel');
            findUsuario.find(data, result);
            return false;
        },
        find: function(data) {
            $(result).html('');
            $(result).addClass("loading");
            $.ajax({
                'url' : '/gestor/establecimientos/buscar-usuarios',
                'type' : 'GET',
                'dataType' : 'html',
                'data' : data,
                'success' : function (html) {
                    $(result).append(html);
                    $(result).removeClass("loading hide");
                    $($('#listaUsuContentBox').find('#mictBeneBtnBox .pag')).bind('click', findUsuario.paginacion);
                    $(chk).bind('change', findUsuario.seleccion);
                    $('.ordenar').unbind();
                    findUsuario.ordenarLista('.ordenar');
                    establecimiento();
                    loadCache = false;
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
        ordenarLista : function(a){
            $(a).bind('click', function(e){
                e.preventDefault();
                var t = $(this);
                var data = $(form).serialize();
                data = data + '&col=' + t.attr('col') + '&ord=' + t.attr('ord') + '&page=' + t.attr('pag');
                findUsuario.find(data, result);
            });
        }
        
    };
    
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
    
    findUsuario.init();
    $(trigger).trigger('click');
    findUsuario.ordenarLista('.ordenar');
};

