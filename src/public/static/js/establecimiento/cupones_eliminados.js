/* 
 */

$(function(){
   // Class
   var CuponEliminado = function(){
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
        this.closeWindowTime =function (){
            setTimeout(
                function(){
                    $('#winRedimirCupon .closeWM').click();
                },
                1500
                );
        };
        function consumopagina(page,CuponEliminado){
            var frmFiltroConsumo = $("#frmRptConsumos"),
                cntMsj = $('#idContentAjaxConsumos');

            cntMsj.html('').css({
                'height':'300px',
                'width' :'100%'
            }).addClass('loading');
            
            $.ajax({
                async : true,
                url : '/establecimiento/cupon/filtro-list-consumos/page/'+page,
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
   var objreport = new CuponEliminado();
   objreport.ajaxConsumos();
   objreport.load();
   
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/establecimiento/error/' + jqXHR.status;
    });
});