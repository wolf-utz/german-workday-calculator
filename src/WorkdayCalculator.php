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

namespace OmegaCode\GermanWorkdayCalculator;

use OmegaCode\GermanWorkdayCalculator\API\APIException;
use OmegaCode\GermanWorkdayCalculator\API\HolidayAPI;
use OmegaCode\GermanWorkdayCalculator\Persistence\FileHandler;

/**
 * Class WorkdayCalculator.
 */
class WorkdayCalculator
{
    const IGNORE_SUNDAY = 7;
    const IGNORE_SATURDAY = 6;

    /**
     * @var FileHandler|null
     */
    private $fileHandler;

    /**
     * @var string
     */
    private $state = States::NATIONAL;

    /**
     * WorkdayCalculator constructor.
     */
    public function __construct()
    {
        $this->fileHandler = new FileHandler();
    }

    /**
     * @param \DateTime $date
     * @param int       $incrementDays
     * @param array     $ignoreDays
     *
     * @return \DateTime
     *
     * @throws APIException
     */
    public function calculateIncrementedDate(\DateTime $date, $incrementDays, array $ignoreDays = [self::IGNORE_SUNDAY])
    {
        $increments = 1;
        $year = $date->format('Y');
        $holidays = $this->getHolidays((int) $date->format('Y'), $this->state);
        while ($increments < $incrementDays) {
            if ($year != $date->format('Y')) {
                $year = $date->format('Y');
                $holidays = $this->getHolidays((int) $year, $this->state);
            }
            $date->add(new \DateInterval('P1D'));
            if (!$this->dateIsHoliday($date, $holidays) && !in_array($date->format('N'), $ignoreDays)) {
                ++$increments;
            }
        }

        return $date;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $till
     * @param array     $ignoreDays
     *
     * @return int
     *
     * @throws APIException
     */
    public function calculateByDateRange(\DateTime $from, \DateTime $till, array $ignoreDays = [self::IGNORE_SUNDAY])
    {
        if ($from >= $till) {
            throw new \InvalidArgumentException('Date from must be greater than date till!', 1525337424);
        }
        $loops = 0;
        $workdaysCount = 1;
        $year = $from->format('Y');
        $holidays = $this->getHolidays((int) $year, $this->state);
        while ($from->format('Y-m-d') != $till->format('Y-m-d')) {
            if ($year != $from->format('Y')) {
                $year = $from->format('Y');
                $holidays = $this->getHolidays((int) $year, $this->state);
            }
            $from->add(new \DateInterval('P1D'));
            if (!$this->dateIsHoliday($from, $holidays) && !in_array($from->format('N'), $ignoreDays)) {
                ++$workdaysCount;
            }
            if ($loops > 9999) {
                throw new \RuntimeException(
                    'Stopped execution here, to avoid infinite looping. Check your argument date range!',
                    1525337787
                );
            }
            ++$loops;
        }

        return $workdaysCount;
    }

    /**
     * @param int    $year
     * @param string $state
     *
     * @return mixed
     *
     * @throws API\APIException
     */
    public function getHolidays($year, $state)
    {
        if (!$this->fileHandler->responseExist($year, $state)) {
            HolidayAPI::fetch($year, $state, $this->fileHandler);
        }

        return $this->fileHandler->readPersistedResponse($year, $state);
    }

    /**
     * @param \DateTime $date
     * @param mixed     $holidays
     *
     * @return bool
     */
    public function dateIsHoliday(\DateTime $date, $holidays)
    {
        $format = 'Y-m-d';
        foreach ($holidays as $holiday) {
            $holidayDate = \DateTime::createFromFormat($format, $holiday->datum);
            if ($date->format($format) === $holidayDate->format($format)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
