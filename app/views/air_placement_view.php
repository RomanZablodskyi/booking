<?
    $arr = $data['transport']['placement'];
    $classes = ['fplace', 'place'];

    for($i = 0; $i < count($arr); $i++){
        foreach ($arr[$i] as $salon){
            foreach ($salon as $row) {
                echo '<section class="row">';
                foreach ($row as $place) {
                    if ($place == null)
                        echo '<div class="space"></div>';
                    else {
                        if (in_array($place, $data['ordered']))
                            echo '<span class="' . $classes[$i] . ' booked" place-id="' . $place .'">' . $place . '</span>';
                        else
                            echo '<span class="' . $classes[$i] . '" place-id="'. $place .'">' . $place . '</span>';
                    }
                }
                echo "</section>";
            }
        }
    }




