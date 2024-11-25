<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    define('COMBINATIONS', 150);
    define('MIN', 1);
    define('MAX', 31);
    define('MAX_DIGITS', 5);
    //define('TOP_NUMBER', explode(',', '2,24,6,42,21,43,38,19,32,26,10,37,27,15,5'));
    define('TOP_NUMBER', explode(',', '11,19,14,2,21,23,30,16,22,31,27,3,5,18,4'));

    define('MIN_TOTAL', 69);
    define('MAX_TOTAL', 104);

    $current_combo;
    $x = 1;
    generate();

    function generate() {  
        global $current_combo, $x;
    
        $max_digits = MAX_DIGITS;
        $current_combo = array();

        $is_top = (mt_rand(1, 100) <= 70) ? 1 : 0;
        if($is_top) {
            // get from top numbers
            $rand = rand(1, 3);

            $max_digits = $max_digits - $rand;
            for($num = 1; $num <= $rand; $num++) {
                $current_combo[] = random();
            }
        }

        // generate 6digits
        for($num = 1; $num <= $max_digits; $num++) {
            $current_combo[] = random();
        }

        $total = array_sum($current_combo);

        if($total >= MIN_TOTAL && $total <= MAX_TOTAL) {
            sort($current_combo);
            echo $total.'【'.implode(', ', $current_combo).'】<br /><br />';
            $x++;
        }

        if($x < COMBINATIONS) {
            generate();
           
        }
        return;
    }

    function topRand() {
        global $current_combo;

        $num = TOP_NUMBER[rand(0, count(TOP_NUMBER))];

        if(in_array($num, $current_combo)) {
            return topRand();
        }

        return $num;
    }

    function random() {
        global $current_combo;

        $num = rand(MIN, MAX);
        if(in_array($num, $current_combo)) {
            return random();
        }

        return $num;
    }

  
    
?>