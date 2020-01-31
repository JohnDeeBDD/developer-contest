<?php

namespace DeveloperContest;

class SettingsPage{

    public function enableSettingsPage(){
        add_action( 'admin_menu', array($this, 'addMenuPage' ));
        add_action('init', array($this, 'listenForSubmission'));
        add_action('init', [$this, 'listenForFreelancerSubmission']);
    }

    public function listenForFreelancerSubmission(){
        if(isset($_POST['create-new-submission'])) {
            if ($_POST['create-new-submission']) {
                $this->verifyNonce();
                $templatePostID = $_POST['create-new-submission'];
                $userID = get_current_user_id();
                echo($templatePostID . " " . $userID);
                die();
            }
        }
    }

    public function listenForSubmission(){
        if(!(isset($_POST['contestPostID']))){
            return;
        }
        $this->verifyNonce();

        $this->validateSubmission($_POST['contestPostID']);
        wp_set_post_tags( $_POST['contestPostID'], 'developer-contest', true );
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

    public function verifyNonce($nonce = null){
        $nonce = $_POST['developerContestNonceField'];
        if(wp_verify_nonce( $nonce, "useDeveloperContestSettingsPage" )){
            return TRUE;
        }else{
            die("SOMETHING IS VERY WRONG. code #25");
        }
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

    public function listFreelancerContestRows(){
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
        $contestRows = $this->listFreelancerContestRows();
        $output = <<<OUTPUT
<div id = "wpbody" role = "main">
   <div id = "wpbody-content">
      <div class = "wrap">
         <h1>
            Design Contest
         </h1>
         <form method = "post">
         <table class = "form-table">
            <tbody>
               <tr>
                  <th scope = "row">
                     <label for="contestPostID">Post or Page ID</label>
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
        $nonceField = wp_create_nonce( 'useDeveloperContestSettingsPage');
        $frontEndJQuery = $this->returnJquery();
        $contestRows = $this->listContestRows();
        $output = <<<OUTPUT
<div id = "wpbody" role = "main">
   <div id = "wpbody-content">
      <div class = "wrap">
         <h1>
            Design Contest
         </h1>
         <form method = "post">
         <table class = "form-table">
            <tbody>
               <tr>
                  <th scope = "row">
                     <label for="contestPostID">Post or Page ID</label>
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

    public function returnJquery(){
        //i18n:
        $EnterAPostOrPageID =  __("Enter a post or page ID.", "developer-contest");
        $YouMustEnterAnInteger = __("You must enter an integer.", "developer-contest");

        //jQuery Script:
        $output = <<<OUTPUT
<script>
jQuery(document).ready(function() {
    jQuery("#contestPostID").keyup(function() {
        DeveloperContest.ValidatePostIdAfterKeypress();
    });
    DeveloperContest.AjaxSiteWhenIdNumberInInputBoxIsStableFor1Second();
});

var originalColor = jQuery("#contestPostIDDescription").css("color");
const DeveloperContest = {};
DeveloperContest.fetchPostTitle = function(postID){
    jQuery.ajax({
        url: "/wp-json/developer-contest/v1/fetch-title", // or example_ajax_obj.ajaxurl if using on frontend
            data: {
                'postID': postID
            },
            success: function(data) {

                if(data == "Error: post does not exist"){
                    //console.log('83');
                    DeveloperContest.setDescriptionMessageToDefault();
                }else{
                    jQuery("#contestPostIDDescription").text(data);
                }
                // This outputs the result of the ajax request
                //console.log(data);
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
}
//var DeveloperContestPostIdValidationBool = FALSE;
DeveloperContest.AjaxSiteWhenIdNumberInInputBoxIsStableFor1Second = function(){
    //We dont want to AJAX the site after every keypress, but only after it's been there for a whole second
    // Get the input box
    let input = document.getElementById('contestPostID');

    // Init a timeout variable to be used below
    let timeout = null;

    // Listen for keystroke events
    input.addEventListener('keyup', function (e) {
    // Clear the timeout if it has already been set.
    // This will prevent the previous task from executing
    // if it has been less than <MILLISECONDS>
        clearTimeout(timeout);

    // Make a new timeout set to go off in 1000ms (1 second)
        timeout = setTimeout(function () {
            var postID = jQuery("#contestPostID").val();
            //if(DeveloperContest.IsItAValidPostIdEntry()){
            if(DeveloperContest.validatePostIdTextField(postID)){
                console.log("ajaxing " + postID);
                DeveloperContest.fetchPostTitle(postID);    
            }
        }, 1000);
    });
}
DeveloperContest.IsItAValidPostIdEntry = function(){
    //This function checks to see that the entry is a number or blank
    var postID = jQuery("#contestPostID").val();
    //console.log("postID" + postID);
    if(Math.floor(postID) == postID && jQuery.isNumeric(postID))  {
            //console.log("postID" + postID);
        return true;
    }else{
        return false;
    }
}
DeveloperContest.setDescriptionMessageToDefault = function(){
    jQuery("#contestPostIDDescription").text("$EnterAPostOrPageID");
    jQuery("#contestPostIDDescription").css("color", originalColor);
}
DeveloperContest.ValidatePostIdAfterKeypress = function(){
        var postID = jQuery("#contestPostID").val();
        if(DeveloperContest.IsItAValidPostIdEntry() || (postID == "")){
            DeveloperContest.setDescriptionMessageToDefault();

        }else{
            jQuery("#contestPostIDDescription").html("$YouMustEnterAnInteger");
            jQuery("#contestPostIDDescription").css("color", "red");
        }    
}
DeveloperContest.validatePostIdTextField = function(postID){
    if(postID == ""){
        return false;
    }
    if(Math.floor(postID) == postID && jQuery.isNumeric(postID)){
        return true;
    }
    return false;
}
DeveloperContest.lastAjaxSent = null;
</script>
OUTPUT;
    return $output;
    }

}

