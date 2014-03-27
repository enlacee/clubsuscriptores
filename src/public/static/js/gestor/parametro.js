/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
   // Class
   var Parametro = function(){
        this.ajaxParametro = function(){
            var pager = $('.parampage'),
                cboTipoParam = $('#tipo_parametro');
            
            cboTipoParam.bind('change',function(){parameterPagina(1);});
            pager.live('click',function(){
                var pagina = $(this).attr('rel'),
                    nroPagPG = $('#nropagePG');
                nroPagPG.val(pagina);
                parameterPagina(pagina);
                return false;
            });
        };
        function parameterPagina(page){
            var cboTipo = $("#tipo_parametro"),
                cntMsj = $('#idContentAjaxParam');

            cntMsj.html('').css({
                'height':'300px',
                'width':'100%'
            }).addClass('loading');

            $.ajax({
                async : true,
                url : '/gestor/configuracion/lista-parametros/page/'+page,
                type : 'POST',
                dataType : 'html',
                contentType: 'application/x-www-form-urlencoded',
                data : 'tipo_parametro=' + cboTipo.val(),
                success : function(res){
                    cntMsj.removeAttr('style')
                          .removeClass('loading good')
                          .html(res);
                },
                error : function(res){
                }
            });
        }
        this.editParameter = function(){
    		var A = $('.editParameterG'),
                aCloseParam = $('#idCloseEditParamG'),
                btnQuitP = $('#adminGquit');
            
            btnQuitP.live('click', function(){
                aCloseParam.trigger('click');
            });
    		A.live('click', function(e){
    			e.preventDefault();			
    			var cntMsj = $('#content-divEditParameter'),
                    id = $(this).attr('rel');
                cntMsj.text('')
                      .removeClass('hide bad good')
                      .css({'height':'200px'})
                      .addClass('loading')
                      .addClass("center");

                $.ajax({
                    async : true,
                    url : '/gestor/configuracion/editar-parametro/',
                    type : 'POST',
                    dataType : 'html',
                    contentType: 'application/x-www-form-urlencoded',
                    data : 'id='+id,
                    success : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                        jq.pasteMaxlength('#valor');
                    },
                    error : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    }
                });						
    		});
            var btnSaveP = $('#adminGSave'),
                nroPagPG = $('#nropagePG');
            btnSaveP.live('click', function(e){
    			e.preventDefault();			
    			var cntMsj = $('#content-divEditParameter'),
                    frmParamG = $('#frmParamtroG');
                cntMsj.text('')
                      .removeClass('hide bad good')
                      .css({'height':'200px'})
                      .addClass('loading')
                      .addClass("center");

                $.ajax({
                    async : true,
                    url : '/gestor/configuracion/opera/case/edit',
                    type : 'POST',
                    dataType : 'html',
                    contentType: 'application/x-www-form-urlencoded',
                    data : frmParamG.serialize(),
                    success : function(res){
                        cntMsj.html(res).removeAttr('style').addClass('good')
                        .removeClass('loading');
                        setTimeout(function(){
                            cntMsj.removeClass('good');
                            aCloseParam.trigger('click');
                        },2000);
                        parameterPagina(nroPagPG.val());
                        //cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    },
                    error : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    }
                });						
    		});
    	};
   };
   
   // init
   var objp = new Parametro();
   objp.editParameter();
   objp.ajaxParametro();
   
   $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
       if(jqXHR.status == 401)
           window.location.href = '/gestor/error/' + jqXHR.status;
   });
});