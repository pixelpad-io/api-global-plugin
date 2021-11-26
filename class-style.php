<?php

class Style {

    public static function load() {

        /* Enqueue scripts and stylesheets regionally (typically difference CDN) */
        add_action("admin_enqueue_scripts", array(__CLASS__, "enqueue_styles"), 30);
        add_action("admin_enqueue_scripts", array(__CLASS__, "enqueue_script_data"), 25);
        add_action("admin_enqueue_scripts", array(__CLASS__, "enqueue_scripts"), 20);

        // remove bloat
        add_action('admin_enqueue_scripts', array(__CLASS__, 'dequeue_scripts'), 15);
        add_action('admin_enqueue_scripts', array(__CLASS__, 'dequeue_styles'), 15);
    }


    /**
     * remove gutenberg editor
     */
    public static function dequeue_styles() {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-block-style');
    }

    /**
     * remove wordpress jquery, which is too old
     */
    public static function dequeue_scripts() {
        wp_deregister_script("jquery");
        wp_deregister_script("wp-embed");
    }

    public static function enqueue_script_data() {
        /* 5.0.0 */ // wp_script_add_data( 'bootstrapBundle', array( 'integrity', 'crossorigin' ) , array( 'sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0', 'anonymous' ) );
        /* 4.5.3 */
        wp_script_add_data('bootstrapBundle', array('integrity', 'crossorigin'), array('sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx', 'anonymous'));
        /* 5.0.0 */ //wp_script_add_data( 'bootstrapStyle', array( 'integrity', 'crossorigin' ) , array( 'sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl', 'anonymous' ) );
        /* 4.5.3 */
        wp_script_add_data('jqueryui', array('integrity', 'crossorigin'), array('sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=', 'anonymous'));
        wp_script_add_data('jqueryuicss', array('integrity', 'crossorigin'), array('sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==', 'anonymous'));

        wp_script_add_data('bootstrapStyle', array('integrity', 'crossorigin'), array('sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2', 'anonymous'));
        wp_script_add_data('jquery', array('integrity', 'crossorigin'), array('sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj', 'anonymous'));
        wp_script_add_data('fontawesome', array('integrity', 'crossorigin'), array('sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p', 'anonymous'));
    }

    public static function enqueue_scripts() {
        wp_enqueue_script(
            'jquery',
            "https://code.jquery.com/jquery-3.5.1.min.js",
            array(),
            '3.5.1',
            true
        );
        wp_enqueue_script(
            "jqueryui", //all the ajax scripts
            "https://code.jquery.com/ui/1.12.1/jquery-ui.min.js",
            array("jquery"),
            "1.0.1"
        );
        wp_enqueue_script(
            'bootstrapBundle',
            "https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js",
            array('jquery'),
            '4.5.3',
            true
        );
    }

    public static function enqueue_styles() {
        wp_enqueue_style(
            'bootstrapStyle',
            "https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css",
            array(),
            "4.5.3",
            "all"
        );
        wp_enqueue_style(
            'robotoSlab',
            "https://fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700",
            array(),
            "1.15.1",
            "all"
        );
        wp_enqueue_style(
            'roboto',
            "https://fonts.googleapis.com/css?family=Roboto:100,300,400,700",
            array(),
            "1.15.1",
            "all"
        );
        wp_enqueue_style(
            'sourceSansPro',
            "https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700&display=swap",
            array(),
            "1.15.1",
            "all"
        );
        wp_enqueue_style(
            'rubik',
            "https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,700;0,900;1,500&display=swap",
            array(),
            "1.15.1",
            "all"
        );
        wp_enqueue_style(
            'fontawesome',
            "https://pro.fontawesome.com/releases/v5.10.0/css/all.css",
            array(),
            "5.10.0",
            "all"
        );
        wp_enqueue_style(
            'jqueryuicss',
            "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css",
            array(),
            "1.12.1",
            "all"
        );
    }
}
