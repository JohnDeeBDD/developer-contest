<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('See that the browser can launch');
$I->loginAsAdmin();
$I->amOnPage("/wp-admin/admin.php?page=developer-contest");

$JS = <<<javaScript
    return DeveloperContest.validatePostIdTextField("");
javaScript;
$response = $I->executeJS($JS);
$I->assertEquals(false, $response);


$JS = <<<javaScript
    return DeveloperContest.validatePostIdTextField("123");
javaScript;
$response = $I->executeJS($JS);
$I->assertEquals(true, $response);

$JS = <<<javaScript
    return DeveloperContest.validatePostIdTextField("123abc");
javaScript;
$response = $I->executeJS($JS);
$I->assertEquals(false, $response);