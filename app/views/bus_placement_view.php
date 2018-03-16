<?
foreach ($data['transport']['placement'] as $rows){
    echo '<section class="row">';
        foreach ($rows as $place) {
            if ($place == null)
                echo '<div class="space"></div>';
            else
                if (in_array($place, $data['ordered']))
                    echo '<span class="place booked" place-id="' . $place .'">' . $place . '</span>';
                else
                    echo '<span class="place" place-id="'. $place .'">' . $place . '</span>';
        }
    echo '</section>';
}
