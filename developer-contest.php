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
//die('Developer Contest!');

$SettingsPage = new SettingsPage;
$SettingsPage->enableSettingsPage();






