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

// ci | keepalive | standards
global $mode;

function cron($hourStrNZT, $day) {
    // e.g. NZST of '11pm', SAT
    // hour is passed as NZST, though needs to be converted to UTC
    global $mode;
    list($hour, $day) = nztToUtc($hourStrNZT, $day);
    if ($mode == 'ci') {
        if ($day > 50) {
            // daily
            return sprintf('0 %d * * *', $hour);
        } else {
            // normal - once per week on a particular day
            return sprintf('0 %d * * %d', $hour, $day);
        }
    } else {
        // keepalive + standards
        // run once per month, defaults to the 1st of the month 
        return sprintf('0 %d 1 * *', $hour);
    }
}

function nztToUtc($hourStrNZT, $day) {
    // note this is UTC to NZST, NZDT (daylight savings time) is not considered
    $am = strpos($hourStrNZT, 'am') !== false;
    $hour = preg_replace('/[^0-9]/', '', $hourStrNZT);
    if ($am) {
        // e.g. NZST 11am SAT = UTC hour 23 SAT
        $hour += 12;
        if ($hour == 24) {
            $hour = 0;
        }
    } else {
        // pm
        // e.g. NZST 11pm SAT = UTC hour 11 SUN 
        $day += 1;
        if ($day == 8) {
            $day = 1;
        }
    }
    return [$hour, $day];
}

function ghrepoToDay($ghrepo) {
    // generate a "predictable yet random" day between 1-28 based on $ghrepo string
    return (preg_replace('/[^0-9]/', '', md5($ghrepo)) % 28) + 1;
}

