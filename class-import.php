<?php

namespace PIXELPAD;

//add_action("admin_head", "\PIXELPAD\Import::importXML");

/**
 * import classrooms through wordpress xml
 */
class Import {

    public static function importXML() {
        $file = dirname(__FILE__) . "/notifs.xml";
        $classrooms = simplexml_load_file($file, "SimpleXMLElement", LIBXML_NOCDATA);

        foreach ($classrooms->channel->item as $item) {
            $wp = $item->children("http://wordpress.org/export/1.2/");
            $postDate = $wp->post_date->__toString();
            $postDateGMT = $wp->post_date_gmt->__toString();
            $postModified = $wp->post_modified->__toString();
            $postModifiedGMT = $wp->post_modified_gmt->__toString();
            $importId = $wp->post_id->__toString();
            $postName = $wp->post_name->__toString();
            $postType = $wp->post_type->__toString();
            $postStatus = $wp->status->__toString();
            $postTitle = $item->title->__toString();

            $post_data = array(
                "post_title" => $postTitle,
                "post_author" => 1,
                "post_date" => $postDate,
                "post_date_gmt" => $postDateGMT,
                "post_modified" => $postModified,
                "post_modified_gmt" => $postModifiedGMT,
                "import_id" => $importId,
                "post_name" => $postName,
                "post_type" => $postType,
                "post_status" => $postStatus
            );

            if (is_null(get_post($importId))) {
                $id = wp_insert_post($post_data);
            } else {
                $id = $importId;
                $post_data += array("ID" => $importId);
                wp_update_post($post_data);
            }

            foreach ($wp->postmeta as $meta) {
                $metaKey =  $meta->meta_key->__toString();
                $metaValue = $meta->meta_value->__toString();
                update_post_meta($id, $metaKey, $metaValue);
            }
        }
    }

    public static function render_classroom_data() {
        $posts = get_posts(
            array(
                "post_type" => "school",
                "numberposts" => -1
            )
        );

        foreach ($posts as $post) {
            echo "<pre>";
            echo $post->post_date_gmt;
            echo "</pre>";
        }
    }
}
