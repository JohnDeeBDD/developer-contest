<?php

namespace DeveloperContest;

class Action_CreateNewContestEntry extends Action_Abstract{
    public $namespace;
    public $roles = ["administrator", "freelancer"];
    public $actionName = "create-new-contest-entry";
    public $availableLocations = ["developer-contest"];


    public function __construct(){
        parent::__construct();

    }
    public function enable(){
        add_action('init', [$this, 'listenForHtmlSubmission']);
    }

    //who can do this action
    public function setRoles($roles = []){}

    //where can this action occur
    public function setScreens($screens = []){}

    private function enableApi(){}
    public function listenForHtmlSubmission(){
        //die('listening');
        if(isset($_REQUEST['action'])){
            if($_REQUEST['action'] == ($this->namespace . "-" . $this->actionName)){
                //die("listenForStartContestSubmission action set");
                if (isset($_REQUEST['contestPostID'])){
                    $postID = $_REQUEST['contestPostID'];
                    if(!($this->validateFormSubmission($postID))){
                        wp_die("SOMETHING IS WRONG! PostID did not validate.");
                    };
                    if(!(isset($_REQUEST['developer-contest-create-new-contest-entry-nonce']))){
                        wp_die("SOMETHING IS WRONG! NONCE NOT FOUND.");
                    }
                    if(!(\wp_verify_nonce( $_REQUEST['developer-contest-create-new-contest-entry-nonce'], 'developer-contest-create-new-contest-entry-nonce' ))){
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
        //die("doing action");
        $this->duplicatePost($postID);
    }

    public function duplicatePost($post_id) {
        $title   = get_the_title($post_id);
        $oldpost = get_post($post_id);
        $ContestEntry = __("Contest Entry:", "developer-contest");
        $current_user = wp_get_current_user();
        $userName = $current_user->user_login;
        $post    = array(
            'post_title' => "$ContestEntry $title by $userName",
            'post_status' => 'draft',
            'post_type' => $oldpost->post_type
        );
        $new_post_id = wp_insert_post($post);
        // Copy post metadata scp 20200107_parler_f086dd1e5a1cd0111133_20200305190543_archive.zip ubuntu@54.68.103.203:/var/www/html/wphttps/20200107_parler_f086dd1e5a1cd0111133_20200305190543_archive.zip
        $data = get_post_custom($post_id);
        foreach ( $data as $key => $values) {
            foreach ($values as $value) {
                add_post_meta( $new_post_id, $key, $value );

            }
        }
        add_post_meta($new_post_id, "developer-contest-entry", $post_id);

        return $new_post_id;
    }
}