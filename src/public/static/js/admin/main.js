/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* 
Main Administrador
 */

$(function(){
   // Class
   var Administrador = function(){
       this.loginVM = function(){
                var linkOlvidoC = $('#linkLoginMLS');
                if(linkOlvidoC.size()>0){
                        var cntPass = $('#contentForgotMLS'),
                        cntLog = $('#contentLoginMLS'),
                        linkPass = $('#backLogMLS');
                        linkOlvidoC.bind('click', function(e){
                                e.preventDefault();										
                                cntLog.slideUp('fast',function(){
                                        cntPass.slideDown();		
                                });												
                        });
                        linkPass.bind('click', function(e){
                                e.preventDefault();										
                                cntPass.slideUp('fast',function(){
                                        cntLog.slideDown();		
                                });												
                        });					
                }
        }
   };
   
   // init
   var objad = new Administrador();
   objad.loginVM();
   
   $(this).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if(jqXHR.status == 401)
            window.location.href = '/establecimiento/error/' + jqXHR.status;
   });
});