/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$( function() {
    var formAlertas = {
        MultiSelectValidator : function(s) {
            $(s).bind("change", function(){
                var actual = $(this);
                $.each($(s), function(index, value){
                    var error = false;
                    $.each($(s), function(index2, value2){
                        if ($(value).val()==$(value2).val() &&
                            $(value).attr("id")!=$(value2).attr("id") &&
                            $(value).val()>-1 ) {
                            error = true;
                        }
                    });
                    if (error) {
                        $(value).parent().find(".response").addClass("bad").html("Categorias deben ser diferentes.");
                        $(value).addClass("unready");
                    } else {
                        $(value).parent().find(".response").removeClass("bad").html("");
                        $(value).removeClass("unready");
                    }
                });
            });
        },
        submit : function(f) {
            $(f).submit(function() {
                var comboscategoria = $(".categoriax").hasClass("unready");
                if(!comboscategoria) {
                    this.submit();
                } else {
                    return false
                }
            })
        }
    }
    formAlertas.MultiSelectValidator(".categoriax");
    formAlertas.submit('#frmAlertas');
})
