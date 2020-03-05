<?php
$I = new AcceptanceTester($scenario);

$I->wantTo('Confirm the fetch-title API is working');

/**
 * GIVEN THERE IS A POST
 */
$testPostTitle = "Test post for fetch-post API";
$postID = wp_insert_post( ['post_title' => $testPostTitle] );
/*
/**
 * WHEN AN ADMIN ATTEMPTS AN API AJAX REQUEST
 */

$I->loginAsAdmin();
$I->amOnPage("/wp-admin/admin.php?page=developer-contest");
$I->executeJS("DeveloperContest.fetchPostTitleAjax($postID);return;");
$I->waitForJs('return jQuery.active == 0', 10);
$response = $I->executeJS("return fetchPostTitleResponse;");

/**
 * THEN THE API SHOULD RESPOND WITH THE POST TITLE
 */

$I->assertEquals($response, $testPostTitle, "Error xx response: $response");

//Cleanup
wp_delete_post( $postID, TRUE);



$I->amOnPage("/wp-admin/admin.php?page=developer-contest");

//There is no post 123
$I->executeJS("DeveloperContest.fetchPostTitleAjax(123);return;");
$I->waitForJs('return jQuery.active == 0', 10);

$response = $I->executeJS("return fetchPostTitleResponse;");
$I->assertEquals($response, "Error: post does not exist", "Error: $response");