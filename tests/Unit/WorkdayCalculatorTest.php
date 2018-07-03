<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Wolf Utz <utz@riconet.de>, riconet
 *      Created on: 13.05.18 21:06
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace OmegaCode\GermanWorkdayCalculator\Tests\Unit;

use OmegaCode\GermanWorkdayCalculator\API\APIException;
use OmegaCode\GermanWorkdayCalculator\States;
use OmegaCode\GermanWorkdayCalculator\Tests\Helper\TestUtility;
use PHPUnit\Framework\TestCase;
use OmegaCode\GermanWorkdayCalculator\WorkdayCalculator;

/**
 * Class WorkdayCalculatorTest.
 */
class WorkdayCalculatorTest extends TestCase
{
    /**
     * @var WorkdayCalculator
     */
    protected $calculator;

    /**
     * Sets up the test case.
     */
    protected function setUp()
    {
        $this->calculator = new WorkdayCalculator();
        $this->calculator->setState(States::NATIONAL);
    }

    /**
     * @throws \Exception
     */
    public function testCalculateIncrementedDateReturnsTheRightDate()
    {
        $testDateTime = \DateTime::createFromFormat('Y-m-d', '2018-05-07');
        $expectedDateTime = \DateTime::createFromFormat('Y-m-d', '2018-05-14');
        $actualDateTime = $this->calculator->calculateIncrementedDate($testDateTime, 6);
        $this->assertEquals($expectedDateTime, $actualDateTime);
    }

    /**
     * @throws \Exception
     */
    public function testCalculateIncrementedDateReturnsIncrementedYear()
    {
        $testDateTime = \DateTime::createFromFormat('Y-m-d', '2018-05-07');
        $expectedDateTime = \DateTime::createFromFormat('Y-m-d', '2019-07-17');
        $actualDateTime = $this->calculator->calculateIncrementedDate($testDateTime, 365);
        $this->assertEquals($expectedDateTime, $actualDateTime);
    }

    /**
     * @throws APIException
     */
    public function testCalculateByDateRangeCalculatesTheRightValue()
    {
        $dateTimeFrom = \DateTime::createFromFormat('Y-m-d', '2018-01-01');
        $dateTimeTill = \DateTime::createFromFormat('Y-m-d', '2018-12-31');
        $actualValue = (int) $this->calculator->calculateByDateRange($dateTimeFrom, $dateTimeTill);
        $this->assertEquals(305, $actualValue);
    }

    /**
     * @throws APIException
     */
    public function testCalculateByDateRangeAdditionalYearCalculatesTheRightValue()
    {
        $dateTimeFrom = \DateTime::createFromFormat('Y-m-d', '2018-01-01');
        $dateTimeTill = \DateTime::createFromFormat('Y-m-d', '2019-12-31');
        $actualValue = (int) $this->calculator->calculateByDateRange($dateTimeFrom, $dateTimeTill);
        $this->assertEquals(610, $actualValue);
    }

    /**
     * @throws APIException
     */
    public function testCalculateByDateRangeWrongArguments()
    {
        $this->expectException(\InvalidArgumentException::class);
        $dateTimeFrom = \DateTime::createFromFormat('Y-m-d', '2018-01-01');
        $dateTimeTill = \DateTime::createFromFormat('Y-m-d', '2017-12-31');
        $this->calculator->calculateByDateRange($dateTimeFrom, $dateTimeTill);
    }


    public function testSetStateSetsSate()
    {
        $this->calculator->setState(States::NATIONAL);
        $this->calculator->setState(States::HESSEN);
        $this->assertEquals(States::HESSEN, $this->calculator->getState());
    }

    protected function tearDown()
    {
        parent::tearDown();
        TestUtility::clearTempFolder();
    }
}
