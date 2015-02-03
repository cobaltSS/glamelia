$(document).ready(function (e)
{
    $('.btn-close').on('click', function ()
    {
        $(this).parents('.popup').hide();
        unfadeBG();
        return false;
    });
 
  if ($('#description').length)
       CKEDITOR.replace('description');
});



function DelPhoto(el, url, photo)
{
    $(el).parent('span').hide();
    $.ajax(
            {
                dataType: 'json',
                url: url,
                type: "POST",
                data: {
                    qString: photo
                },
                success: function ()
                {
                   // this.hide();
                }});

}

function DelSubcat(el, url, id)
{
    $.ajax(
            {
                dataType: 'json',
                url: url,
                type: "POST",
                data: {
                    qString: id
                },
                success: function ()
                {
                    $(el).parent('div.list').hide();
                }});

}


function EditSubcat(el, url, id, id_cat)
{
    var subname = $(el).parent('div.list').children('input').val();
    if (!subname)
    {
        $(el).parent('div.list').children('div').text('Пожалуйста введите название подкатегории');
        return false;
    } else {
        $.ajax(
                {
                    dataType: 'json',
                    url: url,
                    type: "POST",
                    data: {
                        id: id,
                        id_cat: id_cat,
                        name: subname
                    },
                    success: function ()
                    {
                    }});
    }

}

function ShowSubcat(el)
{
    var id_cat = $(el).val();
    url = '/admin/category/getsubcat';
    $.ajax(
            {
                dataType: 'json',
                url: url,
                type: "POST",
                data: {
                    id_category: id_cat
                },
                success: function (data)
                {
                    var subcat = '';
                    if (data)
                        jQuery.each(data.subcat, function (key, val) {
                            subcat += '<option value="' + this.id + '">' + this.name + '</option>';
                        });

                    if (subcat)
                        $('#subcategory_id').append(subcat);
                    else 
                         $('#subcategory_id :not(option:first)').remove();

                }});

}


function AddSubcat(id, url)
{
    var subname = $('#sub_val').val();
    if (!subname)
    {
        $('.error').text('Пожалуйста введите название подкатегории');
        return false;
    } else {
        $.ajax(
                {
                    dataType: 'json',
                    url: url,
                    type: "POST",
                    data: {
                        id_cat: id,
                        name: subname
                    },
                    success: function (sub)
                    {
                        unfadeBG();
                        $('#subcat').hide();
                        $('#subcategory').append(
                                '<div class="list">' +
                                '<div class="error"></div>' +
                                '<input type=text value=' + subname + '>' +
                                '<img  onClick="DelSubcat(this, \'' + sub.urldel + '\', \'' + sub.id_sub + '\')" src ="/img/backet.png">' +
                                '<img  onClick="EditSubcat(this, \'' + sub.urledit + '\', \'' + sub.id_sub + '\',\'' + id + '\')" src ="/img/ico/edit.png">  ' +
                                '</div>'
                                );
                        return true;

                    }});
    }


}



function ShowAddSubcat()
{
    fadeBG();
    alignPopup('#subcat');
    $('#subcat').show();

}

function unfadeBG()
{
    $("#overlay-bg").hide();
}
function fadeBG()
{
    $("#overlay-bg").show();
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






