/*
 Compartir por Mail
 */
$( function() {
	var msgs = {
		cDef : {
			good : 'Bien',
			bad  : 'Campo Requerido',
			def  : 'Opcional'
		},
		cEmail : {
			good : '¡OK!',
			bad  : 'No parece ser un e-mail válido.',
			def  : 'Ingrese e-mail correcto'
		},
		cName : {
			good : 'El nombre se ve genial.',
			bad  : '¡Se requiere su nombre!',
			bad2 : '¡Se requiere el nombre!',
			def  : 'Ingrese nombre correcto'
		},
		cAreaMsg : {
			good : 'Buen mensaje.',
			bad  : '¡Se requiere el mensaje!',
			def  : 'Ingrese mensaje correcto'
		},
		cQuestions : {
			good   : '¡Ok.!',
			bad    : 'Responder pregunta',
			def    : 'Responda con criterio'
		},
		cReport : {
			bad : 'Detalla el motivo.'
		},
		mailSend  : 'El e-mail fue enviado.',
		mailError : 'No se pudo enviar el e-mail.'
	}
	var vars = {
		rs       : '.response',
		okR      :'ready',
		sendFlag : 'sendN',
		loading  : '<div class="loading"></div>'
	}
	var formShareF = {
		fMail : function(a,good,bad,def) {
			$(a).focus();
			$(a).bind('blur', function() {
				var t = $(this),
				r = t.next(vars.rs),
				ep = /^(([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+)?$/g;
				if(ep.test(t.val())&& t.val()!='') {
					r.removeClass('bad').addClass('good').text(good);
					t.addClass(vars.okR);
				} else {
					r.removeClass('good').addClass('bad').text(bad);
					t.removeClass(vars.okR);
				}
			});
		},
		fInput : function(a,good,bad,def) {
			var A = $(a),
			r = A.next(vars.rs);
			A.blur( function() {
				var t = $(this);
				if(t.val().length>0) {
					r.removeClass('bad').addClass('good').text(good);
					t.addClass(vars.okR);
				} else {
					r.removeClass('good').addClass('bad').text(bad);
					t.removeClass(vars.okR);
				}
			}).keyup( function() {
				var t = $(this);
				if(t.val().length===0) {
					r.removeClass('good').addClass('bad').text(bad);
					t.removeClass(vars.okR);
				} else {
					r.removeClass('good bad').text(def);
					t.addClass(vars.okR);
				}
			});
		},
		fSubmit : function(a,b,c,f1,f2,f3,f4,f5) {
			var A=$(a),
			B=$(b),
			F1 = $(f1), F2 = $(f2), F3 = $(f3),
			F4 = $(f4), F5 = $(f5);
			A.bind('click', function(e) {
				e.preventDefault();
				var t = $(this),
				urlSlug = t.attr('rel'),
				nombreEmisor = F1.val(),
				correoEmisor = F2.val(),
				nombreReceptor = F3.val(),
				correoReceptor = F4.val(),
				mensajeCompartir = F5.val(),
				hdnOculto = $('input#hdnOculto').val();
				if(B.find('.'+vars.okR).size()>=c) {
					B.addClass('hide');
					$('#loadMFF').remove();
					$('#iEscpF').append('<div id="loadMFF" class="loading all"></div>');
					$.ajax({
						'url' : urlSlug,
						'type' : 'POST',
						'dataType' : 'JSON',
						'data' : {
							'nombreEmisor' : nombreEmisor,
							'correoEmisor' : correoEmisor,
							'nombreReceptor' : nombreReceptor,
							'correoReceptor' : correoReceptor,
							'mensajeCompartir' : mensajeCompartir,
							'hdnOculto' : hdnOculto
						},
						'success' : function(res) {
							var frm = $('#iEscpF');
							if(res.status == 'ok') {
								B.addClass('hide');
								$('#loadMFF').remove();
								frm.append('<div id="loadMFF" class="block"><div class="good msjLoadMFF">' + res.msg + '</div></div>');
								formShareF._clearFields(frm);
							} else {								
								B.addClass('hide');
								$('#loadMFF').remove();
								frm.append('<div id="loadMFF" class="block"><div class="bad msjLoadMFF">' + msgs.mailError + '</div></div>');
								formShareF._clearFields(frm);
							}
						},
						'error' : function(res) {
							var frm = $('#iEscpF');
							B.addClass('hide');
							$('loadMFF').remove();
							frm.append('<div id="loadMFF" class="block"><div class="bad msjLoadMFF">' + msgs.mailError + '</div></div>');
							formShareF._clearFields(frm);
						}
					});
					//B.submit();
				} else {
					if($.trim(F1.val())==='') {
						F1.next(vars.rs).removeClass('def good').addClass('bad').text(msgs.cDef.bad);
					}
					if($.trim(F2.val())==='') {
						F2.next(vars.rs).removeClass('def good').addClass('bad').text(msgs.cDef.bad);
					}
					if($.trim(F3.val())==='') {
						F3.next(vars.rs).removeClass('def good').addClass('bad').text(msgs.cDef.bad);
					}
					if($.trim(F4.val())==='') {
						F4.next(vars.rs).removeClass('def good').addClass('bad').text(msgs.cDef.bad);
					}
				}
			});
		},
		fAreaQ : function(a,good,bad,def) {
			var A = $(a);
			A.blur( function() {
				var t = $(this),
				r = t.next(vars.rs);
				if(t.val().length>0) {
					r.removeClass('bad').addClass('good').text(good);
					t.addClass(vars.okR);
				} else {
					r.removeClass('good').addClass('bad').text(bad);
					t.removeClass(vars.okR);
				}
			}).keyup( function() {
				var t = $(this),
				r = t.next(vars.rs);
				if(t.val().length===0) {
					r.removeClass('good').addClass('bad').text(bad);
					t.removeClass(vars.okR);
				} else {
					r.removeClass('good bad').text(def);
					t.addClass(vars.okR);
				}
			});
		},
		resetQues : function(reset){
			var resetC = $(reset);
			resetC.bind('click', function(){
				$('#questionsWM textarea').removeClass('ready').val('').next().text('').removeClass('good bad');
			});
		},
		resetBtn : function(btnReset, chars){
			btnR = $(btnReset);
			btnR.click(function(){
				var t = $(this),
				frm = t.parents('form');
				formShareF._clearFields(frm);
				$('#nCaracterP').text(chars); 
			});
		},
		_clearFields : function(frm){
			frm.find('.inputReset').val('').removeClass('ready').siblings('.response').text('').removeClass('good bad').addClass('def');
		},
		resetBtnClose : function(btnCloseReset, chars){
			btnRC = $(btnCloseReset);
                        function resetIn(){
                            var frm = $('#formShareCA');
                            formShareF._clearFields(frm);
                            frm.removeClass('hide');
                            $('#loadMFF').remove(); 
                            $('#nCaracterP').text(chars);                                     
                        }                                               
			btnRC.click(function(){
                            var t = $(this);
                            resetIn();                                                                     
			});
                        var clickMsj = $('.mensajeI');
                        clickMsj.click(function(){
                            $(document).keyup(function(e){
                                if(e.keyCode == 27){
                                    resetIn();
                                }
                            });                          
                        });    
                        var m = $('#mask');
                        m.click(function(e){
                            resetIn();
                        });				
		},
		charArea : function(area,num,chars) {
			var trigger = $(area);
			$(num).html(chars);
			trigger.bind('keyup click blur focus change paste', function(e) {
				var t = $(this),
				countN = $(num),
				valueArea;
				countN.html(chars);
				var key = e.which;
				var length = t.val().length;
				countN.html( (chars - length) + ' ' );
				if( length > chars ) {
					valueArea = t.val().substring(chars, '') ;
					trigger.val(valueArea);
					countN.html('0');
				}
			});
		},
    funcHistoryBack : function(link){
     $(link).click(function(e){
      e.preventDefault();
      window.history.back();
     });
    },
    questionHeight : function(btnA){
	    function _heightD(){
	    	divH = $('#cntScrollData');
	    	if(divH.size()>0){
		    	setTimeout(function(){
			    	heightDiv = divH.height();
			    	if(heightDiv >= 350){
			    		divH.css({
			    			'height':'350px',
			    			'overflow-y':'scroll'
			    		});
			    	}
		    	},150);	   			    		
	    	}	    	
	    }
			_heightD();
    	$(btnA).click(function(){
	    	_heightD();
    	});
    }
	};
	// init
	//envio de compartir por mail
	formShareF.fMail('#fCAMail',msgs.cEmail.good,msgs.cEmail.bad,msgs.cEmail.def);
	formShareF.fMail('#fCAMailDes',msgs.cEmail.good,msgs.cEmail.bad,msgs.cEmail.def);
	formShareF.fInput('#fCAName',msgs.cName.good,msgs.cName.bad,msgs.cName.def);
	formShareF.fInput('#fCANameDes',msgs.cName.good,msgs.cName.bad2,msgs.cName.def);
    formShareF.funcHistoryBack('#historyBackA');
	formShareF.fAreaQ('.questionI',msgs.cQuestions.good,msgs.cQuestions.bad,msgs.cQuestions.def);
	formShareF.fMail('#fEmail',msgs.cEmail.good,msgs.cEmail.bad,msgs.cEmail.def);
	formShareF.fSubmit('#fSendCA','#formShareCA',4,'#fCAName','#fCAMail','#fCANameDes','#fCAMailDes','#fCACustomMsg');
	formShareF.resetBtn('.resetBtn', 300);
	formShareF.resetBtnClose('.resetBtnClose', 300);
	formShareF.charArea('#fCACustomMsg','#nCaracterP',300);	
	formShareF.resetQues('.resetQuest');
	formShareF.questionHeight('.btnPost');
});