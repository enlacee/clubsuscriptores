$(function(){
    var today = (urls.fDayCurrent<10? ("0" + urls.fDayCurrent):urls.fDayCurrent)+"/"+(urls.fMonthCurrent)+"/"+urls.fYearCurrent;
    var msg = {
        fechaini : {
            bad : 'Seleccione Fecha Inicio.',
            good : 'OK!'
        },
        fechafin : {
            bad : 'Seleccione Fecha Fin.',
            good : 'OK!'
        }
    };
    var url = {
        filtros : '/gestor/conciliacion/filtros-index',
        editar : '/gestor/conciliacion/editar-cupon',
        grabar : '/gestor/conciliacion/grabar-cupon',
        conciliar : '/gestor/conciliacion/conciliar-cupon'
    };
    var Conciliacion = {
       validarFechas : function(a, b) {
           if ($(a).val()=="") {
               $(a).parents(".adminConciSearch").find(".response").removeClass("ready").
                   addClass("bad").html(msg.fechaini.bad);
               return false;
           } else {
               $(a).parents(".adminConciSearch").find(".response").removeClass("bad").addClass("good").
                   html(msg.fechaini.good);
           }

           if ($(b).val()=="") {
               $(b).parents(".adminConciSearch").find(".response").removeClass("ready").
                   addClass("bad").html(msg.fechafin.bad);
               return false;
           } else {
               $(b).parents(".adminConciSearch").find(".response").removeClass("bad").addClass("good").
               html(msg.fechafin.good);
           }

           return true;
       },
       btnBuscar : function(a, b, c, d) {
           $(a).bind("click", function(e){
               e.preventDefault();
               if ($(b).val()!="" && $(c).val()!="") {
                   $(d).submit();
               } else {
                   Conciliacion.validarFechas(b, c);
               }
           });
       },
       cargaDataFiltros: function(pagina, conciliar, orden, campo) {
            var tabla =  $("#mictBenContent");
            var ide   =  tabla.attr("idestablecimiento");
            var fi    =  tabla.attr("fechaini");
            var ff    =  tabla.attr("fechafin");
            var page  =  pagina==""?tabla.attr("page"):pagina;
            var p     =  $("#promociones").val();
            var e     =  $("#estado").val();
            var v     =  $("#voucher").val();
            var c     =  conciliar==""?"":conciliar;
            var ord   =  orden==""?tabla.attr("ord"):orden;
            var cam   =  campo==""?tabla.attr("col"):campo;

            if (ord==undefined)  ord="";
            if (cam==undefined)  cam="";
            if (page==undefined) page="";

            $("#result").addClass("loading").html("");
            $.ajax({
                url : url.filtros,
                type : 'POST',
                dataType : 'html',
                data : {
                    'idestablecimiento' : ide,
                    'fechaini'          : fi,
                    'fechafin'          : ff,
                    'page'              : page,
                    'promociones'       : p,
                    'estado'            : e,
                    'voucher'           : v,
                    'conciliar'         : c,
                    'ord'               : ord,
                    'col'               : cam
                },
                success : function(res){
                    $("#result").removeClass("loading").html(res);
                }
            });
       },
       conciliarCupon: function(conciliar) {
            var c     =  conciliar==""?"":conciliar;
            var csrf  = $('#csrf').text();
            var tabla =  $("#mictBenContent");
            var page = tabla.attr("page");
            $("#result").addClass("loading").html("");
            $.ajax({
                url : url.conciliar,
                type : 'POST',
                dataType : 'html',
                data : {
                    'conciliar'         : c,
                    'csrf'              : csrf
                },
                success : function(res){
                    Conciliacion.cargaDataFiltros(page);
                    //$("#result").removeClass("loading");
                }
            });
       },
       filtrosConciliacion: function(a) {
            var actual = $(a);
            actual.bind("change", function(){
                Conciliacion.cargaDataFiltros();
            });
       },
       txtvoucher : function(a) {
           $(a).bind("keypress", function(e){
               var code = (e.keyCode ? e.keyCode : e.which);
               if(code == 13) {
                  Conciliacion.cargaDataFiltros();
               }
           });
       },
       paginado : function(a) {
            $(a).live("click", function(){
                var page = $(this).attr("rel");
                Conciliacion.cargaDataFiltros(page);
            });
       },
       calendario: function(a) {
           $(a).bind("click", function(){
                $(this).parent().find("input[type='text']").focus();
           });
       },
       conciliar : function(a) {
          $(a).live("change", function(){
               var idcupon = $(this).attr("id");
               Conciliacion.conciliarCupon(idcupon);
          });
       },
       editarCupon : function(a) {
           $(a).live("click", function(){
               var content = $("#iEditConciliacion");
               content.addClass("loading").html("");
               var idcupon = $(this).attr("rel");
                $.ajax({
                   url : url.editar,
                   type : 'POST',
                   dataType : 'html',
                   data : {
                      "id" : idcupon
                   },
                   success: function(result) {
                       content.removeClass("loading").html(result);
                   }
                });
           });
       },
       cerrarEditar : function(a) {
           $(a).live("click", function(){
               $(".closeWM").click();
           });
       },
       grabarCupon : function(a) {
           $(a).live("click", function(){
               var content = $("#iEditConciliacion");
               var idcupon = $(this).attr("idcupon");
               var voucher = $("#vouchercupon").val();
               var comentario = $("#comentariocupon").val();
               content.addClass("loading").html("");
               $.ajax({
                   url: url.grabar,
                   type: 'post',
                   dataType: 'html',
                   data: {
                       "id" : idcupon,
                       "voucher" : voucher,
                       "comentario" : comentario,
                       "csrf" : $('#csrf').text()
                   },
                   success: function(result){
                       $(".closeWM").click();
                       content.removeClass("loading").html("");
                       Conciliacion.cargaDataFiltros("");
                   }
               });
           });
       },
       enterCamposEditar : function(a) {
           $(a).live("keypress", function(e){
               var code = (e.keyCode ? e.keyCode : e.which);
               if(code == 13) {
                  e.preventDefault();
                  //$(a).not(this).focus();
               }
           });
       },
       ordenar : function(a) {
            $(a).live("click", function(){
                var orden = "DESC";
                var campo = $(this).attr("campo");
                var clase = $(this).hasClass("esblueArrow");
                if (clase) orden="DESC"; else orden="ASC";
                Conciliacion.cargaDataFiltros("","", orden, campo);
            });
       },
       start: function(a, b, c, d, e, f, g, h, i, j, k, l, m, n) {
          var vigencia = $("#fechaini, #fechafin").datepicker({
                changeMonth: true,
                changeYear: true,
                showMonthAfterYear: false,
                onSelect: function( selectedDate ) {
                    var option = this.id == "fechaini" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                    vigencia.not( this ).datepicker( "option", option, date );
                   Conciliacion.validarFechas('#fechaini', '#fechafin');
                }
            });

          Conciliacion.btnBuscar(a,b,c,d);
          Conciliacion.filtrosConciliacion(e);
          Conciliacion.txtvoucher(f);
          Conciliacion.paginado(g);
          Conciliacion.calendario(h);
          Conciliacion.conciliar(i);
          Conciliacion.editarCupon(j);
          Conciliacion.cerrarEditar(k);
          Conciliacion.grabarCupon(l);
          Conciliacion.enterCamposEditar(m);
          Conciliacion.ordenar(n);
       }
    };
    Conciliacion.start(
            "#btnSearchConsume",
            "#fechaini",
            "#fechafin",
            "#formBox",
            ".filtros",
            "#voucher",
            ".redimidopage",
            ".ui-datepicker-trigger",
            ".conciliar",
            ".editarconciliacion",
            "#adminGquit",
            "#adminGSave",
            ".campoeditar",
            ".ordenarFecha"
    );
});
