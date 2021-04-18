<?php
//https://github.com/gravityforms/simpleaddon/blob/master/simpleaddon.php

define( 'GF_SIMPLE_ADDON_VERSION', '2.1' );
add_action( 'gform_loaded', array( 'GF_Simple_AddOn_Bootstrap', 'load' ), 5 );
class GF_Simple_AddOn_Bootstrap {
public static function load() {
if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
return;
}

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/gravity-forms-tenstreet-gf-settings-page.php';
GFAddOn::register( 'GFTenstreetSettings' );
}
}
function gf_simple_addon() {
return GFTenstreetSettings::get_instance();
}

?>