<?php

class ContestTest extends \Codeception\TestCase\WPTestCase
{

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $Contest = new \DeveloperContest\Contest;
    }

    /**
     * @test
     * it should return status "no post"
     */
    public function itShouldReturnStatus_noPost()
    {
        $Contest = new \DeveloperContest\Contest;
        $status = $Contest->getStatus(666);
        $this->assertEquals("no-post", $status, "Error: status $status returned.");
    }

    /**
     * @test
     * it should return status "no contest"
     */
    public function itShouldReturnStatus_noContest()
    {
        $post = array(
            'post_title'    => 'My post',
            'post_content'  => 'This is my post.',
            'post_status'   => 'publish',
        );
        $postID = wp_insert_post( $post);
        $Contest = new \DeveloperContest\Contest;
        $status = $Contest->getStatus($postID);
        $this->assertEquals("no-contest", $status, "Error: status $status returned.");
    }

    /**
     * @test
     * it should return status "active"
     */
    public function itShouldReturnStatus_active(){
        $post = array(
            'post_title'    => 'My post',
            'post_content'  => 'This is my post.',
            'post_status'   => 'publish',
        );
        $postID = wp_insert_post( $post);
        $Admin = new \DeveloperContest\Role_Admin();
        $Admin->designatePostAsContest($postID);


        $Contest = new \DeveloperContest\Contest;
        $status = $Contest->getStatus($postID);
        $this->assertEquals("active", $status, "Error: status $status returned.");
    }


}