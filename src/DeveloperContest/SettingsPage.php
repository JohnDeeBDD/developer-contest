<?php
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

?>
<style>
    #developer-contest-s-error-post-not-found{color:red; display:none;}
</style>
<div id = "wpbody" role = "main">
    <div id = "wpbody-content">
        <div class = "wrap">
            <h1>
                <?php _e('Design Contest', 'developer-contest'); ?>
            </h1>
            <form method = "post" action = "/wp-admin/admin.php?page=developer-contest" id = "developer-contest-form">
                <input type = "hidden" name = "developer-contest-designate-post-as-contest-nonce" id = "developer-contest-designate-post-as-contest-nonce" value = "<?php echo(wp_create_nonce("developer-contest-designate-post-as-contest-nonce")); ?>" />
                <table class = "form-table">
                    <tbody>
<?php
$user = wp_get_current_user();
if ( in_array( 'administrator', (array) $user->roles ) ) {
    include("SettingsPage_AddContestRow.php");
    include("SettingsPage_ActiveContestRowAdmin.php");
}else {
    include("SettingsPage_ActiveContestRowFreelancer.php");
}
?>
                    </tbody>
                </table>
                <div id = "contest-end">
                <input type = "text" name ="contest-end" id = "" /></div>
            </form>
            <?php
                $SiteAuth = new \DeveloperContest\SiteAuth();
                echo ($SiteAuth->returnUiHtml());
            ?>
        </div>
        <!-- END: #wrap -->
    </div>
    <!-- END: #wpbody-content -->
</div>
<!-- END: #wpbody -->
