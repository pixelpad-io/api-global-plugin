<?php

namespace CUSTOMPOSTS;

class Updater {

    public $plugin_slug;
    public $version;

    public function __construct() {
        $this->plugin_slug = plugin_basename(dirname(GLOBAL_PLUGIN_DIR));
        $this->plugin_data_file = GLOBAL_PLUGIN_DIR . "index.php";
        $this->plugin_data = get_plugin_data( $this->plugin_data_file );
        $this->version = $this->plugin_data['Version'];
        $this->jsonURL = "https://raw.githubusercontent.com/pixelpad-io/api-global-plugin/master/info.json";

        add_filter("plugins_api", array($this, "info"), 20, 3);
        add_filter("site_transient_update_plugins", array($this, "update"));
    }

    public function info($res, $action, $args) {
        if ("plugin_information" !== $action) {
            return false;
        }
        if ($this->plugin_slug !== $args->slug) {
            return false;
        }

        $remote = $this->request();
        if (!$remote) {
            return false;
        }

        $res = new \stdClass();

        $res->name = $remote->name;
        $res->slug = $remote->slug;
        $res->version = $remote->version;
        $res->tested = $remote->tested;
        $res->requires = $remote->requires;
        $res->author = $remote->author;
        $res->author_profile = $remote->author_profile;
        $res->download_link = $remote->download_url;
        $res->trunk = $remote->download_url;
        $res->requires_php = $remote->requires_php;
        $res->last_updated = $remote->last_updated;

        $res->sections = array(
            "description" => $remote->sections->description,
            "installation" => $remote->sections->installation,
            "changelog" => $remote->sections->changelog
        );
        return $res;
    }

    public function update($transient) {

        if (empty($transient->checked)) {
            return $transient;
        }

        $remote = $this->request();
        if ($remote && version_compare($this->version, $remote->version, "<")) {
            $res = new \stdClass();
            $res->slug = $this->plugin_slug;
            $res->plugin = plugin_basename(GLOBAL_PLUGIN_DIR . "index.php");
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;

            $transient->response[$res->plugin] = $res;
        }
        return $transient;
    }

    public function request() {
        $remote = wp_remote_get(
            $this->jsonURL,
            array(
                "timeout" => 10,
                "headers" => array(
                    "Accept" => "application/json"
                )
            )
        );

        if (
            is_wp_error($remote)
            || 200 !== wp_remote_retrieve_response_code($remote)
            || empty(wp_remote_retrieve_body($remote))
        ) {
            return false;
        }

        $remote = json_decode(wp_remote_retrieve_body($remote));
        return $remote;
    }
}
