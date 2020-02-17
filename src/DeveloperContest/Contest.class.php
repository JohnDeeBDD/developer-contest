<?php

namespace DeveloperContest;

class Contest{

    public function getStatus($postID){
        if ( !(get_post_status( $postID) ) ) {
            return "no-post";
        }

        if(!($this->isContest($postID))){
            return "no-contest";
        }
        $status = get_post_meta( $postID, "developer-contest", true);
        return $status;
    }

    public function isContest($postID){
        if(get_post_meta($postID, "developer-contest", true)){
            return TRUE;
        }else{
            return FALSE;
        }
    }



}
/*
 *         noPost
        noContest
        active
open
        closed
 */