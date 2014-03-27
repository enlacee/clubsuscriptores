/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$( function() {
    //    $(this).find('div.brkpag').attr('style', 'page-break-before:always;');
    var inicial = 100;
    var divs = $(this).find('div.brkpag');
    
    var pixeles = inicial;
    
    divs.each(function() {
        var div = $(this);
        var alto = div.height();
        var cat = 0;
        if(div.hasClass('ncat')) {
            cat = 32;
        }
        pixeles = pixeles + alto + cat;
        if(pixeles > maximo) {
            if(div.hasClass('ncat')) {
                div.prev().attr('style', 'page-break-before:always;');
                pixeles = 32 + alto;
            } else {
                div.attr('style', 'page-break-before:always;');
                pixeles = alto;
            }
        }
    })
})