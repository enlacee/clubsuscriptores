/*
   Proceso de Buscador BENEFICIOS/CATALOGO
*/
$( function() {

    var sectionUrl = {
        query        : 'query',
        categoria    : 'categoria',
        concatString : '+',
        separator    : '/'
    };

    var frmSearch = {
        placeH : function(a) {
            var actual = $(a);
            actual.bind("focus", function(){
                var alt = $(this).attr("alt");
                var texto = $(this).val();
                if(alt == texto) {
                    $(this).val("");
                }
            }).bind("blur", function(){
                var texto = $(this).val();
                var alt = $(this).attr("alt");
                if (alt == texto || texto=="") $(this).val(alt);
            });
        },
        returnString : function(val) {
            val = val.replace(/-+/g,' ');
            val = val.replace(/_+/g,' ');
            val = val.replace(/\.+/g,'');
            val = val.replace(/\s+/g, sectionUrl.concatString);
            val = val.replace(/,+/g,'');
            val = val.replace(/\%+/g,'');
            return val;
        },
        btnBuscar : function(a, b, c) {
            var frm = $(a);
            var texto = $(b);
            frm.bind("submit", function(){
               var categoria = $(c).val();
               var x   = frmSearch.returnString(texto.val());
               var alt = frmSearch.returnString(texto.attr("alt"));
               if(x==alt) x="";
               var cadena = this.action;
               if(categoria!=0) cadena+=sectionUrl.separator+
                                        //"nuevo/todos"+
                                        //sectionUrl.categoria+
                                        //sectionUrl.separator+
                                        categoria;
               if(x!="") cadena+=sectionUrl.separator+
                                 //sectionUrl.query+
                                 //sectionUrl.separator+
                                 x;
               window.location=cadena;
               return false;
            });
        },
        paginadorCombo : function(a) {
            var actual = $(a);
            actual.bind("change", function(){
                var valor=$(this).val();
                var action = $("#frmnpaginado").attr("action");
                var monstrandoDe = $(this).attr("monstrandode");
                
                //$("#frmnpaginado").attr("action",action+"np/"+valor+"#ac");
                location.href = action+valor+"-"+monstrandoDe+"#ac";
                //$("#frmnpaginado").submit();
            });
        },
//        linkPaginacion : function(a) {
//            var actual = $(a);
//            actual.bind("click", function(e){
//                e.preventDefault();
//                var action = $(this).attr("href");
//                
//                location.href = action;                
//                
//            });
//        },
        start : function(a, b, c) {
//            frmSearch.linkPaginacion("a.linkPag");
            frmSearch.placeH(b);
            frmSearch.btnBuscar(a, b, c);
            frmSearch.paginadorCombo("#npaginado");
            var jstrap = new jStrap();
            jstrap.removeAllSpaces('#textForgotP');
        }
    };
    frmSearch.start("#formBox", "#formBox input[name=search]", "#categorias");
});