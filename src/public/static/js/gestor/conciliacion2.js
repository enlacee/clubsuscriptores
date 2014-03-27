/* 
 */

$(function(){
   // Class
   var ReportConcilia = function(){
       this.load = function(){
            var dateDesde = $("#fecha_consumo_desde"),
                dateHasta = $("#fecha_consumo_hasta"),
                routeImage = $("#txturlimageCG");
            
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
            resultPromo();
       };
       this.ajaxRedimidosC = function(){
            var pager = $('.redimidopage'),
                btnSConciliaR = $('#btnSearchConcilia'),
                aReportC = $('#idReporteConcilia'),
                cboPromo = $('#promocion');
            
            btnSConciliaR.bind('click',function(){
                resultPromo();
            });
            cboPromo.bind('change',function(){ redimidospagina(1); });
            aReportC.bind('click',function(){redimidospagina(1);});
            pager.live('click',function(){
                pagina = $(this).attr('rel');
                redimidospagina(pagina);
            });
        };
        function redimidospagina(page){
            var frmFiltroConsumo = $("#frmRptRedimidosC"),
                cntMsj = $('#idContentAjaxRedimidosC'),
                divMntoC = $('#divMontoConcilia'),
                divShowF = $('#divMuestraFechas');

            cntMsj.html('').css({
                'height':'300px',
                'width' :'100%'
            }).addClass('loading');

            $.ajax({
                async : true,
                url : '/gestor/conciliacion/lista-redimidos/page/'+page,
                type : 'POST',
                dataType : 'html',
                contentType: 'application/x-www-form-urlencoded',
                data : frmFiltroConsumo.serialize(),
                success : function(res){
                    cntMsj.removeAttr('style')
                          .removeClass('loading good')
                          .html(res);
                    divMntoC.html('S/. '+formatNumber($('#txtMntoTotConcilia').val(),''));
                    divShowF.html($('#txtFechaCadsConcilia').val());
                },
                error : function(res){ }
            });
        }
        function resultPromo(){
            var feciniC = $('#fecha_consumo_desde').val(),
                fecfinC = $('#fecha_consumo_hasta').val(),
                estabId = $('#establecimiento').val(),
                cbopromo = $('#promocion'),
                txtNameEst = $('#spNameEstG'),
                txtViewFec = $('#divMuestraFechas'),
                txtMntoCon = $('#divMontoConcilia');
            $.ajax({
                url : '/gestor/conciliacion/json/case/getDatos',
                type : 'POST',
                dataType : 'JSON',
                data : {
                    'fecha_consumo_ini' : feciniC,
                    'fecha_consumo_fin' : fecfinC,
                    'establecimientoId' : estabId
                },
                success : function(res){
                    var cadCbo = '<option value="">.:: Todas ::.</option>';
                    $.each(res.promos,function(i, item){
                        cadCbo += '<option value="'+item.id+'">'+item.titulo+'</option>';
                    });
                    cbopromo.html(cadCbo);
                    txtNameEst.html(res.nombre);
                    txtViewFec.html(res.cad_fecha);
                    txtMntoCon.html('S/. '+formatNumber(res.total_conciliado,''));
                    redimidospagina(1);
                },
                error : function(res){ }
            });
        }
        function formatNumber(num,prefix)  
        {  
            num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2)  
            prefix = prefix || '';  
            num += '';
            var splitStr = num.split('.');  
            var splitLeft = splitStr[0];  
            var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';  
            splitRight = splitRight + '00';  
            splitRight = splitRight.substr(0,3);  
            var regx = /(\d+)(\d{3})/;  
            while (regx.test(splitLeft)) {  
                splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');  
            }
            return prefix + splitLeft + splitRight;  
        }
   };
   
   // init
   var objreport = new ReportConcilia();
   objreport.ajaxRedimidosC();
   objreport.load();
   
    $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/gestor/error/' + jqXHR.status;
    });
});