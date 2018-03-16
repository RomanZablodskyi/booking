<h2 class="theme">Додавання маршруту</h2>

<? if(isset($_SESSION['route_status'])):?>
    <p><?=$_SESSION['route_status']?></p>
<? endif;?>

<form action="/suser/create_route" method="post" name="addRoute">
    <section class="schedule">

        <section class="daySchedule">
            <p>Понеділок</p>
            <input type="checkbox" name="monday" value="1">
            <span>Час відправлення:</span><input type="time" name="mtime">
            <span>Тривалість маршруту:</span><input type="time" name="mdur">
        </section>
        <section class="daySchedule">
            <p>Вівторок</p>
            <input type="checkbox" name="tuesday" value="2">
            <span>Час відправлення:</span><input type="time" name="ttime">
            <span>Тривалість маршруту:</span><input type="time" name="tdur">
        </section>
        <section class="daySchedule">
            <p>Середа</p>
            <input type="checkbox" name="wednesday" value="3">
            <span>Час відправлення:</span><input type="time" name="wtime">
            <span>Тривалість маршруту:</span><input type="time" name="wdur">
        </section>
        <section class="daySchedule">
            <p>Четвер</p>
            <input type="checkbox" name="thursday" value="4">
            <span>Час відправлення:</span><input type="time" name="thtime">
            <span>Тривалість маршруту:</span><input type="time" name="thdur">
        </section>
        <section class="daySchedule">
            <p>П'ятниця</p>
            <input type="checkbox" name="friday" value="5">
            <span>Час відправлення:</span><input type="time" name="ftime">
            <span>Тривалість маршруту:</span><input type="time" name="fdur">
        </section>
        <section class="daySchedule">
            <p>Субота</p>
            <input type="checkbox" name="saturday" value="6">
            <span>Час відправлення:</span><input type="time" name="stime">
            <span>Тривалість маршруту:</span><input type="time" name="sdur">
        </section>
        <section class="daySchedule">
            <p>Неділя</p>
            <input type="checkbox" name="sunday" value="7">
            <span>Час відправлення:</span><input type="time" name="sntime">
            <span>Тривалість маршруту:</span><input type="time" name="sndur">
        </section>
    </section>

    <section class="prices">
        <label for="price">Ціна</label>
        <input type="number" min="1" name="price" id="price">
        <label for="bs_price">Ціна (бізнес-клас)</label>
        <input type="number" min="1" name="bc_price" id="bs_price">
    </section>

    <section class="origin">
        <span>Місце відправлення</span>
        <select name="ocountry">
         <? foreach ($data['countries'] as $countries):?>
            <option value="<?=$countries['country_id']?>"><?=$countries['country_name']?></option>
         <? endforeach;?>
        </select>
        <select name="ocity"></select>
        <select name="oplaces"></select>
    </section>

    <section class="destination">
        <span>Місце прибуття</span>
        <select name="dcountry">
            <? foreach ($data['countries'] as $countries):?>
                <option value="<?=$countries['country_id']?>"><?=$countries['country_name']?></option>
            <? endforeach;?>
        </select>
        <select name="dcity"></select>
        <select name="dplaces"></select>
    </section>

    <section class="transport">
        <span>Транспорт</span>
        <select name="transport">
        <? foreach ($data['transports'] as $transport):?>
            <option type-id="<?=$transport['ttype_id']?>" value="<?=$transport['trans_id']?>"><?=$transport['trans_name']?></option>
        <? endforeach;?>
        </select>
    </section>

    <input type="hidden" name="coords">
    <input type="submit" value="Додати" name="addRoute"></form>

</form>

<section id="map"></section>
<button id="btn_cancel">Відмінити</button>
<button id="btn_clear">Очистити</button>

<script src="/web/scripts/maps/initMap.js"></script>
<script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEtSf3HH0j0zs5XY9gw2H7c4Q-5OMdJ0M&callback=initMap">
</script>
<script src="/web/scripts/maps/createMarkersRoute.js"></script>
<script src="/web/scripts/maps/createWaypointsRoute.js"></script>
<script>
    $(document).ready(
        function () {

            var places = <? echo json_encode($data['places'])?>,
                cities = <? echo json_encode($data['cities'])?>,
                o_CountrySelect = $('select[name=ocountry]'),
                o_CitySelect = $('select[name=ocity]'),
                o_PlaceSelect = $('select[name=oplaces]'),
                d_CountrySelect = $('select[name=dcountry]'),
                d_CitySelect = $('select[name=dcity]'),
                d_PlaceSelect = $('select[name=dplaces]'),
                transport = $('select[name=transport]');

            function setCitiesValues(elem, selectCity, selectPlaces) {
                var selectedCountry = elem.val();

                selectCity.find('option').remove();
                selectPlaces.find('option').remove();

                for(var i = 0; i < cities.length; i++){
                    if(cities[i]['country_id'] === parseInt(selectedCountry)){
                        selectCity.append($('<option>',{
                            value: cities[i]['city_id'],
                            text: cities[i]['city_name']
                        }))
                    }
                }

                setPlacesValues(selectCity.find('option'), selectPlaces);
            }

            function setPlacesValues(elem, selectPlaces) {
                var selectedPlaces = elem.val();
                selectPlaces.find('option').remove();

                for(var i = 0; i < places.length; i++){
                    if(places[i]['city_id'] === parseInt(selectedPlaces)){
                        selectPlaces.append($('<option>',{
                            value: places[i]['place_id'],
                            text: places[i]['place_name']
                        }))
                    }
                }

            }

            function deleteMapListeners() {
                route.clearMap();
                route.disableRoute();
                markers.clearMap();

                google.maps.event.clearListeners(map, 'click');

                $("#btn_cancel").unbind('click');
                $("#btn_clear").unbind('click');

            }

            function setMapListener(trans_type) {

                if(parseInt(trans_type) == 1){
                    deleteMapListeners();

                    map.addListener('click', function (event) {
                        markers.createMarker(event.latLng, 'Точка маршруту')
                    });

                    $("#btn_cancel").on("click", markers.clearLastMarker);
                    $("#btn_clear").on("click", markers.clearMap);

                }else{
                    deleteMapListeners();

                    route.createRoute();

                    $("#btn_cancel").on("click", route.cancelLastPoint);
                    $("#btn_clear").on("click", route.clearMap);
                }
            }

            setCitiesValues( o_CountrySelect, o_CitySelect, o_PlaceSelect);
            setCitiesValues( d_CountrySelect, d_CitySelect, d_PlaceSelect);
            setPlacesValues( o_CitySelect, o_PlaceSelect);
            setPlacesValues( d_CitySelect, d_PlaceSelect);

            setMapListener(transport.find('option:selected').attr('type-id'));

            o_CountrySelect.on('change', function () {
                setCitiesValues($(this), o_CitySelect, o_PlaceSelect);
            });

            d_CountrySelect.on('change', function () {
                setCitiesValues($(this), d_CitySelect, d_PlaceSelect);
            });

            o_CitySelect.on('change', function () {
                setPlacesValues($(this), o_PlaceSelect);
            });

            d_CitySelect.on('change', function () {
                setPlacesValues($(this), d_PlaceSelect);
            });

            transport.on('change', function () {
                var selectedType = $(this).find('option:selected').attr('type-id');

                setMapListener(selectedType);
            });

            $('form[name=addRoute]').on('submit', function () {
                var input = $(this).find('input[name=coords]');

                if(transport.find('option:selected').attr('type-id') == 1)
                    input.val(markers.getJSONCoords());
                else
                    input.val(route.getJSONCoords());
            });
    })
</script>