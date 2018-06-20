<?php

/**
 * This class defines all code necessary to Companies Setting from admin side
 * @class       AngellEYE_PayPal_For_Divi_Company_Setting_Class
 * @version	1.0.0
 * @package		Angelleye_Paypal_For_Divi/admin/partials
 * @category	Class
 * @author      Angell EYE <service@angelleye.com>
 */
class AngellEYE_PayPal_For_Divi_Company_Setting_Class extends WP_List_Table {

    var $data = array();

    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */

    /**     * ***********************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     * ************************************************************************* */
    public function __construct() {
        global $status, $page;

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'company', //singular name of the listed records
            'plural' => 'companies', //plural name of the listed records
            'ajax' => true        //does this table support ajax?
        ));
    }

    /**
     * Hook in methods
     * @since    0.1.0
     * @access   static
     */
    public static function init() {
        add_action('angelleye_paypal_for_divi_company_create_setting', array(__CLASS__, 'angelleye_paypal_for_divi_company_create_setting'));
        add_action('angelleye_paypal_for_divi_company_setting_save_field', array(__CLASS__, 'angelleye_paypal_for_divi_company_setting_save_field'));
        add_action('angelleye_paypal_for_divi_company_setting', array(__CLASS__, 'angelleye_paypal_for_divi_company_setting'));
    }

    public function get_data() {
        global $wpdb;
        $companies = $wpdb->prefix . 'angelleye_paypal_for_divi_companies'; // do not forget about tables prefix
        $this->data = $wpdb->get_results("SELECT * FROM `{$companies}`", ARRAY_A);
        return $this->data;
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'title':  
            case 'account_id':
            case 'paypal_mode':    
                return $item[$column_name];
        }
    }

    function column_title($item) {

        //Build row actions
        $nonce = wp_create_nonce('delete_company' . $item['ID']);
        $actions = array(
            'edit' => sprintf('<a href="'.esc_url(admin_url('admin.php')).'?page=%s&tab=company&action=%s&cmp_id=%s">%s</a>', sanitize_key($_REQUEST['page']), 'edit', $item['ID'],__('Edit','angelleye_paypal_divi')),
            'delete' => sprintf('<a href="'.esc_url(admin_url('admin.php')).'?page=%s&tab=company&action=%s&cmp_id=%s&_wpnonce=' . $nonce . '">%s</a>', sanitize_key($_REQUEST['page']), 'delete', $item['ID'],__('Delete','angelleye_paypal_divi'))
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:silver"></span>%3$s',
                /* $1%s */ $item['title'],
                /* $2%s */ $item['ID'],
                /* $3%s */ $this->row_actions($actions)
        );
    }

    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />',
                /* $1%s */ $this->_args['singular'], //Let's simply repurpose the table's singular label ("company")
                /* $2%s */ $item['ID']                //The value of the checkbox should be the record's id
        );
    }

    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'title' => __('PayPal Account Name','angelleye_paypal_divi'),
            'account_id' => __('PayPal Account ID','angelleye_paypal_divi'),
            'paypal_mode' => __('PayPal Mode','angelleye_paypal_divi')
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'title' => array('title', false)     //true means it's already sorted
        );
        return $sortable_columns;
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $data = $this->get_data();

        function usort_reorder($a, $b) {
            $orderby = (!empty($_REQUEST['orderby'])) ? sanitize_key($_REQUEST['orderby']) : 'ID'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? sanitize_key($_REQUEST['order']) : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order === 'asc') ? $result : -$result; //Send final sort direction to usort
        }

        usort($data, 'usort_reorder');
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

        $this->items = $data;

        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page, //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
        ));
    }

    public static function angelleye_paypal_for_divi_company_setting() {
        global $wpdb;

        $table = new AngellEYE_PayPal_For_Divi_Company_Setting_Class();
        $table_name_company = $wpdb->prefix . "angelleye_paypal_for_divi_companies";
        if (isset($_GET['action']) && sanitize_key($_GET['action']) == 'delete') {
            if (isset($_GET['cmp_id']) && !empty(sanitize_key($_GET['cmp_id']))) {
                $obj_company_operation_delete = new AngellEYE_PayPal_For_Divi_Company_Operations();

                $get_current_id = $wpdb->get_row("SELECT ID FROM $table_name_company where ID='".sanitize_key($_GET['cmp_id'])."'");

                if (isset($get_current_id->ID) && !empty($get_current_id->ID)) {

                    $delete_result = $obj_company_operation_delete->paypal_for_divi_delete_company();


                    if (!$delete_result) {
                        ?>
                        
                        <div id="setting-error-settings_updated" class="error settings-error"> 
                            <p><?php echo '<strong>' . __('Something went wrong item not deleted.', 'angelleye_paypal_divi') . '</strong>'; ?>
                            </p>
                        </div>

                    <?php } else { ?>
                        <script>window.localStorage.clear()</script>
                        <div id="setting-error-settings_updated" class="updated settings-error"> 
                            <p><?php echo '<strong>' . __('Paypal Account deleted Successfully.', 'angelleye_paypal_divi') . '</strong>'; ?>
                            </p>
                        </div>
                        <?php
                    }
                } else {
                    
                }
            }
        }


        $table->prepare_items();
        $table_getdata = $table->get_data();
        $message = '';


        if (isset($_GET['action']) && sanitize_key($_GET['action']) == 'edit') {
            if (isset($_GET['cmp_id']) && !empty(sanitize_key($_GET['cmp_id']))) {
                ?>
                <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                <h2 class="floatleft"><?php _e('PayPal Account List', 'angelleye_paypal_divi') ?> </h2>
                <a href="<?php echo esc_url(admin_url('admin.php?page=angelleye-paypal-divi-option&tab=company')); ?>" class="cls_addcompany button-primary"><?php _e('Add Paypal Account','angelleye_paypal_divi'); ?></a>
            <?php } else { ?>
                <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                <h2><?php _e('Companies List', 'custom_table_example') ?> 

                </h2>
                <?php
            }
        }
        ?>
        <?php echo $message; ?>

        <form id="accounts-table" method="GET">
            <input type="hidden" name="page" value="<?php echo sanitize_key($_REQUEST['page']) ?>"/>
            <?php $table->display() ?>
        </form>

        <?php
    }

    public static function angelleye_paypal_for_divi_company_create_setting() {
        ?>
        <form action="" enctype="multipart/form-data" id="paypal_for_divi_integration_form" method="post" name="paypal_for_divi_integration_form">
            <h3><?php _e('Add PayPal Account', 'angelleye_paypal_divi'); ?></h3>

            <p><?php _e('You may configure one or more PayPal accounts to specify where the payment should be sent for any given button you create from the Divi Builder.', 'angelleye_paypal_divi'); ?></p>
           
            <?php
            if (isset($_GET['action']) && sanitize_key($_GET['action']) == 'edit') {
                if (isset($_GET['cmp_id']) && !empty(sanitize_key($_GET['cmp_id']))) {
                    $getid = sanitize_key($_GET['cmp_id']);
                    global $wpdb;
                    $table_name = $wpdb->prefix . "angelleye_paypal_for_divi_companies";
                    $records = $wpdb->get_row("select * from $table_name where ID='$getid'");
                    $ID = isset($records->ID) ? $records->ID : '';
                    $title = isset($records->title) ? $records->title : '';              
                    $paypal_for_divi_account_id=isset($records->account_id) ? $records->account_id : '';
                    $paypal_mode = isset($records->paypal_mode) ? $records->paypal_mode : '';
                    if ($paypal_mode == 'Sandbox') {
                        $sandbox_checked = 'checked';
                    } else {
                        $sandbox_checked = '';
                    }
                    if ($paypal_mode == 'Live') {
                        $live_checked = 'checked';
                    } else {
                        $live_checked = '';
                    }
                }
                $button_text = __('Edit PayPal Account','angelleye_paypal_divi');
            } else {
                $button_text = __('Add PayPal Account','angelleye_paypal_divi');
            }
            ?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th class="titledesc" scope="row"><label for="CompanyTitle"><?php _e('PayPal Account Name:', 'angelleye_paypal_divi'); ?></label></th>
                        <td class="forminp forminp-text"><input autocomplete="off" required="" class="" id="company_title" name="company_title" style="min-width:300px;" type="text" value="<?php echo isset($title) ? esc_html($title) : ''; ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row"><label for="paypal_for_divi_account_id"><?php _e('Paypal Account ID', 'angelleye_paypal_divi'); ?></label></th>
                        <td class="forminp forminp-text"><input class="" id="paypal_for_divi_account_id" name="paypal_for_divi_account_id" style="min-width:300px;" type="text" value="<?php echo isset($paypal_for_divi_account_id) ? esc_html($paypal_for_divi_account_id) : ''; ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row"><label for="paypal_mode"><?php _e('PayPal Mode', 'angelleye_paypal_divi'); ?></label></th>
                        <td class="forminp forminp-radio">
                            <fieldset>
                                <ul class="ul_paypal_mode">
                                    <li><label><input class="" <?php echo isset($sandbox_checked) ? esc_attr($sandbox_checked) : ''; ?> name="paypal_mode" type="radio" value="Sandbox" ><?php esc_html_e('Sandbox','angelleye_paypal_divi');?></label></li>
                                    <li><label><input class="" <?php echo isset($live_checked) ? esc_attr($live_checked) : ''; ?> name="paypal_mode" type="radio" value="Live"><?php esc_html_e('Live','angelleye_paypal_divi');?></label></li>
                                </ul>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p class="submit"><input class="button-primary" name="paypal_intigration_form" type="submit" value="<?php esc_html_e($button_text); ?>"></p>

            <h3><?php _e('PayPal Sandbox Notes', 'angelleye_paypal_divi'); ?></h3>

            <p><?php _e('The <a href="http://sandbox.paypal.com" target="_blank">PayPal sandbox</a> is essentially
                a fake PayPal site where you can create sandbox PayPal accounts for testing purposes.
                This allows you to create buttons and test them without spending real money to do so.', 'angelleye_paypal_divi'); ?></p>
            <p><?php _e('In order to create PayPal sandbox accounts you must first create
                a <a href="http://developer.paypal.com" target="_blank">PayPal developer account</a>.
                Your sandbox accounts will be created within that.', 'angelleye_paypal_divi'); ?></p>
            <p><?php _e("For more details on that you may refer to
                <a href='https://developer.paypal.com/docs/classic/lifecycle/ug_sandbox/'>PayPal's sandbox documentation</a>.", 'angelleye_paypal_divi'); ?></p>
        </form>
        <?php
    }

    /**
     * angelleye_paypal_for_divi_company_setting_save_field function used for display acoount setting block from admin side
     * @since    0.1.0
     * @access   public
     */
    public static function angelleye_paypal_for_divi_company_setting_save_field() {
        global $wpdb;
        $table_name = $wpdb->prefix . "angelleye_paypal_for_divi_companies";
        $obj_company_operation = new AngellEYE_PayPal_For_Divi_Company_Operations();


        if (isset($_POST['paypal_intigration_form'])) {

            if (isset($_GET['action']) && sanitize_key($_GET['action']) == 'edit') {
                if (isset($_GET['cmp_id']) && !empty(sanitize_key($_GET['cmp_id']))) {
                    $edit_result = $obj_company_operation->paypal_for_divi_edit_company();
                    ?>
                    <script>window.localStorage.clear()</script>
                    <div id="setting-error-settings_updated" class="updated settings-error"> 
                        <p><?php echo '<strong>' . __('Changes were saved successfully.', 'angelleye_paypal_divi') . '</strong>'; ?></p></div>
                    <?php
                }
            } else {

                $add_result = $obj_company_operation->paypal_for_divi_add_company();
                    
                if ($add_result == false) {
                    ?>
                    <div id="setting-error-settings_updated" class="error settings-error"> 
                        <p><?php echo '<strong>' . __('Something went wrong.', 'angelleye_paypal_divi') . '</strong>'; ?>
                        </p>
                    </div>

                <?php } else { ?>
                <script>window.localStorage.clear()</script>
                    <div id="setting-error-settings_updated" class="updated settings-error"> 
                        <p><?php echo '<strong>' . __('PayPal account added successfully.', 'angelleye_paypal_divi') . '</strong>'; ?>
                        </p>
                    </div>
                    <?php
                }
            }
        }
    }

}

AngellEYE_PayPal_For_Divi_Company_Setting_Class::init();
