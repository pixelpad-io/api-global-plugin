<?php

class Bloat {
    public function __construct() {
    }

    public static function removeMenuItems() {
        remove_menu_page("index.php");                  //Dashboard
        remove_menu_page("edit.php");                   //Posts
        //remove_menu_page("edit.php?post_type=page");    //pages
        remove_menu_page("upload.php");                 //Media
        remove_menu_page("edit-comments.php");          //Comments
        //remove_menu_page("themes.php");                 //Appearance
        //remove_menu_page("plugins.php");                //Plugins 
        //remove_menu_page("tools.php");                  //Tools
        //remove_menu_page("options-general.php");        //Settings
        remove_menu_page("users.php");                  //users
    }



}
