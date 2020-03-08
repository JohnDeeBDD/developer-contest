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
    $Freelancer = new Role_Freelancer;
    //$Freelancer->enable();
}
register_activation_hook( __FILE__, '\DeveloperContest\activatePlugin' );


$Role_Admin = new Role_Admin;
add_action("init", [$Role_Admin, "enable"]);

$Role_Freelancer = new Role_Freelancer;
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

// ssh ubuntu@54.68.103.203
// cd /var/www/html/wp-content/plugins/WPbdd
// xvfb-run java -Dwebdriver.chrome.driver=/var/www/html/wp-content/plugins/WPbdd/chromedriver -jar selenium.jar// xvfb-run java -Dwebdriver.chrome.driver=/var/www/chromedriver -jar selenium.jar
// cd /var/www/html/wphttps/wp-content/plugins/developer-contest
// bin/codecept run acceptance -vvv --html

