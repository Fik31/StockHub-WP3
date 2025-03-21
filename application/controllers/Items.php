<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Items extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Items';
		$this->load->model('model_brands');
	}

	public function index()
	{
		if (!in_array('viewBrand', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$result = $this->model_brands->getBrandData();
		$this->data['results'] = $result;
		$this->render_template('items/index', $this->data);
	}

	public function fetchBrandData()
	{
		$result = array('data' => array());
		$data = $this->model_brands->getBrandData();
		foreach ($data as $key => $value) {
			// button
			$buttons = '';
			if (in_array('viewBrand', $this->permission)) {
				$buttons .= '<center><button type="button" class="btn btn-warning btn-sm" onclick="editBrand(' . $value['id'] . ')" data-toggle="modal" data-target="#editBrandModal"><i class="fa fa-pencil"></i></button>';
			}
			if (in_array('deleteBrand', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-danger btn-sm" onclick="removeBrand(' . $value['id'] . ')" data-toggle="modal" data-target="#removeBrandModal"><i class="fa fa-trash"></i></button></center>';
			}
			$status = ($value['active'] == 1) ? '<center><span class="label label-success">Active</span></center>' : '<center><span class="label label-warning">Inactive</span></center>';
			$result['data'][$key] = array(
				$value['name'],
				$status,
				$buttons
			);
		} // /foreach
		echo json_encode($result);
	}

	public function fetchBrandDataById($id)
	{
		if ($id) {
			$data = $this->model_brands->getBrandData($id);
			echo json_encode($data);
		}
		return false;
	}

	public function create()
	{
		if (!in_array('createBrand', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$response = array();
		$this->form_validation->set_rules('brand_name', 'Item name', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');
		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'name' => $this->input->post('brand_name'),
				'active' => $this->input->post('active'),
			);

			$create = $this->model_brands->create($data);
			if ($create == true) {
				$response['success'] = true;
				$response['messages'] = 'Succesfully created';
			} else {
				$response['success'] = false;
				$response['messages'] = 'Error in the database while creating the brand information';
			}
		} else {
			$response['success'] = false;
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}
		echo json_encode($response);
	}

	public function update($id)
	{
		if (!in_array('updateBrand', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$response = array();
		if ($id) {
			$this->form_validation->set_rules('edit_brand_name', 'Item name', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'name' => $this->input->post('edit_brand_name'),
					'active' => $this->input->post('edit_active'),
				);
				$update = $this->model_brands->update($data, $id);
				if ($update == true) {
					$response['success'] = true;
					$response['messages'] = 'Succesfully updated';
				} else {
					$response['success'] = false;
					$response['messages'] = 'Error in the database while updated the brand information';
				}
			} else {
				$response['success'] = false;
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	public function remove()
	{
		if (!in_array('deleteBrand', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$brand_id = $this->input->post('brand_id');
		$response = array();
		if ($brand_id) {
			$delete = $this->model_brands->remove($brand_id);
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the brand information";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}
		echo json_encode($response);
	}
}
