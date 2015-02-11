function c(obj){
    console.log(obj)
}

$(document).ready(function(){



    //Таймер

    if($("#timer")){
        /*$("#timer").countdown({
            date: "12:00 september 26, 2013",
            htmlTemplate: " <div><span class='time-days'>%d</span>дней</div> <div><span class='timer-hours'>%h</span>часов</div> <div><span class='timer-mins'>%i</span>минут</div>",
            offset: 1,
            onComplete: function( event ){
		$(this).html("00 : 00 : 00");
            },
            leadingZero: true
        });*/
    };
    
    //Слайдер на главной
    if($("#bigSlider .slides").size()>0){
        $(".slideNext .previewPhoto").attr('src',$('.slides .slide:eq(1)').find('img').attr('src'));
        $("#bigSlider .slides").carouFredSel({
            auto   : false,
            scroll : {
                    items : 1,
                    duration : 500,
                    pauseDuration : 10000,
                    onBefore : function(){
                            $(".slideText").stop().animate({opacity: 0},100);
                            $(".previewPhoto,.nextText").stop().animate({opacity: 0},500);
                    },
                    onAfter  : function(){
                            $(".slide").each(function(n) {
                                $(this).attr("id", "item"+n);
                            });
                            var text = $('#item1 a span span').html(),
                                image = $('#item1 img').attr('src').replace('big','small');
                            $(".slideNext .nextText span").html(text);
                            $(".slideNext .previewPhoto").attr('src',image);
                            $(".previewPhoto,.nextText").stop().animate({opacity: 1},500);
                            $(".slideText").stop().animate({opacity: 1},1000);
                    }
            },
            prev : ".slideBack",
            next : ".slideNext"
        });
    }
    
    //Слайдер партнеров
    if($("#partners").size()>0){
        $(".partners").carouFredSel({
            auto   : true,
            scroll : {
                    items : 8,
                    duration : 300,
                    pauseDuration : 6000
            },
            prev : ".partnerLeft",
            next : ".partnerRoght"
        });
    }
    //Слайдер расписания
    if($(".scheduleSlider").size()>0){
        $(".scheduleSlider").carouFredSel({
            auto   : true,
            scroll : {
                    items : 1,
                    duration : 400,
                    pauseDuration : 6000
            },
            prev : ".scheduleLeft",
            next : ".scheduleRight"
        });
    }

    //Меню
    var timeOff;
    $('#catalog .dopMenu .dopMenuUl').each(function(){if($(this).find('li').size()>0){$(this).parent().find('img.showCategory').show()}})
    $('#shops .dopMenu .dopMenuUl li a').each(function(){var $this=$(this); $this.text($this.text().split(',')[0])})
    $('.menu > li > a').hover(function(){
        clearTimeout(timeOff);
        if(!$(this).next('.dopMenu').hasClass('open') || !$(this).hasClass('active')){
            if($('.dopMenu.open').size() > 0){
                $('.dopMenu').removeClass('open').stop().slideUp(100);
                $('.menu > li > a.active').removeClass('active');
            }else{
                $('.menu > li > a.active').removeClass('active');
            }
            $(this).addClass('active').next('.dopMenu').addClass('open').stop().slideDown(500);
            
        }

    },function(){

        if($(this).next('.dopMenu').size() > 0){
            timeOff = setTimeout(function(){
                $('.dopMenu').removeClass('open').stop().slideUp('normal', function(){$('.menu > li > a.active').removeClass('active');});
            },10);
        }else{
            $(this).removeClass('active');
        }

    });
    $('.dopMenu').hover(function(){
        clearTimeout(timeOff);
    },function(){
        timeOff = setTimeout(function(){
            $('.dopMenu').removeClass('open').slideUp('normal',function(){$('.menu > li > a.active').removeClass('active');});
           // $('.dopMenu').find('.open_ul').slideUp('normal').removeClass('open_ul')
        },300);
    });

    //Подпункты в выпадающих списках меню
    $('.main_link').click(function () {

        var $this = $(this),
             current_block_ul = $this.parent().find('.dopMenuUl')
        if (current_block_ul.hasClass('open_ul')) {
            $(this).find('.transform').removeClass('transform')
            current_block_ul.slideUp('800').removeClass('open_ul')
        } else {
            $this.parents('.dopMenu').find('.open_ul').slideUp('800').removeClass('open_ul')
            $this.parents('.dopMenu').find('.transform').removeClass('transform')
            current_block_ul.slideDown('800').addClass('open_ul')
            $this.find('img').addClass('transform')
        }
    })
    //Отзывы
    $('button.showReviewsForm').click(function(){
        var $this = $(this)
        if($('.review_form_add').hasClass('bounceIn')){
            $('.review_form_add').removeClass('bounceIn').addClass('bounceOut')
            }
        else{$('.review_form_add').removeClass('bounceOut').addClass('bounceIn').show().focus();
            if($('.review_form_add').is(":visible"))$('.review_form_add').click();}
    })
//    //Войти
//    $('#enter').click(function(){
//        if($('.enter').is(':hidden')){
//            $('#enter span').css('border','none');
//            $('.mask').show();
//            $('.enter').stop().fadeIn();
//        }else{
//            $('.mask').hide();
//            $('.enter').stop().fadeOut('normal',function(){$('#enter span').css('border-bottom','');});
//        }
//        return false;
//    });
//    $('.mask').click(function(){
//        if(!$('.enter').is(':hidden')){
//            $('#enter').click();
//        }
//    });

    //Переключение типа поля
    $('.eye').click(function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active').prev('input').attr('type','password');
        }else{
            $(this).addClass('active').prev('input').attr('type','text');
        }
    });

    //Навигация
