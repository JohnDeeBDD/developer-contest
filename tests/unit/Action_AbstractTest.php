<?php

class Action_AbstractTest extends \Codeception\TestCase\WPTestCase
{

    /**
     * @test
     * it should be extensible
     */
    public function it_should_be_extensible(){
        $Action = new Action_MockAction();
    }

}
require_once("/var/www/html/wp-content/plugins/developer-contest/src/DeveloperContest/Action_Abstract.class.php");
class Action_MockAction extends \DeveloperContest\Action_Abstract{
    public $namespace;

    public $actionName = "mock-action";

    public function enableApi(){}
    public function listenForHtmlSubmission(){
        //die('listening');
        if(isset($_REQUEST['action'])){
            // var_dump($_REQUEST['wickedPostID']); die();
            if($_REQUEST['action'] == ($this->namespace . "-" . $this->actionName)){
                //die("listenForStartContestSubmission action set");
                if (isset($_REQUEST['contestPostID'])){
                    $postID = $_REQUEST['contestPostID'];
                    //var_dump($postID);die();
                    if(!($this->validateSubmission($postID))){
                        wp_die("SOMETHING IS WRONG! PostID did not validate. $postID is the postid #35");
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
    public function verifyNonce(){}

    public function validateSubmission($postID){}

    public function doAction($postID){
        //die("doing action");
        $this->duplicatePost($postID);
    }

    public function getActionButtonUiHtml($postID){
        $nonce = wp_create_nonce( "developer-contest-create-new-contest-entry-nonce" );
        $output = <<<OUTPUT
<input type = "button" id = "developer-contest-create-new-contest-entry-button-$postID" class = "developer-contest-action-button" value = "New Entry" />
<input type = "hidden" name = "developer-contest-create-new-contest-entry-nonce" value = "$nonce" />
<script>
jQuery("#developer-contest-create-new-contest-entry-button-$postID").click(function(){
    console.log("click");
     jQuery('#developer-contest-form').attr('action', '/wp-admin/admin.php?page=developer-contest&action=developer-contest-create-new-contest-entry');
     jQuery('#developer-contest-form').append('<input type="hidden" name="contestPostID" value="$postID" /> ');
     jQuery("#developer-contest-form").submit(); // Submit
});
</script>
OUTPUT;
        return $output;
    }

}