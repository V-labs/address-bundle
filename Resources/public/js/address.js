$(function(){

    $('[data-provider="address"]').each(function(){

        var source = this;
        var $source = $(source);

        $source.select2({
            width: '100%',
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
                            city['id'] = city['id'];
                            city['text'] = city['zip_code'] + ' (' + city['name'] + ')';
                        }
                    }
                    return {
                        results: data['cities']
                    };
                }).bind(this)
            }
        });
    });
});
