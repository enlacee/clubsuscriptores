/* 
 */
$(function(){
    var form = '#formBoxAdminG';
    var selects = '#searchbt';
    var trigger = '#searchbt';
    var result = '#categoriaContentBox';
    var pag = '.pag';
    var chk = '#chk_all';
    var chk_item = '.chk_item';
    var filtro = {
        init: function () {
            $(trigger).bind('click', function(){
                var data = $(form).serialize();
                var t = $('#mictBenContent');
                if (t.size()>0) {
                	data = data + '&col=' + t.attr('col') + '&ord=' + t.attr('ord') + '&page=' + t.attr('pag');
                }
                filtro.findLista(data, result);
            });
            $(selects).bind('change', function(){
                var data = $(form).serialize();
                filtro.findLista(data, result);
            });
            $(form).submit( function () {
                return false;
            });
        },
        paginacion : function(){
            var data = $(form).serialize();
            var t = $('#mictBenContent');
            data = data + '&col=' + t.attr('col') + '&ord=' + t.attr('ord') + '&page=' + $(this).attr('rel');
            filtro.findLista(data, result);
            return false;
        },
        findLista: function(data) {
        	
            $(result).html('');
            $(result).addClass("loading");
            
            $.ajax({
                'url' : '/gestor/categorias/buscar-categorias',
                'type' : 'GET',
                'dataType' : 'html',
                'data' : data,
                'success' : function (html) {
                    $(result).append(html);
                    
                    $('.ordenar').unbind();
                    $('.elimina').die();
                    $('#mictBenContent td input[type=checkbox]').die();
                    filtro.selectCheck('#mictBenContent');
                    filtro.ordenarLista('.ordenar');
                    filtro.eliminarCategoria('.elimina');
                    $(result).removeClass("loading hide");
                    $(pag).bind('click', filtro.paginacion);
                    $(chk).bind('change', filtro.seleccion);
                    
                    categoria();
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
        selectCheck : function(tabla) {
			//para las filas
			$(tabla+" td input[type=checkbox]").live("change", function() {
				var clase = $(this).parents("tr").attr("class");
				if(clase==undefined || clase=="" || clase=="even") {
					$(this).parents("tr").children('td').addClass("pintar");
					$(this).parents("tr").addClass("pintar");
				} else{
					$(this).parents("tr").children('td').removeClass("pintar");
					$(this).parents("tr").removeClass("pintar");
				}
			});
			
			//para el header
			$(tabla+" thead input[type=checkbox]").live("change", function() {
				var objeto = $(this);
				var checkboxes = objeto.parents("thead").next().find("td input[type=checkbox]");
				if(!objeto.is(':checked')) {
					objeto.parents("thead").next().find("tr td").removeClass("pintar");
					objeto.parents("thead").next().find("tr").removeClass("pintar");
					//recorremos y removemos y agregamos sin checked
					$.each(checkboxes, function(index, item) {
						var id = $(item).attr("id");
						var estado = $(item).attr("estado");
						var content = $(objeto).parents("thead").next().find("td input[id="+id+"]").parent();
						$(item).remove();
						var cb = "<input type='checkbox' name='select' estado='"+estado+"' id='"+id+"'>";
						$(content).html(cb);
					});
				} else {
					objeto.parents("thead").next().find("tr td").addClass("pintar");
					objeto.parents("thead").next().find("tr").addClass("pintar");
					//recorremos y removemos y agregamos con checked
					$.each(checkboxes, function(index, item) {
						var id = $(item).attr("id");
						var estado = $(item).attr("estado");
						var content = $(objeto).parents("thead").next().find("td input[id="+id+"]").parent();
						$(item).remove();
						var cb = "<input type='checkbox' checked='checked' name='select' estado='"+estado+"' id='"+id+"'>";
						$(content).html(cb);
					});
				}
			});
		},
		ordenarLista : function(a){
            $(a).bind('click', function(e){
                e.preventDefault();
                var t = $(this);
                var data = $(form).serialize();
                data = data + '&col=' + t.attr('col') + '&ord=' + t.attr('ord') + '&page=' + t.attr('pag');
                filtro.findLista(data, result);
            });
        },
        
        eliminarCategoria : function (a) {
        	$(a).live('click', function(e){
        		var rel = $(this).attr('rel');
        		var btnSave = $('.btnEliminarCate');
        		
        		btnSave.bind('click',function (e){
        			e.preventDefault();
        			var contenedor= $('.contenedor');
        			var contenedorMensaje = $('.elimCategoria');
        			contenedorMensaje.addClass('hide');
        			contenedor.addClass('loading');
        			$.ajax({
        				'url' : '/gestor/categorias/eliminar-categoria',
                        'type' : 'POST',
                        'dataType' : 'JSON',
                        'data' : {
                        	'idCat': rel
                        },
                        'success' : function (html) {
                        	contenedor.removeClass('loading');
                        	if (html == 1) {
                        		contenedor.append('<span class="good alignC bold f16"> Eliminación exitoso </span>');
                        		setTimeout(function(){
                            		$('#winAlertCategoria .closeWM').click();
                            		location.reload();
                            	}, 1500);
                        	} else {
                        		contenedor.append('<span class="bad alignC bold f16"> Error al eliminar. </span>');
                        		contenedorMensaje.next().remove();
                        	}
                        	$('.elimina').die();
                        	$('.btnEliminarCate').unbind('click');
                        	
                        	
                        	filtro.eliminarCategoria('.elimina');
                        },
                        'error' : function(html) {
                        	$('.elimina').die();
                        	$('.btnEliminarCate').unbind('click');
                        	contenedor.removeClass('loading');
                        	contenedor.append('<span class="bad alignC bold f16"> Error al eliminar. </span>');
                        	setTimeout(function(){
                        		$('#winAlertCategoria .closeWM').click();
                        		contenedorMensaje.removeClass('hide');
                        		contenedorMensaje.next().remove();
                        	}, 1500);
                        	filtro.eliminarCategoria('.elimina');
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
    $('.elimina').die();
    filtro.init();
    $(trigger).trigger('click');
});
