<?php

namespace DeveloperContest;

class Action_FetchPostTitleFromIdEvenIfPostIsUnpublished extends Action_Abstract{

    public function enableApi(){

    }

    public function doRegisterRoutes(){
        //die("Action_FetchPostTitleFromIdEvenIfPostIsUnpublished line 12");
        register_rest_route(
            'developer-contest/v1',
            'fetch-title',
            array(
                'methods'               => array('POST'),
                'callback'              => array($this, 'doAction'),
                'permission_callback'   => function(){
                    if(!(current_user_can( 'activate_plugins' ))){
                        return FALSE;
                    }
                    return TRUE;
                },
            )
        );
    }

    public function doAction($request){
        if(!(isset($_REQUEST['postID']))){
            return "Error: No post id";
        }
        $postID = $_REQUEST['postID'];
        if(!(is_numeric ( $postID ))){
            return "Error: something is wrong with the input";
        }
        if(!($this->doesPostExist($postID))){
            return "Error: post does not exist";
        }
        return ($this->returnPostTitle($postID));
    }

    public function returnPostTitle($postID){
        $title = get_the_title( $postID );
        return $title;
    }

    public function doesPostExist($postID){
        //an existing post will have some kind of post status that is a string
        return is_string( get_post_status($postID) );
    }

}

