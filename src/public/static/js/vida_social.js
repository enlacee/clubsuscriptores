/* 
vida social, galeria de imagenes
*/

$(function(){
    // Class
    var Articulo = function(){
        this.loadArt = function(){ 
            var url = document.location.href,
            domain = url.lastIndexOf(document.location.host) + document.location.host.length + 1,
            params = url.substring(domain),
            splitUrl = params.split('/');
            if( splitUrl[0] == 'articulo' ){						
                var cntModalLoad = $('#winVerVidaSocial'),
                idCatalogo = splitUrl[1],
                indice = splitUrl[3];								
                if(cntModalLoad.size() > 0){
                    var m = $('#mask'),
                    w = $('.window'),
                    s = 'fast',
                    o = 0.50; 
                    var mH = $(document).height(),
                    mW = $(window).width();
                    m.css({
                        'height':mH
                    });		
                    m.fadeTo(s,o);				
                    cntModalLoad.fadeIn(s, function(){
                        _ajaxLoadImgs(idCatalogo, indice);
                    });
                    $(document).keyup(function(e){
                        if(e.keyCode === 27) {
                            m.hide();
                            w.hide();
                        }
                    });						
                }													
            }					
        }
        this.viewDetailArticulo = function(linkArt){
            var linkArticulo = $(linkArt);
            linkArticulo.bind('click', function(e){
                e.preventDefault();
                var t = $(this),
                id = t.prev().val(),
                indice = t.attr('indice');
                _ajaxLoadImgs(id, indice);	
            });		
        };		
        _ajaxLoadImgs = function(id, indice){
            var cntMsj = $('#content-winVerVidaSocial');
            cntMsj.empty().removeClass('hide').addClass('loading');							
            $.ajax({
                url : '/suscriptor/vida-social/detail-articulo/',
                type : 'POST',
                dataType : 'html',
                data : 'id=' + id,
                success : function(res){
                    cntMsj.removeClass('loading').html(res);
                    _iGallery(indice);
                },
                error : function(res){    						
                    cntMsj.removeClass('loading').html(res);
                }
            });			
        }
        _iGallery = function(indice){
            var slide = $('#slide-dos'),
            cntOptSlide = $('#cntOptGallery'),
            cntImgBig = $('#vsphotoLbox'),
            aOptSlide = $('#listGallery .aListG'),
            numeroPics = aOptSlide.length,
            itemsBlock = 3,
            anchoSlide = 435,
            nClicks = Math.ceil(numeroPics/itemsBlock),
            prev = $('#prevG'),
            next = $('#nextG'),
            tamAncho = 495,
            tamAlto = 371;
            //mouse enter, mouse leave			
            slide.mouseleave(function(){
                if(numeroPics > 0){
                    //cntOptSlide.addClass('hide');
                }
            }).mouseenter(function(){
                if(numeroPics > 0){
                    //cntOptSlide.removeClass('hide');
                }					
            });
            //valida flechas
            if(numeroPics <= itemsBlock){
                prev.hide();
                next.hide()
            }else{	
                prev.show();
                next.show();
            }
            //Carga inicial
            //carousel
            //var count = 1,
            indice = parseInt(indice);
            var loadCount = Math.ceil(indice/itemsBlock),
            count = loadCount,
            cntGallery = $('#listGallery');
            cntGallery.css({
                'width': anchoSlide*nClicks,
                'left' : -anchoSlide*(loadCount-1)
            });		
            //valores inicales
            aOptSlide.eq(indice-1).children('img').addClass('scaleImg inactive');
            //Facebook, Twitter Compartir
            var urlSocial = aOptSlide.eq(indice-1).attr('url'),
            twitter = $('#twitterUrl'),
            urlTwitter = 'http://twitter.com/home?status=',
            facebook = $('#facebookUrl'),
            urlFacebook = 'http://www.facebook.com/sharer.php?u=';                    
            twitter.attr('href', urlTwitter + urls.siteUrl + urlSocial);
            facebook.attr('href', urlFacebook + urls.siteUrl + urlSocial);             
            
            if(indice > itemsBlock){
                prev.show();
                if(indice > itemsBlock*(nClicks-1) && indice <= itemsBlock*(nClicks)){
                    next.hide();
                }else{
                    next.show();
                }
            }else{
                prev.hide();
            }
            //primera
            cntImgBig.addClass('loading');
            
            slide.find('.slider').html(''+			  
                '<div class="slider">' +
                '  <img indice="' + indice + '" class="imgflujo" src="' + aOptSlide.eq(indice-1).attr("rel") + '" width="'+ tamAncho +'" height="'+ tamAlto +'" onerror="this.src=' + "'http://s.devel.clubsc.info/images/vida_social_g.jpg'" + '"/>' +
                '</div><div class="descriptive-text">'+aOptSlide.eq(indice-1).attr('lang')+'</div>').removeClass('hide');

            // teclado 
            $(document).keyup(function(e){
                var key = e.keyCode || e.charCode || e.which || window.e ;
                // izquierda
                if(key == 37){
                    if(prev.is(':visible')){
                        prevArrow(prev);        		
                    }
                }
                // derecha
                if(key == 39){
                    if(next.is(':visible')){
                        nextArrow(next);		
                    }
                }
            });			
            // prev - next
            next.click(function(e){
                e.preventDefault();
                e.stopPropagation();
                var t = $(this);
                //Clicks
                nextArrow(t);					
            });
			
            function nextArrow(self){
                if(nClicks > 1 && count < nClicks){
                    count++;
                    cntGallery.animate({
                        'left':-(anchoSlide*(count-1))
                    });
                    if(count > 1){
                        (count>=nClicks)?self.hide():'';
                        prev.show();
                    }
                }				
            }
            prev.click(function(e){
                e.preventDefault();
                e.stopPropagation();
                var t = $(this);
                //Clicks
                prevArrow(t);
            });
			
            function prevArrow(self){
                if(nClicks > 1 && count > 0){
                    count--;
                    cntGallery.animate({
                        'left':-anchoSlide*(count-1)
                    });
                    if(count > 0){
                        (count <= 1)?self.hide():'';
                        next.show();
                    }
                }
            }
						
            //click foto
            aOptSlide.click(function(e){
                e.preventDefault();
                e.stopPropagation();
                var t = $(this),
                indice = t.children('img').attr('indice');				
                if(!(t.hasClass('scaleImg'))){
                    var urlImg = $.trim(t.attr('rel')),
                    textImg = t.attr('lang'),
                    ancho = 495,
                    alto = 371;					
                    aOptSlide.children('img').removeClass('scaleImg inactive');
                    t.children('img').addClass('scaleImg inactive');
                    cntImgBig.empty().addClass('loading');
                    cntImgBig.html('' +
                        '<div class="slider">'+'<img indice="' + indice + '" class="imgflujo" src="' + urlImg + '" width="' + ancho + '" height="' + alto + '" onerror="this.src=' + "'http://s.devel.clubsc.info/images/vida_social_g.jpg'" + '"/>' +
                        '</div><div class="descriptive-text">'+textImg+'</div>'+'');
                    // Url
                    //window.history.pushState("objectOrstring", "Title", "/new-url");
                    //Add URL Social
                    twitter.attr('href', urlTwitter + urls.siteUrl + t.attr('url'));
                    facebook.attr('href', urlFacebook + urls.siteUrl + t.attr('url'));                
                }		
            });
        }
        this.compartirMail = function(){
            var aArticulo = $('.aShareArticulo'),
            //btnShare  = $('#fSendCA'),
            hdnOculto = $('input#hdnOculto');
            aArticulo.bind('click',function(){
                hdnOculto.val($(this).attr('rel'));
            });
        };
    };   
    // init
    var objart = new Articulo();
    objart.viewDetailArticulo('.verDetailArticulo');
    objart.loadArt();
    objart.compartirMail();
});