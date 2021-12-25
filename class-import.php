<?php

namespace PIXELPAD;

/**
 * import classrooms through wordpress xml
 */
class Import {

    public static function get_xml_post() {
        $post = $_POST["xmlurl"] ?? false;
        if ($post) {
            self::import_custom_post(file_get_contents($post));
        }
        return false;
    }

    public static function import_custom_post($xmlstring) {

        $classrooms = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        foreach ($classrooms->channel->item as $item) {
            $wp = $item->children("http://wordpress.org/export/1.2/");
            $content = $item->children("content", true);
            $postDate = $wp->post_date->__toString();
            $postDateGMT = $wp->post_date_gmt->__toString();
            $postModified = $wp->post_modified->__toString();
            $postModifiedGMT = $wp->post_modified_gmt->__toString();
            $importId = $wp->post_id->__toString();
            $postName = $wp->post_name->__toString();
            $postType = $wp->post_type->__toString();
            $postStatus = $wp->status->__toString();
            $postContent = $content->encoded->__toString();
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
                "post_status" => $postStatus,
                "post_content" => $postContent
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
                $metaValue = wp_slash($meta->meta_value->__toString());
                update_post_meta($id, $metaKey, $metaValue);
            }
        }
    }

    public static function render_import_page() { ?>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1>PixelPAD Import File</h1>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col small muted">
                    URL wordpress import file (.xml)
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <form method="post">
                        <input class="form-control mb-2" type="text" name="xmlurl" placeholder="https://">
                        <input type="submit" value="submit">
                    </form>
                </div>
            </div>
        </div>
<?php
    }

    public static function add_admin_menu() {
        $page_title = "Import custom post";
        $menu_title = "PixelPAD Import";
        $capability = "manage_options";
        $menu_slug = "pixelpad_import";
        $function = "\PIXELPAD\Import::render_import_page";
        $icon = "dashicons-database-import";
        $position = 75;
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon, $position);
    }
}
