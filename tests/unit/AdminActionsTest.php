<?php

class AdminActionsTest extends \Codeception\TestCase\WPTestCase{

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable(){
        $AdminActions = new \DeveloperContest\AdminRole();
    }

    /**
     * @test
     * it should designate the post as a "contest"
     * We will be adding a custom post metat to the post
     */
    public function itShouldDesignateThePostAsAContest(){
        $AdminActions = new \DeveloperContest\AdminRole();
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


        $this->assertEquals("active", "$returnedMeta", "the returned meta is :  $returnedMeta");
    }

    /**
     * @test
     * it should designate a contest as "open"
     */
}