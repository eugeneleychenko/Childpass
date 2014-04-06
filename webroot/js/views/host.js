$(document).ready(function () {

    $('.internsList').on('click', '.addToFavoriteAction', function () {
        var itemId = $(this).closest('.intern').attr('data-itemId');
        var button = $(this);

        $.post(
            Yii.app.createUrl('favorite'),
            { 'internId': itemId }
        ).done(function (data) {
            button.closest('.intern').addClass('favorite');
            button.removeClass('addToFavoriteAction').addClass('removeFromFavoriteAction');
        }).fail(function (data) {
            alert('Ooops! Error occured: ' + data.responseText);
        }).always(function() {
            button.removeAttr('disabled');
        });

        $(this).attr('disabled', 'disabled');

        return false;
    }).on('click', '.removeFromFavoriteAction', function () {
        var itemId = $(this).closest('.intern').attr('data-itemId');
        var button = $(this);

        $.ajax({
            url: Yii.app.createUrl('favorite/deleteByUserId'),
            data: {'internId': itemId},
            type: 'DELETE',
            success: function (data) {
                button.closest('.intern').removeClass('favorite');
                button.removeClass('removeFromFavoriteAction').addClass('addToFavoriteAction');
            },
            error: function (data) {
                alert('Ooops! Error occured: ' + data.responseText);
            },
            complete : function() {
                button.removeAttr('disabled');
            }
        });

        $(this).attr('disabled', 'disabled');

        return false;
    });

    $(".basic").jRating({
        step: true,
        length: 5, // nb of stars
        bigStarsPath  : Yii.app.baseUrl + '/libs/jRating/jquery/icons/stars.png',
        smallStarsPath: Yii.app.baseUrl + '/images/star-small.png',
        sendRequest   : false,
        showRateInfo  : false,
        type          : 'small',
        rateMax       : 5,
        isDisabled    : true
    });

    $('.internsList .headerRow .checkmarkColumn input').click(function() {
        var value = this.checked;
        $('.internsList .intern .mainData .checkmarkColumn input').attr('checked', value == true);
    });

    $('.internsList .headerRow .columns[data-fieldName]').click(function() {
        var fieldName = $(this).attr('data-fieldName');
        var sortOrder = $('input[name=sort_order]', '#host-dashboard-filter-form');
        if (sortOrder.val() === fieldName + ' ASC') {
            sortOrder.val(fieldName + ' DESC');
        } else {
            sortOrder.val(fieldName + ' ASC');
        }

        $('#host-dashboard-filter-form').find('input[type=submit]').click();
    });


    function showSortMark() {
        var sortOrder = $('input[name=sort_order]', '#host-dashboard-filter-form').val();
        if (!sortOrder) {
            return;
        }

        sortOrder = sortOrder.split(' ');
        $('.internsList .headerRow .columns[data-fieldName=' + sortOrder[0] + ']').addClass('sorted-' + sortOrder[1]);
    }

    showSortMark();

    $('form.bulkActions').submit(function (event) {
        var selectedItems = [];
        var button = $(this).find('input[type=submit]');

        $('.internsList .intern .checkmarkColumn [type=checkbox]:checked').each(function() {
            selectedItems.push($(this).closest('.intern').attr('data-itemId'));
        });

        if (selectedItems.length == 0) {
            event.preventDefault();
            return;
        }

        var action = $(this).find('[name=action]').val();

        if (action == 'export') {
            exportInternsToCSV(selectedItems, this);
        } else {
            event.preventDefault();
        }
    });

    function exportInternsToCSV(items, form) {
        form.action = Yii.app.createUrl('host/export-interns-to-csv');

        $(form).find('[name=internsIds]').val(items.join(','));
    }
});