<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/stephanieland352/gravity-forms-tenstreet
 * @since      1.0.0
 *
 * @package    Rlc_Tenstreet_Gf_Integration
 * @subpackage Rlc_Tenstreet_Gf_Integration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rlc_Tenstreet_Gf_Integration
 * @subpackage Rlc_Tenstreet_Gf_Integration/admin
 * @author     Stephanie Land
 */
class Rlc_Tenstreet_Gf_Integration_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->apiResponse ='';
// Create Settings Page
        add_action( 'admin_menu', array($this,'add_plugin_settings_link'),99 );
        add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
        // Send to 3rd party
        add_action( 'gform_pre_submission', array($this, 'post_to_third_party'), 10, 2 );
        // add field to notification settings
        add_filter( 'gform_notification', array($this, 'gform_notification_add_api_info'), 10, 3 );
        add_filter( 'gform_notification_ui_settings', array($this, 'add_api_notification_setting'), 10, 3 );
        add_filter( 'gform_pre_notification_save', array($this, 'api_notification_setting_save'), 10, 2 );
	}
    // Create Settings Page
    public  function add_plugin_settings_link(){
        add_submenu_page(
            'gf_edit_forms',
            'Tenstreet Settings',
            'Tenstreet Settings',
            'publish_pages',
            'tenstreet-integration-settings',
            array($this, "show_setting_page_fields")
        );
    }
    public function register_plugin_settings()
    {
        register_setting(
            'main_settings_option_group', // Option group
            'tenstreet_setting_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );
        add_settings_section(
            'basic_settings_section', // ID
            'Tenstreet API Settings', // Title
            function(){
                print 'Enter your settings below:';
            }, // Callback
            'tenstreet-settings-page' // Page
        );
        add_settings_field(
            'tenstreet_client_id', // ID
            'Client ID', // Title
            function(){
                printf(
                    '<input type="text" id="client_id" name="tenstreet_setting_options[tenstreet_client_id]" value="%s" />',
                    isset( $this->options['tenstreet_client_id'] ) ? esc_attr( $this->options['tenstreet_client_id']) : ''
                );
            }, // Callback
            'tenstreet-settings-page', // Page
            'basic_settings_section' // Section
        );
        add_settings_field(
            'tenstreet_password',
            'Password',
            function(){
                printf(
                    '<input type="password" id="tenstreet_password" name="tenstreet_setting_options[tenstreet_password]" value="%s" />',
                    isset( $this->options['tenstreet_password'] ) ? esc_attr( $this->options['tenstreet_password']) : ''
                );
            }, // Callback
            'tenstreet-settings-page',
            'basic_settings_section'
        );
        add_settings_field(
            'tenstreet_service_name', // ID
            'Service Name', // Title
            function(){
                printf(
                    '<input type="text" id="tenstreet_service_name" name="tenstreet_setting_options[tenstreet_service_name]" value="%s" />',
                    isset( $this->options['tenstreet_service_name'] ) ? esc_attr( $this->options['tenstreet_service_name']) : ''
                );
            }, // Callback
            'tenstreet-settings-page', // Page
            'basic_settings_section' // Section
        );
        add_settings_field(
            'tenstreet_source', // ID
            'Tenstreet Source Name', // Title
            function(){
                printf(
                    '<input type="text" id="tenstreet_source" name="tenstreet_setting_options[tenstreet_source]" value="%s" />',
                    isset( $this->options['tenstreet_source'] ) ? esc_attr( $this->options['tenstreet_source']) : ''
                );
            }, // Callback
            'tenstreet-settings-page', // Page
            'basic_settings_section' // Section
        );
        add_settings_field(
            'tenstreet_company_id', // ID
            'Tenstreet Company ID', // Title
            function(){
                printf(
                    '<input type="text" id="tenstreet_company_id" name="tenstreet_setting_options[tenstreet_company_id]" value="%s" />',
                    isset( $this->options['tenstreet_company_id'] ) ? esc_attr( $this->options['tenstreet_company_id']) : ''
                );
            }, // Callback
            'tenstreet-settings-page', // Page
            'basic_settings_section' // Section
        );
        add_settings_field(
            'tenstreet_dev_mode', // ID
            'Dev Mode', // Title
            function(){
                $html = '<input type="checkbox" id="tenstreet_dev_mode" name="tenstreet_setting_options[tenstreet_dev_mode]" value="1"' . checked( 1, $this->options['tenstreet_dev_mode'], false ) . '/>';
                $html .= '<label for="checkbox_example">Put plugin in dev mode</label>';
                echo $html;
            }, // Callback
            'tenstreet-settings-page', // Page
            'basic_settings_section' // Section
        );
    }
    public function show_setting_page_fields() {
        // Set class property
        $this->options = get_option( 'tenstreet_setting_options' );
        ?>
        <div class="wrap">

            <form method="post" action="options.php">
                <?php
                if ( isset( $_REQUEST['settings-updated'] ) ) {

                    ?>
                    <div class="updated fade">
                        <p><?php esc_html_e( 'Settings Updated', 'gravityforms' ); ?>.</p>
                    </div>
                    <?php
                }
                ?>
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'main_settings_option_group' );
                do_settings_sections( 'tenstreet-settings-page' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
// Send to 3rd party
    function post_to_third_party( $entry, $form ) {
        if( !class_exists( 'WP_Http' ) ) {
            include_once( ABSPATH . WPINC. '/class-http.php' );
        }
        $enabled = $entry['tenstreetsettings']['tenstreet_enabled'];
        if(! $enabled){
            return;
        }
        $firstName = $this->getGravityPostName($entry['tenstreetsettings']['tenstreet_first_name']);
        $firstName = $_POST[$firstName];
        $lastName = $this->getGravityPostName($entry['tenstreetsettings']['tenstreet_last_name']);
        $lastName = $_POST[$lastName];
        $email = $this->getGravityPostName($entry['tenstreetsettings']['tenstreet_email']);
        $email = $_POST[$email];
        $phone = $this->getGravityPostName($entry['tenstreetsettings']['tenstreet_phone']);
        $phone = $_POST[$phone];
        $city = $this->getGravityPostName($entry['tenstreetsettings']['tenstreet_city']);
        $city = $_POST[$city];
        $state = $this->getGravityPostName($entry['tenstreetsettings']['tenstreet_state']);
        $state = $_POST[$state];
        if(($firstName === null && $lastName === null) || ($phone === null && $email === null) || ($firstName === '' && $lastName === '') || ($phone === '' && $email === '')) {
            $this->apiResponse = array(
                'status' => 'DATA NOT SENT',
                'description' => 'This entry is missing either First and last name OR missing email and phone number',
                'response' => array(
                    'code'=> '',
                    'message'=> ''
                ),
            );
            return;
        }
        if($phone === null){
            $phone = '';
        }
        if($email === null){
            $email = '';
        }
        if($city === null) {
            $city='';
        }
        if($state === null) {
            $state = '';
        }
        $options = get_option( 'tenstreet_setting_options' );
        $devMode = $options['tenstreet_dev_mode'];
        $clientID = $options['tenstreet_client_id'];
        $password = $options['tenstreet_password'];
        $serviceName = $options['tenstreet_service_name'];
        $sourceName = $options['tenstreet_source'];
        if($devMode) {
            $post_url = 'https://devdashboard.tenstreet.com/post/';
            $mode = 'DEV';
            $companyID = 15;
        } else {
            $post_url = 'https://dashboard.tenstreet.com/post/';
            $mode = 'PROD';
            $companyID = $options['tenstreet_company_id'];
        }
        $appReferrer = '';
        $referralCode = '';
        if(isset($_COOKIE['utm_ref'])) {
            $referralCode = $_COOKIE['utm_ref'];
            $appReferrer = '<ApplicationData><AppReferrer>'.$referralCode.'</AppReferrer></ApplicationData>';
        }
        $xml ='<TenstreetData><Authentication><ClientId>'.$clientID.'</ClientId><Password>'.$password.'</Password><Service>'.$serviceName.'</Service></Authentication><Mode>'.$mode.'</Mode><Source>'.$sourceName.'</Source><CompanyId>'.$companyID.'</CompanyId><PersonalData><PersonName><GivenName>'.$firstName.'</GivenName><FamilyName>'.$lastName.'</FamilyName></PersonName><PostalAddress><CountryCode>US</CountryCode><Municipality>'.$city.'</Municipality><Region>'.$state.'</Region></PostalAddress><ContactData><InternetEmailAddress>'.$email.'</InternetEmailAddress><PrimaryPhone>'.$phone.'</PrimaryPhone></ContactData></PersonalData>'.$appReferrer.'</TenstreetData>';
      GFCommon::log_debug( 'gform_after_submission: body => ' . print_r( $xml, true ) );
        $request = new WP_Http();
        $response = $request->post( $post_url, array(
            'method' => 'POST',
            'body' => $xml
        ) );
        $status = simplexml_load_string($response['body']);
        $this->apiResponse = array(
                'status' => $status->Status,
                'mode'=>$mode,
                'description' => $status->Description,
                'referral' => $referralCode,
                'response' => array(
                        'code'=> $response['response']['code'],
                        'message'=> $response['response']['message']
                ),
        );
        GFCommon::log_debug( 'gform_after_submission: response => ' . print_r( $response, true ) );
    }
    function gform_notification_add_api_info( $notification, $form, $entry ) {
        // append a signature to the existing notification
        // message with .=
        if($notification['add_api_result_notification'] && $form['tenstreetsettings']['tenstreet_enabled']){
            $apiInfo = $this->apiResponse;
            $notification['message'] .= '<h3> Tenstreet API Info for this Entry</h3><p><b>Status: </b>'.$apiInfo['status'][0].' - '.$apiInfo['description'][0].'</p>';
            if(! empty($apiInfo['response']['code'])){
                $notification['message'] .= '<p><b>Response: </b>'.$apiInfo['response']['code'].' - '.$apiInfo['response']['message'].'</p>';
            }
            if(! empty($apiInfo['mode'])){
                $notification['message'] .='<p><b>Mode: </b>'.$apiInfo['mode'].'</p>';
            }
            if($apiInfo['referral'] !== ''){
                $notification['message'] .='<p><b>Referral Code: </b>'.$apiInfo['referral'].'</p>';
            }
        }
        return $notification;
    }
    function getGravityPostName($input)
    {
        $result="";
        for($i=0;$i<strlen($input);$i++)
        {
            $result.= ($input[$i]=='.')?'_':$input[$i];
        }
        return 'input_'. $result;
    }
    function add_api_notification_setting( $ui_settings, $notification, $form ) {
        $ui_settings['add_api_result_notification'] = '
        <tr>
            <th><label for="add_api_result_notification">Add Tenstreet Api Notification</label></th>
            <td><input ' . checked( 1, rgar( $notification, 'add_api_result_notification' ), false ).' type="checkbox"  name="add_api_result_notification" value="1"></td>
        </tr>';
        return $ui_settings;
    }
    function api_notification_setting_save( $notification, $form ) {
        $notification['add_api_result_notification'] = rgpost( 'add_api_result_notification' );
        return $notification;
    }
    /**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rlc_Tenstreet_Gf_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rlc_Tenstreet_Gf_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	//	wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/gravity-forms-tenstreet-admin.css', array(), $this->version, 'all' );
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rlc_Tenstreet_Gf_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rlc_Tenstreet_Gf_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	//	wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gravity-forms-tenstreet-admin.js', array( 'jquery' ), $this->version, false );

	}
}
