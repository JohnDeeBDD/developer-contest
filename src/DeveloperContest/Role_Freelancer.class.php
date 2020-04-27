<?php

namespace DeveloperContest;

class Role_Freelancer{

    public function enableRole(){
        remove_role('freelancer');
        $result = add_role('freelancer', 'Freelancer',
    array(
// Dashboard
        'read' => true, // Access to Dashboard and Users -> Your Profile.
        'update_core' => false, // Can NOT update core. I added a plugin for this.
       // 'edit_posts' => true, //Access to Posts, Add New, Comments and moderating comments.
// Posts
        'edit_posts' => true, //Access to Posts, Add New, Comments and moderating comments.
        'create_posts' => false, // Allows user to create new posts
        'delete_posts' => true, // Can delete posts.
        'publish_posts' => false, // Can publish posts. Otherwise they stay in draft mode.
        'delete_published_posts' => true, // Can delete published posts.
        'edit_published_posts' => true, // Can edit posts.
        'edit_others_posts' => false, // Can edit other users posts.
        'delete_others_posts' => false, // Can delete other users posts.
// Categories, comments and users
        'manage_categories' => false, // Access to managing categories.
        'moderate_comments' => false, // Access to moderating comments. Edit posts also needs to be set to true.
        'edit_comments' => false, // Comments are blocked out for this user.
        'edit_users' => false, // Can not view other users.
// Pages
        'edit_pages' => true, // Access to Pages and Add New (page).
        'publish_pages' => false, // Can publish pages.
        'edit_other_pages' => false, // Can edit other users pages.
        'edit_published_ pages' => true, // Can edit published pages.
        'delete_pages' => false, // Can delete pages.
        'delete_others_pages' => false, // Can delete other users pages.
        'delete_published_pages' => false, // Can delete published pages.
// Media Library
        'upload_files' => true, // Access to Media Library.
// Appearance
        'edit_themes_options' => false, // Access to Appearance panel options.
        'switch_themes' => false, // Can not switch themes.
        'delete_themes' => false, // Can NOT delete themes.
        'install_themes' => false, // Can not install a new theme.
        'update_themes' => false, // Can NOT update themes.
        'edit_themes' => false, // Can not edit themes - through the appearance editor.
// Plugins
        'activate_plugins' => false, // Access to plugins screen.
        'edit_plugins' => false, // Can not edit plugins - through the appearance editor.
        'install_plugins' => false, // Access to installing a new plugin.
        'update_plugins' => false, // Can update plugins.
        'delete_plugins' => false, // Can NOT delete plugins.
// Settings
        'manage_options' => false, // Can not access Settings section.
// Tools
        'import' => false, // Can not access Tools section.
    )
);



    }

    public function returnActionButtons($postID){
        $Action_CreateNewContestEntry = new Action_CreateNewContestEntry;
        $output = $Action_CreateNewContestEntry->getActionButtonUiHtml($postID);
        return $output;
    }

    public function isPostAContest($postID){}
    public function startNewEntry($postID){}
    public function getSettingsPageContestLink($postID){}
    public function openEntry($postID){}

}