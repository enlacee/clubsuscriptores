/* 
Main Establecimiento
 */

$(function(){
   // Class
   var Establecimiento = function(){
        this.loginVM = function(){
                var linkOlvidoC = $('#linkLoginMLS');
                if(linkOlvidoC.size()>0){
                        var cntPass = $('#contentForgotMLS'),
                        cntLog = $('#contentLoginMLS'),
                        linkPass = $('#backLogMLS');
                        linkOlvidoC.bind('click', function(e){
                                e.preventDefault();										
                                cntLog.slideUp('fast',function(){
                                        cntPass.slideDown();		
                                });												
                        });
                        linkPass.bind('click', function(e){
                                e.preventDefault();										
                                cntPass.slideUp('fast',function(){
                                        cntLog.slideDown();		
                                });												
                        });					
                }
        }
       this.load = function(){
            
       };
       this.ajaxBeneficios = function(){
            var pager = $('.benefpage'),
                btnSBenef = $('#btnSearchBenef'),
                arrow = $('#idArrowOrder'),
                txtname = $('#descripcion'),
                frmFiltroBeneficio = $("#frmBeneficios");
            
            frmFiltroBeneficio.bind('submit',function(){ return false; });
            btnSBenef.bind('click',function(){benefpagina(1,'');});
            arrow.live('click',function(){
                var order = '';
                if($(this).hasClass('esblueArrow')){
                    order = 'asc';
                }else{
                    order = 'desc';
                }
                benefpagina(1,order);
                return false; 
            });
            pager.live('click',function(){
                pagina = $(this).attr('rel');
                benefpagina(pagina,'');
            });
            txtname.bind('keyup',function(e){
                e.preventDefault();
                var key = e.keyCode || e.charCode || e.which || window.e ;
                if(key === 13) { //presionamos enter
                    benefpagina(1,'');
                }
            });
            
            function benefpagina(page,order){
                var frmFiltroBeneficio = $("#frmBeneficios"),
                    cntMsj = $('#idContentAjaxBenef'),
                    campord = (order==''?'desc':order);

                cntMsj.html('').css({
                    'height':'300px',
                    'width':'100%'
                }).addClass('loading');

                $.ajax({
                    async : true,
                    url : '/establecimiento/beneficios-ofertados/filtro-list-beneficios/page/'+page+'/order/'+campord,
                    type : 'POST',
                    dataType : 'html',
                    contentType: 'application/x-www-form-urlencoded',
                    data : frmFiltroBeneficio.serialize(),
                    success : function(res){
                        cntMsj.removeAttr('style')
                              .removeClass('loading good')
                              .html(res);
                    },
                    error : function(res){

                    }
                });
            }
        };
   };
   
   // init
   var objest = new Establecimiento();
   objest.load();
   objest.ajaxBeneficios();
   objest.loginVM();
   
   $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/establecimiento/error/' + jqXHR.status;
   });
});