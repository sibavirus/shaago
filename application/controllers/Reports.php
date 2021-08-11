<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reports extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Reports_model');
        $this->load->model('Constant_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->helper('excel');

        $settingResult = $this->db->get_where('site_setting');
        $settingData = $settingResult->row();

        $setting_timezone = $settingData->timezone;

        date_default_timezone_set("$setting_timezone");
    }

    public function index()
    {
        $this->load->view('dashboard', 'refresh');
    }

    // ****************************** View Page -- START ****************************** //

    // Sold By Products Report;
    public function sold_by_products()
    {
        $siteSettingData                 = $this->Constant_model->getDataOneColumn('site_setting', 'id', '1');
        $siteSetting_dateformat         = $siteSettingData[0]->datetime_format;
        $siteSetting_currency             = $siteSettingData[0]->currency;

        if ($siteSetting_dateformat == 'Y-m-d') {
            $dateformat = 'yyyy-mm-dd';
        }
        if ($siteSetting_dateformat == 'Y.m.d') {
            $dateformat = 'yyyy.mm.dd';
        }
        if ($siteSetting_dateformat == 'Y/m/d') {
            $dateformat = 'yyyy/mm/dd';
        }
        if ($siteSetting_dateformat == 'm-d-Y') {
            $dateformat = 'mm-dd-yyyy';
        }
        if ($siteSetting_dateformat == 'm.d.Y') {
            $dateformat = 'mm.dd.yyyy';
        }
        if ($siteSetting_dateformat == 'm/d/Y') {
            $dateformat = 'mm/dd/yyyy';
        }
        if ($siteSetting_dateformat == 'd-m-Y') {
            $dateformat = 'dd-mm-yyyy';
        }
        if ($siteSetting_dateformat == 'd.m.Y') {
            $dateformat = 'dd.mm.yyyy';
        }
        if ($siteSetting_dateformat == 'd/m/Y') {
            $dateformat = 'dd/mm/yyyy';
        }

        $data['site_dateformat']                 = $siteSetting_dateformat;
        $data['site_currency']                     = $siteSetting_currency;
        $data['dateformat']                     = $dateformat;

        $data['lang_dashboard']                 = $this->lang->line('dashboard');
        $data['lang_customers']                 = $this->lang->line('customers');
        $data['lang_gift_card']                 = $this->lang->line('gift_card');
        $data['lang_add_gift_card']             = $this->lang->line('add_gift_card');
        $data['lang_list_gift_card']             = $this->lang->line('list_gift_card');
        $data['lang_debit']                     = $this->lang->line('debit');
        $data['lang_sales']                     = $this->lang->line('sales');
        $data['lang_today_sales']                 = $this->lang->line('today_sales');
        $data['lang_opened_bill']                 = $this->lang->line('opened_bill');
        $data['lang_reports']                     = $this->lang->line('reports');
        $data['lang_sales_report']                 = $this->lang->line('sales_report');
        $data['lang_expenses']                     = $this->lang->line('expenses');
        $data['lang_expenses_category']         = $this->lang->line('expenses_category');
        $data['lang_pnl']                         = $this->lang->line('pnl');
        $data['lang_pnl_report']                 = $this->lang->line('pnl_report');
        $data['lang_pos']                         = $this->lang->line('pos');
        $data['lang_return_order']                 = $this->lang->line('return_order');
        $data['lang_return_order_report']         = $this->lang->line('return_order_report');
        $data['lang_inventory']                 = $this->lang->line('inventory');
        $data['lang_products']                     = $this->lang->line('products');
        $data['lang_list_products']             = $this->lang->line('list_products');
        $data['lang_print_product_label']         = $this->lang->line('print_product_label');
        $data['lang_product_category']             = $this->lang->line('product_category');
        $data['lang_purchase_order']             = $this->lang->line('purchase_order');
        $data['lang_setting']                     = $this->lang->line('setting');
        $data['lang_outlets']                     = $this->lang->line('outlets');
        $data['lang_users']                     = $this->lang->line('users');
        $data['lang_suppliers']                 = $this->lang->line('suppliers');
        $data['lang_system_setting']             = $this->lang->line('system_setting');
        $data['lang_payment_methods']             = $this->lang->line('payment_methods');
        $data['lang_logout']                     = $this->lang->line('logout');
        $data['lang_point_of_sales']             = $this->lang->line('point_of_sales');
        $data['lang_amount']                     = $this->lang->line('amount');
        $data['lang_monthly_sales_outlet']         = $this->lang->line('monthly_sales_outlet');
        $data['lang_no_match_found']             = $this->lang->line('no_match_found');
        $data['lang_create_return_order']         = $this->lang->line('create_return_order');

        $data['lang_action']                     = $this->lang->line('action');
        $data['lang_edit']                         = $this->lang->line('edit');
        $data['lang_status']                     = $this->lang->line('status');
        $data['lang_add']                         = $this->lang->line('add');
        $data['lang_back']                         = $this->lang->line('back');
        $data['lang_update']                     = $this->lang->line('update');
        $data['lang_active']                     = $this->lang->line('active');
        $data['lang_inactive']                     = $this->lang->line('inactive');
        $data['lang_name']                         = $this->lang->line('name');
        $data['lang_search_product']             = $this->lang->line('search_product');
        $data['lang_add_to_list']                 = $this->lang->line('add_to_list');
        $data['lang_submit']                     = $this->lang->line('submit');
        $data['lang_receive']                     = $this->lang->line('receive');
        $data['lang_view']                         = $this->lang->line('view');
        $data['lang_created']                     = $this->lang->line('created');
        $data['lang_tax']                         = $this->lang->line('tax');
        $data['lang_discount_amount']             = $this->lang->line('discount_amount');
        $data['lang_total']                     = $this->lang->line('total');
        $data['lang_totat_payable']             = $this->lang->line('totat_payable');
        $data['lang_discount']                     = $this->lang->line('discount');
        $data['lang_sale_id']                     = $this->lang->line('sale_id');
        $data['lang_tax_total']                 = $this->lang->line('tax_total');
        $data['lang_export_to_excel']             = $this->lang->line('export_to_excel');
        $data['lang_type']                         = $this->lang->line('type');
        $data['lang_print']                     = $this->lang->line('print');

        $data['lang_product_name']                 = $this->lang->line('product_name');
        $data['lang_product_code']                 = $this->lang->line('product_code');
        $data['lang_previous_sales']             = $this->lang->line('previous_sales');
        $data['lang_customer']                     = $this->lang->line('customer');
        $data['lang_per_item_price']             = $this->lang->line('per_item_price');
        $data['lang_total_items']                 = $this->lang->line('total_items');
        $data['lang_sub_total']                 = $this->lang->line('sub_total');
        $data['lang_grand_total']                 = $this->lang->line('grand_total');
        $data['lang_paid_amt']                     = $this->lang->line('paid_amt');
        $data['lang_return_change']             = $this->lang->line('return_change');
        $data['lang_paid_by']                     = $this->lang->line('paid_by');
        $data['lang_date']                         = $this->lang->line('date');
        $data['lang_products']                     = $this->lang->line('products');
        $data['lang_quantity']                     = $this->lang->line('quantity');

        $data['lang_all_outlets']                 = $this->lang->line('all_outlets');
        $data['lang_choose_outlet']             = $this->lang->line('choose_outlet');
        $data['lang_choose_paid_by']             = $this->lang->line('choose_paid_by');
        $data['lang_all']                         = $this->lang->line('all');
        $data['lang_start_date']                 = $this->lang->line('start_date');
        $data['lang_end_date']                     = $this->lang->line('end_date');
        $data['lang_get_report']                 = $this->lang->line('get_report');

        $this->load->view("sold_product_report", $data);
    }

    // View Sales Report;
    public function sales_report()
    {
        $siteSettingData = $this->Constant_model->getDataOneColumn('site_setting', 'id', '1');
        $siteSetting_dateformat = $siteSettingData[0]->datetime_format;
        $siteSetting_currency = $siteSettingData[0]->currency;

        if ($siteSetting_dateformat == 'Y-m-d') {
            $dateformat = 'yyyy-mm-dd';
        }
        if ($siteSetting_dateformat == 'Y.m.d') {
            $dateformat = 'yyyy.mm.dd';
        }
        if ($siteSetting_dateformat == 'Y/m/d') {
            $dateformat = 'yyyy/mm/dd';
        }
        if ($siteSetting_dateformat == 'm-d-Y') {
            $dateformat = 'mm-dd-yyyy';
        }
        if ($siteSetting_dateformat == 'm.d.Y') {
            $dateformat = 'mm.dd.yyyy';
        }
        if ($siteSetting_dateformat == 'm/d/Y') {
            $dateformat = 'mm/dd/yyyy';
        }
        if ($siteSetting_dateformat == 'd-m-Y') {
            $dateformat = 'dd-mm-yyyy';
        }
        if ($siteSetting_dateformat == 'd.m.Y') {
            $dateformat = 'dd.mm.yyyy';
        }
        if ($siteSetting_dateformat == 'd/m/Y') {
            $dateformat = 'dd/mm/yyyy';
        }

        $data['site_dateformat'] = $siteSetting_dateformat;
        $data['site_currency'] = $siteSetting_currency;
        $data['dateformat'] = $dateformat;

        $data['lang_dashboard'] = $this->lang->line('dashboard');
        $data['lang_customers'] = $this->lang->line('customers');
        $data['lang_gift_card'] = $this->lang->line('gift_card');
        $data['lang_add_gift_card'] = $this->lang->line('add_gift_card');
        $data['lang_list_gift_card'] = $this->lang->line('list_gift_card');
        $data['lang_debit'] = $this->lang->line('debit');
        $data['lang_sales'] = $this->lang->line('sales');
        $data['lang_today_sales'] = $this->lang->line('today_sales');
        $data['lang_opened_bill'] = $this->lang->line('opened_bill');
        $data['lang_reports'] = $this->lang->line('reports');
        $data['lang_sales_report'] = $this->lang->line('sales_report');
        $data['lang_expenses'] = $this->lang->line('expenses');
        $data['lang_expenses_category'] = $this->lang->line('expenses_category');
        $data['lang_pnl'] = $this->lang->line('pnl');
        $data['lang_pnl_report'] = $this->lang->line('pnl_report');
        $data['lang_pos'] = $this->lang->line('pos');
        $data['lang_return_order'] = $this->lang->line('return_order');
        $data['lang_return_order_report'] = $this->lang->line('return_order_report');
        $data['lang_inventory'] = $this->lang->line('inventory');
        $data['lang_products'] = $this->lang->line('products');
        $data['lang_list_products'] = $this->lang->line('list_products');
        $data['lang_print_product_label'] = $this->lang->line('print_product_label');
        $data['lang_product_category'] = $this->lang->line('product_category');
        $data['lang_purchase_order'] = $this->lang->line('purchase_order');
        $data['lang_setting'] = $this->lang->line('setting');
        $data['lang_outlets'] = $this->lang->line('outlets');
        $data['lang_users'] = $this->lang->line('users');
        $data['lang_suppliers'] = $this->lang->line('suppliers');
        $data['lang_system_setting'] = $this->lang->line('system_setting');
        $data['lang_payment_methods'] = $this->lang->line('payment_methods');
        $data['lang_logout'] = $this->lang->line('logout');
        $data['lang_point_of_sales'] = $this->lang->line('point_of_sales');
        $data['lang_amount'] = $this->lang->line('amount');
        $data['lang_monthly_sales_outlet'] = $this->lang->line('monthly_sales_outlet');
        $data['lang_no_match_found'] = $this->lang->line('no_match_found');
        $data['lang_create_return_order'] = $this->lang->line('create_return_order');

        $data['lang_action'] = $this->lang->line('action');
        $data['lang_edit'] = $this->lang->line('edit');
        $data['lang_status'] = $this->lang->line('status');
        $data['lang_add'] = $this->lang->line('add');
        $data['lang_back'] = $this->lang->line('back');
        $data['lang_update'] = $this->lang->line('update');
        $data['lang_active'] = $this->lang->line('active');
        $data['lang_inactive'] = $this->lang->line('inactive');
        $data['lang_name'] = $this->lang->line('name');
        $data['lang_search_product'] = $this->lang->line('search_product');
        $data['lang_add_to_list'] = $this->lang->line('add_to_list');
        $data['lang_submit'] = $this->lang->line('submit');
        $data['lang_receive'] = $this->lang->line('receive');
        $data['lang_view'] = $this->lang->line('view');
        $data['lang_created'] = $this->lang->line('created');
        $data['lang_tax'] = $this->lang->line('tax');
        $data['lang_discount_amount'] = $this->lang->line('discount_amount');
        $data['lang_total'] = $this->lang->line('total');
        $data['lang_totat_payable'] = $this->lang->line('totat_payable');
        $data['lang_discount'] = $this->lang->line('discount');
        $data['lang_sale_id'] = $this->lang->line('sale_id');
        $data['lang_tax_total'] = $this->lang->line('tax_total');
        $data['lang_export_to_excel'] = $this->lang->line('export_to_excel');
        $data['lang_type'] = $this->lang->line('type');
        $data['lang_print'] = $this->lang->line('print');

        $data['lang_product_name'] = $this->lang->line('product_name');
        $data['lang_product_code'] = $this->lang->line('product_code');
        $data['lang_previous_sales'] = $this->lang->line('previous_sales');
        $data['lang_customer'] = $this->lang->line('customer');
        $data['lang_per_item_price'] = $this->lang->line('per_item_price');
        $data['lang_total_items'] = $this->lang->line('total_items');
        $data['lang_sub_total'] = $this->lang->line('sub_total');
        $data['lang_grand_total'] = $this->lang->line('grand_total');
        $data['lang_paid_amt'] = $this->lang->line('paid_amt');
        $data['lang_return_change'] = $this->lang->line('return_change');
        $data['lang_paid_by'] = $this->lang->line('paid_by');
        $data['lang_date'] = $this->lang->line('date');
        $data['lang_products'] = $this->lang->line('products');
        $data['lang_quantity'] = $this->lang->line('quantity');

        $data['lang_all_outlets'] = $this->lang->line('all_outlets');
        $data['lang_'] = $this->lang->line('choose_outlet');
        $data['lang_choose_paid_by'] = $this->lang->line('choose_paid_by');
        $data['lang_all'] = $this->lang->line('all');
        $data['lang_start_date'] = $this->lang->line('start_date');
        $data['lang_end_date'] = $this->lang->line('end_date');
        $data['lang_get_report'] = $this->lang->line('get_report');
        $data['lang_choose_outlet'] = $this->lang->line('choose_outlet');

        $this->load->view('sales_report', $data);
    }

    // Sold By Products Report;
    public function product_margin()
    {
        $siteSettingData                 = $this->Constant_model->getDataOneColumn('site_setting', 'id', '1');
        $siteSetting_dateformat         = $siteSettingData[0]->datetime_format;
        $siteSetting_currency             = $siteSettingData[0]->currency;

        if ($siteSetting_dateformat == 'Y-m-d') {
            $dateformat = 'yyyy-mm-dd';
        }
        if ($siteSetting_dateformat == 'Y.m.d') {
            $dateformat = 'yyyy.mm.dd';
        }
        if ($siteSetting_dateformat == 'Y/m/d') {
            $dateformat = 'yyyy/mm/dd';
        }
        if ($siteSetting_dateformat == 'm-d-Y') {
            $dateformat = 'mm-dd-yyyy';
        }
        if ($siteSetting_dateformat == 'm.d.Y') {
            $dateformat = 'mm.dd.yyyy';
        }
        if ($siteSetting_dateformat == 'm/d/Y') {
            $dateformat = 'mm/dd/yyyy';
        }
        if ($siteSetting_dateformat == 'd-m-Y') {
            $dateformat = 'dd-mm-yyyy';
        }
        if ($siteSetting_dateformat == 'd.m.Y') {
            $dateformat = 'dd.mm.yyyy';
        }
        if ($siteSetting_dateformat == 'd/m/Y') {
            $dateformat = 'dd/mm/yyyy';
        }

        $data['site_dateformat']                 = $siteSetting_dateformat;
        $data['site_currency']                     = $siteSetting_currency;
        $data['dateformat']                     = $dateformat;

        $data['lang_dashboard']                 = $this->lang->line('dashboard');
        $data['lang_customers']                 = $this->lang->line('customers');
        $data['lang_gift_card']                 = $this->lang->line('gift_card');
        $data['lang_add_gift_card']             = $this->lang->line('add_gift_card');
        $data['lang_list_gift_card']             = $this->lang->line('list_gift_card');
        $data['lang_debit']                     = $this->lang->line('debit');
        $data['lang_sales']                     = $this->lang->line('sales');
        $data['lang_today_sales']                 = $this->lang->line('today_sales');
        $data['lang_opened_bill']                 = $this->lang->line('opened_bill');
        $data['lang_reports']                     = $this->lang->line('reports');
        $data['lang_sales_report']                 = $this->lang->line('sales_report');
        $data['lang_expenses']                     = $this->lang->line('expenses');
        $data['lang_expenses_category']         = $this->lang->line('expenses_category');
        $data['lang_pnl']                         = $this->lang->line('pnl');
        $data['lang_pnl_report']                 = $this->lang->line('pnl_report');
        $data['lang_pos']                         = $this->lang->line('pos');
        $data['lang_return_order']                 = $this->lang->line('return_order');
        $data['lang_return_order_report']         = $this->lang->line('return_order_report');
        $data['lang_inventory']                 = $this->lang->line('inventory');
        $data['lang_products']                     = $this->lang->line('products');
        $data['lang_list_products']             = $this->lang->line('list_products');
        $data['lang_print_product_label']         = $this->lang->line('print_product_label');
        $data['lang_product_category']             = $this->lang->line('product_category');
        $data['lang_purchase_order']             = $this->lang->line('purchase_order');
        $data['lang_setting']                     = $this->lang->line('setting');
        $data['lang_outlets']                     = $this->lang->line('outlets');
        $data['lang_users']                     = $this->lang->line('users');
        $data['lang_suppliers']                 = $this->lang->line('suppliers');
        $data['lang_system_setting']             = $this->lang->line('system_setting');
        $data['lang_payment_methods']             = $this->lang->line('payment_methods');
        $data['lang_logout']                     = $this->lang->line('logout');
        $data['lang_point_of_sales']             = $this->lang->line('point_of_sales');
        $data['lang_amount']                     = $this->lang->line('amount');
        $data['lang_monthly_sales_outlet']         = $this->lang->line('monthly_sales_outlet');
        $data['lang_no_match_found']             = $this->lang->line('no_match_found');
        $data['lang_create_return_order']         = $this->lang->line('create_return_order');

        $data['lang_action']                     = $this->lang->line('action');
        $data['lang_edit']                         = $this->lang->line('edit');
        $data['lang_status']                     = $this->lang->line('status');
        $data['lang_add']                         = $this->lang->line('add');
        $data['lang_back']                         = $this->lang->line('back');
        $data['lang_update']                     = $this->lang->line('update');
        $data['lang_active']                     = $this->lang->line('active');
        $data['lang_inactive']                     = $this->lang->line('inactive');
        $data['lang_name']                         = $this->lang->line('name');
        $data['lang_search_product']             = $this->lang->line('search_product');
        $data['lang_add_to_list']                 = $this->lang->line('add_to_list');
        $data['lang_submit']                     = $this->lang->line('submit');
        $data['lang_receive']                     = $this->lang->line('receive');
        $data['lang_view']                         = $this->lang->line('view');
        $data['lang_created']                     = $this->lang->line('created');
        $data['lang_tax']                         = $this->lang->line('tax');
        $data['lang_discount_amount']             = $this->lang->line('discount_amount');
        $data['lang_total']                     = $this->lang->line('total');
        $data['lang_totat_payable']             = $this->lang->line('totat_payable');
        $data['lang_discount']                     = $this->lang->line('discount');
        $data['lang_sale_id']                     = $this->lang->line('sale_id');
        $data['lang_tax_total']                 = $this->lang->line('tax_total');
        $data['lang_export_to_excel']             = $this->lang->line('export_to_excel');
        $data['lang_type']                         = $this->lang->line('type');
        $data['lang_print']                     = $this->lang->line('print');

        $data['lang_product_name']                 = $this->lang->line('product_name');
        $data['lang_product_code']                 = $this->lang->line('product_code');
        $data['lang_previous_sales']             = $this->lang->line('previous_sales');
        $data['lang_customer']                     = $this->lang->line('customer');
        $data['lang_per_item_price']             = $this->lang->line('per_item_price');
        $data['lang_total_items']                 = $this->lang->line('total_items');
        $data['lang_sub_total']                 = $this->lang->line('sub_total');
        $data['lang_grand_total']                 = $this->lang->line('grand_total');
        $data['lang_paid_amt']                     = $this->lang->line('paid_amt');
        $data['lang_return_change']             = $this->lang->line('return_change');
        $data['lang_paid_by']                     = $this->lang->line('paid_by');
        $data['lang_date']                         = $this->lang->line('date');
        $data['lang_products']                     = $this->lang->line('products');
        $data['lang_quantity']                     = $this->lang->line('quantity');

        $data['lang_all_outlets']                 = $this->lang->line('all_outlets');
        $data['lang_choose_outlet']             = $this->lang->line('choose_outlet');
        $data['lang_choose_paid_by']             = $this->lang->line('choose_paid_by');
        $data['lang_all']                         = $this->lang->line('all');
        $data['lang_start_date']                 = $this->lang->line('start_date');
        $data['lang_end_date']                     = $this->lang->line('end_date');
        $data['lang_get_report']                 = $this->lang->line('get_report');

        $this->load->view("product_margin_report", $data);
    }

    // ****************************** View Page -- END ****************************** //

    // ****************************** Export Excel -- START ****************************** //
    
    public function exportSalesReport()
    {
        $report = $this->input->get('report');
        $url_start = $this->input->get('start_date');
        $url_end = $this->input->get('end_date');
        $url_outlet = $this->input->get('outlet');
        $url_paid_by = $this->input->get('paid');

        $siteSettingData = $this->Constant_model->getDataOneColumn('site_setting', 'id', '1');
        $site_dateformat = $siteSettingData[0]->datetime_format;
        $site_currency = $siteSettingData[0]->currency;

        $default_border = array(
            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('rgb' => '000000'),
        );

        $acc_default_border = array(
            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('rgb' => 'c7c7c7'),
        );
        $outlet_style_header = array(
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 10,
                'name' => 'Arial',
                'bold' => true,
            ),
        );
        $top_header_style = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff03'),
            ),
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 15,
                'name' => 'Arial',
                'bold' => true,
            ),
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
        );
        $style_header = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff03'),
            ),
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Arial',
                'bold' => true,
            ),
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ),
        );
        $account_value_style_header = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Arial',
            ),
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ),
        );
        $text_align_style = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ),
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'ffff03'),
            ),
            'font' => array(
                'color' => array('rgb' => '000000'),
                'size' => 12,
                'name' => 'Arial',
                'bold' => true,
            ),
        );

        $lang_sales_report = $this->lang->line('sales_report');
        $lang_date = $this->lang->line('date');
        $lang_sale_id = $this->lang->line('sale_id');
        $lang_type = $this->lang->line('type');
        $lang_outlet_name = $this->lang->line('outlet_name');
        $lang_cust_name = $this->lang->line('customer_name');
        $lang_total_items = $this->lang->line('total_items');
        $lang_sub_total = $this->lang->line('sub_total');
        $lang_tax = $this->lang->line('tax');
        $lang_grand_total = $this->lang->line('grand_total');
        $lang_total = $this->lang->line('total');
        $lang_payment = $this->lang->line('payment');

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', "$lang_sales_report");

        $sheet->getStyle('A1')->applyFromArray($top_header_style);
        $sheet->getStyle('B1')->applyFromArray($top_header_style);
        $sheet->getStyle('C1')->applyFromArray($top_header_style);
        $sheet->getStyle('D1')->applyFromArray($top_header_style);
        $sheet->getStyle('E1')->applyFromArray($top_header_style);
        $sheet->getStyle('F1')->applyFromArray($top_header_style);
        $sheet->getStyle('G1')->applyFromArray($top_header_style);

        $sheet->setCellValue('A2', "$lang_date");
        $sheet->setCellValue('B2', "$lang_sale_id");
        $sheet->setCellValue('C2', "$lang_outlet_name");
        $sheet->setCellValue('D2', "$lang_payment");
        $sheet->setCellValue('E2', "$lang_sub_total ($site_currency)");
        $sheet->setCellValue('F2', "$lang_tax ($site_currency)");
        $sheet->setCellValue('G2', "$lang_grand_total ($site_currency)");

        $sheet->getStyle('A2')->applyFromArray($style_header);
        $sheet->getStyle('B2')->applyFromArray($style_header);
        $sheet->getStyle('C2')->applyFromArray($style_header);
        $sheet->getStyle('D2')->applyFromArray($style_header);
        $sheet->getStyle('E2')->applyFromArray($style_header);
        $sheet->getStyle('F2')->applyFromArray($style_header);
        $sheet->getStyle('G2')->applyFromArray($style_header);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->getRowDimension('1')->setRowHeight(30);

        $jj                 = 3;
        $total_sub_amt         = 0;
        $total_tax_amt         = 0;
        $total_grand_amt     = 0;

        $start_date         = $url_start . ' 00:00:00';
        $end_date             = $url_end . ' 23:59:59';


        $paid_sort             = '';
        if ($url_paid_by != '-') {

            $ordPayResult     = $this->db->query("SELECT order_id FROM order_payments WHERE created_datetime >= '$start_date' AND created_datetime <= '$end_date' AND payment_method_id = '$url_paid_by' ");
            $ordPayData     = $ordPayResult->result();
            for ($k = 0; $k < count($ordPayData); $k++) {
                $ordPay_order_id     = $ordPayData[$k]->order_id;

                $paid_sort             .= "id = '$ordPay_order_id' || ";

                unset($ordPay_order_id);
            }
            unset($ordPayData);
            unset($ordPayResult);

            if (strlen($paid_sort) > 0) {
                $paid_sort             = trim($paid_sort, "|| ");
                $paid_sort             = "($paid_sort) AND ";
            }
        }

        $outlet_sort = '';
        if ($url_outlet == '-') {
            $outlet_sort = ' AND outlet_id > 0 ';
        } else {
            $outlet_sort = " AND outlet_id = '$url_outlet' ";
        }

        $orderResult = $this->db->query("SELECT * FROM orders WHERE $paid_sort ordered_datetime >= '$start_date' AND ordered_datetime <= '$end_date' AND status = '1' $outlet_sort ORDER BY ordered_datetime DESC ");
        $orderRows = $orderResult->num_rows();
        if ($orderRows > 0) {
            $orderData = $orderResult->result();
            for ($od = 0; $od < count($orderData); ++$od) {
                $order_id                 = $orderData[$od]->id;
                $order_dtm                 = date("$site_dateformat H:i A", strtotime($orderData[$od]->ordered_datetime));
                $outlet_id                 = $orderData[$od]->outlet_id;
                $subTotal                 = $orderData[$od]->subtotal;
                $tax                     = $orderData[$od]->tax;
                $grandTotal             = $orderData[$od]->grandtotal;
                $pay_method_id            = $orderData[$od]->payment_method;
                $payment_method_name     = $orderData[$od]->payment_method_name;
                $cheque_numb             = $orderData[$od]->cheque_number;
                $order_type             = $orderData[$od]->status;
                $outlet_name             = $orderData[$od]->outlet_name;

                if (!empty($cheque_numb)) {
                    $payment_method_name = $payment_method_name . " (Cheque No. : $cheque_numb)";
                }

                $sheet->setCellValue("A$jj", "$order_dtm");
                $sheet->setCellValue("B$jj", "$order_id");
                $sheet->setCellValue("C$jj", "$outlet_name");

                if ($order_type == "1") {

                    $payment_name_list        = "";
                    $ordPayResult             = $this->db->query("SELECT * FROM order_payments WHERE order_id = '$order_id' ORDER BY id ");
                    $ordPayData             = $ordPayResult->result();
                    for ($op = 0; $op < count($ordPayData); $op++) {
                        $ordPay_name         = $ordPayData[$op]->payment_method_name;
                        $payment_name_list    .= $ordPay_name . ", ";
                        unset($ordPay_name);
                    }
                    unset($ordPayData);
                    unset($ordPayResult);

                    if (strlen($payment_name_list) > 0) {
                        $payment_name_list     = trim($payment_name_list, ", ");
                    } else {
                        $payment_name_list     = "-";
                    }

                    $sheet->setCellValue("D$jj", "$payment_name_list");
                } else if ($order_type == "2") {
                    $sheet->setCellValue("D$jj", "$payment_method_name");
                }

                $sheet->setCellValue("E$jj", "$subTotal");
                $sheet->setCellValue("F$jj", "$tax");
                $sheet->setCellValue("G$jj", "$grandTotal");

                $sheet->getStyle("A$jj")->applyFromArray($account_value_style_header);
                $sheet->getStyle("B$jj")->applyFromArray($account_value_style_header);
                $sheet->getStyle("C$jj")->applyFromArray($account_value_style_header);
                $sheet->getStyle("D$jj")->applyFromArray($account_value_style_header);
                $sheet->getStyle("E$jj")->applyFromArray($account_value_style_header);
                $sheet->getStyle("F$jj")->applyFromArray($account_value_style_header);
                $sheet->getStyle("G$jj")->applyFromArray($account_value_style_header);

                $total_sub_amt += $subTotal;
                $total_tax_amt += $tax;
                $total_grand_amt += $grandTotal;

                unset($order_dtm);
                unset($outlet_id);
                unset($subTotal);
                unset($tax);
                unset($grandTotal);
                unset($pay_method_id);

                ++$jj;
            }
            unset($orderData);
        }
        unset($orderResult);
        unset($orderRows);

        $sheet->mergeCells("A$jj:D$jj");
        $sheet->setCellValue("A$jj", "$lang_total");
        $sheet->setCellValue("E$jj", "$total_sub_amt");
        $sheet->setCellValue("F$jj", "$total_tax_amt");
        $sheet->setCellValue("G$jj", "$total_grand_amt");

        $sheet->getStyle("A$jj")->applyFromArray($text_align_style);
        $sheet->getStyle("B$jj")->applyFromArray($style_header);
        $sheet->getStyle("C$jj")->applyFromArray($style_header);
        $sheet->getStyle("D$jj")->applyFromArray($style_header);
        $sheet->getStyle("E$jj")->applyFromArray($style_header);
        $sheet->getStyle("F$jj")->applyFromArray($style_header);
        $sheet->getStyle("G$jj")->applyFromArray($style_header);

        $sheet->getRowDimension("$jj")->setRowHeight(30);

        $writer = new Xlsx($spreadsheet);
        $filename = 'export-sales-report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function exportSoldByModelReport()
    {
        $url_start = $this->input->get('start_date');
        $url_end = $this->input->get('end_date');
        $url_outlet = $this->input->get('outlet');
        $url_paid_by = $this->input->get('paid');
        $url_category = $this->input->get('category');

        $lang_sales_report = $this->lang->line('sales_report');
        $lang_total = $this->lang->line('total');

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', "$lang_sales_report");

        $sheet->setCellValue('A2', $this->lang->line("product_code"));
        $sheet->setCellValue('B2', $this->lang->line("product_name"));
        $sheet->setCellValue('C2', $this->lang->line("product_category"));
        $sheet->setCellValue('D2', $this->lang->line("total_price"));
        $sheet->setCellValue('E2', $this->lang->line("sold_by_product_sold_qty"));

        apply_excel_headers_style($sheet, 4);
        
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);

        $sheet->getRowDimension('1')->setRowHeight(30);

        $start_dtm 		= $url_start . " 00:00:00";
        $end_dtm 		= $url_end . " 23:59:59";
        $overall_amount = 0;
        $overall_qty = 0;

        $conditions = "";
        if($url_outlet !='' && $url_outlet !='-')
            $conditions .= " AND orders.outlet_id=$url_outlet";
        if($url_category !='' && $url_category !='-')
            $conditions .= " AND order_items.product_category=$url_category";
        if($url_paid_by !='' && $url_paid_by !='-')
            $conditions .= " AND orders.payment_method=$url_paid_by";

        $ordItemResult 	= $this->db->query(
        "SELECT DISTINCT product_code, IF(products.name IS NOT NULL,products.name, CONCAT(max(order_items.product_name),' - (deleted product)') ) as product_name, category.name as category_name, SUM(order_items.price) as price, SUM(order_items.qty) as qty 
        FROM order_items 
        LEFT JOIN orders ON orders.id = order_items.order_id
        LEFT JOIN products ON products.code = order_items.product_code
        LEFT JOIN category ON category.id = products.category
        WHERE order_items.created_datetime >= '$start_dtm' AND order_items.created_datetime <= '$end_dtm'
        $conditions AND order_items.status = '1'
        GROUP BY product_code, products.name, category.name
        ORDER BY product_name ASC");

        $ordItemData 	= $ordItemResult->result_array();
        $rowsCount  =count($ordItemData);
        unset($ordItemResult);

        $jj = 3;//where row index will end
        if ($rowsCount > 0) {
            for ($ri = 0; $ri < $rowsCount; ++$ri) {
                $row = $ordItemData[$ri];
                $sheet->setCellValue("A$jj", $row['product_code']);
                $sheet->setCellValue("B$jj", $row['product_name']);
                $sheet->setCellValue("C$jj", $row['category_name']);
                $sheet->setCellValue("D$jj", $row['price']);
                $sheet->setCellValue("E$jj", $row['qty']);

                apply_excel_content_row_style($sheet, $jj, 4);

                $overall_amount += $row['price'];
                $overall_qty += $row['qty'];
                unset($row);
                $jj++;
            }
            unset($orderData);
        }
        unset($orderResult);
        unset($orderRows);

        $sheet->mergeCells("A$jj:D$jj");
        $sheet->setCellValue("A$jj", "$lang_total");
        $sheet->setCellValue("D$jj", "$overall_amount");
        $sheet->setCellValue("E$jj", "$overall_qty");

        apply_excel_headers_style($sheet, 4, $jj);

        $sheet->getRowDimension("$jj")->setRowHeight(30);

        $writer = new Xlsx($spreadsheet);
        $filename = 'export-sales-report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
    

    public function exportProductMarginReport()
    {
        // $this->load->helper('apply_excel_content_row_style');
        \PhpOffice\PhpSpreadsheet\Shared\File::setUseUploadTempDirectory(true);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', $this->lang->line('product_profit_margin'));

        $sheet->setCellValue('A2', $this->lang->line("product_code"));
        $sheet->setCellValue('B2', $this->lang->line("product_name"));
        $sheet->setCellValue('C2', $this->lang->line("product_category"));
        $sheet->setCellValue('D2', $this->lang->line("cost"));
        $sheet->setCellValue('E2', $this->lang->line("total_price"));
        $sheet->setCellValue('F2', $this->lang->line("sold_by_product_sold_qty"));

        apply_excel_headers_style($sheet, 4);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        $sheet->getRowDimension('1')->setRowHeight(30);

        $search_category = $_GET['category'];
        $search_code = $_GET['code'];
        $search_name = $_GET['name'];

        $conditions = "";
        if($search_name !='' && $search_name !='-')
            $conditions .= " AND products.name LIKE '%$search_name%'";
        if($search_code !='' && $search_code !='-')
            $conditions .= " AND products.code LIKE '%$search_code%'";
        if($search_category !='' && $search_category !='-')
            $conditions .= " AND products.category=$search_category";

        $ordItemResult 	= $this->db->query(
        "SELECT *, products.name as products_name, category.name as category_name, (retail_price - purchase_price) as margin 
        FROM products 
        LEFT JOIN category ON category.id = products.category
        WHERE products.created_datetime != '0000-00-00 00:00:00'
        $conditions
        ORDER BY products.name ASC");

        $ordItemData 	= $ordItemResult->result_array();
        $rowsCount  =count($ordItemData);
        unset($ordItemResult);

        $jj = 3;//where row index will end
        if ($rowsCount > 0) {
            for ($ri = 0; $ri < $rowsCount; ++$ri) {
                $row = $ordItemData[$ri];
                $sheet->setCellValue("A$jj", $row['code']);
                $sheet->setCellValue("B$jj", $row['products_name']);
                $sheet->setCellValue("C$jj", $row['category_name']);
                $sheet->setCellValue("D$jj", $row['purchase_price']);
                $sheet->setCellValue("E$jj", $row['retail_price']);
                $sheet->setCellValue("F$jj", $row['margin']);

                apply_excel_content_row_style($sheet, $jj, 5);
                unset($row);
                $jj++;
            }
            unset($orderData);
        }
        unset($orderResult);
        unset($orderRows);

        $sheet->getRowDimension("$jj")->setRowHeight(30);

        $writer = new Xlsx($spreadsheet);
        $filename = 'export-sales-report';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        // ob_clean();
        $writer->save('php://output');
    }

    
    // ****************************** Export Excel -- END ****************************** //
}
