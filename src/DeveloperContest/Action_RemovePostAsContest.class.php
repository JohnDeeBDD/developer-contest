<?php

namespace DeveloperContest;

class Action_RemovePostAsContest extends Action_Abstract{
    public $namespace = "developer-contest";
    public $roles = ["administrator"];
    public $actionName = "remove-post-as-contest";
    public $availableLocations = ["developer-contest"];


    public function __construct()
    {
        parent::__construct();
        //die("Action_DesignatePostAsContest");
    }
    public function enable(){
        add_action("init", [$this, "listenForHtmlSubmission"]);
    }

    //who can do this action
    public function setRoles($roles = []){}

    //where can this action occur
    public function setScreens($screens = []){}

    public function enableApi(){}
    public function listenForHtmlSubmission(){
        //die("listening");
        if(isset($_REQUEST['action'])){
            if($_REQUEST['action'] == ($this->namespace . "-" . $this->actionName)){
                //die("action set");
                if (isset($_REQUEST['post-id'])){
                    $postID = $_REQUEST['post-id'];
                    if(!($this->validateFormSubmission($postID))){
                        wp_die("SOMETHING IS WRONG! PostID did not validate.");
                    };
                    if(!(isset($_REQUEST['developer-contest-designate-post-as-contest-nonce']))){
                        wp_die("SOMETHING IS WRONG! NONCE NOT FOUND.");
                    }
                    if(!(\wp_verify_nonce( $_REQUEST['developer-contest-designate-post-as-contest-nonce'], 'developer-contest-designate-post-as-contest-nonce' ))){
                        wp_die("SOMETHING IS WRONG! INVALID NONCE!");
                    }
                    $this->doAction($postID);
                }else{
                    header("HTTP/1.1 401 Unauthorized");
                    wp_die( 'ERROR: postID not found' );
                }
            }
        }
    }
    private function verifyNonce(){}
    private function verifyUser(){}

    public function validateFormSubmission($postID){
        if(is_int(intval($postID))){
            if ( get_post_status ( $postID) ) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function doAction($postID){
        delete_post_meta($postID, "developer-contest" );
    }

    public function getActionButtonUiHtml($postID){
        $javaScript = $this->returnJS($postID);
        $nonce = wp_create_nonce("remove-post-as-contest");
        $output = <<<OUTPUT
<input type = "button" name = "developer-contest-action-remove-post-as-contest-button" id = "developer-contest-action-remove-post-as-contest-button" value = "Remove"/>
<input type = "hidden" name = "developer-contest-action-remove-post-as-contest-nonce" id = "developer-contest-action-remove-post-as-contest-nonce" value = "$nonce" /> 
$javaScript
OUTPUT;
        return $output;
    }

    public function returnJS($postID){
        //This is the frontend JS that handles the action button
        $output = <<<OUTPUT
<script>
jQuery(document).ready(function(){
    //alert("Action_RemovePostAsContest.class.php");
    jQuery("#developer-contest-action-remove-post-as-contest-button").click(function(){
        jQuery('#developer-contest-form').attr('action', '/wp-admin/admin.php?page=developer-contest&action=developer-contest-remove-post-as-contest&post-id=$postID');
        jQuery("#developer-contest-form").submit(); // Submit
    });
});
</script>
OUTPUT;
        return $output;
    }
}