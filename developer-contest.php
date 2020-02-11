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

//die("Developer Contest Plugin!");

require_once (plugin_dir_path(__FILE__). 'src/DeveloperContest/autoloader.php');

function activatePlugin() {
    $Freelancer = new Freelancer;
    $Freelancer->enableRole();
}
register_activation_hook( __FILE__, '\DeveloperContest\activatePlugin' );


$AdminRole = new AdminRole;
$AdminRole->enableAbilityToStartContest();

$SettingsPage = new SettingsPage;
$SettingsPage->enable();

$EditorUI = new EditorUI;
$EditorUI->enableEditorUI();