<?php

class REST {
    public function __construct() {
    }

    /**
     * register custom post "meta fields" for callback
     */
    public static function registerMeta() {

        $customPosts = array(
            "assets", 
            "gamejam", "gamejamteam", "gamejamreview",
            "notif",
            "district", "school","classroom",
            "comment",
            "pp-project",
            "classes",
            "pp-course",
            "reports",
            "pixelpack",
            "ftcchallenge",
            "ftcsubmission"
        );

        foreach ($customPosts as $customPost) {
            register_rest_field($customPost, 'postMeta', array(
                'get_callback' => function ($data) {
                    return get_post_meta($data['id']);
                },
            ));
        }
    }
}
