<?php

class AdminActionUpdateContestDataCest
{

    public function theApitShouldWork(\AcceptanceTester $I)
    {
        $Action = new \DeveloperContest\Action_UpdateContestData;
        $I->moveToScreenWhereActionIsAvailable($Action);
    }
}