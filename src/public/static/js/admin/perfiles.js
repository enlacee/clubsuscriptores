/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
   // Class
   var Perfiles = function(){
       this.ajaxPerfiles = function(){
            var btnSPerf = $('#btnSearchPerfil');
            
            btnSPerf.bind('click',function(){perfilpagina();});
        };
        function perfilpagina(){
            var frmFiltro = $("#frmPerfiles"),
                cntMsj = $('#idContentAjaxPerf');

            cntMsj.html('').css({
                'height':'300px',
                'width':'100%'
            }).addClass('loading');

            $.ajax({
                async : true,
                url : '/admin/perfiles/lista-perfiles/',
                type : 'POST',
                dataType : 'html',
                contentType: 'application/x-www-form-urlencoded',
                data : frmFiltro.serialize(),
                success : function(res){
                    cntMsj.removeAttr('style')
                          .removeClass('loading good')
                          .html(res);
                },
                error : function(res){ }
            });
        }
        this.operaPerfil = function(){
    		var A = $('.perfilOperaA'),
                cerrarA = $('#idCerrarPA'),
                btnCerrar = $('#btnClosePA'),
                cbomodulo = $('#modulo'),
                winPerf = $('#winAdminPerfil');
            
            btnCerrar.live('click',function(){
                cerrarA.trigger('click');
            });
            cbomodulo.live('change',function(e){
                var cboModule = $(this),
                    winHeight = $('#content-winAdminPerfil').height();
                
                //console.log($('#modulo option:selected').text());return;
                //console.log(cboModule.find('option:selected').text());return;
                //console.log(cboModule.find('option:eq(1)').text());return;
                //console.log(cboModule.find('option').eq(2).text());return;
                
                if(cboModule.val()!=0 || cboModule.val()!=''){
                    //, height: (winHeight+40)+'px'
                    //winPerf.animate({width:'780px'},800);
                }
                var cntMsj = $('#divContentOptModule'),
                    nameModule = $('#spNameModule');
                    
                    cntMsj.text('')
                      .removeClass('hide bad good')
                      .css({'height':'150px'})
                      .addClass('loading')
                      .addClass("center");
                      
                $.ajax({
                    async : true,
                    url : '/admin/perfiles/opciones-modulo/',
                    type : 'POST',
                    dataType : 'html',
                    contentType: 'application/x-www-form-urlencoded',
                    data : 'idmodulo='+cboModule.val(),
                    success : function(res){
                        if(cboModule.val()!=0 || cboModule.val()!=''){
                            nameModule.html(cboModule.find('option:selected').text());
                        }else{ nameModule.html(''); }
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    },
                    error : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good').html(res);
                    }
                });
            });
    		A.live('click', function(e){
    			e.preventDefault();
    			var cntMsj = $('#content-winAdminPerfil'),
                        id = $(this).attr('rel');
                        cntMsj.text('')
                              .removeClass('hide bad good')
                              .css({'height':'300px'})
                              .addClass('loading')
                              .addClass("center");
                        
                        $.ajax({
                            async : true,
                            url : '/admin/perfiles/opera-perfil/',
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
        this.savePerfil = function(){
            var btSave = $('#btnSavePA'),
                cerrarA = $('#idCerrarPA');
                
            btSave.live('click', function(e){
    			e.preventDefault();
                var csrf = $('#csrf2'),
                    csrfOld = $('input[name=csrf]');
                csrfOld.val(csrf.text());
                
    			var cntMsj = $('#content-winAdminPerfil'),
                    fmrDataPA = $('#formPerfilA').serialize();
                
                cntMsj.text('')
                      .removeClass('hide bad good')
                      .css({'height':'300px'})
                      .addClass('loading')
                      .addClass("center");
                
                $.ajax({
                    async : true,
                    url : '/admin/perfiles/opera-perfil/',
                    type : 'POST',
                    dataType : 'html',
                    contentType: 'application/x-www-form-urlencoded',
                    data : fmrDataPA,
                    success : function(res){
                        cntMsj.removeAttr('style').removeClass('loading good')
                              .html(res);
                        var flagP = $('#divRContentPerfilAd').attr('rel');
                        if(flagP=='1'){
                            cntMsj.addClass('good');
                            setTimeout(function(){
                                cntMsj.removeClass('good');
                                cerrarA.trigger('click');
                            },2000);
                            perfilpagina();
                        }
                    },
                    error : function(res){
                        cntMsj.removeAttr('style').html(res);
                    }
                });
    		});
        };
        this.deletePerf = function(){
            var btnOkPA = $('#idAceptarOkPA'),
                linkDelete = $('.linkDeletePA'),
                cerrarA = $('#idCerrarPA');
                
            linkDelete.live('click', function(){
                $('#txtidperf').val($(this).attr('rel'));
            });
            btnOkPA.bind('click', function(){
                var cntMsj = $('#msjConfirmDelPerfAd'),
                    contentDelPA = $('#content-modalConfirmDeletePA'),
                    idPerfil = $('#content-modalConfirmDeletePA #txtidperf').val();
                cntMsj.text('')
                      .removeClass('hide bad good')
                      .css({'height':'150px'})
                      .addClass('loading')
                      .addClass("center");
                contentDelPA.addClass('hide');
                $.ajax({
                    'url' : '/admin/perfiles/delete/',
                    'type' : 'POST',
                    'dataType' : 'html',
                    'data' : { 'id' : idPerfil },
                    'success' : function(data) {
                        cntMsj.removeAttr('style').removeClass('loading').addClass('good')
                              .html(data);
                        setTimeout(function(){
                            cerrarA.trigger('click');
                            contentDelPA.removeClass('hide');
                            cntMsj.html('');
                        },2000);
                        perfilpagina();
                    },
                    'error' : function(data) {
                    }
                });
            });
        };
   };
   
   // init
   var objperf = new Perfiles();
   objperf.ajaxPerfiles();
   objperf.operaPerfil();
   objperf.savePerfil();
   objperf.deletePerf();
   
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/admin/error/' + jqXHR.status;
    });
});