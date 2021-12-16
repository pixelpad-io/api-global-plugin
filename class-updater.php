<?php

namespace PIXELPAD;

class Updater {

    public function __construct() {
    }

    function load() {
        // info.json is the file with the actual plugin information on your server
        $remote = wp_remote_get(
            "https://github.com/pixelpad-io/api-global-plugin/blob/master/info.json",
            array(
                "timeout" => 10,
                "headers" => array(
                    "Accept" => "application/json"
                )
            )
        );

        // do nothing if we don"t get the correct response from the server
        if (
            is_wp_error($remote)
            || 200 !== wp_remote_retrieve_response_code($remote)
            || empty(wp_remote_retrieve_body($remote)) 
            ) {
            return false;
        }

        $remote = json_decode(wp_remote_retrieve_body($remote));
        $res = new \stdClass();
        $res->name = $remote->name;
        $res->slug = $remote->slug;
        $res->author = $remote->author;
        $res->author_profile = $remote->author_profile;
        $res->version = $remote->version;
        $res->tested = $remote->tested;
        $res->requires = $remote->requires;
        $res->requires_php = $remote->requires_php;
        $res->download_link = $remote->download_url;
        $res->trunk = $remote->download_url;
        $res->last_updated = $remote->last_updated;
        $res->sections = array(
            "description" => $remote->sections->description,
            "installation" => $remote->sections->installation,
            "changelog" => $remote->sections->changelog
        );
        return $res;
    }
 
    public function misha_push_update( $transient ){
     
        if ( empty( $transient->checked ) ) {
            return $transient;
        }
    
        $remote = wp_remote_get( 
            'https://rudrastyh.com/wp-content/uploads/updater/info.json',
            array(
                'timeout' => 10,
                'headers' => array(
                    'Accept' => 'application/json'
                )
            )
        );
    
        if( 
            is_wp_error( $remote )
            || 200 !== wp_remote_retrieve_response_code( $remote )
            || empty( wp_remote_retrieve_body( $remote ) )
        ) {
            return $transient;	
        }
        
        $remote = json_decode( wp_remote_retrieve_body( $remote ) );
     
            // your installed plugin version should be on the line below! You can obtain it dynamically of course 
        if(
            $remote
            && version_compare( $this->version, $remote->version, '<' )
            && version_compare( $remote->requires, get_bloginfo( 'version' ), '<' )
            && version_compare( $remote->requires_php, PHP_VERSION, '<' )
        ) {
            
            $res = new \stdClass();
            $res->slug = $remote->slug;
            $res->plugin = plugin_basename( __FILE__ ); // it could be just YOUR_PLUGIN_SLUG.php if your plugin doesn't have its own directory
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;
            $transient->response[ $res->plugin ] = $res;
            
            //$transient->checked[$res->plugin] = $remote->version;
        }
     
        return $transient;
    
    }


}
