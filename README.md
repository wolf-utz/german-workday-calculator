# Workday Claculator

## Description
This library adds a class to calculate wordays using the api from https://feiertage-api.de/."

## Installation
Add `omegacode/german-workday-calculator` as a dependency in your composer.json.

## Usage
```php
<?php
use OmegaCode\WorkdayCalculator;
// Create an instance of the claculator and set the states for german holidays to Hessen.
$calculator = new WorkdayCalculator();
$calculator->setState("HE"); // Check the info section for all possibilities.
// Create some test date time objects.
$testDate = \DateTime::createFromFormat('Y-m-d', "2018-05-07");
$testDateFrom = \DateTime::createFromFormat('Y-m-d', "2018-04-30");
$testDateTill = \DateTime::createFromFormat('Y-m-d', "2018-05-14");

$result1 = $calculator->calculateIncrementedDate($testDate, 6); // 2018-05-14
$result2 = $calculator->calculateByDateRange($testDateFrom, $testDateTill);  // 11
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