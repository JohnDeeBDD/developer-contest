<?php

namespace DeveloperContest;

class SettingsPage{

    public function enable(){
        add_action( 'admin_menu', array($this, 'addMenuPage' ));
       //add_action('init', array($this, 'listenForAdminOrEditorSubmission'));
       // add_action('init', [$this, 'listenForFreelancerSubmission']);
    }

    public function addMenuPage() {
        $user = wp_get_current_user();
        if ( in_array( 'administrator', (array) $user->roles ) ) {
            add_menu_page( "developer-contest", "admnDesign Contest", "activate_plugins","developer-contest", array($this, "renderDeveloperContestSettingsPage"));
        }
        if ( in_array( 'freelancer', (array) $user->roles ) ) {
            add_menu_page( "developer-contest", "freeDesign Contest", "edit_posts","developer-contest", array($this, "renderDeveloperContestFreelancerSettingsPage"));
        }
    }

    public function validateSubmission($postID){
        $validated = FALSE;
        if(substr($postID, 0, strlen("0")) === "0"){
            return false;
        }
        if(is_numeric($postID)){
            $postID = intval($postID);
        }else{
            return false;
        }
        if(is_int($postID)){
            $validated = true;
        }

        //at this point, we know the input is a number. Now let's check if it's a valid postID
        $API = new Api_FetchPostTitleFromIdEvenIfPostIsUnpublished();
        $validated = $API->doesPostExist($postID);

        return $validated;
    }

    public function listContestRows(){
        $output = "";
        $args = ['post_type' => 'post', 'post_status' => 'publish'];

        $my_secondary_loop = new \WP_Query($args);
        if( $my_secondary_loop->have_posts() ):
            while( $my_secondary_loop->have_posts() ): $my_secondary_loop->the_post();
                $output = $output . "<tr><th></th><td>" . get_the_title() . "</tr></td>"; // your custom-post-type post's title
            endwhile;
        endif;
        wp_reset_postdata();


        return  ($output);

    }

    public function returnFreelancerContestRows(){
        $output = "";
        $args = ['tag' => 'developer-contest'];

        $my_secondary_loop = new \WP_Query($args);
        if( $my_secondary_loop->have_posts() ):
            while( $my_secondary_loop->have_posts() ): $my_secondary_loop->the_post();
                $postID = get_the_ID();
                $userID = get_current_user_id();
                $output = $output . "<tr><th>". ($this->getFreelancerItemButton($postID, $userID)). "</th><td>" . get_the_title() . "</tr></td>"; // your custom-post-type post's title
            endwhile;
        endif;
        wp_reset_postdata();
        return  ($output);
    }

    public function getFreelancerItemButton($postID, $userID){
        //this function should return the available actions for a particular post: create entry, delete entry
        $output = <<<OUTPUT
<input type = "submit" name = "createSubmission" value = "Create New Entry" />
OUTPUT;
        return $output;
    }

    public function renderDeveloperContestFreelancerSettingsPage(){
        $nonceField = wp_create_nonce( 'useDeveloperContestSettingsPage');
        $frontEndJQuery = $this->returnJquery();
        $contestRows = $this->returnFreelancerContestRows();

        $output = <<<OUTPUT
<div id = "wpbody" role = "main">
   <div id = "wpbody-content">
      <div class = "wrap">
         <h1>
            Design Contest!!<br />
         </h1>
         <form method = "post">
         <table class = "form-table">
            <tbody>
               <tr>
                  <th scope = "row">
                     <label for=""></label>
                  </th>
                  <td>
                     <input type = "text" name = "contestPostID" id = "contestPostID" size = "5" />
                     <p class = "description" id = "contestPostIDDescription" >Enter a post or page ID.</p>
                     <input type = "hidden" name = "developerContestNonceField" id = "developerContestNonceField" value = "$nonceField" />
                     $frontEndJQuery
                  </td>
               </tr>
               $contestRows
            </tbody>
         </table>
         <div>
            <input type = "submit" class="button button-primary"/>
         </div>
         </form>
      </div>
      <!-- END: #wrap -->
   </div>
   <!-- END: #wpbody-content -->
</div>
<!-- END: #wpbody -->
OUTPUT;
        echo $output;
    }
    public function renderDeveloperContestSettingsPage(){
       // $nonceField = wp_create_nonce( 'useDeveloperContestSettingsPage');
        //$frontEndJQuery = $this->returnJquery();
        $contestRows = $this->listContestRows();
       // $otherNonce = wp_create_nonce( 'wp_rest' );
        require("SettingsPage.php");
    }

    public function returnJquery(){
        $src = plugins_url();
        $src = $src . "/developer-contest/src/SettingsPage.js";
        $src = "/var/www/html/wp-content/plugins/developer-contest/src/SettingsPage.js";

    //   if ( isset( $scripts->registered['wp-api'] ) ) {
        //   $scripts->registered['wp-api']->src = $src;
     //   } else {
           // wp_register_script(  'developer-contest-settings', $src, array( 'jquery', 'underscore', 'backbone', 'wp-api' ), '1.0', true );
    //    }
        //wp_register_script( 'developer-contest-settings', $src, array( 'jquery', 'underscore', 'backbone'), '1.0', true );
      //  wp_enqueue_script( 'wp-api' );
     //   wp_localize_script( 'wp-api', 'wpApiSettings', array(
     //       'root' => esc_url_raw( rest_url() ),
       //     'nonce' => wp_create_nonce( 'wp_rest' )
       // ) );


        //i18n:
        $EnterAPostOrPageID =  __("Enter a post or page ID.", "developer-contest");
        $YouMustEnterAnInteger = __("You must enter an integer.", "developer-contest");

    return;
    }

}