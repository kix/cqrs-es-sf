<?php

use Behat\Behat\Context\Context;

final class FeatureContext implements Context
{
    public function __construct()
    {
    }

    /**
     * @When I submit an event named :arg1 happening on :arg2 at :arg3 in :arg4
     */
    public function iSubmitAnEventNamedHappeningOnAtIn($arg1, $arg2, $arg3, $arg4): void
    {
    }

    /**
     * @When the event named :arg1 happening on :arg2 at :arg3 in :arg4 is approved by an admin
     */
    public function theEventNamedHappeningOnAtInIsApprovedByAnAdmin($arg1, $arg2, $arg3, $arg4): void
    {
    }

    /**
     * @Then I see the event named :arg1 happening on :arg2 at :arg3 in :arg4 in the event list
     */
    public function iSeeTheEventNamedHappeningOnAtInInTheEventList($arg1, $arg2, $arg3, $arg4): void
    {
    }
}
