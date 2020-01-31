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


function activatePlugin() {
    $Freelancer = new Freelancer;
    $Freelancer->enableRole();
}
register_activation_hook( __FILE__, '\DeveloperContest\activatePlugin' );


$SettingsPage = new SettingsPage;
$SettingsPage->enableSettingsPage();

$Freelancer = new Freelancer;
$Freelancer->enableRole();

$API = new Api_FetchPostTitleFromIdEvenIfPostIsUnpublished;
$API->enableApi();

$EditorUI = new EditorUI;
$EditorUI->enableEditorUI();

