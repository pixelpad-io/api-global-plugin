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
            "district", "school", "classroom",
            "comment",
            "pp-project",
            "classes",
            "pp-course",
            "reports",
            "pixelpacks",
            "ftcchallenge", "ftcsubmission"
        );

        foreach ($customPosts as $customPost) {
            register_rest_field($customPost, 'post_meta', array(
                'get_callback' => function ($data) {
                    return get_post_meta($data['id']);
                },
            ));

            //add meta key and value to the query
            add_filter("rest_" . $customPost . "_query", "REST::include_meta_query", 10, 2);
        }
    }

    public static function include_meta_query($args, $request) {
        $args += array(
            'meta_key'   => $request['meta_key'],
            'meta_value' => $request['meta_value'],
            'meta_query' => $request['meta_query'],
        );
        return $args;
    }
}
