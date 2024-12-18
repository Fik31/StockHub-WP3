<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Orders';
		$this->load->model('model_orders');
		$this->load->model('model_products');
		$this->load->model('model_company');
	}

	public function index()
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Orders';
		$this->render_template('orders/index', $this->data);
	}

	public function fetchOrdersData()
	{
		$result = array('data' => array());
		$data = $this->model_orders->getOrdersData();
		foreach ($data as $key => $value) {
			$count_total_item = $this->model_orders->countOrderItem($value['id']);
			$date = date('d-m-Y', $value['date_time']);
			$time = date('h:i a', $value['date_time']);
			$date_time = $date . ' ' . $time;
			// button
			$buttons = '';
			if (in_array('viewOrder', $this->permission)) {
				$buttons .= '<center><a target="__blank" href="' . base_url('Orders/printDiv/' . $value['id']) . '" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>';
			}
			if (in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="' . base_url('Orders/update/' . $value['id']) . '" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>';
			}
			if (in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-danger btn-sm" onclick="removeFunc(' . $value['id'] . ')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button></center>';
			}
			if ($value['paid_status'] == 1) {
				$paid_status = '<center><span class="label label-success">Paid</span></center>';
			} else {
				$paid_status = '<center><span class="label label-warning">Not Paid</span></center>';
			}
			$result['data'][$key] = array(
				$value['bill_no'],
				$value['customer_name'],
				$value['customer_phone'],
				$value['customer_address'],
				$date_time,
				$count_total_item,
				// '$'.$value['net_amount'],
				'Rp ' . number_format($value['net_amount'], 0, ',', '.'),
				$paid_status,
				$buttons
			);
		} // /foreach
		echo json_encode($result);
	}

	public function create()
	{
		if (!in_array('createOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Orders';
		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		$this->form_validation->set_rules('customer_address', 'Customer Address', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$order_id = $this->model_orders->create();
			if ($order_id) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('Orders/update/' . $order_id, 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('Orders/create/', 'refresh');
			}
		} else {
			// false case
			$company = $this->model_company->getCompanyData(1);
			$this->data['company_data'] = $company;
			$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
			$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;
			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->render_template('orders/create', $this->data);
		}
	}

	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if ($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	public function getTableProductRow()
	{
		$products = $this->model_products->getActiveProductData();
		echo json_encode($products);
	}

	public function update($id)
	{
		if (!in_array('updateOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		if (!$id) {
			redirect('dashboard', 'refresh');
		}
		$this->data['page_title'] = 'Update Order';
		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$update = $this->model_orders->update($id);
			if ($update == true) {
				$this->session->set_flashdata('success', 'Successfully updated');
				redirect('Orders/update/' . $id, 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('Orders/update/' . $id, 'refresh');
			}
		} else {
			// false case
			$company = $this->model_company->getCompanyData(1);
			$this->data['company_data'] = $company;
			$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
			$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;
			$result = array();
			$orders_data = $this->model_orders->getOrdersData($id);
			$result['order'] = $orders_data;
			$orders_item = $this->model_orders->getOrdersItemData($orders_data['id']);
			foreach ($orders_item as $k => $v) {
				$result['order_item'][] = $v;
			}
			$this->data['order_data'] = $result;
			$this->data['products'] = $this->model_products->getActiveProductData();
			$this->render_template('orders/edit', $this->data);
		}
	}

	public function remove()
	{
		if (!in_array('deleteOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$order_id = $this->input->post('order_id');
		$response = array();
		if ($order_id) {
			$delete = $this->model_orders->remove($order_id);
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the product information";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}
		echo json_encode($response);
	}

	public function printDiv($id)
	{
		if (!in_array('viewOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		if ($id) {
			$order_data = $this->model_orders->getOrdersData($id);
			$orders_items = $this->model_orders->getOrdersItemData($id);
			$company_info = $this->model_company->getCompanyData(1);
			$order_date = date('d/m/Y', $order_data['date_time']);
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";
			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          ' . $company_info['company_name'] . '
			          <small class="pull-right">Date: ' . $order_date . '</small>
			        </h2>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			        
			        <b>Bill ID:</b> ' . $order_data['bill_no'] . '<br>
			        <b>Customer:</b> ' . $order_data['customer_name'] . '<br>
			        <b>Address:</b> ' . $order_data['customer_address'] . ' <br />
			        <b>Contact:</b> ' . $order_data['customer_phone'] . '
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-striped table-bordered">
			          <thead>
			          <tr>
			            <th>Product</th>
			            <th>Price</th>
			            <th>Qty</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>';

			foreach ($orders_items as $k => $v) {
				$product_data = $this->model_products->getProductData($v['product_id']);
				$html .= '<tr>
				            <td>' . $product_data['name'] . '</td>
							<td>Rp ' . number_format($v['rate'], 0, ',', '.') . '</td>
				            <td>' . $v['qty'] . '</td>
							<td>Rp ' . number_format($v['amount'], 0, ',', '.') . '</td>
			          	</tr>';
			}

			$html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
			      
			      <div class="col-xs-6 pull pull-right">

			        <div class="table-responsive">
			          <table class="table table-striped">
			            <tr>
			              <th style="width:50%">Gross Amount:</th>
			              <td>Rp ' . number_format(floatval($order_data['gross_amount']), 0, ',', '.') . '</td>
			            </tr>';

			if ($order_data['service_charge'] > 0) {
				$html .= '<tr>
				              <th>Service Charge (' . $order_data['service_charge_rate'] . '%)</th>
				              <td>Rp ' . number_format(floatval($order_data['service_charge']), 0, ',', '.') . '</td>
				            </tr>';
			}

			if ($order_data['vat_charge'] > 0) {
				$html .= '<tr>
				              <th>Vat Charge (' . $order_data['vat_charge_rate'] . '%)</th>
				              <td>Rp ' . number_format(floatval($order_data['vat_charge']), 0, ',', '.') . '</td>
				            </tr>';
			}


			$html .= ' <tr>
			              <th>Discount:</th>
			              <td>Rp ' . number_format(floatval($order_data['discount']), 0, ',', '.') . '</td>
			            </tr>
			            <tr>
			              <th>Net Amount:</th>
			              <td>Rp ' . number_format(floatval($order_data['net_amount']), 0, ',', '.') . '</td>
			            </tr>
			            <tr>
			              <th>Bill Status:</th>
			              <td>' . $paid_status . '</td>
			            </tr>
			          </table>
			        </div>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
		</body>
	</html>';

			echo $html;
		}
	}
}
