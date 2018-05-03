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

namespace OmegaCode;

use OmegaCode\API\Exception\APIException;
use OmegaCode\API\HolidayAPI;
use OmegaCode\Persistence\FileHandler;

/**
 * Class WorkdayCalculator.
 */
class WorkdayCalculator
{
    /**
     * @var null|FileHandler
     */
    private $fileHandler;

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
     *
     * @return \DateTime
     *
     * @throws \Exception
     */
    public function calculateIncrementedDate(\DateTime $date, $incrementDays)
    {
        $increments = 1;
        $year = $date->format('Y');
        $holidays = $this->getHolidays((int) $date->format('Y'), 'NATIONAL');
        while ($increments < $incrementDays) {
            if ($year != $date->format('Y')) {
                $year = $date->format('Y');
                $holidays = $this->getHolidays((int) $year, 'NATIONAL');
            }
            $date->add(new \DateInterval('P1D'));
            if (!$this->dateIsHoliday($date, $holidays) && 7 != $date->format('N')) {
                ++$increments;
            }
        }

        return $date;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $till
     *
     * @return int
     *
     * @throws APIException
     * @throws \Exception
     */
    public function calculateByDateRange(\DateTime $from, \DateTime $till)
    {
        if ($from >= $till) {
            throw new \InvalidArgumentException('Date from must be graeter than date till!', 1525337424);
        }
        $loops = 0;
        $workdaysCount = 1;
        $year = $from->format('Y');
        $holidays = $this->getHolidays((int) $year, 'NATIONAL');
        while ($from->format('Y-m-d') != $till->format('Y-m-d')) {
            if ($year != $from->format('Y')) {
                $year = $from->format('Y');
                $holidays = $this->getHolidays((int) $year, 'NATIONAL');
            }
            $from->add(new \DateInterval('P1D'));
            if (!$this->dateIsHoliday($from, $holidays) && 7 != $from->format('N')) {
                ++$workdaysCount;
            }
            if ($loops > 9999) {
                throw new \RuntimeException('Stopped execution here, to avoid infinite looping. Check your argument date range!', 1525337787);
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
     * @throws APIException
     */
    public function getHolidays($year, $state)
    {
        if (!$this->fileHandler->responseExist($year, $state)) {
            HolidayAPI::fetch($year, $state);
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
}
