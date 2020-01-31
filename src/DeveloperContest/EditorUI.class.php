<?php

namespace DeveloperContest;

class EditorUI{

    public function enableEditorUI(){
        add_action('init', [$this, 'detectIfFreelancer']);

        add_action('add_meta_boxes', array($this, 'doAddMetaBox'));
    }

public function detectIfFreelancer(){
    $user = wp_get_current_user();
    if ( in_array( 'frexelancer', (array) $user->roles ) ) {
        die('freelancer');
    }
}

public function metaBoxCallback(){
    echo ("!!!!!!");
}

public function doAddMetaBox()
{
    add_meta_box(
        'my-meta-box',
        'My Meta Box',
        array($this, 'metaBoxCallback'),
        null,
        'normal', 'high',
        array(
            '__block_editor_compatible_meta_box' => true,
            '__back_compat_meta_box' => false,
        )
    );
}

}