$crons = [
    cron('10pm', SAT) => [
        // used if a ghrepo isn't defined anywhere
        'fallback'
    ],
    cron('3am', DAILY) => [
        'silverstripe/recipe-kitchen-sink',
    ],
    cron('4am', DAILY) => [
        'silverstripe/silverstripe-installer',
    ],
    cron('11pm', SUN) => [
        'silverstripe/silverstripe-reports',
        'silverstripe/silverstripe-siteconfig',
        'silverstripe/silverstripe-versioned',
        'silverstripe/silverstripe-versioned-admin',
    ],
    cron('12am', MON) => [
        'silverstripe/comment-notifications',
        'silverstripe/cwp',
        'silverstripe/cwp-agencyextensions',
        'silverstripe/cwp-core',
    ],
    cron('1am', MON) => [
        'silverstripe/cwp-pdfexport',
        'silverstripe/cwp-search',
        'silverstripe/cwp-starter-theme',
        'silverstripe/cwp-watea-theme',
        'silverstripe/silverstripe-simple',
    ],
    cron('2am', MON) => [
        'silverstripe/doorman',
        'silverstripe/silverstripe-serve',
        'silverstripe/silverstripe-graphql-devtools',
        'silverstripe/silverstripe-testsession',
        'silverstripe/webpack-config',
    ],
    cron('11pm', MON) => [
        'silverstripe/silverstripe-akismet',
        'silverstripe/silverstripe-auditor',
        'silverstripe/silverstripe-admin',
    ],
    cron('12am', TUE) => [
        'silverstripe/silverstripe-asset-admin',
        'silverstripe/silverstripe-assets',
        'silverstripe/silverstripe-blog',
    ],
    cron('1am', TUE) => [
        'silverstripe/silverstripe-campaign-admin',
        'silverstripe/silverstripe-ckan-registry',
        'silverstripe/silverstripe-cms',
    ],
    cron('2am', TUE) => [
        'silverstripe/silverstripe-config',
        'silverstripe/silverstripe-errorpage',
        'silverstripe/silverstripe-framework',
    ],
    cron('11pm', TUE) => [
        'silverstripe/silverstripe-graphql',
        'silverstripe/silverstripe-comments',
        'silverstripe/silverstripe-content-widget',
        'silverstripe/silverstripe-contentreview',
    ],
    cron('12am', WED) => [
        'silverstripe/silverstripe-crontask',
        'silverstripe/silverstripe-documentconverter',
        'silverstripe/silverstripe-elemental',
    ],
    cron('1am', WED) => [
        'silverstripe/silverstripe-elemental-bannerblock',
        'silverstripe/silverstripe-elemental-fileblock',
        'silverstripe/silverstripe-environmentcheck',
    ],
    cron('2am', WED) => [
        'silverstripe/silverstripe-externallinks',
        'silverstripe/silverstripe-fulltextsearch',
        'silverstripe/silverstripe-gridfieldqueuedexport',
    ],
    cron('11pm', WED) => [
        'silverstripe/silverstripe-html5',
        'silverstripe/silverstripe-hybridsessions',
        'silverstripe/silverstripe-iframe',
        'silverstripe/silverstripe-ldap',

    ],
    cron('12am', THU) => [
        'silverstripe/silverstripe-lumberjack',
        'silverstripe/silverstripe-mimevalidator',
        'silverstripe/silverstripe-postgresql',
        'silverstripe/silverstripe-realme',
    ],
    cron('1am', THU) => [
        'silverstripe/silverstripe-session-manager',
        'silverstripe/recipe-authoring-tools',
        'silverstripe/recipe-blog',
    ],
    cron('2am', THU) => [
        'silverstripe/recipe-ccl',
        'silverstripe/recipe-cms',
        'silverstripe/recipe-collaboration',
    ],
    cron('11pm', THU) => [
        'silverstripe/recipe-content-blocks',
        'silverstripe/recipe-core',
        'silverstripe/recipe-form-building',
        'silverstripe/recipe-reporting-tools',

    ],
    cron('12am', FRI) => [
        'silverstripe/recipe-plugin',
        'silverstripe/recipe-services',
        'silverstripe/recipe-solr-search',
    ],
    cron('1am', FRI) => [
        'silverstripe/silverstripe-registry',
        'silverstripe/silverstripe-restfulserver',
        'silverstripe/silverstripe-securityreport',
    ],
    cron('2am', FRI) => [
        'silverstripe/silverstripe-segment-field',
        'silverstripe/silverstripe-selectupload',
        'silverstripe/silverstripe-sharedraftcontent',
    ],
    cron('11pm', FRI) => [
        'silverstripe/silverstripe-sitewidecontent-report',
        'silverstripe/silverstripe-spamprotection',
        'silverstripe/silverstripe-spellcheck',
        'silverstripe/silverstripe-subsites',
    ],
    cron('12am', SAT) => [
        'silverstripe/silverstripe-tagfield',
        'silverstripe/silverstripe-taxonomy',
        'silverstripe/silverstripe-textextraction',
        'silverstripe/silverstripe-userforms',
    ],
    cron('1am', SAT) => [
        'silverstripe/silverstripe-widgets',
        'silverstripe/silverstripe-mfa',
        'silverstripe/silverstripe-totp-authenticator',
    ],
    cron('2am', SAT) => [
        'silverstripe/silverstripe-webauthn-authenticator',
        'silverstripe/silverstripe-login-forms',
        'silverstripe/silverstripe-security-extensions',
    ],
    cron('11pm', SAT) => [
        'silverstripe/silverstripe-upgrader',
        'silverstripe/silverstripe-versionfeed',
        'silverstripe/sspak',
        'silverstripe/vendor-plugin',
    ],
    cron('12am', SUN) => [
        'symbiote/silverstripe-advancedworkflow',
        'symbiote/silverstripe-gridfieldextensions',
        'symbiote/silverstripe-multivaluefield',
        'symbiote/silverstripe-queuedjobs',
    ],
    cron('1am', SUN) => [
        'silverstripe/cow',
        'silverstripe/eslint-config',
        'silverstripe/MinkFacebookWebDriver',
    ],
    cron('2am', SUN) => [
        'silverstripe/recipe-testing',
        'silverstripe/silverstripe-behat-extension',
    ],
];

$mode = 'ci';
$ghrepoToCiCron = [];
foreach ($crons as $cron => $ghrepos) {
    $minute = 0;
    foreach ($ghrepos as $ghrepo) {
        $ghrepoToCron[$ghrepo] = preg_replace('/^[0-9]+ /', "$minute ", $cron);
        $minute += 10;
    }
}

$mode = 'standards';
$ghrepoToStandardsCron = [];
foreach ($crons as $cron => $ghrepos) {
    foreach ($ghrepos as $ghrepo) {
        $day = ghrepoToDay($ghrepo);
        // run on the 50th minute of an hour sometime between the 1st and the 28th of each month
        $ghrepoToStandardsCron[$ghrepo] = preg_replace('/^0 ([0-9]+) 1 /', "55 $1 $day ", $cron);
    }
}

$mode = 'keepalive';
$ghrepoToKeepaliveCron = [];
foreach ($crons as $cron => $ghrepos) {
    foreach ($ghrepos as $ghrepo) {
        $day = ghrepoToDay($ghrepo);
        // run on the 55th minute of an hour sometime between the 1st and the 28th of each month
        $ghrepoToKeepaliveCron[$ghrepo] = preg_replace('/^0 ([0-9]+) 1 /', "50 $1 $day ", $cron);
    }
}
