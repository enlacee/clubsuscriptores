
$(function(){
    var Articulo = {
       filtrosArticulo: function(a, b) {
            var actual = $(a);
            actual.bind("change", function(){
                $(b).submit();
            });
       },
       frmSubmitFiltrosArticulo : function(a) {
            var actual = $(a);
            actual.bind("submit", function(e){
                e.preventDefault();
                var action = $(this).attr("action");
                var estado = $("#estado").val();
                var query = $("#query").val();

                if (estado!="") {
                    action=action+"/estado/"+estado;
                }
                if (query!="") {
                    action=action+"/query/"+query;
                }
                $(this).attr("action", action);
                document.location.href =action;
            });
       },
       darBajaArticulo: function(b){
            var btnAcepBaja = $('#aceptBajaArtBtn'),
                aLinkBaja = $('.vidaSocialDarBajaG'),
                idArtBaja = '';
            aLinkBaja.live('click', function(){
                idArtBaja = $(this).attr('rel');
            });
            btnAcepBaja.bind('click', function(){

                var cntMsj3 = $('#divMessageBajaA'),
                    cerrarA = $('#aCloseBajaA');

                /*cntMsj3.text('').removeClass('hide bad good')
                      .addClass('loading center');*/
                $.ajax({
                    url : '/gestor/vida-social/dar-baja-articulo',
                    type : 'POST',
                    dataType : 'JSON',
                    data : { idArticulo : idArtBaja },
                    success : function(res){
                        /*cntMsj3.html(res.mensaje).addClass('good')
                        .removeClass('loading');*/
                        if(res.success){
                            /*setTimeout(function(){
                                //cntMsj3.removeClass('good');
                            },500);*/
                            cerrarA.trigger('click');
                            $(b).submit();
                        }
                    },
                    error : function(res){
                        cntMsj3.html('Error').addClass('bad');
                    }
                });
            });
       },
       startFiltros: function(a, b) {
          Articulo.filtrosArticulo(a, b);
          Articulo.frmSubmitFiltrosArticulo(b);
          Articulo.darBajaArticulo(b);
       }
    };
    Articulo.startFiltros(".filtroListarArticulos", "#formBoxAdminG");
})