//    if($('.navigation').size() > 0){
//        if($('.navigation a.active').parents('ul').hasClass('dropMenu')){
//            $('.navigation a.active').parents('ul.dropMenu').show().parent('li').addClass('opened');
//        }else if($('.navigation a.active').parents('ul').hasClass('navigation')){
//            if($('.navigation a.active').next('ul').size() > 0){
//                $('.navigation a.active').parent('li').addClass('opened');
//                $('.navigation a.active').next('ul').slideDown();
//            }else{
//                $('.navigation a.active').parent('li').addClass('active');
//            }
//        }
//    
//        if($('.leftCol').height() > $('.rightCol').height()){
//            //alert($('.leftCol').height());
//            setTimeout(function(){
//                $('.twoCol').css('min-height',$('.leftCol').height() - 12);
//            },100);
//        }
//        
//    }

    $('.navigation > li > a').click(function(){
        if($('.navigation .opened').hasClass('active')){
            $('.navigation .opened').removeClass('opened');
        }else{
            $('.navigation .opened ul').stop().slideUp();
            $('.navigation .opened').removeClass('opened');
        }
        if($(this).next('ul').size() > 0){
            
            $(this).parent('li').addClass('opened');
            $(this).next('ul').stop().slideDown();
            return false;
        }else{
            $(this).parent('li').addClass('opened');
        }
        if($('.leftCol').height() > $('.rightCol').height()){
            $('.twoCol').css('min-height',$('.leftCol').height() - 6);
        }
        return false;
    });

    // Select
    $('.slct,.selectArrow').click(function(){
	var dropBlock = $(this).parent().find('.drop');
	if( dropBlock.is(':hidden') ) {
                $('.slct.active').click();
                $('.mask2').css('display','block');
		dropBlock.slideDown();
		$(this).parent('.select').find('.slct').addClass('active');
                $(this).parent('.select').find('.selectArrow').addClass('active');
		$('.drop').find('li').click(function(f){
                    f.preventDefault();
                    var selectResult = $(this).html();
                    $(this).parent().parent().find('input').val(selectResult);
                    var element = $(this);
                    element.parent().parent().find('.slct').html(selectResult);
                    dropBlock.slideUp('normal',function(){
                        element.parent().parent().find('.slct').removeClass('active');
                        element.parent().parent().find('.selectArrow').removeClass('active');
                    });
                    $('.mask2').css('display','');
		});
	} else {
                var element = $(this);
		dropBlock.slideUp('normal',function(){
                        element.parent().parent().find('.slct').removeClass('active');
                        element.parent().parent().find('.selectArrow').removeClass('active');
                    });
                $('.mask2').css('display','');
	}
 	return false;
    });
    $('.mask2').click(function(){
        $('.slct.active').click();
    });

    //Правила заливки работ
    $('.rules').click(function(){
        $('.requirements,.mask3').stop().fadeIn();
        return false;
    });
    $('.mask3,.closePopup').click(function(){
        if(!$('.requirements').is(':hidden')){
            $('.requirements,.mask3').stop().fadeOut();
        }
        return false;
    });

    //Загрузка файлов
    $('.btnLoad').click(function(){
        $(this).prev('.fileLoading').click();
        return false;
    });
    $('.fileLoading').change(function(){
        if(!$(this).val() == ''){
            var nameFile = $(this).val();
            $(this).parents('.fileLoad').append('<span class="fileName">'+nameFile+'<i class="delFile"></i></span>');
            $(this).parents('.fileLoad').find('.noSelect').fadeOut();
        }else{
            $(this).parents('.fileLoad').find('.noSelect').fadeIn();
        }
    });
    $(document).on("click", ".delFile", function(){
        $(this).parent('span').remove();
        if(!$('.fileName').size() > 0){
            $('.noSelect').fadeIn();
        }
    });
    
    //Разворачивание подкатегорий
    $('.titleToggle a').click(function(){
        if($(this).parents('.toggle').find('.toggleblock').is(':hidden')){
            $(this).parents('.toggle').find('.toggleblock').stop().slideDown();
        }else{
            $(this).parents('.toggle').find('.toggleblock').stop().slideUp();
        }
        return false;
    });
});


//Маштабирование контента
jQuery(function() {
    $.fn.resizeContent = function() {
        //$('.slides');
        var bodyWidth = $(window).width(),
        bodyHeight = $(window).height();
        if($('.twoCol').size() > 0){
            $('.leftCol').css('minHeight',$('.rightCol').height() + 6);
        }
        
    };
    $(this).resizeContent();
    $(window).resize(function(){
        $(this).resizeContent();
    });
});