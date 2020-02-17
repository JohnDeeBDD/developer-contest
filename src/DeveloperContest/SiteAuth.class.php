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

ss
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
            //return "xx";
            return ($UiText . $dbOption);
        }
        $dbOption = $this->generateAuthToken();
       //return "zzz";
        return ($UiText . $dbOption);
    }
}
