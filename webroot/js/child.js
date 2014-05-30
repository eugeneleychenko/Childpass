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
    if (confirm("You really want to delete this photo?")) {
        $.ajax({
            url: Yii.app.createUrl('child-photo/delete'),
            data: {'photo_id': photo_item.attr('data-photo_id')},
            type: 'POST',
            success: function (response) {
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
}

$(function() {
    $('#relatives_table tbody').on('click', '.delete_relative', function() {
        deleteRelative($(this));
        return false;
    });

    $('#add_relative').on('click', function() {
        addNewRelative();
    });

    function addNewRelative() {
        var namePrefix = 'Relative[' + relativesNumber  + ']';
        var  select = $('#relatives_table tbody').find('tr').first().find('select').clone();
        select.removeAttr('id');
        select.attr('name', namePrefix + '[relation_id]');
        select.val('');
        var row =  '<tr><td><input name="' + namePrefix + '[first_name]"></td><td><input name="' + namePrefix + '[last_name]"> </td><td>' + select.text() + '</td><td><button type="type" class="button small delete_relative">-</button></td></tr>';
        $('#relatives_table').append(row);
        $('#relatives_table tbody').find('tr').last().find('td').eq(2).html(select);
        relativesNumber++;
    }

    function deleteRelative(button) {
        if (!confirm('Do you want to remove this relative?')) {
            return false;
        }

        var row = button.closest('tr');

        var childRelativeIdElement = row.find('.child_relative_id');


        if (!childRelativeIdElement.length || !childRelativeIdElement.val()) {
            row.remove();
            relativesNumber--;
            return false;
        }

        var relativeIdElement = row.find('.relative_id');
        var relativeId = relativeIdElement.val();
        if (!relativeId) {
            return false;
        }

        $.ajax({
            url: '/child/deleteRelativeMapping',
            data: {
                'childId' : childId,
                'relativeId' : relativeId
            },
            type: 'DELETE',
            success: function(data) {
                row.remove();
                relativesNumber--;
            },
            complete: function() {
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        });
}

});

