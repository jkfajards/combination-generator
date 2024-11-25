<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $lotto_mode = 5;
    define('COMBINATIONS', 5);
    define('MAX_DIGITS', $lotto_mode);

    //define('WINNING_NUMBERS', [8, 9, 11, 27, 36, 38]);
    //define('WINNING_NUMBERS', [13, 14, 19, 24, 28]);
    define('WINNING_NUMBERS', [7, 15, 18, 24, 29, 31, 34]);

    switch( $lotto_mode ) {
        case 5 :
            define('TICKET_PRICE', 200);
            define('MIN', 1);
            define('MAX', 31);
            define('MIN_TOTAL', 69);
            define('MAX_TOTAL', 92);
            define('TOP_NUMBER', explode(',', '11,19,14,2,21,23,30,16,22,31,27,3,5,18,4')); // lotto 5
            define('PRIZE_POOL', [3 => 1000, 4 => 10700, 5 => 13000800]);
        break;
        case 6 :
            define('TICKET_PRICE', 200);
            define('MIN', 1);
            define('MAX', 43);
            define('MIN_TOTAL', 113);
            define('MAX_TOTAL', 148);
            define('TOP_NUMBER', explode(',', '2,24,6,42,21,43,38,19,32,26,10,37,27,15,5')); // lotto 6
            define('PRIZE_POOL', [3 => 1000, 4 => 5800, 5 => 280000, 6 => 300100700]);
        break;
        case 7 :
            define('TICKET_PRICE', 300);
            define('MIN', 1);
            define('MAX', 37);
            define('MIN_TOTAL', 113);
            define('MAX_TOTAL', 140);
            define('TOP_NUMBER', explode(',', '15,13,9,30,4,32,34,26,17,35,36,29,31,11,21')); // lotto 7
            define('PRIZE_POOL', [3 => 1000, 4 => 1400, 5 => 8600, 6 => 727400, 7 => 748847700]);
        break;
    }

    $hits = 0;
    $current_combo;
    $x = 1;
    $total_price = 0;
    generate();

    function generate() {  
        global $current_combo, $x, $hits, $total_price;
    
        $max_digits = MAX_DIGITS;
        $current_combo = array();

        $hits = 0;
        $is_top = (mt_rand(1, 100) <= 70) ? 0 : 1;
        if($is_top) {
            // get from top numbers
            $rand = rand(1, 3);

            $max_digits = $max_digits - $rand;
            for($num = 1; $num <= $rand; $num++) {
                $current_combo[] = addHits(topRand());
            }
        }

        // generate 6digits
        for($num = 1; $num <= $max_digits; $num++) {
            $current_combo[] =  addHits(random());
        }

        $total = array_sum($current_combo);

        if($total >= MIN_TOTAL && $total <= MAX_TOTAL) {
            sort($current_combo);
            if($hits >= 3) { 
                $total_price += PRIZE_POOL[$hits];
            }
            echo $total.'【'.implode(', ', $current_combo).'】（'.$hits.' hits）'.$is_top.'<br /><br />';

            $x++;
        }

        if($x <= COMBINATIONS) {
            generate();
        }
        return;
    }

    echo 'Ticket Price : '.number_format(COMBINATIONS*TICKET_PRICE).'円<br/>';
    echo 'Total Price : '.number_format($total_price).'円';

    function addHits($num) {
        global $hits;
        if(in_array($num, WINNING_NUMBERS)) {
            $hits++;
        }
        return $num;
    }

    function topRand() {
        global $current_combo;

        $num = TOP_NUMBER[rand(0, (count(TOP_NUMBER) - 1) )];

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