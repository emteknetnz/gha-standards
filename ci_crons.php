<?php

# github crons run on UTC

# https://crontab.guru

const MON = 1;
const TUE = 2;
const WED = 3;
const THU = 4;
const FRI = 5;
const SAT = 6;
const SUN = 7;
const DAILY = 99;

function cron($hourStrNZST, $day) {
    // e.g. NZST of '11pm', SAT
    // hour is passed as NZST, though needs to be converted to UTC
    $am = strpos($hourStrNZST, 'am') !== false;
    $hour = preg_replace('/[^0-9]/', '', $hourStrNZST);
    if ($am) {
        // e.g. NZST 11am SAT = UTC hour 23 SAT
        $hour += 12;
    } else {
        // pm
        // e.g. NZST 11pm SAT = UTC hour 11 SUN 
        $day += 1;
        if ($day == 8) {
            $day = 1;
        }
    }
    if ($day > 50) {
        // daily
        return sprintf('0 %d * * *', $hour);
    } else {
        // once per week on a particular day
        return sprintf('0 %d * * %d', $hour, $day);
    }
}

$fallback = cron('11pm', SAT);

$crons = [
    cron('3am', DAILY) => [
        'silvertripe/installer',
    ],
    cron('4am', DAILY) => [
        'silvertripe/recipe-kitchen-sink',
    ],
    cron('11pm', MON) => [
    ],
    cron('12pm', MON) => [
    ],
    cron('1am', MON) => [
    ],
    cron('2am', MON) => [
    ],
    cron('11pm', TUE) => [
    ],
    cron('12pm', TUE) => [
    ],
    cron('1am', TUE) => [
    ],
    cron('2am', TUE) => [
    ],
    cron('11pm', WED) => [
    ],
    cron('12pm', WED) => [
    ],
    cron('1am', WED) => [
    ],
    cron('2am', WED) => [
    ],
    cron('11pm', THU) => [
    ],
    cron('12pm', THU) => [
    ],
    cron('1am', THU) => [
    ],
    cron('2am', THU) => [
    ],
    cron('11pm', FRI) => [
    ],
    cron('12pm', FRI) => [
    ],
    cron('1am', FRI) => [
    ],
    cron('2am', FRI) => [
    ],
    cron('11pm', SAT) => [
    ],
    cron('12pm', SAT) => [
    ],
    cron('1am', SAT) => [
    ],
    cron('2am', SAT) => [
    ],
    cron('11pm', SUN) => [
    ],
    cron('12pm', SUN) => [
    ],
    cron('1am', SUN) => [
    ],
    cron('2am', SUN) => [
    ],
];

print_r($crons);
