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

namespace OmegaCode\GermanWorkdayCalculator\Persistence;

/**
 * Class FileHandler.
 */
class FileHandler
{
    /**
     * @var string
     */
    private $persistencePath = '';

    /**
     * FileHandler constructor.
     */
    public function __construct()
    {
        $this->persistencePath = __DIR__.'/../../tmp/';
    }

    /**
     * @param int    $year
     * @param string $state
     *
     * @return bool
     */
    public function responseExist($year, $state)
    {
        return file_exists($this->persistencePath."api-response_$year-$state.json");
    }

    /**
     * @param int    $year
     * @param string $state
     *
     * @return mixed
     */
    public function readPersistedResponse($year, $state)
    {
        $response = file_get_contents($this->persistencePath."api-response_$year-$state.json");

        return json_decode($response);
    }

    /**
     * @param int    $year
     * @param string $state
     * @param string $response
     *
     * @return mixed
     */
    public function persistResponse($year, $state, $response)
    {
        file_put_contents($this->persistencePath."api-response_$year-$state.json", $response);

        return json_decode($response);
    }

    /**
     * @param $path
     */
    public function setPersistencePath($path)
    {
        if (!is_readable($path)) {
            throw new \InvalidArgumentException("Path $path does not exist or is not readable!", 1556568843);
        }
        $this->persistencePath = $path;
    }
}
