/*
   Proceso de Buscador BENEFICIOS/CATALOGO
*/
$( function() {

    var frmSideSearch = {
        clickLink: function(a) {
            var actual = $(a);
            actual.bind("mousedown", function(e){
                e.preventDefault();
                var link = $(this).find("span").attr("link");
                location.href=link+"#ac";
            });
        },
        clickRadio: function(a) {
            var actual = $(a),
                form='#formCategoria';
                
            actual.bind("click", function(e){
                //e.preventDefault();
                var link = $(this).next("span").attr("link"),
                    url= $(this).next("span").attr("url"),
                    linkCate=frmSideSearch._urlConCategoria(link);
                url = url + linkCate[1]+"#ac";
                if(linkCate[0]){
                    $(form).attr("action",url);                                
                    $('<input>', {
                        id: 'hd_categoria',
                        name: 'hd_categoria',
                        value: frmSideSearch._valorCategoria(),
                        type: 'hidden'
                    }).appendTo(form);
                    $(form).attr("method","POST");
                    $(form).submit();
                } else {
                    location.href=url;
                }
                
            });
        },
        _urlConCategoria: function(link){
            var linkSplit='',
                valor='',
                $return=false
                ;
                
            if(link!=''){
                linkSplit=link.split('--');
                if(linkSplit.length > 3){
                    valor='categorias-online';
                    $return=true;
                } else {
                    valor=link;
                }
            }
            return new Array($return,valor);
        },
        _valorCategoria: function(){
            var A=$('input[name="chkboxcate"]:checked'),
                arreglo=new Array(),
                i=0,
                $return='';
            
            A.each(function(index) { 
                //alert(index + ' - '+$(this).attr("value"));
                arreglo[i] = $(this).attr("value");
                i++;
            });
            if(i>3){
                $return=arreglo.join("--");
            }
            return $return;
        },
        start : function(a, b) {
            frmSideSearch.clickLink(a);
            frmSideSearch.clickRadio(b);
        }
    };
    frmSideSearch.start(".itemSearchBeneficios", ".itemSearchBeneficiosRadio input");
});