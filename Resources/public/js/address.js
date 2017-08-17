$(function(){

    //$.fn.select2.defaults.set('language', 'fr');

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

        // Needed for french translation
        // Select2 use by default english translation
        // TODO: rework this and try to configure select2 to use fr language by default
        var language = {
            inputTooLong: function (e) {
                var t = e.input.length - e.maximum, n = "Supprimez " + t + " caractère";
                return t !== 1 && (n += "s"), n
            }, inputTooShort: function (e) {
                var t = e.minimum - e.input.length, n = "Saisissez " + t + " caractère";
                return t !== 1 && (n += "s"), n
            }, loadingMore: function () {
                return "Chargement de résultats supplémentaires ..."
            }, maximumSelected: function (e) {
                var t = "Vous pouvez seulement sélectionner " + e.maximum + " élément";
                return e.maximum !== 1 && (t += "s"), t
            }, noResults: function () {
                return "Aucun résultat trouvé"
            }, searching: function () {
                return "Recherche en cours ..."
            }
        };

        var tradInputTooShort = $select.data('trans-inputtooshort');
        if (tradInputTooShort !== undefined)
            language.inputTooShort = function (args) { return tradInputTooShort; };

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
            },
            language: language
        });

        $select.change(function(){
            var data = $(this).select2('data')[0];
            if (typeof data['name'] != 'undefined') {
                $target.val(data['name']);
            }
        });
    });

});
