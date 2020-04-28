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

//die("developer-contest plugin");
require_once (plugin_dir_path(__FILE__). 'src/DeveloperContest/autoloader.php');
require_once (plugin_dir_path(__FILE__). 'src/GeneralChickenAuth/autoloader.php');

$Action_DesignatePostAsContest = new Action_DesignatePostAsContest;
$Action_DesignatePostAsContest->enableVia(['html']);

$Action_RemovePostAsContest = new Action_RemovePostAsContest;
$Action_RemovePostAsContest->enableVia(['html']);

function activatePlugin() {
    $Freelancer = new Role_Freelancer;
    $Freelancer->enableRole();
}
register_activation_hook( __FILE__, '\DeveloperContest\activatePlugin' );


$Role_Admin = new Role_Admin;
add_action("init", [$Role_Admin, "enable"]);

$Role_Freelancer = new Role_Freelancer;
$Action_CreateNewContestEntry = new Action_CreateNewContestEntry;
//$Action_CreateNewContestEntry->namespace = "developer-contest";
//$Action_CreateNewContestEntry->enable(['html']);

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

// ssh ubuntu@54.68.103.203
// cd /var/www/html/wp-content/plugins/WPbdd
// xvfb-run java -Dwebdriver.chrome.driver=/var/www/html/wp-content/plugins/WPbdd/chromedriver -jar selenium.jar
//// xvfb-run java -Dwebdriver.chrome.driver=/var/www/chromedriver -jar selenium.jar
// cd /var/www/html/wphttps/wp-content/plugins/developer-contest
// bin/codecept run acceptance -vvv --html

