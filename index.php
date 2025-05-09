<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $lotto_mode = 7;
    define('COMBINATIONS', 30);
    define('MAX_DIGITS', $lotto_mode);
    $generated_combos = [];

    define('ODD_NUM', 2);

    switch( $lotto_mode ) {
        case 5 :
            define('WINNING_NUMBERS', [7, 11, 18, 25, 26]); // winning numbers
            define('BONUS_NUMBER', 6);

            define('TICKET_PRICE', 200);
            define('MIN', 1);
            define('MAX', 31);
            define('MIN_TOTAL', 81);
            define('MAX_TOTAL', 92);
            define('TOP_NUMBER', explode(',', '11,19,14,2,21,23,30,16,22,31,27,3,5,18,4')); // lotto 5
            define('PRIZE_POOL', [0=> 130500, 3 => 800, 4 => 7800, 5 => 8873100]);
        break;
        case 6 :
            define('WINNING_NUMBERS', [5,16,17,28,36,40]);
            define('BONUS_NUMBER', 14);

            define('TICKET_PRICE', 200);
            define('MIN', 1);
            define('MAX', 43);
            define('MIN_TOTAL', 131);
            define('MAX_TOTAL', 148);
            define('TOP_NUMBER', explode(',', '2,24,6,42,21,43,38,19,32,26,10,37,27,15,5')); // lotto 6
            define('PRIZE_POOL', [0 => 9101900, 3 => 1000, 4 => 6500, 5 => 305500, 6 => 300100700]);
        break;
        case 7 :
            define('WINNING_NUMBERS', [3,10,12,18,19,24,32]);
            define('BONUS_NUMBER', 4);

            define('TICKET_PRICE', 300);
            define('MIN', 1);
            define('MAX', 37);
            define('MIN_TOTAL', 113);
            define('MAX_TOTAL', 126);
            define('TOP_NUMBER', explode(',', '15,13,9,30,4,32,34,26,17,35,36,29,31,11,21')); // lotto 7
            define('PRIZE_POOL', [0 => 12275400, 3 => 1000, 4 => 1300, 5 => 6900, 6 => 727400, 7 => 1200000000]);
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

                  // echo $x.'.【'.$str_combo.'】 ('. $total.')（'.$hits.' hits'.$bonus.'）<br />';
                }
                echo $x.'.【'.$str_combo.'】 ('. $total.')（'.$hits.' hits'.$bonus.'）<br />';
                
                
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

    function random() {
        global $current_combo;

        $oddCount = count(array_filter($current_combo, function($num) {
            return $num % 2 !== 0;
        }));

        if($oddCount < ODD_NUM) {
            do {
                $num = rand(MIN, MAX);
            } while ($num % 2 === 0);
        } else {
            do {
                $num = rand(MIN, MAX);
            } while ($num % 2 !== 0);
        }

        if(in_array($num, $current_combo)) {
            return random();
        }

        return $num;
    }
?>