<?php

namespace DeveloperContest;

class AdminRole{


    public function enableAbilityToStartContest(){
        $this->listenForStartContestSubmission();

        add_action('rest_api_init', array($this, 'doRegisterRouteStartContest'));
        add_action('admin_enqueue_scripts', array($this, 'enableAdminJS'));

        $fetchPostTitle = new Api_FetchPostTitleFromIdEvenIfPostIsUnpublished;
        $fetchPostTitle->enableApi();
    }

    public function listenForStartContestSubmission(){
        if(isset($_REQUEST['page'])){
            if($_REQUEST['page'] == "developer-contest"){
                if(isset($_REQUEST['action'])){
                    if($_REQUEST['action'] == "designate-post-as-contest"){
                        if(isset($_REQUEST['wpnonce'])){

                        }else{
                            die("something is very wrong. No nonce.");
                        }
                    }
                }
            }
        }
    }

    public function enableAbilityToDeclareWinner(){

        // add_action( "designate-post-as-contest", [$this, "designatePostAsContest"], 10, 1 );
        /*
        public function enableActionDesignatePostAsConstest(){}
        public function enableActionDesignateContestWinner(){}
    */
    }

    public function enableAdminJS() {
        wp_register_script(
            'developer-contest-admin-settings-page',
            plugin_dir_url(__FILE__) . 'settings-page.js', // here is the JS file
            ['jquery', 'wp-api'],
            '1.0',
            true
        );
        wp_localize_script( 'developer-contest-admin-settings-page', 'DeveloperContest', []);
        wp_enqueue_script('developer-contest-admin-settings-page');
    }
        public function designatePostAsContest($postID){
            update_post_meta( $postID, "developer-contest", "active");
            echo "success";
        }

    public function doRegisterRouteStartContest(){
        register_rest_route(
            'developer-contest/v1',
            'designate-post-as-contest',
            array(
                'methods'               => array('GET', 'POST'),
                'callback'              => function($request)
                {
                    $postID = $request["post-id"];
                    $AdminActions = new \DeveloperContest\AdminRole();
                    $AdminActions->designatePostAsContest($postID);
                },
                'permission_callback'   =>  function(){
                    $user = wp_get_current_user();
                    //var_dump($user);die();
                    if(!(current_user_can( 'activate_plugins' ))){
                        return FALSE;
                    }
                    if(!($this->validateRequestDesignatePostAsContest($postID))){
                        return FALSE;
                    }
                    return TRUE;
                 },
            )
        );
    }

    public function validateRequestDesignatePostAsContest($postID){
        if(is_int(intval($postID))){
            if ( get_post_status ( $postID) ) {
                return TRUE;
            }
        }
        return FALSE;
    }
}