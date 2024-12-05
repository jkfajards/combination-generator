<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $lotto_mode = 6;
    define('COMBINATIONS', 50);
    define('MAX_DIGITS', $lotto_mode);
    $generated_combos = [];

    switch( $lotto_mode ) {
        case 5 :
            define('WINNING_NUMBERS', [10, 12, 13, 21, 31]); // winning numbers
            define('BONUS_NUMBER', 23);

            define('TICKET_PRICE', 200);
            define('MIN', 1);
            define('MAX', 31);
            define('MIN_TOTAL', 69);
            define('MAX_TOTAL', 92);
            define('TOP_NUMBER', explode(',', '11,19,14,2,21,23,30,16,22,31,27,3,5,18,4')); // lotto 5
            define('PRIZE_POOL', [0=> 130300, 3 => 1000, 4 => 10600, 5 => 11538300]);
        break;
        case 6 :
            define('WINNING_NUMBERS', [19,23,29,33,36,40]);
            define('BONUS_NUMBER', 27);

            define('TICKET_PRICE', 200);
            define('MIN', 1);
            define('MAX', 43);
            define('MIN_TOTAL', 167);
            define('MAX_TOTAL', 184);
            define('TOP_NUMBER', explode(',', '2,24,6,42,21,43,38,19,32,26,10,37,27,15,5')); // lotto 6
            define('PRIZE_POOL', [0 => 9101900, 3 => 1000, 4 => 5800, 5 => 280000, 6 => 300100700]);
        break;
        case 7 :
            define('WINNING_NUMBERS', [4,9,10,23,28,30,34]);
            define('BONUS_NUMBERS', 18);

            define('TICKET_PRICE', 300);
            define('MIN', 1);
            define('MAX', 37);
            define('MIN_TOTAL', 127);
            define('MAX_TOTAL', 140);
            define('TOP_NUMBER', explode(',', '15,13,9,30,4,32,34,26,17,35,36,29,31,11,21')); // lotto 7
            define('PRIZE_POOL', [0 => 4787500, 3 => 1000, 4 => 1400, 5 => 8900, 6 => 727400, 7 => 748847700]);
        break;
    }

    $hits = 0;
    $current_combo;
    $x = 1;
    $total_price = 0;
    generate();

    echo 'Ticket Price : '.number_format(COMBINATIONS*TICKET_PRICE).'円<br/>';
    echo 'Total Price : '.number_format($total_price).'円';

    function generate() {  
        global $generated_combos, $current_combo, $x, $hits, $total_price;
    
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

            $str_combo = implode(', ', $current_combo);

            if(!in_array($str_combo, $generated_combos)) {
                $bonus = '';
                if($hits >= 3) { 
                    if($hits == (MAX_DIGITS-1)) {
                        if(in_array(BONUS_NUMBER, $current_combo)) {
                            $total_price += PRIZE_POOL[0];
                            $bonus = ' BONUS!';
                        } else {
                            $total_price += PRIZE_POOL[$hits];
                        }
                    } else {
                        $total_price += PRIZE_POOL[$hits];
                    }
                    echo $total.'【'.$str_combo.'】（'.$hits.' hits'.$bonus.'）<br />';
                }
    
                $generated_combos[] = $str_combo;
                $x++;
            }
            
        }

        if($x <= COMBINATIONS) {
            generate();
        }
        return;
    }

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