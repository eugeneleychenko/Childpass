(function($) {
    $(document).ready(function() {
        $('.questions').find('.query').each(
            function(index, query) {
                var $query = $(query);
                $query.find('input:radio').not($('.query-expanded input:radio')).change(
                    function() {
                        var
                            $this = $(this),
                            $query = $(query),
                            $expForm = $query.find('.query-expanded');
                        if ($this.val() === $query.data('expand-on-value')) {
                            $expForm.show();
                        } else {
                            $expForm.hide();
                            $expForm.find("input[type='radio']").prop('checked', 0);
                            $expForm.find("input[type='text']").val('');
                            $expForm.find("textarea").val('');
                        }
                    }
                );
            }
        );
    });
})(jQuery);
