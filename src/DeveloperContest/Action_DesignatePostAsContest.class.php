<?php

namespace DeveloperContest;

class Action_DesignatePostAsContest extends Action_Abstract{
    public $namespace;
    public $roles = ["administrator"];
    public $actionName = "designate-post-as-contest";
    public $availableLocations = ["developer-contest"];


    public function __construct(){
        parent::__construct();
    }

    //who can do this action
    public function setRoles($roles = []){

    }

    //where can this action occur
    public function setScreens($screens = []){}

    private function enableApi(){}
    public function listenForHtmlSubmission(){
        //return;
        if(isset($_REQUEST['action'])){
            if($_REQUEST['action'] == ($this->namespace . "-" . $this->actionName)){
                //die("listenForStartContestSubmission action set");
                if (isset($_REQUEST['contestPostID'])){
                    $postID = $_REQUEST['contestPostID'];
                    if(!($this->validateFormSubmission($postID))){
                        wp_die("SOMETHING IS WRONG! PostID did not validate.");
                    };
                    if(!(isset($_REQUEST['developer-contest-designate-post-as-contest-nonce']))){
                        wp_die("SOMETHING IS WRONG! NONCE NOT FOUND.");
                    }
                    if(!(\wp_verify_nonce( $_REQUEST['developer-contest-designate-post-as-contest-nonce'], 'developer-contest-designate-post-as-contest-nonce' ))){
                        wp_die("SOMETHING IS WRONG! INVALID NONCE!");
                    }
                    $this->doAction($_REQUEST['contestPostID']);
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
            update_post_meta( $postID, "developer-contest", "active");
    }
}