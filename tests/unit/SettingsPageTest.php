<?php

class SettingsPageTest extends \Codeception\TestCase\WPTestCase{

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable(){
        $SettingsPage = new \DeveloperContest\SettingsPage();
    }

    /**
     * @test
     * it should validate the postID submission
     */
    public function itShouldValidateThePostIdSubmission(){
        $post = array(
            'import_id'         =>  123,
            'comment_status'    =>  'open',
            'post_content'      =>  'hi world!',
            'post_name'         =>  'title_1',
            'post_status'       =>  'publish',
            'post_title'        =>  'your title',
            'post_type'         =>  'post',
        );

        $post_id = wp_insert_post($post);



        $SettingsPage = new \DeveloperContest\SettingsPage();

        $postID = "123";
        $result = $SettingsPage->validateSubmission($postID);
        $this->assertEquals(true, $result, "Failed value $postID");

        $postID = 123;
        $result = $SettingsPage->validateSubmission($postID);
        $this->assertEquals(true, $result, "Failed value $postID");

        $postID = "123aaaa";
        $result = $SettingsPage->validateSubmission($postID);
        $this->assertEquals(false, $result, "Failed value $postID");

        $postID = "";
        $result = $SettingsPage->validateSubmission($postID);
        $this->assertEquals(false, $result, "Failed value $postID");

        $postID = "0123";
        $result = $SettingsPage->validateSubmission($postID);
        $this->assertEquals(false, $result, "Failed value $postID");

    }
}