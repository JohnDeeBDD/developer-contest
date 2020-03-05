<?php
/**
 * Plugin Name:       Developer Contest
 * Plugin URI:
 * Description:       A contest pluigin
 * Version:           1
 * Author:            John Dee
 * Author URI:        https://home.parler.com/
 * License:           GPLv2 or later
 */

namespace DeveloperContest;

require_once (plugin_dir_path(__FILE__). 'src/DeveloperContest/autoloader.php');

$Action = new Action_DesignatePostAsContest;
//$Action->setPluginSlug();
$slug = $Action->namespace;
//die($slug);


function activatePlugin() {
    $Freelancer = new FreelancerRole;
    $Freelancer->enable();
}
register_activation_hook( __FILE__, '\DeveloperContest\activatePlugin' );


$AdminRole = new AdminRole;
add_action("init", [$AdminRole, "enable"]);

$Freelancer = new FreelancerRole;
$Action_CreateNewContestEntry = new Action_CreateNewContestEntry;
$Action_CreateNewContestEntry->enable();

$SettingsPage = new SettingsPage;
$SettingsPage->enable();

$EditorUI = new EditorUI;
$EditorUI->enableEditorUI();

$SiteAuth = new SiteAuth;
$SiteAuth->enableApi();


/*
 * status
 * active
 * done
 * endTime
 * startTime
 * prize
 *
 */