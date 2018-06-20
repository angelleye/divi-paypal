<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.angelleye.com/
 * @since      1.0.0
 *
 * @package    Angelleye_Paypal_For_Divi
 * @subpackage Angelleye_Paypal_For_Divi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Angelleye_Paypal_For_Divi
 * @subpackage Angelleye_Paypal_For_Divi/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class Angelleye_Paypal_For_Divi_Admin {

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
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
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
         * defined in Angelleye_Paypal_For_Divi_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Angelleye_Paypal_For_Divi_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/angelleye-paypal-for-divi-admin.css', array(), $this->version, 'all');
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
         * defined in Angelleye_Paypal_For_Divi_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Angelleye_Paypal_For_Divi_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        //wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/angelleye-paypal-for-divi-admin.js', array('jquery'), $this->version, false);
    }

    private function load_dependencies() {
        /**
         * The class responsible for defining all actions that occur in the Dashboard
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/angelleye-paypal-for-divi-admin-display.php';

        /**
         * The class responsible for defining function for display Html element
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/angelleye-paypal-for-divi-admin-html-output.php';                

        /**
         * The class responsible for defining function for display company setting tab
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/angelleye-paypal-for-divi-companies.php';

        /**
         * The class responsible for defining function for add edit delete operation on company
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/angelleye-paypal-for-divi-companies_operation.php';

        /**
         * The class responsible for defining function for display custom tab store
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/angelleye-paypal-for-divi-store-setting.php';
    }

    public function links_action_paypal_divi($links) {
        $links[] = '<a href="https://www.angelleye.com/category/docs/divi-paypal-module-documentation/" target="_blank">' . __('Docs', 'angelleye_paypal_divi') . '</a>';
        $links[] = '<a href="https://wordpress.org/support/plugin/angelleye-paypal-for-divi/" target="_blank">' . __('Support', 'angelleye_paypal_divi') . '</a>';
        $links[] = '<a href="https://wordpress.org/support/plugin/angelleye-paypal-for-divi/reviews/" target="_blank">' . __('Write a Review', 'angelleye_paypal_divi') . '</a>';
        return $links;
    }

// links_action_paypal_divi()

    /* Checks If divi is installed/activate */

    public function check_divi_available() {

        $theme_data = wp_get_theme();
        $is_child = $this->is_child($theme_data);
        if ($is_child) {
            $parent_name = $theme_data->parent()->Name;
            if ($parent_name != 'Divi') {
                /* code when divi theme is not activated. */
                global $pagenow;
                if ($pagenow == 'plugins.php') {
                    $this->display_paypal_divi_notice();
                }
            }
        } else {
            if ($theme_data->Name != 'Divi') {
                /* code when divi theme is not activated. */
                global $pagenow;
                if ($pagenow == 'plugins.php') {
                    $this->display_paypal_divi_notice();
                }
            }
        }
    }

    public function is_child($theme_data) {
        /* Check current theme is child or not */
        $parent = $theme_data->parent();
        if (!empty($parent)) {
            return TRUE;
        }
        return FALSE;
    }

    /* Display a notice */

    public function display_paypal_divi_notice() {
        ?>
        <div class="notice notice-info is-dismissible">        
            <p>
        <?php echo sprintf('<b>%1$s</b>%2$s<a href="https://www.elegantthemes.com/gallery/divi/" target="_blank">%3$s</a> %4$s <b>%1$s</b>.',
                            __('PayPal for Divi','angelleye_paypal_divi'),
                            __('is designed for the ','angelleye_paypal_divi'),
                            __('Divi theme by Elegant Themes.','angelleye_paypal_divi'),
                            __('Please install and activate the Divi theme prior to activating ','angelleye_paypal_divi')
                ); ?>
            </p>
        </div>
        <?php
    }

}
