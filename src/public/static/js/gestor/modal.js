/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(function() {
    var CS = function(opts){  
        //window modal and alert
        this.winModal = function(){
            var a = $('.winModal'),
            m = $('#mask'),
            w = $('.window'),
            c = $('.closeWM'),
            s = 'fast',
            o = 0.50; 
            var jash = $.trim(window.location.hash).split("-");
            var url = location.href.substring(7,location.href.length).split("/");
            if(jash.length > 0 && jash[0]!="" && url[1].substring(0,url[1].length-1)!="suscripciones") {
                if($('body').find('"' + jash[0] + '"').size() > 0){
                    var mH = $(document).height(),
                    mW = $(window).width();
                    $('html, body').animate({
                        scrollTop:0
                    }, s);
                    m.css({
                        'height':mH
                    });		
                    m.fadeTo(s,o);				
                    $(jash[0]).fadeIn(s);
                    $(document).keyup(function(e){
                        if(e.keyCode === 27) {
                            m.hide();
                            w.hide();
                        }
                    });						
                }
                if(jash.length==2) {
                    $(jash[0]+" input[name=return]").val(Base64.decode(jash[1]));
                }
            }
            a.live('click',function(e){
                e.preventDefault();
                var t = $(this),
                i = t.attr('href'),
                mH = $(document).height(),
                mW = $(window).width();					
                if(!(t.hasClass('noScrollTop'))){
                    $('html, body').animate({
                        scrollTop:0
                    }, s);
                }
                // cadena solo # 
                if( $.browser.msie && $.browser.version.substr(0,1) < 8 ) {
                    var strI = i.split('#'),
                    strId = strI[1];
                    i = '#' + strId;   
                }							
                m.css({
                    'height':mH
                });			
                m.fadeTo(s,o);	
                $(i).fadeIn(s);			
                $(document).keyup(function(e){
                    if(e.keyCode === 27) {
                        m.hide();
                        w.hide();
                    }
                });	
                /* url aplicada */				
                var oRedirect = t.attr('return');
                if(oRedirect){
                    var receptorUrl = $('#return');
                    receptorUrl.val(oRedirect);
                }														 
            });
            c.click(function(e){
                e.preventDefault();
                var linkCloseX = $(this),
                content = linkCloseX.parent();
                m.hide();
                w.hide();		
                if(linkCloseX.hasClass('closeRegiFast')){
                    var inputsNRP = content.find('input.inputRpm');
                    //reset
                    $.each(inputsNRP, function(i, val){
                        var inptRPEA = inputsNRP.eq(i);
                        if($.trim(inptRPEA.val()) != ''){
                            inptRPEA.val('').removeClass('ready bienRegFast malRegFast').parents('.placeHRel').find('.txtPlaceHR').removeClass('hide');
                        }			
                    });
                    var inputsNSM = $('input#wmPMail'),
                    altIpt = inputsNSM.attr('alt');
                    inputsNSM.removeClass('readyLogin').addClass('cGray').val(altIpt);
                    $('#wmPPass').removeClass('readyLogin').val('');
                    content.find('.respW').removeClass('bad good').text('');	
                    $('#textForgotPReg').val('');	

                    $('#cntRegisterWM').css('display','block');
                    $('#cntForgotPReg').css('display','none');	
                }else if(linkCloseX.hasClass('closeResLogin')){
                    //reset Login
                    var mailRT = $('#wmMail'),
                    passRT = $('#wmPass'),
                    reMail = $('#textForgotP');
                    mailRT.val(mailRT.attr('alt')).removeClass('readyLogin').addClass('cGray').next().removeClass('bad good').text('');				
                    passRT.val('').removeClass('readyLogin').next().removeClass('bad good').text('');
                    reMail.val('');
                }			
            });		
            m.click(function(e){
                $(this).hide();
                w.hide();
            });			
        };
    }


    var cs = new CS();
    cs.winModal();
})