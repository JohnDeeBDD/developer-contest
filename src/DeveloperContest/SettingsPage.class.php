<?php

namespace DeveloperContest;

class SettingsPage{

    public function enableSettingsPage(){
        add_action( 'admin_menu', array($this, 'sitepoint_settings_page' ));
    }

    public function sitepoint_settings_page() {
        add_management_page( "developer-contest", "Design Contest", "activate_plugins","developer-contest", array($this, "renderDeveloperContestSettingsPage"));
    }
    public function renderDeveloperContestSettingsPage(){
        echo ("HELLOWXX");
    }

}