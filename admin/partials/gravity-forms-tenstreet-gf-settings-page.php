<?php

GFForms::include_addon_framework();

class GFTenstreetSettings extends GFAddOn {

    protected $_version = GF_SIMPLE_ADDON_VERSION;
    protected $_min_gravityforms_version = '1.9';
    protected $_slug = 'tenstreetsettings';
    protected $_path =   'admin/class-gravity-forms-tenstreet-gravity-forms-settings.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms Tenstreet';
    protected $_short_title = 'Tenstreet Settings';

    private static $_instance = null;

    /**
     * Get an instance of this class.
     *
     * @return GFTenstreetSettings
     */
    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new GFTenstreetSettings();
        }
        return self::$_instance;
    }

    // # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

    /**
     * Configures the settings which should be rendered on the Form Settings > Tenstreet tab.
     *
     * @return array
     */
    public function form_settings_fields( $form ) {
        return array(
            array(
                'title'  => esc_html__( 'Tenstreet Form Settings', 'tenstreetsettings' ),
                'fields' => array(
                    array(
                        'label'   => esc_html__( 'Tenstreet Integration', 'tenstreetsettings' ),
                        'type'    => 'checkbox',
                        'name'    => 'tenstreet_enabled',
                        'tooltip' => esc_html__( 'Send this forms data to Tenstreet', 'tenstreetsettings' ),
                        'choices' => array(
                            array(
                                'label' => esc_html__( 'Enabled', 'tenstreetsettings' ),
                                'name'  => 'tenstreet_enabled',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'First Name (required)', 'tenstreetsettings' ),
                        'type'  => 'field_select',
                        'name'  => 'tenstreet_first_name',
                    ),
                    array(
                        'label' => esc_html__( 'Last Name (required)', 'tenstreetsettings' ),
                        'type'  => 'field_select',
                        'name'  => 'tenstreet_last_name',
                    ),
                    array(
                        'label' => esc_html__( 'Email (required)', 'tenstreetsettings' ),
                        'type'  => 'field_select',
                        'name'  => 'tenstreet_email',
                    ),
                    array(
                        'label' => esc_html__( 'Phone', 'tenstreetsettings' ),
                        'type'  => 'field_select',
                        'name'  => 'tenstreet_phone',
                    ),
                    array(
                        'label' => esc_html__( 'City', 'tenstreetsettings' ),
                        'type'  => 'field_select',
                        'name'  => 'tenstreet_city',
                    ),
                    array(
                        'label' => esc_html__( 'State', 'tenstreetsettings' ),
                        'type'  => 'field_select',
                        'name'  => 'tenstreet_state',
                    ),

                ),
            ),
        );

    }



}