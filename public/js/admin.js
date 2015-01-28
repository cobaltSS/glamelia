/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



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
                    this.hide();
                }});

}

