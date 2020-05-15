<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('sale');

$regions = [];
$res = \Bitrix\Sale\Location\LocationTable::getList(array(
    'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID, '=TYPE.CODE' => 'REGION'/*, 'PARENT_ID' => 61*/), //Урал
    'select' => array('*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE')
));
while ($item = $res->fetch()) {
    $regions[] = $item;
}

$cities = [];
$res = \Bitrix\Sale\Location\LocationTable::getList(array(
    'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID, '=TYPE.CODE' => 'CITY'), //Московская область
    'select' => array('*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE')
));
while ($item = $res->fetch()) {
    $cities[] = $item;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Зависимые списки</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css"
          rel="stylesheet">
</head>
<body>
<style>
    body {
        background-color: #d5dbdf;
    }

    select {
        display: none;
    }

    .select2-dropdown.select2-dropdown--below {
        padding: 0;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        background-color: #fff;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    .select2-results__options {
        padding: 0;
        margin: 0;
        max-height: 177px;
        overflow: auto;
    }

    .select2-results__option {
        background-color: transparent;
        cursor: pointer;
        height: 36px;
        position: relative;
        outline: 0;
        white-space: nowrap;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
        overflow: hidden;
        width: 100%;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        display: inline-block;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        color: #525c68;
        line-height: 36px;
        padding: 0 15px;
        text-decoration: none;
        vertical-align: middle;
        -webkit-transition: color .2s linear;
        -o-transition: color .2s linear;
        transition: color .2s linear;
    }

    .select2-results__option:hover {
        background-color: #f6f8f9;
    }

    .select2-container {
        width: 385px !important;
        position: relative;
        display: block;
        margin-bottom: 10px;
        color: #424956;
        word-break: break-word;
        font: 15px/17px "Helvetica Neue", Helvetica, Arial, sans-serif;
        transition: 220ms opacity linear;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        -webkit-box-align: center;
        align-items: center;
    }

    .select2-container--open {
        background-color: #fff;
        -webkit-box-shadow: 0 7px 21px rgba(83, 92, 105, .12), 0 -1px 6px 0 rgba(83, 92, 105, .06);
        box-shadow: 0 7px 21px rgba(83, 92, 105, .12), 0 -1px 6px 0 rgba(83, 92, 105, .06);
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-pack: stretch;
        -ms-flex-pack: stretch;
        justify-content: stretch;
    }

    .selection {
        width: 100%;
    }

    .select2-selection__rendered {
        position: relative;
        padding: 10px 26px 10px 9px;
        background-image: none !important;
        cursor: pointer;
        min-width: 80px;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        display: block;
        box-sizing: border-box;
        min-height: 39px;
        max-width: 100%;
        width: 100%;
        outline: 0;
        border: 1px solid #c4c7cc;
        border-radius: 1px;
        background-color: #fff;
        color: #424956;
        font: 15px/17px "Helvetica Neue", Helvetica, Arial, sans-serif;
        transition: 220ms all ease;
    }

    .select2-selection__rendered:before, .select2-selection__rendered:after {
        position: absolute;
        top: 50%;
        left: calc(100% - 3px);
        width: 8px;
        height: 2px;
        background-color: #979797;
        content: "";
        transition: all 250ms ease;
        -webkit-transform-origin: center;
        -ms-transform-origin: center;
        transform-origin: center;
    }

    .select2-selection__rendered:before {
        -webkit-transform: translate(-50%, -50%) rotate(45deg);
        -ms-transform: translate(-50%, -50%) rotate(45deg);
        transform: translate(-50%, -50%) rotate(45deg);
        margin-left: -15px;
    }

    .select2-selection__rendered:after {
        -webkit-transform: translate(-50%, -50%) rotate(-45deg);
        -ms-transform: translate(-50%, -50%) rotate(-45deg);
        transform: translate(-50%, -50%) rotate(-45deg);
        margin-left: -10px;
    }

    .select2-container--open .select2-selection__rendered:before {
        -webkit-transform: translate(-50%, -50%) rotate(-45deg);
        -ms-transform: translate(-50%, -50%) rotate(-45deg);
        transform: translate(-50%, -50%) rotate(-45deg);
        margin-left: -15px;
    }

    .select2-container--open .select2-selection__rendered:after {
        -webkit-transform: translate(-50%, -50%) rotate(45deg);
        -ms-transform: translate(-50%, -50%) rotate(45deg);
        transform: translate(-50%, -50%) rotate(45deg);
        margin-left: -10px;
    }

    .select2-search__field {
        width: 100%;
        height: 36px;
        border: none;
        font: 15px/17px "Helvetica Neue", Helvetica, Arial, sans-serif;
        padding: 0 15px 0 9px;
    }

    .select2-search__field:focus {
        outline: none;
    }

    .select2-selection:focus {
        outline: none;
    }

    .select2-selection__rendered:hover, .select2-selection__rendered:active, .select2-container--open .select2-selection__rendered {
        border-color: #64a6f3;
    }

    .select2-results__options [aria-selected=true] {
        background-color: #f6f8f9;
        color: #525c68;
        font-weight: bold;
    }

    .mCSB_dragger_bar {
        width: 8px !important;
        background-color: rgba(0, 0, 0, .5) !important;
    }

    .mCSB_scrollTools .mCSB_draggerRail {
        background: transparent !important;
    }

    .mCSB_inside > .mCSB_container {
        margin-right: 12px;
    }
</style>
<input type="text" id="address" name="address" style="width: 100%;"/>
<div>
    <select name="region" id="region">
        <option value="0">Выберите регион</option>
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region['ID'] ?>"><?= $region['NAME_RU'] ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div>
    <select name="city" id="city">
        <option value="0">Выберите город</option>
        <?php foreach ($cities as $city): ?>
            <option data-region-id="<?= $city['REGION_ID'] ?>"
                    value="<?= $city['ID'] ?>"><?= $city['NAME_RU'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
<script>
    (function (Module, $) {
        var optionRegionSelect,
            optionCitySelect,
            data = {
                cities: {},
                regions: {}
            };

        function initSelectCity(region) {
            var html = [];
            for (var key in data.cities) {
                if (data.cities[key].REGION_ID === region) {
                    var option = $('<option value="' + data.cities[key].ID + '"></option>');
                    option.append(data.cities[key].NAME_RU);
                    html.push(option);
                }
            }
            html.unshift('<option value="0">Выберите город</option>');
            $("#city").html(html);
        }

        function initSelectsCustomScrollbars() {
            var Results = $.fn.select2.amd.require._defined['select2/results'];

            function initSelectScrollbar($results) {
                $results.mCustomScrollbar({axis: "y", advanced: {updateOnContentResize: true}});
            }

            Results.prototype.clear = function () {
                this.$results.mCustomScrollbar('destroy');
                this.$results.empty();
                initSelectScrollbar(this.$results);
            };

            Results.prototype.append = function (data) {
                this.$results.mCustomScrollbar('destroy');

                this.hideLoading();

                var $options = [];

                if (data.results == null || data.results.length === 0) {
                    if (this.$results.children().length === 0) {
                        this.trigger('results:message', {
                            message: 'noResults'
                        });
                    }

                    return;
                }

                data.results = this.sort(data.results);

                for (var d = 0; d < data.results.length; d++) {
                    var item = data.results[d];

                    var $option = this.option(item);

                    $options.push($option);
                }

                this.$results.append($options);

                initSelectScrollbar(this.$results);
            };

            $('select')
                .select2({
                    language: {
                        noResults: function () {
                            return "Совпадения не найдены";
                        }
                    }
                })
                .on("select2:open", function () {
                    var self = $(this);

                    if (!self.data('select2').$results.find('.mCSB_container').length) {
                        setTimeout(
                            function () {
                                self.data('select2').$results.mCustomScrollbar({
                                    axis: "y",
                                    advanced: {updateOnContentResize: true}
                                });
                            },
                            1
                        )
                    }
                });
        }

        $.extend(Module, {
            init: function init(options) {
                $.extend(data, options.data);

                initSelectsCustomScrollbars();

                $("#region").change(function () {
                    initSelectCity($(this).val());
                });

                $("#city").change(function () {
                    optionRegionSelect = $('#region option:checked');
                    optionCitySelect = $('#city option:checked');
                    var regionName = optionRegionSelect.text() === 'Выберите регион'
                        ? ''
                        : optionRegionSelect.text() + ', ';
                    var cityName = optionCitySelect.text();
                    $('#address').val(regionName + cityName);
                });
            }
        });
    })((Locations = window.Locations || {}) || {}, $);

    Locations.init({
        data: {
            cities: <?= json_encode($cities)?>,
            regions: <?= json_encode($regions)?>
        }
    });
</script>
</body>
</html>