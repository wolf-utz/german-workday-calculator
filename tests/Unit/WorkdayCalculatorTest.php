<?php
/**
 * Copyright 2018. Wolf-Peter Utz.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
