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

namespace OmegaCode\API;

use OmegaCode\Persistence\FileHandler;

/**
 * Class HolidayAPI.
 */
class HolidayAPI
{
    const API_BASE_URL = 'https://feiertage-api.de/api/';

    const YEAR_PARAMETER = '?jahr=';

    const STATE_PARAMETER = '&nur_land=';

    /**
     * @param int         $year
     * @param string      $state       possible states are:
     *                                 - BW (Baden-Württemberg)
     *                                 - BY (Bayern)
     *                                 - BE (Berlin)
     *                                 - BB (Brandenburg)
     *                                 - HB (Bremen)
     *                                 - HH (Hamburg)
     *                                 - HE (Hessen)
     *                                 - MV (Mecklenburg-Vorpommern)
     *                                 - NI (Niedersachsen)
     *                                 - NW (Nordrhein-Westfalen)
     *                                 - RP (Rheinland Pfalz)
     *                                 - SL (Saarland)
     *                                 - SN (Sachsen)
     *                                 - ST (Sachen-Anhalt)
     *                                 - SH (Schleswig Holstein)
     *                                 - TH (Thüringen)
     *                                 - NATIONAL
     * @param FileHandler $fileHandler
     *
     * @throws APIException
     */
    public static function fetch($year, $state, FileHandler $fileHandler)
    {
        try {
            $response = file_get_contents(self::API_BASE_URL.self::YEAR_PARAMETER.$year.self::STATE_PARAMETER.$state);
            $jsonResponse = json_decode($response);
            if (JSON_ERROR_NONE != json_last_error() || is_null($jsonResponse)) {
                throw new \RuntimeException(
                    'The API does not return a valid JSON string or returns null. Check your request!',
                    1525352105
                );
            }
            $fileHandler->persistResponse($year, $state, $response);
        } catch (\Exception $e) {
            throw new APIException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
