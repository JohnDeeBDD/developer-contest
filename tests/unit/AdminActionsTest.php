<?php

class AdminActionsTest extends \Codeception\TestCase\WPTestCase{

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable(){
        $AdminActions = new \DeveloperContest\Role_Admin();
    }

    /**
     * @test
     * it should designate the post as a "contest"
     * We will be adding a custom post meta to the post
     */
    public function itShouldDesignateThePostAsAContest(){

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
        return $postID;
    }

    /**
     * @test
     * it should designate a contest as "open"
     */
}