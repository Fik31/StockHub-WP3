<?php

class Permission extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Permission';
		$this->load->model('model_groups');
	}

	public function index()
	{
		if (!in_array('viewGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$groups_data = $this->model_groups->getGroupData();
		$this->data['groups_data'] = $groups_data;
		$this->render_template('permission/index', $this->data);
	}

	public function create()
	{
		if (!in_array('createGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->form_validation->set_rules('group_name', 'Permission name', 'required');
		if ($this->form_validation->run() == TRUE) {
			// true case
			$permission = serialize($this->input->post('permission'));
			$data = array(
				'group_name' => $this->input->post('group_name'),
				'permission' => $permission
			);
			$create = $this->model_groups->create($data);
			if ($create == true) {
				$this->session->set_flashdata('success', 'Successfully created');
				redirect('Permission/', 'refresh');
			} else {
				$this->session->set_flashdata('errors', 'Error occurred!!');
				redirect('Permission/create', 'refresh');
			}
		} else {
			// false case
			$this->render_template('permission/create', $this->data);
		}
	}

	public function edit($id = null)
	{
		if (!in_array('updateGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		if ($id) {
			$this->form_validation->set_rules('group_name', 'Permission name', 'required');

			if ($this->form_validation->run() == TRUE) {
				// true case
				$permission = serialize($this->input->post('permission'));
				$data = array(
					'group_name' => $this->input->post('group_name'),
					'permission' => $permission
				);
				$update = $this->model_groups->edit($data, $id);
				if ($update == true) {
					$this->session->set_flashdata('success', 'Successfully updated');
					redirect('Permission/', 'refresh');
				} else {
					$this->session->set_flashdata('errors', 'Error occurred!!');
					redirect('Permission/edit/' . $id, 'refresh');
				}
			} else {
				// false case
				$group_data = $this->model_groups->getGroupData($id);
				$this->data['group_data'] = $group_data;
				$this->render_template('permission/edit', $this->data);
			}
		}
	}


	public function delete($id)
	{

		if (!in_array('deleteGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if ($id) {
			if ($this->input->post('confirm')) {

				$check = $this->model_groups->existInUserGroup($id);
				if ($check == true) {
					$this->session->set_flashdata('error', 'Group exists in the users');
					redirect('Permission/', 'refresh');
				} else {
					$delete = $this->model_groups->delete($id);
					if ($delete == true) {
						$this->session->set_flashdata('success', 'Successfully removed');
						redirect('Permission/', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Error occurred!!');
						redirect('Permission/delete/' . $id, 'refresh');
					}
				}
			} else {
				$this->data['id'] = $id;
				$this->render_template('permission/delete', $this->data);
			}
		}
	}
}
