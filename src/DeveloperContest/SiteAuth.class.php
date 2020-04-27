<?php

namespace DeveloperContest;

abstract class AbstractAuth{
    public $slug;

    abstract public function authenticateApiRequest();
    abstract public function returnUiHtml();
    private function fetchAuthTokenFromDB(){
        $slug = $this->slug;
        $authToken = get_option($slug);
        return $authToken;
    }
    public abstract function generateAuthToken();
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}




class SiteAuth extends AbstractAuth
{
    public function enableApi(){
        add_action ('rest_api_init', array($this, 'registerIsDeveloperContestApi'));
        add_action ('rest_api_init', array($this, 'registerResetAuthApi'));
        add_action("init", [$this, "listenForManualReset"]);
    }

    public function listenForManualReset(){
        if(isset($_GET['developer-contest-site-auth-token-reset'])){
            $user = wp_get_current_user();
            if ( in_array( 'administrator', (array) $user->roles ) ) {
                $this->resetAuth();
            }
        }
    }

    public function getSiteAuthToken()
    {
        $developerContestSiteAuthToken = get_option('developerContest-site-auth-token');
        //die($developerContestSiteAuthToken);
        if ($developerContestSiteAuthToken == FALSE) {
            return FALSE;
        }
        return $developerContestSiteAuthToken;
        //todo: is the token EVER used?
    }


    public function authenticateApiRequest()
    {
        //the auth token must be passed in the request body as ['developerContest-site-auth-token']
        $token = false;
        if (isset($_POST['developerContest-site-auth-token'])) {
            $token = $_POST['developerContest-site-auth-token'];
        }
        if (isset($_GET['developerContest-site-auth-token'])) {
            $token = $_GET['developerContest-site-auth-token'];
        }
        if ($token == false) {
            return false;
        }
        $dbOption = get_option('developerContest-site-auth-token');
        if ($token == $dbOption) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function generateAuthToken()
    {
        $siteUrl = get_site_url();
        //we remove the "scheme" from the string before hashing
        $siteUrl = parse_url($siteUrl, PHP_URL_HOST);
        $hash = substr(md5($siteUrl), 0, 10);
        $random = $this->generateRandomString(40);
        $token = $hash . $random;
        update_option('developerContest-site-auth-token', $token);
        return $token;
    }

    public function returnUiHtml()
    {
        global $Constants;
        $serverUrl = "https://wphttps.par.pw/";
        $UiText = __("Copy and then paste this code here: <a href = '$serverUrl' target = '_blank'>DeveloperContest.com</a><br />");
        $dbOption = get_option('developerContest-site-auth-token');
        if ($dbOption) {

            return ($UiText . $dbOption);
        }
        $dbOption = $this->generateAuthToken();
        return ($UiText . $dbOption);
    }

    public function registerIsDeveloperContestApi(){
        register_rest_route(
            'developer-contest/v1',
            'is-developer-contest',
            array(
                'methods'               => array('GET','POST'),
                'callback'              => [$this, 'doRegisterRemoteSite'],
                'permission_callback'   =>
                    array(
                        new \DeveloperContest\SiteAuth(),
                        'authenticateApiRequest'
                    )
            )
        );
    }
    public function doRegisterRemoteSite(){
        update_option("developer-contest-auth-registration", true);
        return true;
    }
    public function isRemoteSiteRegistered(){
        if(get_option("developer-contest-auth-registration")){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function resetAuth(){
        delete_option("developer-contest-auth-registration" );
        delete_option( "developerContest-site-auth-token");
        return "reset";
    }

    public function registerResetAuthApi(){
        register_rest_route(
            'developer-contest/v1',
            'reset-auth',
            array(
                'methods'               => array('GET','POST'),
                'callback'              => [$this, 'resetAuth'],
                'permission_callback'   => [$this, 'authenticateApiRequest']
            )
        );
    }
}