# Workday Claculator

## Description
This library adds a class to calculate wordays using the api from https://feiertage-api.de/."

## Installation
Add `omegacode/german-workday-calculator` as a dependency in your composer.json.

## Usage
```php
<?php
require_once __DIR__.'/vendor/autoload.php';

// Create an instance of the calculator and set the states for german holidays to Hessen.
$calculator = new OmegaCode\GermanWorkdayCalculator\WorkdayCalculator();
$calculator->setState(OmegaCode\GermanWorkdayCalculator\States::NATIONAL); // Check the info section for all possibilities.

// Create some test date time objects.
$testDate = \DateTime::createFromFormat('Y-m-d', '2018-05-07');
$testDateFrom = \DateTime::createFromFormat('Y-m-d', '2018-07-03');
$testDateTill = \DateTime::createFromFormat('Y-m-d', '2018-07-10');

try {
    $result1 = $calculator->calculateIncrementedDate(
        $testDate,
        365,
        [
            \OmegaCode\GermanWorkdayCalculator\WorkdayCalculator::IGNORE_SUNDAY,
            \OmegaCode\GermanWorkdayCalculator\WorkdayCalculator::IGNORE_SATURDAY,
        ]
    );
} catch (Exception $e) {
    echo '[ERROR]: '.$e->getMessage().PHP_EOL;
}

try {
    $result2 = $calculator->calculateByDateRange(
        $testDateFrom,
        $testDateTill,
        [
            \OmegaCode\GermanWorkdayCalculator\WorkdayCalculator::IGNORE_SUNDAY,
            \OmegaCode\GermanWorkdayCalculator\WorkdayCalculator::IGNORE_SATURDAY,
        ]
    );
} catch (OmegaCode\GermanWorkdayCalculator\API\APIException $e) {
    echo '[ERROR]: '.$e->getMessage().PHP_EOL;
} catch (Exception $e) {
    echo '[ERROR]: '.$e->getMessage().PHP_EOL;
}

echo $result1->format('Y-m-d').PHP_EOL; // 2019-10-14
echo $result2.PHP_EOL; // 6
```

## Info

### Possible states for the calculator:
* BW (Baden-Württemberg)
* BY (Bayern)
* BE (Berlin)
* BB (Brandenburg)
* HB (Bremen)
* HH (Hamburg)
* HE (Hessen)
* MV (Mecklenburg-Vorpommern)
* NI (Niedersachsen)
* NW (Nordrhein-Westfalen)
* RP (Rheinland Pfalz)
* SL (Saarland)
* SN (Sachsen)
* ST (Sachen-Anhalt)
* SH (Schleswig Holstein)
* TH (Thüringen)
* NATIONAL