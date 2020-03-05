<?php

namespace DeveloperContest;

abstract class Action_Abstract{

    public $namespace;
    public $actionName;
    public $roles = [];
    abstract public function doAction($args);

    public function setNamespace($namespace = null){
        //This function assumes it lives in "wp-content/plugins/pluginDir/src/NAMESPACE"
        if ($namespace == null){
            $namespace = (dirname(plugin_basename(__FILE__)));
            $namespace = substr($namespace, 0, strpos($namespace, '/'));
        }
        $this->namespace = $namespace;
        return true;
    }

    public function __construct(){
        $this->setNamespace();
        add_action("init", [$this, "listenForHtmlSubmission"]);
        $this->enableApi();
    }

    //who can do this action
    public function setRoles($roles = []){

    }

    //where can this action occur
    public function setScreens($screens = []){}

    private function enableApi(){}
    public function getActionButtonUiHtml($args){}
    public function listenForHtmlSubmission(){}
    public function validateFormSubmission($args){}
    private function verifyNonce(){}
    private function verifyUser(){}


}
