<?php

class FreelancerActionsTest extends \Codeception\TestCase\WPTestCase
{

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $FrelancerRole = new \DeveloperContest\Role_Freelancer();
    }

    /**
     * @test
     * it should identify a valid contest
     */
    public function isShouldIdentifyAnActiveContest(){
        $FrelancerRole = new \DeveloperContest\Role_Freelancer();

        $AdminActions = new \DeveloperContest\Role_Admin();
        $post = array(
            'post_title'    => 'My post',
            'post_content'  => 'This is my post.',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_category' => array( 8,39 )
        );
        $postID = wp_insert_post( $post);
        $AdminActions->designatePostAsContest($postID);



        $returnedMeta = get_post_meta($postID, "developer-contest", true);

    }

    /**
     * @test
     * it should create an entry
     */
    public function itShouldCreateAnEntry(){

    }
}

/*

    public function isPostAContest($postID){}
    public function startNewEntry($postID){}
    public function getSettingsPageContestLink($postID){}
    public function openEntry($postID){}
*/