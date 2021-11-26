<?php

namespace CUSTOMPOSTS;

/**
 * Parent class CustomPost used to create custom post object
 */
abstract class CustomPost {

    /** @var string The post type */
    public $type = null;

    /** @var string wp-dash-icon for custom post type */
    public $icon = null;

    /** @var string display name for custom post type */
    public $name = null;

    /** @var array Data to save to post when user clicks save */
    public $metaItems = array();

    /** @var array what does this post support title, editor, custom-fields */
    public $support = array("");

    /** @var array should slug be rewritten when creating post type */
    public $rewrite = array();

    /** @var string post title */
    public $post_title = "";
    
    /** @var string post slug */
    public $post_slug = "";

    abstract function get_meta_items();

    abstract function filter_columns_head($defaults);

    abstract function filter_columns_content($column_name, $post_id);

    //Children of CustomPost must have function add_<<type>>_meta_box();
    //abstract function add_${type}_meta_box();

    /**
     * register the post type
     * @param string $type the type
     * @param string $icon wp-dash-icon for sidebar
     * @param string $name display name for users
     */
    public function create_custom_post_type() {
        $type = $this->type;
        $icon = $this->icon;
        $name = $this->name;
        $support = $this->support;
        $rewrite = $this->rewrite;
        register_post_type($type,
                array(
                    'labels' => array(
                        'name' => __($name),
                        'singular_name' => __($name)
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'rewrite' => array('slug' => $type),
                    'menu_icon' => $icon,
                    'supports' => $support,
                    'rewrite' => $rewrite,
                    'show_in_rest' => true
                )
        );
    }

    /**
     * run all the actions required to create the custom post, save the custom
     * meta, and save the comment data
     * @return none
     */
    public function run_actions() {
        add_action("save_post", array($this, "save_meta"), 10, 2);
        add_action('wp_loaded', array($this, 'create_custom_post_type'));
        add_action("add_meta_boxes", array($this, "meta_init"));
        add_filter("manage_" . $this->type . "_posts_columns", array($this, "filter_columns_head"), 10, 1);
        add_action("manage_" . $this->type . "_posts_custom_column", array($this, "filter_columns_content"), 10, 2);
    }
    
    /**
     * should you save the meta. save meta is intended to be called when the user
     * clicks "update" in the admin area. but it's also being called when we use wp_insert_post
     * this checks against that, and if its the right post type, and if there are meta items to update
     * @param type $post_id
     * @param type $post
     * @return boolean
     */
    private function _should_save_meta($post_id, $post){
        if ($post->post_type != $this->type) {
            return false;
        }
        /* $_POST is empty on wp_insert_post, but also empty when user creates draft
         */
        if (empty($_POST)) {
            if ($post->post_status !== "auto-draft"){
                return false;
            }
        }
        $meta = $this->get_meta_items();
        if ($meta === false) {
            return false;
        }
        return true;
    }
    
    /**
     * Runs when user clicks on save post. This function is also called when a
     * post is in draft.
     * @param int $post_id the post id
     * @param object $post the post
     * @return none
     */
    public function save_meta($post_id = false, $post = false) {
        if (!$this->_should_save_meta($post_id, $post)){
            //be careful not to remove this as it could crash the database
            return;
        }
        
        $meta = $this->get_meta_items();
        //update the post content so it's searchable
        remove_action('save_post', array($this, 'save_meta')); //infinite loop fix when using wp_update_post + save_post
        wp_update_post(
                array(
                    "ID" => $post_id,
                    "post_content" => json_encode($meta),
                    "post_author" => $post->post_author === "" ? get_current_user_id() : $post->post_author,
                    "meta_input" => $meta
                )
        );

        //only update the post title if it exists.
        if ($this->post_title !== "") {
            wp_update_post(
                    array(
                        "ID" => $post_id,
                        "post_title" => $this->post_title
                    )
            );
        }
        //only update the post slug if it exists.
        if ($this->post_slug !== "") {
            wp_update_post(
                    array(
                        "ID" => $post_id,
                        "post_name" => $this->post_slug
                    )
            );
        }
        

        add_action('save_post', array($this, 'save_meta'));
    }

    /**
     * adds the metabox
     * add_meta_box( $id, $title, $callback, $page, $context, $priority );
     */
    public function meta_init() {
        $type = $this->type;
        add_meta_box($type . "_meta_id", $type, array($this, "add_" . $type . "_meta_box"), $type, "normal", "default");
    }

}
