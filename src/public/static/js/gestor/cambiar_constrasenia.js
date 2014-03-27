/*
	 Registro
*/
$( function() {
    var msgs = {
        cDef : {
            good :'Bien',
            bad : 'Campo Requerido',
            def :'Opcional'
        },
        cName : {
            good : 'OK',
            bad : '¡Se requiere tu clave actual',
            def : 'Ingresa tu clave actual'
        },
        cPass : {
            good : '¡OK! Verifica la seguridad de tu clave',
            bad : '¡Usa de 6 a 32 caracteres!',
            def : '¡Usa de 6 a 32 caracteres! Sé ingenioso.',
            sec : {
                msgDef : 'Nivel de seguridad',
                msg1 : 'Demasiado corta',
                msg2 : 'Débil',
                msg3 : 'Fuerte',
                msg4 : 'Muy fuerte'
            }
        },
        cRePass : {
            good : '¡OK!',
            bad : 'Las contraseñas introducidas no coinciden. Vuelve a intentarlo.',
            def : 'Tienen que ser iguales'
        },
        changePass : 'Hubo un error al intentar actualizar tus datos, verifica tu información'
    }
    var vars = {
        rs : '.response',
        okR :'ready',
        sendFlag : 'sendN',
        loading : '<div class="loading"></div>'
    }
    var formRegistro = {
        fPass : function(a,b,c) {
            var good = msgs.cPass.good,
            bad = msgs.cPass.bad,
            def = msgs.cPass.def,
            msgDef = msgs.cPass.sec.msgDef,
            msg1 = msgs.cPass.sec.msg1,
            msg2 = msgs.cPass.sec.msg2,
            msg3 = msgs.cPass.sec.msg3,
            msg4 = msgs.cPass.sec.msg4,
            pf1 = $('#pf1'),
            pf2 = $('#pf2'),
            pf3 = $('#pf3'),
            pf4 = $('#pf4'),
            msg = $('#txtPass'),
            ep = /[a-z]|[A-Z]|\d|[^A-Za-z0-9]/,
            epMin = /[a-z]/,
            epMay = /[A-Z]/,
            epNum = /\d/,
            epEsp = /[^A-Za-z0-9]/,
            epMinC = /[a-z]+[A-Z]+|[A-Z]+[a-z]+|[a-z]+\d+|\d+[a-z]+|[a-z]+[^A-z0-9]+|[^A-z0-9]+[a-z]+/,
            epMayC = /[A-Z]+\d+|\d+[A-Z]+|[A-Z]+[^A-z0-9]+|[^A-z0-9]+[A-Z]+/,
            epEspC = /[^A-z0-9]+\d+|\d+[^A-z0-9]+/;
            $(a).keyup( function() {
                var t = $(this),
                v = $(this).val(),
                r = t.parents('.block').find(vars.rs);
                if(v.length>=(b)) {
                    r.removeClass('bad').addClass('good').text(good);
                    if(ep.test(t.val())) {
                        pf1.removeClass('bgRed bgGreen').addClass('bgYellow');
                        pf2.removeClass('bgRed bgGreen').addClass('bgYellow');
                        pf3.removeClass('bgRed bgGreen');
                        pf4.removeClass('bgGreen');
                        msg.text(msg2);

                        if( epMinC.test(t.val()) || epMayC.test(t.val()) || epEspC.test(t.val()) ) {
                            pf1.removeClass('bgRed bgYellow').addClass('bgGreen');
                            pf2.removeClass('bgRed bgYellow').addClass('bgGreen');
                            pf3.removeClass('bgYellow').addClass('bgGreen');
                            pf4.removeClass('bgGreen');
                            msg.text(msg3);
                        }
                        if(epMay.test(t.val()) && epNum.test(t.val()) && epEsp.test(t.val())) {
                            pf1.removeClass('bgRed bgYellow').addClass('bgGreen');
                            pf2.removeClass('bgRed bgYellow').addClass('bgGreen');
                            pf3.removeClass('bgYellow').addClass('bgGreen');
                            pf4.addClass('bgGreen');
                            msg.text(msg4);
                        }
                    }
                    t.addClass(vars.okR);
                } else {
                    r.removeClass('good bad').text(def);
                    pf1.addClass('bgRed').removeClass('bgYellow bgGreen');
                    pf2.removeClass('bgYellow bgGreen');
                    pf3.removeClass('bgGreen');
                    pf4.removeClass('bgGreen');
                    msg.text(msg1);
                    t.removeClass(vars.okR);
                }
                if(v.length==0) {
                    pf1.removeClass('bgRed bgYellow');
                    pf2.removeClass('bgRed bgYellow');
                    msg.text(msgDef);
                }
                var cc = $(c);
                if(cc.val().length>0) {
                    rr = cc.next(vars.rs);
                    if(cc.val()!==t.val()) {
                        rr.removeClass('god bad').text(msgs.cRePass.def);
                    }
                    else {
                        rr.removeClass('bad').addClass('good').text(msgs.cRePass.good);
                    }
                }
            }).blur( function() {
                var t = $(this)
                r = t.parents('.block').find(vars.rs);
                if(t.val().length>=b) {
                    r.removeClass('bad').addClass('good').text(good);
                    t.addClass(vars.okR);
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
            });
        },
        fRePass : function(a,b,c) {
            var good = msgs.cRePass.good,
            bad = msgs.cRePass.bad,
            def = msgs.cRePass.def,
            r = $(a).parents('.block').find(vars.rs);
            $(a).keyup( function() {
                var t=$(this);
                if(t.val().length>=c) {
                    if(t.val()===$(b).val()) {
                        r.removeClass('bad').addClass('good').text(good);
                        t.addClass(vars.okR);
                    } else {
                        r.removeClass('good bad').text(def);
                        t.removeClass(vars.okR);
                    }
                } else {
                    r.removeClass('good bad').text(def);
                    t.removeClass(vars.okR);
                }
            }).blur( function() {
                var t=$(this);
                if(t.val().length>=c) {
                    if(t.val()!==$(b).val()) {
                        r.removeClass('good').addClass('bad').text(bad);
                        t.removeClass(vars.okR);
                    } else {
                        r.removeClass('bad').addClass('good').text(good);
                        t.addClass(vars.okR);
                    }
                }
                else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
            });
        },
        fInput : function(a,good,bad,def) {
            var A = $(a),
            r = A.parents('.block').find(vars.rs);
            A.blur( function() {
                var t = $(this);
                if(t.val().length>0) {
                    r.removeClass('bad').addClass('good').text(good);
                    t.addClass(vars.okR);
                } else {
                    r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                }
            }).keypress( function() {
                var t = $(this);
                if(t.val().length===0) {
                    //r.removeClass('good').addClass('bad').text(bad);
                    t.removeClass(vars.okR);
                } else {
                    r.removeClass('good bad').text(def);
                    t.addClass(vars.okR);
                }
            });
        },
        fOldPass : function(a){
            var trigger = $(a),
            res = trigger.siblings('.response');
            if( ($('body').is('#myAccount')) ) {
                trigger.keyup( function(){
                    res.removeClass('bad good').addClass('def').text('');
                });
            }				
        },
        fSubmit : function(a,b,c,f1,f2,f3) {
            var A=$(a), B=$(b), c = 3, z = 10, y = 11,
            F1 = $(f1), F2 = $(f2), F3 = $(f3);
            A.bind('click', function(e) {
                e.preventDefault();
                if(B.find('.'+vars.okR).size()>=c) {
                    //B.submit();
                    var url = B.attr('action'),
                    msjE = $('#idMessageChangePassE');
                    msjE.text('');
                    msjE.removeClass('hide error success').addClass('loading');
                    $.ajax({
                        url : url,
                        type : 'POST',
                        dataType : 'JSON',
                        data : B.serialize(),
                        success : function(res){
                            msjE.addClass(res.state);
                            msjE.fadeIn('fast').delay(1800).fadeOut('slow');
                            msjE.removeClass('loading');                          
                            msjE.text(res.message);
                            if(res.state=='success'){
                                formRegistro._clearFields(B);
                            }    
                        },
                        error : function(res){
                           msjE.removeClass('loading');
                           msjE.text(msgs.changePass);
                           formRegistro._clearFields(B); 
                        }
                    });
                } else {					
                    $('#cClaveCP .fields').not('.ready').removeClass('ready').parents('.block').find('.response').removeClass('def good').addClass('bad').text(msgs.cDef.bad);
                }
            });
        },
        _clearFields : function(frm){
            frm.get(0).reset();
            frm.find('.inputN').val('').removeClass('ready').siblings('.response').text('').removeClass('good bad');
        }
    };
    formRegistro.fPass('#fClave',6,'#fRClave');
    formRegistro.fRePass('#fRClave','#fClave',6);
    formRegistro.fInput('#fACtns', msgs.cName.good, msgs.cName.bad, msgs.cName.def);
    formRegistro.fOldPass('#fACtns');
    formRegistro.fSubmit('#saveChangesBt','#cClaveCP',3,'#fClave','#fRClave','#fNames');
});