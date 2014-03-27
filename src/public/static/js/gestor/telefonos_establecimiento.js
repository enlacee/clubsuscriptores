$(function(){
    var csrf = $("#csrf").text();
    var jstrap = new jStrap();
    var establecimientoPhone = {
        modalAddPhone : function(a){
            var btnSave = $('.btnSavePhoneG'),
            btnQuit = $('.btnQuitPhoneG'),
            wClosePhone = $('#idClosePhoneG'),
            contenido = $('#content-divAddNumbersPhone');
                
            btnSave.live('click',function(){
                var statePhone = true,
                valsP = $('#valuesPhonesInsert'), mvalsP = '',
                valsIdP = $('#idValuesPhonesInsert'), mvalsIdP = '',
                mensajePhone = $('#msjPhones');
                
                $('#divContenidoPhonesNum input[name^=txtNumberPhone]').each(function(){
                    if($(this).val()!=''){
                        statePhone = statePhone && true;
                        $(this).prev().html('&nbsp;&nbsp;');
                    }else{ 
                        statePhone = statePhone && false;
                        $(this).prev().html('*');
                        $(this).parent().find('.msjRespELM').addClass('hide');
                    }
                    mvalsP += $(this).val()+',';
                    mvalsIdP += $(this).next().val()+',';
                });
                valsP.val(mvalsP);
                valsIdP.val(mvalsIdP);
                //alert(statePhone); return;
                if(statePhone){
                    $.ajax({
                        'url' : '/gestor/establecimientos/valid-unique-phone-number',
                        'type' : 'POST',
                        'dataType' : 'JSON',
                        'data' : {
                            'phones' : mvalsP,
                            'phoneids': mvalsIdP,
                            'csrf' : csrf
                        },
                        'success' : function(res) {
                            csrf = res.csrf;
                            $("#csrf_base").text(csrf);
                            if(res.flag){
                                if(res.nrophone>=1){
                                    save();
                                }else{
                                    //alert(res.nrophone); return;
                                    mensajePhone.slideDown('fast').delay(2500).slideUp('fast');
                                }
                                
                            }
                        }
                    });
                }
                return false;
            });
            btnQuit.live('click',function(){
                wClosePhone.trigger('click'); 
            });
            $(a).live('click', function(){
                var t = $(this),
                idEst = t.attr('rel');
                
                contenido.addClass("loading center");
                contenido.html("");
                $.ajax({
                    'url' : '/gestor/establecimientos/add-phones-establecimiento',
                    'type' : 'POST',
                    'dataType' : 'html',
                    'data' : {
                        'idEst' : idEst
                    },
                    'success' : function(res) {
                        contenido.removeClass("loading");
                        contenido.html(res);
                        comportamientoNew();
                        jstrap.onlyNumTlf('.tlfNPhone');
                        csrf =  $("#csrf").text();
                        $("#csrf_base").text(csrf);
                    },
                    'error' : function(res) {
                    }
                });

            });
            $('input[name^=txtNumberPhone]').live('blur',function(){
                validacionNumberExists(this);
            });
            function save(){
                var frmPhone = $('#frmPhonesEstable').serialize() + '&csrf=' + csrf;
                //alert(frmPhone); //return;
                contenido.removeClass('good bad').addClass("loading center");
                contenido.html("");
                $.ajax({
                    url : '/gestor/establecimientos/update-phones-establecimiento/',
                    type : 'POST',
                    dataType : 'html',
                    data : frmPhone,
                    success : function(res) {
                        contenido.removeClass("loading").addClass('good');
                        contenido.html(res);
                        setTimeout(function(){
                            contenido.removeClass('good bad');
                            wClosePhone.trigger('click')
                        },2000);
                    },
                    error : function(res) {
                        contenido.html('error');
                    }
                });
            }
        }
    }
    function validacionNumberExists(obj)
    {
        var phoneDigit = $(obj),
        msjR = $(obj).parent().find('.msjRespELM'),
        opflag = true;
        //alert(valDigit);
        if(phoneDigit.val()!=''){
            phoneDigit.addClass('loadingMail');
            msjR.addClass('hide');
            $.ajax({
                'url' : '/gestor/establecimientos/valid-unique-phone-number',
                'type' : 'POST',
                'dataType' : 'JSON',
                'data' : {
                    'phone' : phoneDigit.val(),
                    'phoneid': phoneDigit.next().val(),
                    'csrf' : csrf
                },
                'success' : function(res) {
                    phoneDigit.removeClass('loadingMail');
                    //alert(res.flag);
                    if(!res.flag){
                        msjR.removeClass('hide');
                        opflag = true;
                    }else{
                        opflag = false;
                    }
                    csrf = res.csrf;
                    $("#csrf_base").text(csrf);
                },
                'error' : function(res) { }
            });
        }
        return opflag;
    }
    
    function comportamientoNew()
    {
        var divContent = $('#divContentNewPhone'),
        divContentNews = $('#divContentNews'),
        addP = $('#idAddPhone'),
        deleteP = $('.idDeletePhone'),
        idNroPhone = $('#idnro'),
        contenidoPhone = $('#divContenidoPhonesNum'),
        contenidoPhone2 = $('#divContenidoPhonesNum2'),
        messagePhone = $('#msjPhones');
        
        divContent.hide();
        if(contenidoPhone2.height()>259){
            contenidoPhone.attr('style', 'height:282px;overflow-x:hidden;overflow-y:auto;');
        }
        addP.live('click',function(){
            messagePhone.hide();
            //divContent.slideDown('fast')
            var nro = parseInt(idNroPhone.val()),
            Pcontent = divContent.children();
            
            if(contenidoPhone2.height()>259){
                contenidoPhone.attr('style', 'height:282px;overflow-x:hidden;overflow-y:auto;');
            }
            Pcontent.find('label').html('Teléfono N°&nbsp;&nbsp;:'); //'Teléfono N°'+nro
            Pcontent.removeClass().addClass('block left pContent'+nro);
            Pcontent.find('input:first').attr('id', 'txtNumberPhone'+nro)
            .attr('name', 'txtNumberPhone'+nro);
            Pcontent.find('input:first').next().attr('id', 'idphone'+nro)
            .attr('name', 'idphone'+nro);
            Pcontent.find('select:first').attr('id', 'operador'+nro)
            .attr('name', 'operador'+nro);
            Pcontent.find('input:checkbox:first').attr('id', 'chkActivo'+nro)
            .attr('name', 'chkActivo'+nro);
            Pcontent.hide();
            idNroPhone.val(nro+1);
            //divContentNews.html(divContent.html()+divContentNews.html());
            divContentNews.append(divContent.html());//.hide().slideDown('fast');
            $('.pContent'+nro).slideDown('fast');
            jstrap.onlyNumTlf('.tlfNPhone');
        });
        deleteP.live('click',function(){
            var numPho = $('#frmPhonesEstable .tlfNPhone').size();
            if(numPho<=1){
                messagePhone.slideDown('fast').delay(2500).slideUp('fast');
            }else{
                $(this).parent().slideUp('fast',function(){
                    $(this).remove()
                    });
                if(contenidoPhone2.height()<282){
                    contenidoPhone.removeAttr('style');
                }
            }
        });
    }
    establecimientoPhone.modalAddPhone('.editTelefEstab');
});