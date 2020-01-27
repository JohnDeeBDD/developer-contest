<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('See that the browser can launch');
$I->loginAsAdmin();
$I->amOnPage("/wp-admin/admin.php?page=developer-contest");
$I->see("Enter a post or page ID.");