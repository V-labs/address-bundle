$(function(){

    $('[data-provider="address"]').each(function(){

        var source = this;
        var $source = $(source);
        var targetID = $(this).data('target');
        var $target = $('[data-source="' + targetID + '"]');

        var selectData = [];
        if ($source.val()) {
            selectData = [{
                id: $source.val(),
                text: $target.val() ? $source.val() + ' (' + $target.val() + ')' : $source.val(),
                selected: true
            }];
        }

        var $select = $('<select>');
        for (var x in source.attributes) {
            var attribute = source.attributes[x];
            $select.attr(attribute.name, attribute.value);

        }
        $source.replaceWith($select);

        $select.select2({
            width: '100%',
            data: selectData,
            minimumInputLength: 2,
            allowClear: false,
            ajax: {
                url: Routing.generate('vlabs_address_get_cities'),
                dataType: 'json',
                delay: 200,
                data: function (params) {
                    return {
                        q: params['term']
                    };
                },
                processResults: (function (data) {
                    if (data['cities']) {
                        for (var x in data['cities']) {
                            var city = data['cities'][x];
                            city['id'] = city['zip_code'];
                            city['text'] = city['zip_code'] + ' (' + city['name'] + ')';
                        }
                    }
                    return {
                        results: data['cities']
                    };
                }).bind(this)
            }
        });

        $select.change(function(){
            var data = $(this).select2('data')[0];
            if (typeof data['name'] != 'undefined') {
                $target.val(data['name']);
            }
        });
    });

});
