jQuery(document).ready(function() {
    DeveloperContest.SetupStrings();
    jQuery("#contestPostID").keyup(function() {
        DeveloperContest.validatePostIdAfterKeypress();
    });
    DeveloperContest.ajaxSiteWhenIdNumberInInputBoxIsStableFor1Second();

    jQuery("#developer-contest-form").on('submit', function(e){
        //e.preventDefault();
    });
    jQuery("#developer-contest-postid-submit-button").click(function(){
        alert('click');
        jQuery('#developer-contest-form').attr('action', '/wp-admin/admin.php?page=developer-contest&action=developer-contest-designate-post-as-contest');
        jQuery("#developer-contest-form").submit(); // Submit
    });
});



var originalColor = jQuery("#developer-contest-p-enter-a-post-or-page-id").css("color");

DeveloperContest.ajaxSiteWhenIdNumberInInputBoxIsStableFor1Second = function(){
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
            if(DeveloperContest.validatePostIdTextField(postID)){
                console.log("AjaxSiteWhenIdNumberInInputBoxIsStableFor1Second post" + postID);
                DeveloperContest.fetchPostTitleAjax(postID);
            }
        }, 1000);
    });
}
DeveloperContest.fetchPostTitleAjax = function(postID){
    jQuery.ajax({
        url: "/wp-json/developer-contest/v1/fetch-title",
        data: {
            'postID': postID,
            '_wpnonce': wpApiSettings.nonce,
        },
        method: "POST",
        success: function(data) {
            fetchPostTitleResponse = String(data);
            if(data == "Error: post does not exist"){
                console.log('fetchPostTitleAjax');
                DeveloperContest.setDescriptionMessageToDefault();
            }else{
                console.log("data coming in " + data);
                //jQuery("#developer-contest-preview-and-submit-row").show();
                jQuery("#developer-contest-preview-post-about-to-be-selected").text(fetchPostTitleResponse);
            }
        },
        error: function(errorThrown) {
            fetchPostTitleResponse = JSON.stringify(errorThrown);
            console.log(errorThrown);
        }
    });
}
DeveloperContest.IsItAValidPostIdEntry = function(){
    //This function checks to see that the entry is a number or blank
    var postID = jQuery("#contestPostID").val();
    console.log("checking IsItAValidPostIdEntry" + postID);
    if(Math.floor(postID) == postID && jQuery.isNumeric(postID))  {
        //console.log("postID" + postID);
        return true;
    }else{
        return false;
    }
}
DeveloperContest.SetupStrings = function(){
    //console.log("SetupStrings");
    jQuery("#developer-contest-p-enter-a-post-or-page-id").text(DeveloperContest.stringEnterAPostOrPageID);
}
DeveloperContest.setDescriptionMessageToDefault = function(){
    jQuery("#developer-contest-p-enter-a-post-or-page-id").text(DeveloperContest.stringEnterAPostOrPageID);
    jQuery("#developer-contest-p-enter-a-post-or-page-id").css("color", originalColor);
    jQuery("#developer-contest-postid-submit-button").prop("disabled",false);
}
DeveloperContest.validatePostIdAfterKeypress = function(){
    jQuery("#developer-contest-preview-post-about-to-be-selected").text("");
    var postID = jQuery("#contestPostID").val();
    if(DeveloperContest.IsItAValidPostIdEntry() || (postID == "")){
        console.log("IsItAValidPostIdEntry 90 post is valid");
        DeveloperContest.setDescriptionMessageToDefault();
    }else{
        console.log("post is NOT valid");
        console.log(DeveloperContest.stringEnterOnlyAnInteger);
        jQuery("#developer-contest-p-enter-a-post-or-page-id").html(DeveloperContest.stringEnterOnlyAnInteger);
        jQuery("#developer-contest-p-enter-a-post-or-page-id").css("color", "red");
        jQuery("#developer-contest-postid-submit-button").prop("disabled",true);
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