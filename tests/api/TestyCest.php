<?php

class TestyCest
{

    /**
     * @test
     * it should be do something!
     */
    public function itShouldReturnJoy(ApiTester $I){
        //$I->fetchWpCookies($I);
       // $I->getModule('WPBrowser');
       // $this->getModule('WPBrowser')->amOnPage("/wp-admin");


   //    $I->sendPOST("/wp-json/developer-contest/v1/fetch-title");
        $I->seeResponseContains('true');
    }
}