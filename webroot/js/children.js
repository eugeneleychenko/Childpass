function afterSelect(e, v, m) {
    $('.MultiFile-remove').text('');
    var fileSize = e.files[0].size;
    if(fileSize > 1024*1024*10) {
        alert("Exceeds file upload limit 10MB");
        $(".MultiFile-remove:last").click();
    }
    return true;
}

function deleteChildPhoto(button) {
    button = $(button);
    var photo_item = button.closest('.child-photo');
    $.ajax({
        url: Yii.app.createUrl('childphoto/delete'),
        data: {'photo_id': photo_item.attr('data-photo_id')},
        type: 'POST',
        success: function (response) {
            response = jQuery.parseJSON(response);
            alert(response);
            if(response.success == true) {
                photo_item.remove();
            } else {
                alert('Error occurred. Please try again.');
            }
        },
        error: function (data) {
            alert('Error occurred. Please try again.');
        }
    });
}