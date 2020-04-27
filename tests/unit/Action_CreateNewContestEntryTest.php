<?php

class Action_CreateNewContestEntryTest extends \Codeception\TestCase\WPTestCase
{

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable(){
        $Action_CreateNewContestEntry = new \DeveloperContest\Action_CreateNewContestEntry();
    }

    /**
    * @test
     * it should create a new contest entry
     */
    public function itShouldCreateANewContestEntry(){
        //given there is an active contest post
        $post = array(
            'post_title'    => 'My post',
            'post_content'  => 'This is my post.',
            'post_status'   => 'publish',
            'post_author'   => 1,
            // 'post_category' => array( 8,39 )
        );
        $postID = wp_insert_post( $post);

        $AdminActions = new \DeveloperContest\Role_Admin();
        $AdminActions->designatePostAsContest($postID);
        $returnedMeta = get_post_meta($postID, "developer-contest", true);
        // $returnedMeta = "my balls";

        $this->assertEquals("active", "$returnedMeta", "the returned meta is :  $returnedMeta");
        //when the action create new entry is performed
        //then a new contest entry should be created
        $Action = new \DeveloperContest\Action_CreateNewContestEntry();
       // $Action->doAction();
    }
}

