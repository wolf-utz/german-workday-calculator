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

namespace OmegaCode\GermanWorkdayCalculator\Tests\Features\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\Mink\Mink;
use Behat\Mink\Session;

/**
 * Class APIContext.
 */
class FeiertageAPIContext extends Mink implements Context
{
    /**
     * @var Session
     */
    private $session;

    /**
     * FeatureContext constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $driver = new \Behat\Mink\Driver\GoutteDriver();
        $this->session = new Session($driver);
        $this->session->start();
    }

    /**
     * @Given calling the url :url is possible.
     */
    public function callingTheUrlIsPossible($url)
    {
        $this->session->visit($url);
        \PHPUnit_Framework_Assert::assertSame(
            200,
            $this->session->getStatusCode()
        );
    }

    /**
     * @When I request :url
     */
    public function iRequest($url)
    {
        $this->session->visit($url);
    }

    /**
     * @Then I should receive a json response
     */
    public function iShouldReceiveAJsonResponse()
    {
        $jsonResponse = json_decode($this->session->getPage()->getContent());
        \PHPUnit_Framework_Assert::assertTrue(is_object($jsonResponse));
    }
}
