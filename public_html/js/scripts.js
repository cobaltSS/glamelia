function c(obj) {
    console.log(obj)
}

function ValidateRew()
{
    var valid = true;
    var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

    if (!pattern.test($('#email').val())) {
        $('#email').prev('.error').text('Проверьте почту');
        valid = false;
    }

    if (!$('#name').val()) {
        $('#name').prev('.error').text('Введите текст');
        valid = false;
    }

    if (!$('#rew').val()) {
        $('#rew').prev('.error').text('Введите текст');
        valid = false;
    }

    if (valid)
    {

        setTimeout(function () {
            $('error').text();
            alert('Спасибо за Ваше внимание к нам. Отзыв будет опублекован в течении дня.');
        }, 3);
        $('#reviews').submit();

    }

}

function alignPopup(selector)
{

    $(selector).css('top', 50 + 'px');

    var doc_scroll = $(document).scrollTop();

    if (doc_scroll > 0)
    {
        $(selector).css('top', doc_scroll + 50 + 'px');
    }
    $(selector).css('left', Math.ceil($(window).width() / 2) - Math.ceil($(selector).width() / 2));
}

$(document).ready(function () {
    
     //Active menu
    var url= window.location.pathname.split('/');
    var find=false;
     $('div#nav .head_menu').each(function(){
        if (this.href.indexOf('/'+url[1]) != -1 || $(this).parent('li').find('a[href*="'+url[1]+'"]').length>0) {
            $(this).addClass('active_menu');
            find=true;
        }
        if(find) return false;
  });
  
   
    $('#cn-slideshow').slideshow();
      
    //Отзывы
     $('.btn-close').click(function () {
        $('.review_form_add').removeClass('bounceIn').addClass('bounceOut');
    });
    $('.showReviewsForm').click(function () {
        var $this = $(this)
        if ($('.review_form_add').hasClass('bounceIn')) {
            $('.review_form_add').removeClass('bounceIn').addClass('bounceOut');
        }
        else {

            $('.review_form_add').removeClass('bounceOut').addClass('bounceIn').show().focus();

            $("#reviews :input[type='text'],input[type='email'],textarea").each(function () {
                $(this).val('');
            });

            alignPopup('.review_form_add');

            if ($('.review_form_add').is(":visible"))
                $('.review_form_add').click();
        }
    });
    
       
    //Переключение типа поля
    $('.eye').click(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active').prev('input').attr('type', 'password');
        } else {
            $(this).addClass('active').prev('input').attr('type', 'text');
        }
    });


    // Select
    $('.slct,.selectArrow').click(function () {
        var dropBlock = $(this).parent().find('.drop');
        if (dropBlock.is(':hidden')) {
            $('.slct.active').click();
            $('.mask2').css('display', 'block');
            dropBlock.slideDown();
            $(this).parent('.select').find('.slct').addClass('active');
            $(this).parent('.select').find('.selectArrow').addClass('active');
            $('.drop').find('li').click(function (f) {
                f.preventDefault();
                var selectResult = $(this).html();
                $(this).parent().parent().find('input').val(selectResult);
                var element = $(this);
                element.parent().parent().find('.slct').html(selectResult);
                dropBlock.slideUp('normal', function () {
                    element.parent().parent().find('.slct').removeClass('active');
                    element.parent().parent().find('.selectArrow').removeClass('active');
                });
                $('.mask2').css('display', '');
            });
        } else {
            var element = $(this);
            dropBlock.slideUp('normal', function () {
                element.parent().parent().find('.slct').removeClass('active');
                element.parent().parent().find('.selectArrow').removeClass('active');
            });
            $('.mask2').css('display', '');
        }
        return false;
    });
    $('.mask2').click(function () {
        $('.slct.active').click();
    });

});