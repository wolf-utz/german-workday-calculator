<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Wolf Utz <utz@riconet.de>, riconet
 *      Created on: 22.05.18 17:30
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

namespace OmegaCode\GermanWorkdayCalculator\Tests\Features\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use OmegaCode\GermanWorkdayCalculator\Tests\Features\Bootstrap\Helper\ContextUtility;

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
