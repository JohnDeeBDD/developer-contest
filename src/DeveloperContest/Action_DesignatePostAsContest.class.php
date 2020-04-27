<?php

namespace DeveloperContest;

class Action_DesignatePostAsContest extends Action_Abstract{
    public $namespace = "developer-contest";
    public $actionName = "designate-post-as-contest";

    public function listenForHtmlSubmission(){
        //die("listenForHtmlSubmission Action_DesignatePostAsContest");
        if(isset($_REQUEST['action'])){
            if($_REQUEST['action'] == ($this->namespace . "-" . $this->actionName)){
                //die("Action_DesignatePostAsContest 14");
                if (isset($_REQUEST['contestPostID'])){
                    $postID = $_REQUEST['contestPostID'];
                    //die("post $postID");
                    if(!($this->validateSubmission($postID))){
                        $className = get_class($this);
                        wp_die("SOMETHING IS WRONG! PostID did not validate. $className line 33");
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
    public function verifyNonce($nonce = null){}
    private function verifyUser(){}

    public function validateSubmission($postID){
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