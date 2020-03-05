<?php

namespace DeveloperContest;

class AdminRole{

    public function __construct(){
        $Action_RemovePostAsContest = new Action_RemovePostAsContest;
        $Action_RemovePostAsContest->enable();
    }

    public function enable(){
        $user = wp_get_current_user();
        if ( in_array( 'administrator', (array) $user->roles ) ) {
           // die("11");
          //  $this->listenForStartContestSubmission();
            add_action('rest_api_init', array($this, 'doRegisterRouteStartContest'));
            $fetchPostTitle = new Api_FetchPostTitleFromIdEvenIfPostIsUnpublished;
            $fetchPostTitle->enableApi();

        }

    }

    public function listenForStartContestSubmission(){
        //die("listenForStartContestSubmission");
        if(isset($_REQUEST['action'])){

            if($_REQUEST['action'] == "developer-contest-designate-post-as-contest"){
                //die("listenForStartContestSubmission action set");
                if (isset($_REQUEST['contestPostID'])){
                    $postID = $_REQUEST['contestPostID'];
                    if(!($this->validateRequestDesignatePostAsContest($postID))){
                        die("SOMETHING IS WRONG! PostID did not validate.");
                    };
                    if(!(isset($_REQUEST['developer-contest-designate-post-as-contest-nonce']))){
                        die("SOMETHING IS WRONG! NONCE NOT FOUND.");
                    }
                    if(!(\wp_verify_nonce( $_REQUEST['developer-contest-designate-post-as-contest-nonce'], 'developer-contest-designate-post-as-contest-nonce' ))){
                        die("SOMETHING IS WRONG! INVALID NONCE!");
                    }
                    $this->designatePostAsContest($_REQUEST['contestPostID']);
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

    public function designatePostAsContest($postID){
            update_post_meta( $postID, "developer-contest", "active");
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

    public function returnActionButtons($postID){
        if (!(current_user_can('manage_options'))) {
            return;
        }
        $removeButton = new Action_RemovePostAsContest;
        $html = $removeButton->getActionButtonUiHtml($postID);
        return ("Details Fund Start End $html");
    }

    public function settingsPageReturnSubmissionsForContest($postID){
            $userID = get_current_user_id();
            //die("user: $userID");
            $args = array(
               // 'author'    =>  $userID,
                'meta_query' => array(
                    array(
                        'key' => 'developer-contest-entry',
                        'value' => $postID
                    )
                )
            );
            $my_secondary_loop = new \WP_Query($args);
            $output = "";
            if( $my_secondary_loop->have_posts() ) {
                while ($my_secondary_loop->have_posts()){
                    $my_secondary_loop->the_post();
                    $postID = get_the_ID();
                    $meta_data = get_post_custom($postID);
                    $output = $output . "<tr><th></th><td>" . "<a href = '" . "/wp-admin/post.php?post=$postID&action=edit" . "' target = '_blank'>"  . get_the_title() . "</a></tr></td>"; // your custom-post-type post's title

                }
            }
           return $output;
    }
}