<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sales extends CI_Controller
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
        $this->load->model('Sales_model');
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

    // View List Sales;
    public function list_sales()
    {
        $paginationData = $this->Constant_model->getDataOneColumn('site_setting', 'id', '1');
        $pagination_limit = $paginationData[0]->pagination;
        $setting_dateformat = $paginationData[0]->datetime_format;

        $data['setting_dateformat'] = $setting_dateformat;

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

        $data['lang_items'] = $this->lang->line('items');

        $this->load->view('list_sales', $data);
    }

    // Opened Bill;
    public function opened_bill()
    {
        $paginationData = $this->Constant_model->getDataOneColumn('site_setting', 'id', '1');
        $pagination_limit = $paginationData[0]->pagination;
        $setting_dateformat = $paginationData[0]->datetime_format;

        $data['setting_dateformat'] = $setting_dateformat;

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

        $data['lang_items'] = $this->lang->line('items');
        $data['lang_opened_bill'] = $this->lang->line('opened_bill');
        $data['lang_ref_number'] = $this->lang->line('ref_number');
        $data['lang_confirm_to_delete_bill'] = $this->lang->line('confirm_to_delete_bill');

        $this->load->view('list_bills', $data);
    }

    // ****************************** View Page -- END ****************************** //

    // ****************************** Action To Database -- START ****************************** //

    // Delete Sales;
    public function deleteSale()
    {
        $order_id = $this->input->get('id');

        $ordDtaData = $this->Constant_model->getDataOneColumn('orders', 'id', $order_id);
        $order_type = $ordDtaData[0]->status;
        $order_outlet_id = $ordDtaData[0]->outlet_id;

        // Delete Order;
        $this->Constant_model->deleteData('orders', $order_id);

        if ($order_type == '1') {
            $ordItemResult = $this->db->query("SELECT * FROM order_items WHERE order_id = '$order_id' ");
            $ordItemData = $ordItemResult->result();
            for ($i = 0; $i < count($ordItemData); ++$i) {
                $oitem_id = $ordItemData[$i]->id;
                $pcode = $ordItemData[$i]->product_code;
                $new_qty = $ordItemData[$i]->qty;

                // Check Id;
                $getInvDtaResult = $this->db->query("SELECT * FROM inventory WHERE product_code = '$pcode' AND outlet_id = '$order_outlet_id' ");
                $getInvDtaRows = $getInvDtaResult->num_rows();
                if ($getInvDtaRows == 1) {
                    $getInvDtaData = $getInvDtaResult->result();

                    $getInv_id = $getInvDtaData[0]->id;
                    $getInv_qty = $getInvDtaData[0]->qty;

                    unset($getInvDtaData);

                    $upd_inv_qty = 0;
                    $upd_inv_qty = $getInv_qty + $new_qty;

                    $upd_data = array(
                                'qty' => $upd_inv_qty,
                    );
                    $this->Constant_model->updateData('inventory', $upd_data, $getInv_id);

                    unset($upd_inv_qty);
                    unset($upd_inv_qty);
                } else {
                    $ins_data = array(
                            'product_code' => $pcode,
                            'outlet_id' => $order_outlet_id,
                            'qty' => $new_qty,
                    );
                    $last_inv_id = $this->Constant_model->insertDataReturnLastId('inventory', $ins_data);
                }
                unset($getInvDtaResult);
                unset($getInvDtaRows);

                // Delete Order Item;
                $this->Constant_model->deleteData('order_items', $oitem_id);

                unset($oitem_id);
                unset($pcode);
                unset($new_qty);
            }
            unset($ordItemResult);
            unset($ordItemData);
            
            // Order Payment -- START;
            $ordPayResult 	= $this->db->query("SELECT * FROM order_payments WHERE order_id = '$order_id' ");
            $ordPayData 	= $ordPayResult->result();
            for($pd = 0; $pd < count($ordPayData); $pd++) {
	            $ordPay_id 	= $ordPayData[$pd]->id;
	            
	            $this->Constant_model->deleteData("order_payments", $ordPay_id);
	            
	            unset($ordPay_id);
            }
            unset($ordPayData);
            unset($ordPayResult);
            // Order Payment -- END;
        }

        if ($order_type == '2') {
            $retItemResult = $this->db->query("SELECT * FROM return_items WHERE order_id = '$order_id' ");
            $retItemData = $retItemResult->result();
            for ($r = 0; $r < count($retItemData); ++$r) {
                $ret_id = $retItemData[$r]->id;
                $ret_pcode = $retItemData[$r]->product_code;
                $ret_qty = $retItemData[$r]->qty;
                $ret_cond = $retItemData[$r]->item_condition;

                if ($ret_cond == '1') {

                     // Check Id;
                    $getInvDtaResult = $this->db->query("SELECT * FROM inventory WHERE product_code = '$ret_pcode' AND outlet_id = '$order_outlet_id' ");
                    $getInvDtaRows = $getInvDtaResult->num_rows();
                    if ($getInvDtaRows == 1) {
                        $getInvDtaData = $getInvDtaResult->result();

                        $getInv_id = $getInvDtaData[0]->id;
                        $getInv_qty = $getInvDtaData[0]->qty;

                        unset($getInvDtaData);

                        $upd_inv_qty = 0;
                        $upd_inv_qty = $getInv_qty - $ret_qty;

                        $upd_data = array(
                                    'qty' => $upd_inv_qty,
                        );
                        $this->Constant_model->updateData('inventory', $upd_data, $getInv_id);

                        unset($upd_inv_qty);
                        unset($upd_inv_qty);
                    }
                    unset($getInvDtaResult);
                    unset($getInvDtaRows);
                }

                 // Delete Order Item;
                $this->Constant_model->deleteData('return_items', $ret_id);

                unset($ret_id);
                unset($ret_pcode);
                unset($ret_qty);
                unset($ret_cond);
            }
            unset($retItemResult);
            unset($retItemData);
        }

        if ($order_type == '1') {
            $this->session->set_flashdata('alert_msg', array('success', 'Delete Sales', 'Successfully Deleted Sales.'));
        } elseif ($order_type == '2') {
            $this->session->set_flashdata('alert_msg', array('success', 'Delete Return', 'Successfully Deleted Return Order.'));
        }
        redirect(base_url().'sales/list_sales');
    }

    // Delete Suspend;
    public function deleteSuspended()
    {
        $id = $this->input->get('id');

        $ckSusData = $this->Constant_model->getDataOneColumn('suspend', 'id', $id);

        if (count($ckSusData) == 1) {
            if ($this->Constant_model->deleteData('suspend', $id)) {
                $this->Constant_model->deleteByColumn('suspend_items', 'suspend_id', $id);

                $this->session->set_flashdata('alert_msg', array('success', 'Delete Opened bill', 'Successfully Deleted Opened Bill.'));
                redirect(base_url().'sales/opened_bill');
            }
        } else {
            $this->session->set_flashdata('alert_msg', array('failure', 'Delete Opened bill', 'Error on Deleting Opened Bill! Please try again!'));
            redirect(base_url().'sales/opened_bill');
        }
    }

    // ****************************** Action To Database -- END ****************************** //

    // ****************************** Action To Database -- START ****************************** //
    // Export Sales -- START;
    public function exportSales()
    {
        $paginationData = $this->Constant_model->getDataOneColumn('site_setting', 'id', '1');
        $setting_dateformat = $paginationData[0]->datetime_format;

        $user_role = $this->session->userdata('user_role');
        $user_outlet = $this->session->userdata('user_outlet');

        $display_date = date("$setting_dateformat", time());

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


        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', $this->lang->line('product_profit_margin'));

        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', "$lang_sales_report : $display_date ");
        apply_excel_headers_style($sheet, 8);

        $sheet->getRowDimension('1')->setRowHeight(30);

        $sheet->setCellValue('A2', "$lang_date");
        $sheet->setCellValue('B2', "$lang_sale_id");
        $sheet->setCellValue('C2', "$lang_type");
        $sheet->setCellValue('D2', "$lang_outlet_name");
        $sheet->setCellValue('E2', "$lang_cust_name");
        $sheet->setCellValue('F2', "$lang_total_items");
        $sheet->setCellValue('G2', "$lang_sub_total");
        $sheet->setCellValue('H2', "$lang_tax");
        $sheet->setCellValue('I2', "$lang_grand_total");

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);

        $sheet->getRowDimension('1')->setRowHeight(30);

        $jj = 3;
        $today_start = date('Y-m-d 00:00:00', time());
        $today_end = date('Y-m-d 23:59:59', time());

        $total_sub_amt = 0;
        $total_tax_amt = 0;
        $total_grand_amt = 0;

        if ($user_role == '1') {
            $salesResult = $this->db->query("SELECT * FROM orders WHERE ordered_datetime >= '$today_start' AND ordered_datetime <= '$today_end' ORDER BY id DESC ");
        } else {
            $salesResult = $this->db->query("SELECT * FROM orders WHERE ordered_datetime >= '$today_start' AND ordered_datetime <= '$today_end' AND outlet_id = '$user_outlet' ORDER BY id DESC ");
        }

        $salesData = $salesResult->result();

        for ($i = 0; $i < count($salesData); ++$i) {
            $order_id = $salesData[$i]->id;
            $cust_fn = $salesData[$i]->customer_name;
            $ordered_dtm = date("$setting_dateformat H:i A", strtotime($salesData[$i]->ordered_datetime));
            $outlet_id = $salesData[$i]->outlet_id;
            $subTotal = $salesData[$i]->subtotal;
            $discountTotal = $salesData[$i]->discount_total;
            $taxTotal = $salesData[$i]->tax;
            $grandTotal = $salesData[$i]->grandtotal;
            $total_items = $salesData[$i]->total_items;
            $payment_method = $salesData[$i]->payment_method;
            $order_type = $salesData[$i]->status;

            $outlet_name = $salesData[$i]->outlet_name;

            $total_sub_amt += $subTotal;
            $total_tax_amt += $taxTotal;
            $total_grand_amt += $grandTotal;

            $type_name = '';
            if ($order_type == '1') {
                $type_name = 'Sale';
            } elseif ($order_type == '2') {
                $type_name = 'Return';
            }

            $sheet->setCellValue("A$jj", "$ordered_dtm");
            $sheet->setCellValue("B$jj", "$order_id");
            $sheet->setCellValue("C$jj", "$type_name");
            $sheet->setCellValue("D$jj", "$outlet_name");
            $sheet->setCellValue("E$jj", "$cust_fn");
            $sheet->setCellValue("F$jj", "$total_items");
            $sheet->setCellValue("G$jj", "$subTotal");
            $sheet->setCellValue("H$jj", "$taxTotal");
            $sheet->setCellValue("I$jj", "$grandTotal");

            apply_excel_content_row_style($sheet, $jj, 8);
            ++$jj;
        }    // End of Loop;

        $sheet->mergeCells("A$jj:F$jj");
        $sheet->setCellValue("A$jj", "$lang_total");
        $sheet->setCellValue("G$jj", "$total_sub_amt");
        $sheet->setCellValue("H$jj", "$total_tax_amt");
        $sheet->setCellValue("I$jj", "$total_grand_amt");
        apply_excel_headers_style($sheet, 8, $jj);

        $sheet->getRowDimension("$jj")->setRowHeight(30);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Today_Sales';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    // Export Sales -- END;
    // ****************************** Action To Database -- END ****************************** //
}
