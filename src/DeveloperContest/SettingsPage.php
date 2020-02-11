<style>
    #developer-contest-s-error-post-not-found{color:red; display:none;}
</style>
<div id = "wpbody" role = "main">
    <div id = "wpbody-content">
        <div class = "wrap">
            <h1>
                <?php _e('Design Contest', 'developer-contest'); ?>
            </h1>
            <form method = "post">
                <table class = "form-table">
                    <tbody>
<?php
    include("AddContestRow.php");
    include("ActiveContestRow.php");
?>
                    </tbody>
                </table>
            </form>
        </div>
        <!-- END: #wrap -->
    </div>
    <!-- END: #wpbody-content -->
</div>
<!-- END: #wpbody -->