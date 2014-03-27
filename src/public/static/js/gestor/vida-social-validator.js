$(function(){
   // Class
    var today = (urls.fDayCurrent<10? ("0" + urls.fDayCurrent):urls.fDayCurrent)+"/"+(urls.fMonthCurrent<10? ("0" + urls.fMonthCurrent):urls.fMonthCurrent)+"/"+urls.fYearCurrent;
    var msg = {
        fecha_inicio_evento : {
            good : '¡OK!',
            bad : 'Ingresa Fecha de Inicio del Evento.',
            def : 'Debe ingresar una fecha del Evento.'
        },
        fecha_inicio : {
            good : '¡OK!',
            bad : 'Ingresa Fecha de Inicio del artículo.',
            def : 'Debe ingresar una fecha de inicio.'
        },
        fecha_fin : {
            good : '¡OK!',
            bad : 'Ingresa Fecha de Fin del artículo.',
            def : 'Debe ingresar una fecha de finalizacion.'
        },
        titulo : {
            good : '¡OK!',
            bad  : 'Ingresa un titulo para el artículo.'
        },
        contenido : {
            good : '¡OK!',
            bad  : 'Ingresa un contenido para el artículo.'
        }

    }
    var vars = {
        rs : '.response',
        okR : 'ready',
        sendFlag : 'sendN',
        loading : '<div class="loading"></div>'
    }
    
    //maxLenght textarea IE
    jq.pasteMaxlength('#contenido');

   var VidaSocialValidator = {
       validaCaja : function(x, y, bad, good) {
            var actual = x;
            if(actual.val().length==0) {
                y.find(".response").removeClass("good").removeClass("hide").addClass("bad").html(bad);
                actual.removeClass(vars.okR);
            } else {
                y.find(".response").removeClass("hide").removeClass("bad").addClass("good").html(good);
                actual.addClass(vars.okR);
            }
       },
       EmptyValid : function(x, bad, good){
            $(x).bind("blur keyup", function(){
                VidaSocialValidator.validaCaja($(this), $(this).parent(), bad, good);
            });
       },
       Guardar : function(){
            $(".adminSaveBtnbene").bind("click", function(e){
                e.preventDefault();
                VidaSocialValidator.validaCaja($("#titulo"), $("#titulo").parent(), msg.titulo.bad, msg.titulo.good);
                VidaSocialValidator.validaCaja($("#contenido"),$("#contenido").parent(), msg.contenido.bad, msg.contenido.good);
                var nimages = VidaSocialValidator.imagenesMinimo(6, false);
                if (!nimages) {
                    $("#preloader").addClass("bad response").html("Ingresa 6 Imágenes como mínimo.");
                } else {
                    nimages = VidaSocialValidator.imagenesMinimo(1, true);
                    if (!nimages) {
                       $("#preloader").addClass("bad response").html("Ingresa 1 Imágen como mínimo."); 
                    } else {
                       $("#preloader").removeClass("bad response").html(""); 
                    }
                }
                
                var tf3 = $('#fechainievent');
                var tf = $('#fechafin');
                var tf2 = $('#fechaini');
                var rf =  tf.parent().parent().find('.response');
                var rf3 =  tf3.parent().parent().find('.response');
                
                if($('#fechainievent').val() != '') {
                   rf3.removeClass('bad').removeClass('hide').addClass('good').html(msg.fecha_inicio_evento.good);
                   tf3.addClass(vars.okR);
                }else{
                    var mensaje = "Ingresa Fecha del Evento.";
                    rf3.removeClass('good').removeClass('hide').addClass('bad').html(mensaje);
                }
                
                if($('#fechaini').val() != '' && $('#fechafin').val() != '') {
                   rf.removeClass('bad').removeClass('hide').addClass('good').html(msg.fecha_fin.good);
                   tf.addClass(vars.okR);
                   tf2.addClass(vars.okR);
                } else {
                    var mensaje = "Ingresa Fechas para el artículo.";
                    if (tf.val()!="") mensaje = msg.fecha_inicio.bad;
                    if (tf2.val()!="") mensaje = msg.fecha_fin.bad;
                    rf.removeClass('good').removeClass('hide').addClass('bad').html(mensaje);
                }

                var c = $(".ready").length;
                if (c==5 && nimages) {
                    $("#frmVidaSocial").submit();
                }
            });
       },
       publicar : function(){
            $("#publicar").bind("change", function(){
                if($(this).is(':checked')) {
                    $("#fechaini").val(today);
                    //$("#fechafin").val(today);
                    $("#fechaini").addClass("ready");
                    //$("#fechafin").addClass("ready");
                } else {
                    $("#fechaini").val("");
                    //$("#fechafin").val("");
                    $("#fechaini").removeClass("ready");
                    //$("#fechafin").removeClass("ready");
                }
            });
       },
       imagenesMinimo : function(n, t){
            if ($("#principal").is(":checked") || t) {
                var a = $("#listaimagenes_content input").val().split("|");
                if(a.length<n || a=="" || a==undefined) {
                    return false
                }
                //console.log(a); return false;
            }
            return true;
       },
       start : function(){

           var fechaEvent = $("#fechainievent").datepicker({
                changeMonth: true,
                changeYear: true,
//                minDate: new Date(vsFechaMin.anio, vsFechaMin.mes - 1, vsFechaMin.dia),
                showMonthAfterYear: false,
                onSelect: function( selectedDate ) {
                    var tf3 = $('#fechainievent');
                    var rf3 =  tf3.parent().parent().find('.response');
                    if($('#fechainievent').val() != '') {
                        rf3.removeClass('bad').removeClass('hide').addClass('good').html(msg.fecha_inicio_evento.good);
                        tf3.addClass(vars.okR);
                    }else{
                        var mensaje = "Ingresa Fecha del Evento.";
                        rf3.removeClass('good').removeClass('hide').addClass('bad').html(mensaje);
                    }
                }
            });
            
           var fechas = $("#fechaini, #fechafin").datepicker({
                changeMonth: true,
                changeYear: true,
                minDate: -0,
                showMonthAfterYear: false,
                onSelect: function( selectedDate ) {
                    var option = this.id == "fechaini" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                    fechas.not( this ).datepicker( "option", option, date );
                    if($('#fechaini').val() == $.trim(today)) {
                        $('#publicar').attr('checked', true);
                    } else {
                        $('#publicar').attr('checked', false);
                    }
                    var tf = $('#fechafin');
                    var tf2 = $('#fechaini');
                    var rf =  tf.parent().parent().find('.response');
                    if($('#fechaini').val() != '' && $('#fechafin').val() != '') {
                        rf.removeClass('bad').removeClass('hide').addClass('good').html(msg.fecha_fin.good);
                        tf.addClass(vars.okR);
                        tf2.addClass(vars.okR);
                    } else {
                        var mensaje = "";
                        if (tf.val()!="") mensaje = msg.fecha_inicio.bad;
                        if (tf2.val()!="") mensaje = msg.fecha_fin.bad;
                        rf.removeClass('good').removeClass('hide').addClass('bad').html(mensaje);
                    }
                }
            });
            
            $("#fechainievent").parent().find("a").bind("click", function(e){
                e.preventDefault();
                $("#fechainievent").trigger("focus");
            });
            $("#fechaini").parent().find("a").bind("click", function(e){
                e.preventDefault();
                $("#fechaini").trigger("focus");
            });
            $("#fechafin").parent().find("a").bind("click", function(e){
                e.preventDefault();
                $("#fechafin").trigger("focus");
            });

            VidaSocialValidator.EmptyValid("#titulo", msg.titulo.bad, msg.titulo.good);
            VidaSocialValidator.EmptyValid("#contenido", msg.contenido.bad, msg.contenido.good);
            VidaSocialValidator.Guardar();
            VidaSocialValidator.publicar();

       }
   };
   
   // init
   VidaSocialValidator.start();
   
});