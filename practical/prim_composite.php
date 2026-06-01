<?php
$num = 7;
$count= 0;

if ($num <= 1) {
    echo "Neither prime nor composite";

}else {
    for ($i =1; $i <=  $num; $i++) {
        if($num % $i == 0) {
            $count++;
        }
    }
    if ($count == 2) {
        echo "$num is prime";

    } else {
        echo "$num is composite";
    }
}

?>