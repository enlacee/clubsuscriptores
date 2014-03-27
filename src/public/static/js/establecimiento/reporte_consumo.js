/* 
 */

$(function(){
   // Class
   var ReportConsumo = function(){
       this.load = function(){
            var tthis=this;
            
            var dateDesde = $("#fecha_emision_desde"),
                dateHasta = $("#fecha_emision_hasta"),
                routeImage = $("#txturlimageC");
            
            $.datepicker.setDefaults(
                $.extend({showMonthAfterYear: false}, $.datepicker.regional['es'])
            );
            dateDesde.datepicker({
                showOn: 'button',
                buttonImage: routeImage.val(), 
                buttonImageOnly: true,
                buttonText: 'Seleccionar Fecha',
                dateFormat: 'dd/mm/yy',
                maxDate: new Date(),
                changeMonth: true,
                changeYear: true
                //,onSelect: function(dateText, inst) { consumopagina(1); }
            });
            dateHasta.datepicker({
                showOn: 'button',
                buttonImage: routeImage.val(), 
                buttonImageOnly: true,
                buttonText: 'Seleccionar Fecha',
                dateFormat: 'dd/mm/yy',
                //minDate: -20, 
                maxDate: new Date(),//'+1M +10D',
                changeMonth: true,
                changeYear: true
                //,onSelect: function(dateText, inst) { consumopagina(1); }
            });
            //carga por defecto de cupones redimidos
            $('select[name=estado]').val('redimido');
            consumopagina(1,this);
            
            //-->>
            this.modalEliminarCupon(".eliminarListaCupon");
            
            var btnAceptaBaja = $('#aceptEliminarCuponBtn'),
                btnCloseBaja = $('#aCloseEliminarB'),
                aCloseBenef = $('.innerWin .closeWM');
            
            btnCloseBaja.live('click', function(){
                aCloseBenef.trigger('click');
            });

            btnAceptaBaja.live('click', function(){
                var t = $(this);

                var cntMsj3 = $('#content-eliminarCupon'),
                    idCup = t.attr('rel');
                    var csrf = $("#csrf").text();

                cntMsj3.text('').removeClass('hide bad good')
                    .addClass('loading center');

                $.ajax({
                    url : '/establecimiento/cupon/eliminar',
                    type : 'POST',
                    dataType : 'html',
                    data : {idCup : idCup, csrf:csrf},
                    success : function(msg){
                        if ($(msg).attr('data-error') == '1') {
                            cntMsj3.html($(msg).attr('msj')).addClass('good').removeClass('loading');
                            consumopagina(1,tthis);
                            setTimeout(function(){
                                cntMsj3.removeClass('good');
                                aCloseBenef.trigger('click');
                            },1000);
                        } else {
                            cntMsj3.html('<div class="bad"> Error al eliminar cupon. </div>')
                                .addClass('bad').removeClass('loading');
                            objreport.closeWindowTime();
                        }
                    },
                    error : function(res){
                        cntMsj3.html('Error').addClass('bad').removeClass('loading');
                    }
                });
            });
       };
       this.ajaxConsumos = function(){
            var pager = $('.consumepage'),
                btnSConsume = $('#btnSearchConsume'),
                aReport = $('#idReporteConsE'),
                txtnombrePromo = $('#nombre_promo');
            
            btnSConsume.bind('click',function(){consumopagina(1,this);});
            aReport.bind('click',function(){consumopagina(1,this);});
            pager.live('click',function(){
                pagina = $(this).attr('rel');
                consumopagina(pagina,this);
            });
            txtnombrePromo.bind('keyup',function(e){
                e.preventDefault();
                var key = e.keyCode || e.charCode || e.which || window.e ;
                if (key === 13) {
                    consumopagina(1,this);
                }                
            });
        };
        this.modalEliminarCupon = function(a) {
            $(a).live('click', function(e) {
                e.preventDefault();
                var t = $(this);
                var tipo = "";
                var idCup = t.attr('rel');
                var csrf = $("#csrf").text();
    			
                if(t.hasClass('eliminarListaCupon')) {
                    tipo = 'GET';
                } else {
                    tipo = 'POST';
                }
                var objreport = new ReportConsumo();
                $("#winRedimirCupon").attr("style","display: block;margin:-116px 0 0 -225px;");
                var contenido = $("#content-winRedimirCupon");
                contenido.addClass("loading");
                contenido.html("");
                $.ajax({
                    'url' : '/establecimiento/cupon/eliminar',
                    'type' : tipo,
                    'dataType' : 'html',
                    'data' : {
                        'idCup' : idCup,
                        'csrf' : csrf
                    },
                    'success' : function(msg) {
                        contenido.removeClass("loading");
                        if ( $(msg).attr('data-error') == '-1') {
                            contenido.html('<div class="bad"> Error al eliminar cupon. </div>');
                            objreport.closeWindowTime();
                        }
                        else if ($(msg).attr('data-error') == '0') {
                            contenido.html(msg);
                        }
                        //$('.admincrearcontraseniaBtn').unbind();
                        //objreport.modalCamClave('.admincrearcontraseniaBtn');
                    },
                    'error' : function(msg) {
                    }
                });
            });
        };
        this.closeWindowTime =function (){
            setTimeout(
                function(){
                    $('#winRedimirCupon .closeWM').click();
                },
                1500
                );
        };
        function consumopagina(page,ReportConsumo){
            var frmFiltroConsumo = $("#frmRptConsumos"),
                cntMsj = $('#idContentAjaxConsumos');

            cntMsj.html('').css({
                'height':'300px',
                'width' :'100%'
            }).addClass('loading');
            
            $.ajax({
                async : true,
                url : '/establecimiento/reporte-consumo/filtro-list-consumos/page/'+page,
                type : 'POST',
                dataType : 'html',
                contentType: 'application/x-www-form-urlencoded',
                data : frmFiltroConsumo.serialize(),
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
   
   // init
   var objreport = new ReportConsumo();
   objreport.ajaxConsumos();
   objreport.load();
   
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/establecimiento/error/' + jqXHR.status;
    });
});