<?php

namespace DeveloperContest;

class Role_Admin{

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
            $fetchPostTitle = new Action_FetchPostTitleFromIdEvenIfPostIsUnpublished;
            $fetchPostTitle->enableApi();

        }

    }

    public function designatePostAsContest($postID){
            //update_post_meta( $postID, "developer-contest", "active");
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
                    $AdminActions = new \DeveloperContest\Role_Admin();
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

        $Action_CreateNewContestEntry = new Action_CreateNewContestEntry;
        $html = $html . ($Action_CreateNewContestEntry->getActionButtonUiHtml($postID));
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