/* 
Categoria
 */
var categoria = function(){
    var msgs = {
        requerido : {
            good : '¡OK!',
            bad : 'Campo Requerido.',
            def : 'Campo Requerido.'
        },
        mensajeOk : {
            goodActualizacion : 'Actualización de la Categoria exitosa.',
            goodCreacion : 'Creación de la Categoria exitosa.'
        },
        mensajeError : {
        	errorSeleccion : 'Debe seleccionar por lo menos una categoria.',
        	errorDifEstado : 'Para realizar el cambio masivamente todas las categorías deben tener el mismo estado.'
        },
        mensajeCate : {
        	correct : 'correcto.',
        	incorrect : 'Nombre de categoria incorrecto.',
        	existe : 'Ya existe una categoría con ese nombre.',
        	ingrese : 'Ingrese nombre de categoria.'
        	
        }
    };    
    var categoriajs = {
	cargarModal : function(a){
            $(a).bind('click', function(){
                var t = $(this);
                var idCat = t.attr('rel');
                var contenido = $('#content-winCategoria');
                contenido.addClass("loading");
                contenido.html("");
                $.ajax({
                    'url' : '/gestor/categorias/nuevo',
                    'type' : 'GET',
                    'dataType' : 'html',
                    'data' : {
                        'idCat' : idCat
                    },
                    'success' : function(res) {
                    	$('.editCat').unbind();
                        $('.adminSaveBtnbene').unbind();
                        
                        contenido.removeClass("loading");
                        contenido.html(res);
                        //maxlenght
                        jq.pasteMaxlength('#fdescripcion');
                        
                        categoriajs.cargarModal('.editCat');
                        //categoriajs.guardarCategoria('.adminSaveBtnbene');
                        categoriajs.closeWindow('.adminQuitBtnbene');
                        
                        var inputText = $('#fnom'),
                        btnSubmit = $('#btnSubmitED');
                        categoriajs.validaFormEditar(inputText, btnSubmit);
                        
                    },
                    'error' : function(res) {
                    }
                });
    			
            });
        },
        validaFormEditar : function(inputText, btnSubmit){
        	
        	btnSubmit.bind('click', function(e){
        		e.preventDefault();
        		var a = $('#fnom');
        		
        		if (a.hasClass('ready')) {
        			$('.adminSaveBtnbene').unbind();
        			categoriajs._processClick($(this));
        		} else {
        			a.next().removeClass('good def').addClass('bad').text(msgs.mensajeCate.ingrese);
        		}
        	});
        	
    		inputText.keyup(categoriajs.debounce(function(){
    			var t = $(inputText);
    			var valNom = t.val();
    			var idCat = $('#idCat').val();
    			
    			if (valNom != '') {
    				t.next().removeClass('bad').addClass('good').text('');
    				t.addClass('loadingMail');
    				t.css({
    	        		'background-position' : '343px 6px'
    	        	});
    				$.ajax({
		                url: '/gestor/categorias/validar-nombre/',
		                type: 'post',
		                data: {
		                	valNom: valNom,
		                    idCat: idCat
		                },
		                dataType: 'json',
		                success: function(response){
		                    if( response != 1){
		                    	t.addClass('ready').removeClass('loadingMail');
		                    	t.next().removeClass('bad def').addClass('good').text(msgs.mensajeCate.correct);
		                    	
		                    }else{
		                    	t.removeClass('ready').removeClass('loadingMail');
		                    	t.next().removeClass('good def').addClass('bad').text(msgs.mensajeCate.existe);
		                    }
		                },
		                error : function (response){
		                	t.removeClass('ready').removeClass('loadingMail');
	                    	t.next().removeClass('good def').addClass('bad').text(msgs.mensajeCate.incorrect);
		                }
		            });
    			} else {
    				t.removeClass('ready');
    				t.next().removeClass('good').addClass('bad').text(msgs.mensajeCate.ingrese);
    			}
    		},500));
        },
        debounce : function(callback, delay){
    	    var self = this, timeout, _arguments;
    	    return function() {
    	      _arguments = Array.prototype.slice.call(arguments, 0),
    	      timeout = clearTimeout(timeout, _arguments),
    	      timeout = setTimeout(function() {
    	        callback.apply(self, _arguments);
    	        timeout = 0;
    	      }, delay);	
    	      return this;
    	    };
    	  },
        guardarCategoria : function(a){
            $(a).bind('click', function(){
            	var t = $(this);
            	categoriajs._processClick(t);
            });
        },
        _processClick : function(t){
            var data = $('#modalCategoria').serialize();
            var contenido = $('#content-winCategoria');
            contenido.addClass("loading");
            contenido.html("");
            $.ajax({
                'url' : '/gestor/categorias/nuevo',
                'type' : 'POST',
                'dataType' : 'html',
                'data' : data,
                'success' : function(msg) {
                    contenido.removeClass("loading");
                    if ( $(msg).attr('data-error') == '-1') {
                        contenido.html(msg);
                    }
                    if ($(msg).attr('data-error') == '1') {
                        contenido.html('<div class="good alignC bold f16">' + msgs.mensajeOk.goodActualizacion + '</div>');
                        $("#searchbt").click();
                        categoriajs.closeWindowTime();
                    }
                    
                    if ($(msg).attr('data-error') == '2') {
                        contenido.html('<div class="good alignC bold f16">' + msgs.mensajeOk.goodCreacion + '</div>');
                        $("#searchbt").click();
                        categoriajs.closeWindowTime();
                    }
                    
                    $('.adminSaveBtnbene').unbind();
                    $('.editCat').unbind();
                    categoriajs.guardarCategoria('.adminSaveBtnbene');
                    categoriajs.closeWindow('.adminQuitBtnbene');
                    categoriajs.cargarModal('.editCat');
                    
                },
                'error' : function(res) {
                }
            });            	
        },        
        
        cargarModalcambioEstado : function(a){
        	$(a).bind('click', function(e){
        		e.preventDefault();
        		var t = $(this);
        		var valores = $('#mictBenContent tbody').find("input:checked");
        		var arreglo_val_id =[];
        		var arreglo_val_estado =[];
        		var dif = 0;
        		var est = '' ; 
				
				if (valores.size()>0) {
					$.each(valores, function(index,item) {
						id = $(item).attr("id");
						estado = $(item).attr("estado");
						arreglo_val_id.push(id);
						arreglo_val_estado.push(estado);
					});
					
					$.each(arreglo_val_estado, function(index,val_1) {
						$.each(arreglo_val_estado, function(index,val_2) {
							if (val_1!= val_2){
								dif = 1;
							} else {
								est = val_2;
							}
						});
					});
					t.removeClass('disabledModal');
	        		var contenido = $('#content-winCambioEstado');
	        		contenido.html('');
	        		contenido.addClass('loading');
	        		
	        		if (dif == 0) {
	        			var data = {
	    						"estado": est,
	    						"idsCat": arreglo_val_id
    					};
	        			
	        			$.ajax({
		        			'url' : '/gestor/categorias/cambio-estado',
		                    'type' : 'GET',
		                    'dataType' : 'html',
		                    'data' : data,
		                    'success' : function(msg) {
		                    	
		                    	$('.cambEstado').unbind();
		                    	$('.adminAceptBtn').unbind();
		                    	
		                    	contenido.removeClass('loading');
		                    	contenido.html(msg);
		                    	
		                    	categoriajs.cargarModalcambioEstado('.cambEstado');
		                    	categoriajs.cambioEstado('.adminAceptBtn');
		                    	categoriajs.cancelar('.adminQuitBtnbene');
		                    }
		        		});
	        		} else {
	        			$('.cambEstado').unbind();
	        			t.addClass('disabledModal');
	        			categoriajs.cargarModalcambioEstado('.cambEstado');
						var msj = msgs.mensajeError.errorDifEstado;
	                	categoriajs.mensajeErrorBanner(msj);
	        		}
	        		
				} else {
					
					$('.cambEstado').unbind();
                	$('.adminAceptBtn').unbind();
                	categoriajs.cargarModalcambioEstado('.cambEstado');
                	t.addClass('disabledModal');
                	var msj = msgs.mensajeError.errorSeleccion;
                	categoriajs.mensajeErrorBanner(msj);
				}
        	});
        },
        cargarModalCambioEstadoPer : function(a){
        	$(a).bind('click', function(e){
        		
        		var t = $(this);
        		var contenido = $('#content-winCambioEstado');
        		var idCat = t.attr('rel');
        		var estado = t.attr('estado');
        		var data =  {
        			'estado' : estado,
    				'idsCat' : idCat
        		};
        		contenido.html('');
        		contenido.addClass('loading');
        		$.ajax({
        			'url' : '/gestor/categorias/cambio-estado',
                    'type' : 'GET',
                    'dataType' : 'html',
                    'data' : data,
                    'success' : function(msg) {
	                	$('.cambEstado').unbind();
	                	$('.adminAceptBtn').unbind();
	                	$('.cambEstadoOne').unbind();
	                	
	                	contenido.removeClass('loading');
	                	contenido.html(msg);
	                	
	                	categoriajs.cargarModalCambioEstadoPer('.cambEstadoOne');
	                	categoriajs.cargarModalcambioEstado('.cambEstado');
	                	categoriajs.cambioEstado('.adminAceptBtn');
	                	categoriajs.cancelar('.adminQuitBtnbene');
                    }
        		});
        	});
        },
        
        mensajeErrorBanner : function(msj){
			var cont = $('#msjError');
			cont.html(msj);
			//aparece El mensaje
			cont.fadeIn('slow', function(){
				cont.removeClass('hide');
				
				//Tiempo en que se ejecuta el ocultar Mensaje
				setTimeout( function(){
					
					//Oculta Mensaje :)
					cont.fadeOut('slow', function(){
						cont.addClass('hide');
						cont.html('');	
					});
				},
                1500);
            });
        },
        
		cambioEstado : function (a){
			$(a).bind('click',function(e){
				e.preventDefault();
				var contenido = $('#content-winCambioEstado');
				var valores = $('#estCa').find('.adminGesCatIco .displayBlock');
				var est = $('#estCa').attr('rel');
				var arreglo_val_id = [];
				$.each(valores, function(index,item) {
					id = $(item).attr("id");
					arreglo_val_id.push(id);
				});
				
				contenido.html('');
				contenido.addClass('loading');
                                var csrf = $("#csrf").text();
				var data = {
					"estado": est,
					"idsCat": arreglo_val_id,
                                        'csrf'   : csrf
				};
				
    			$.ajax({
        			'url' : '/gestor/categorias/cambio-estado',
                    'type' : 'POST',
                    'dataType' : 'html',
                    'data' : data,
                    'success' : function(msg) {
                    	$('.cambEstado').unbind();
                    	$('.adminAceptBtn').unbind();
                    	contenido.removeClass('loading');
                    	contenido.html('<div class="good alignC bold f16">' + msgs.mensajeOk.goodActualizacion + '</div>');
                    	categoriajs.cargarModalcambioEstado('.cambEstado');
                    	categoriajs.cargarModalCambioEstadoPer('.cambEstadoOne');
                    	$("#searchbt").click();
                    	setTimeout(
                            function(){
                                $('#adminGesCategModal .closeWM').click();
                            },
                            1500
                        );
                    }
        		});
			});
		},
        cancelar: function (a){
            $(a).bind('click', function (){
            	$('.cambEstado').unbind();
            	categoriajs.cargarModalcambioEstado('.cambEstado');
                $('#adminGesCategModal .closeWM').click();
            });
            
        },
        closeWindowTime: function (){
            setTimeout(
                function(){
                    $('#loginModalW .closeWM').click();
                },
                1500
            );
        },
        closeWindow: function (a){
            $(a).bind('click', function (){
                $('#loginModalW .closeWM').click();
            });
            
        }
    };
    
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
    
    $('.cambEstado').unbind();
    $('.editCat').unbind();
    categoriajs.cargarModal('.editCat');
    categoriajs.cargarModalcambioEstado('.cambEstado');
    categoriajs.cargarModalCambioEstadoPer('.cambEstadoOne');
};