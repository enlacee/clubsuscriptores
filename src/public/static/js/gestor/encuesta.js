/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
   // Class
   var Encuesta = function(){
       this.ajaxEncuestas = function(){
            var pager = $('.encuestpage'),
                btnSEnc = $('#btnSearchEnc');
            
            btnSEnc.bind('click',function(){encuestpagina(1);});
            pager.live('click',function(){
                pagina = $(this).attr('rel');
                encuestpagina(pagina);
            });
        };
        function encuestpagina(page){
            var frmFiltro = $("#frmEncuestas"),
                cntMsj = $('#idContentAjaxEnct');

            cntMsj.html('').css({
                'height':'300px',
                'width':'100%'
            }).addClass('loading');

            $.ajax({
                async : true,
                url : '/gestor/encuestas/lista-encuestas/page/'+page,
                type : 'POST',
                dataType : 'html',
                contentType: 'application/x-www-form-urlencoded',
                data : frmFiltro.serialize(),
                success : function(res){
                    cntMsj.removeAttr('style')
                          .removeClass('loading good')
                          .html(res);
                },
                error : function(res){

                }
            });
        }
        this.viewEncuesta = function(){
    		var A = $('.encuestaViewG');
    		A.live('click', function(e){
    			e.preventDefault();			
    			var cntMsj = $('#content-winViewEncuestaG'),
                    id = $(this).attr('rel');
                cntMsj.text('')
                      .removeClass('hide bad good')
                      .css({'height':'300px'})
                      .addClass('loading')
                      .addClass("center");

                $.ajax({
                    async : true,
                    url : '/gestor/encuestas/ver-encuesta/',
                    type : 'POST',
                    dataType : 'html',
                    contentType: 'application/x-www-form-urlencoded',
                    data : 'id='+id,
                    success : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    },
                    error : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    }
                });						
    		});		
    	};
        
        this.mostrarInsertarEncuesta = function(x) {
                
        }
        
        this.operaEncuesta = function(){
    		var A = $('.encuestaOperaG'),
                cerrarA = $('#idCerrarEG'),
                btnCerrar = $('#btnCloseEG');
            
            btnCerrar.live('click',function(){
                cerrarA.trigger('click');
            });
    		A.live('click', function(e){
    			e.preventDefault();
    			var cntMsj = $('#content-winViewEncuestaG'),
                        id = $(this).attr('rel');
                        cntMsj.text('')
                              .removeClass('hide bad good')
                              .css({'height':'300px'})
                              .addClass('loading')
                              .addClass("center");

                        $.ajax({
                            async : true,
                            url : '/gestor/encuestas/opera-encuesta/',
                            type : 'GET',
                            dataType : 'html',
                            contentType: 'application/x-www-form-urlencoded',
                            data : 'id='+id,
                            success : function(res){
                                cntMsj.removeAttr('style').removeClass('loading good').html(res);
                            },
                            error : function(res){
                                cntMsj.removeAttr('style').removeClass('loading good').html(res);
                            }
                        });					
    		});		
    	};
        
        
        
        function validForm(publica){
            //e.preventDefault();
            var cntMsj2 = $('#content-winViewEncuestaG'),
                formEncG = $('#frmEncuestasEG'),
                cerrarA = $('#idCerrarEG'),
                activoAu = $('#activo');
            var method = 'POST';

            var csrf = $('#csrf2');
            var csrfOld = $('input[name=csrf]');
            csrfOld.val(csrf.text());
            
            cntMsj2.text('').removeClass('hide bad good')
                  .css({'height':'200px'}).addClass('loading center');
            
            if(activoAu.is(':checked')){
                publica = '/publica/yes';
            }
            $.ajax({
                async : true,
                url : '/gestor/encuestas/opera-encuesta'+publica,
                type : method,
                dataType : 'html',
                contentType: 'application/x-www-form-urlencoded',
                data : formEncG.serialize(),
                success : function(res){
                    cntMsj2.removeAttr('style').removeClass('loading').html(res);
                    var op = $('#divContentEncG').attr('rel');
                    if(op=='1'){
                        if(publica==''){
                            cntMsj2.addClass('good');
                            setTimeout(function(){
                                cntMsj2.removeClass('good');
                                cerrarA.trigger('click');
                            },2000);
                            encuestpagina(1);
                        }
                    }else{
                        //cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    }
                },
                error : function(res){
                    cntMsj.removeAttr('style').removeClass('loading good').html(res);
                }
            });
        }
        this.grabaEncuesta = function(){
            var btnSave = $('#btnGuardarEG'),
                btnAcept = $('#idAceptarOkEG'),
    			btnCancel = $('#idCancelarEG'),
                btnPublica = $('#btnPublicarEG');
            
            btnPublica.live('click',function(){
                validForm('/publica/yes');
            });
            btnSave.live('click',function(){
                validForm('');
            });
            btnAcept.live('click',function(e){
                saveEncEG(e,'/publica/yes');
            });
            btnCancel.live('click',function(){
                $('#winConfirma').slideUp('fast',function(){
                    $('#divContentEncG').slideDown('fast');
                });
                return false;
            });
            
        };
        function saveEncEG(e, publica){
            var frmEncuestaG = $('#frmEncuestasEG'),
                cntMsj3 = $('#content-winViewEncuestaG'),
                cerrarA = $('#idCerrarEG');
            
            var csrf = $('#csrf2');
            var csrfOld = $('input[name=csrf]');
            csrfOld.val(csrf.text());
            cntMsj3.text('').removeClass('hide bad good')
                  .css({'height':'200px'}).addClass('loading center');
            
            e.preventDefault();
            var opResp = true;
            if(opResp){
                $.ajax({
                    url : '/gestor/encuestas/grabar'+publica,
                    type : 'POST',
                    dataType : 'html',//'JSON',
                    data : frmEncuestaG.serialize(),
                    success : function(res){
                        //if(res.success){
                            /*lblMsjEG.html(res.mensaje)
                            .addClass('good')
                            .show('fast').delay(600).hide('fast',function(){
                                winBoxResult.fadeOut('slow',function(){
                                    cerrarA.trigger('click');
                                });
                            });*/
                            cntMsj3.html(res).removeAttr('style').addClass('good')
                            .removeClass('loading');
                            setTimeout(function(){
                                cntMsj3.removeClass('good');
                                cerrarA.trigger('click');
                            },2000);
                            encuestpagina(1);
                        /*}else{
                            lblMsjEG.html(res.mensaje).addClass('bad')
                            .show('fast').delay(1800).hide('slow');
                        }*/
                    },
                    error : function(res){
                        cntMsj3.html('Error').addClass('bad');
                    }
                });
            }
        }
        this.vistaPreviaEnc = function(){
    		var A = $('.encuestaViewPrevG');
    		A.live('click', function(e){
    			e.preventDefault();			
    			var cntMsj = $('#content-winViewPrevEncG'),
                    id = $(this).attr('rel');
                cntMsj.text('')
                      .removeClass('hide bad good')
                      .css({'height':'100px'})
                      .addClass('loading')
                      .addClass("center");

                $.ajax({
                    async : true,
                    url : '/gestor/encuestas/vista-previa/',
                    type : 'POST',
                    dataType : 'html',
                    contentType: 'application/x-www-form-urlencoded',
                    data : 'id='+id,
                    success : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    },
                    error : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    }
                });						
    		});		
    	};
        
        this.darBajaEnc = function(){
            var btnAcepBaja = $('#aceptBajaEncBtn'),
                aLinkBaja = $('.encuestaDarBajaG'),
                idEncBaja = '';
            aLinkBaja.live('click', function(){
                idEncBaja = $(this).attr('rel');
            });
            btnAcepBaja.bind('click', function(){
                
                var cntMsj3 = $('#divMessageBajaE'),
                    cerrarA = $('#aCloseBajaE');

                /*cntMsj3.text('').removeClass('hide bad good')
                      .addClass('loading center');*/
                $.ajax({
                    url : '/gestor/encuestas/dar-baja-encuesta',
                    type : 'POST',
                    dataType : 'JSON',
                    data : { idEnc : idEncBaja },
                    success : function(res){
                        /*cntMsj3.html(res.mensaje).addClass('good')
                        .removeClass('loading');*/
                        if(res.success){
                            /*setTimeout(function(){
                                //cntMsj3.removeClass('good');
                            },500);*/
                            cerrarA.trigger('click');
                            encuestpagina(1);
                        }
                    },
                    error : function(res){
                        cntMsj3.html('Error').addClass('bad');
                    }
                });
            });
        };
   };
   
   // init
   var objenc = new Encuesta();
   objenc.ajaxEncuestas();
   objenc.viewEncuesta();
   objenc.operaEncuesta();
   objenc.grabaEncuesta();
   objenc.vistaPreviaEnc();
   objenc.darBajaEnc();
   
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
});