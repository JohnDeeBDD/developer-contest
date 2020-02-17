<tr>
    <th scope = "row">
        <label for="contestPostID">Post or Page ID</label>
    </th>
    <td>
        <input type = "text" name = "contestPostID" id = "contestPostID" size = "5" />
        <p class = "description" id = "developer-contest-p-enter-a-post-or-page-id" ></p>
        <script>
            jQuery('document').ready(function(){
                DeveloperContest.stringEnterAPostOrPageID = "<?php _e("Enter a post or page ID.", 'developer-contest'); ?>";
                DeveloperContest.stringEnterOnlyAnInteger = "<?php _e("Enter only an integer.", 'developer-contest'); ?>";
            });
        </script>
        <span id = "developer-contest-s-error-post-not-found">Error: post ID not found</span>
    </td>
</tr>
<tr id = "developer-contest-preview-and-submit-row">
    <th scope = "row">
        <label for="">
            <div id = "developer-contest-postid-submit-button-div">
                <input type = "submit" class="button button-primary" id = "developer-contest-postid-submit-button" />
            </div><!-- END: #developer-contest-postid-submit-button-div-->
        </label>
    </th>
    <td>
        <p id = "developer-contest-preview-post-about-to-be-selected" ></p>
    </td>
</tr>