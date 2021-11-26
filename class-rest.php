<?php

class REST {
    public function __construct() {
    }

    /**
     * register assets field for callback
     */
    public static function registerMeta() {
        register_rest_field('assets', 'postMeta', array(
            'get_callback' => function ($data) {
                return get_post_meta($data['id']);
            },
        ));
    }
}
