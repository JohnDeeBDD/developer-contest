<?php

namespace DeveloperContest;

class SettingsPage{

    public function enableSettingsPage(){
        add_action( 'admin_menu', array($this, 'sitepoint_settings_page' ));
    }

    public function sitepoint_settings_page() {
        add_menu_page( "developer-contest", "Design Contest", "activate_plugins","developer-contest", array($this, "renderDeveloperContestSettingsPage"));
    }
    public function renderDeveloperContestSettingsPage(){
        $contestRows = $this->returnContestRows();
        $nonceField = wp_create_nonce( 'createContest');
        $frontEndJQuery = $this->returnJquery();
        $output = <<<OUTPUT
<div id = "wpbody" role = "main">
   <div id = "wpbody-content">
      <div class = "wrap">
         <h1>
            Design Contest
         </h1>
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
            </tbody>
         </table>
         <div>
            <input type = "submit" class="button button-primary"/>
         </div>
      </div>
      <!-- END: #wrap -->
   </div>
   <!-- END: #wpbody-content -->
</div>
<!-- END: #wpbody -->
OUTPUT;
        echo $output;
    }

    public function returnContestRows(){
    }

    public function returnJquery(){
        //i18n:
        $EnterAPostOrPageID =  __("Enter a post or page ID.", "developer-contest");
        $YouMustEnterAnInteger = __("You must enter an integer.", "developer-contest");

        //jQuery Script:
        $output = <<<OUTPUT
<script>
jQuery(document).ready(function() {
    var contestPostIdDescriptionText = jQuery("#contestPostIDDescription").text();
    var contestPostIdValidValue = "";

    jQuery("#contestPostID").keyup(function() {
        //alert(originalColor);
        DeveloperContest.ValidatePostIdAfterKeypress();
    });
    console.log(DeveloperContest.IsItAValidPostIdEntry());
    if(DeveloperContest.IsItAValidPostIdEntry()){
    //if(1==1){

        DeveloperContest.AjaxSiteWhenIdNumberInInputBoxIsStableFor1Second();
    }
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
                    console.log('83');
                    DeveloperContest.setDescriptionMessageToDefault();
                }else{
                    jQuery("#contestPostIDDescription").text(data);
                }
                // This outputs the result of the ajax request
                console.log(data);
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
            if(DeveloperContest.IsItAValidPostIdEntry()){
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
DeveloperContest.lastAjaxSent = null;
</script>
OUTPUT;
    return $output;
    }

}

