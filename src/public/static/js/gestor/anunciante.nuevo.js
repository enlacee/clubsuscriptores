/* 
 */
$(function(){
	
    var jstrap = new jStrap();
	
    var vars = {
        rs : '.response',
        okR :'ready',
        sendFlag : 'sendN',
        loading : '<div class="loading"></div>'
    };
	
    var formContact = {
        validFields : function(obj){
            //function Valid Text
            function validText(inputTxt, type, msjValid){
                $(inputTxt).blur(function(){
                    var t = $(this),
                    resp = t.parents('.bloqueNbeneficio').find('.response');
                    switch(type){
                        case 'text':
                            if(jstrap.isEmpty(t.val())){
                                t.addClass('ready');
                                resp
                                .text(msjValid.good)
                                .addClass('good')
                                .removeClass('def bad');
                            }else{
                                t.removeClass('ready');
                                resp
                                .text(t.attr('errmsg'))
                                .addClass('bad')
                                .removeClass('def good');
                            }
                            break;
                    }
                }).keyup(function(){
                    var t = $(this),                
                    resp = t.parents('.bloqueNbeneficio').find('.response');
	                    
                    switch(type){
                        case 'text':
                            if(t.val().length >= obj.params.minChar){
                                t.addClass('ready'); 
                                resp
                                .text(msjValid.def)
                                .addClass('def')
                                .removeClass('bad good');
                            }else{
                                t.removeClass('ready');
                                resp
                                .text(t.attr('errmsg'))
                                .addClass('bad')
                                .removeClass('def good');
                            }                      
                            break;
                    }
                });  
            }
            
            //Nombre
            validText(obj.fields[0], 'text', {
                good : obj.msjs.nombre.good,
                def : obj.msjs.nombre.def
            });
            //N documento
            jstrap.onlyNumDoc(obj.fields[1]);
            $(obj.fields[1]).bind('blur',function(){
                var valor = jstrap.validateRuc($(obj.fields[1]));
                var a = $(obj.fields[1]);
                var r = a.next(vars.rs);
                if (valor == true ){
                    formContact._fRucValid(a, r, obj);
                } else  {
                	a.removeClass('ready');
                    r.removeClass('good').addClass('bad').text(obj.msjs.nRuc.incorrect);
                }
            });
            
            $(obj.fields[3]).bind('click', function(e){
                e.preventDefault();
                var t = $(this);
                var readys = $(obj.form + ' .ready'),
                fields = $(obj.form + ' .field');
                //alert(readys.size() +' + '+obj.params.limit);
                if (readys.size() >= obj.params.limit){
                    //submir
                    $(obj.form).submit();
                }else{
                    //errors 	
                    fields
                    .not('.ready, :disabled')
                    .removeClass('ready')
                    .parents('.bloqueNbeneficio')
                    .find('.response')
                    .removeClass('def good')
                    .addClass('bad')
                    .text(obj.msjs.defecto);                     
                }
            });
            
        },
        _fRucValid : function(a, r, obj){
            var $ndoc = a;
	            
            $ndoc.addClass('loadingMail');
            var idEmp = $ndoc.attr('rel');
            $.ajax({
                url: '/gestor/anunciante/validar-ruc/',
                type: 'post',
                data: {
                    ndoc: $ndoc.val(),
                    idEmp: idEmp
                },
                dataType: 'json',
                success: function(response){
                    if( response.status == true){
                        $ndoc.addClass('ready').removeClass('loadingMail');
                        r.removeClass('bad def').addClass('good').text(obj.msjs.nRuc.good);
                    }else{
                        $ndoc.removeClass('ready').removeClass('loadingMail');
                        r.removeClass('good def').addClass('bad').text(obj.msjs.nRuc.rucValid);
                        a.removeClass(vars.okR);
                    }
                }
            });
        }
    };
	
    formContact.validFields({
        form : '#formNueEst',   
        fields : [
        '#frazon_social',
        '#fRuc',
        '#fEstado',
        '#adminSaveBtnbene'
        ],
        params : {
            minChar : 1,
            limit : 3
        },
        msjs : {
            defecto : 'Campo requerido.',
            nombre : {
                good : 'La Razon Social es correcto.',
                def : 'Ingrese la Razon Social correcto.'
            },
            nRuc : {
                good : 'Su RUC es correcto',
                bad : '¡Se requiere su RUC!',
                def : 'Ingrese su RUC correcta',
                rucValid : 'Ruc ya registrado.',
                incorrect : '¡Ruc incorrecto!'
            },
            mensaje : {
                good : 'El mensaje es correcto.',
                def : 'Ingrese mensaje correcto.'
            }
        }
    }); 
});

