<?php

namespace DeveloperContest;

abstract class Action_Abstract{

    public $namespace;
    public $actionName;

    abstract public function doAction($args);

    public function enableVia($data = []){
        if(is_array($data)){
            foreach($data as $actionInitiationMethod){
                switch ($actionInitiationMethod) {
                    case "html":
                        add_action('init', [$this, 'listenForHtmlSubmission']);
                        break;
                    case "api":
                        add_action ('rest_api_init', array($this, 'doRegisterRoutes'));
                        break;
                    default:
                        die('ERROR: Abstract Action:: ' . get_called_class() . ' Method set but not an array!');
                }
            }
        }else{
            die('ERROR: Abstract Action. Method set but not an array!');
        }
    }



    public function enableApi(){}
    public function getActionButtonUiHtml($args){}
    public function listenForHtmlSubmission(){}
    public function validateSubmission($args){}

    public function verifyNonce(){}



}
