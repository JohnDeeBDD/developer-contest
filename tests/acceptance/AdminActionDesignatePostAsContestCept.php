<?php
$I = new AcceptanceTester($scenario);

$I->wantTo('Confirm the admin action designate-post-as-contest');

/**
 * GIVEN
 */
$testPostTitle = "Test post for AdminActionDesignatePostAsConcestCept.php";
$postID = wp_insert_post( ['post_title' => $testPostTitle] );
//die("the post id is $postID");
/**
 * WHEN
 */

$I->loginAsAdmin();
$I->amOnPage("/wp-admin/admin.php?page=developer-contest");
$I->fillField("contestPostID", $postID);
$I->waitForText($testPostTitle, 5);
$I->click("#developer-contest-postid-submit-button");

/**
 * THEN
 */
$AdminActions = new \DeveloperContest\Role_Admin();
$AdminActions->designatePostAsContest($postID);

$I->amOnPage("/wp-admin/admin.php?page=developer-contest");
$I->see($testPostTitle);
//Cleanup
wp_delete_post( $postID, TRUE);


