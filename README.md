# Workday Claculator

## Description
This library adds a class to calculate wordays using the api from https://feiertage-api.de/."

## Installation
Add ``omegacode/workday-calculator`` as a dependency in your composer.json.

## Usage

```php
<?php

$calculator = new \OmegaCode\WorkdayCalculator();

$testDate =     \DateTime::createFromFormat('Y-m-d', "2018-05-07");
$testDateFrom = \DateTime::createFromFormat('Y-m-d', "2018-04-30");
$testDateTill =   \DateTime::createFromFormat('Y-m-d', "2018-05-14");

$result1 = $calculator->calculateIncrementedDate($testDate, 6);    // 2018-05-14
$result2 = $calculator->calculateByDateRange($testDateFrom, $testDateTill);  // 11
