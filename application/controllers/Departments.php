<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Departments extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('DepartmentModel');
    $this->load->model('PermissionModel');
    $this->load->library('session');
    $this->load->helper('cookie');
    $this->load->library('form_validation');
  }

  public function index() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $userPermissions = $this->session->userdata('permissions');
    if (!$userPermissions || !is_array($userPermissions)) {
        redirect(ROUTE_ERROR_403);
    }

    $department_list_permission_id = $this->PermissionModel->get_permission_id('department list');
    if (!in_array($department_list_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $data['departments'] = $this->DepartmentModel->get_all_departments();

    $this->load->view('departments/list_department', $data);
  }

  public function create() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $department_create_permission_id = $this->PermissionModel->get_permission_id('department create');
    if (!in_array($department_create_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->load->view('departments/create_department');
  }

  public function store() {
    $department_create_permission_id = $this->PermissionModel->get_permission_id('department create');
    if (!in_array($department_create_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->form_validation->set_rules('name', 'Department Name', 'required|callback_unique_permission_name');
    $this->form_validation->set_rules('description', 'Description', 'required');

    if (!$this->form_validation->run()) {
      $data = [
        'errorMessage'    => 'Failed to create department. Please fix the errors below.',
      ];
      $this->load->view('departments/create_department', $data);
    } else {
      $name = $this->input->post('name', true);
      $description = $this->input->post('description', true);

      $data = array(
        'id' => generate_uuid(),
        'name' => $name,
        'description' => $description,
      );

      $this->DepartmentModel->insert_department($data);
      $this->session->set_flashdata('success', 'Department created successfully');
      redirect(ROUTE_DEPARTMENTS_LIST);
    }
  }

  public function unique_permission_name($name, $id = null) {
    $u = $this->DepartmentModel->get_department_by_name($name);
    if ($u && $u->id != $id) {
      $this->form_validation->set_message(
        'unique_permission_name',
        'Permission name already exists'
      );
      return false;
    }
    return true;
  }

  public function edit($id) {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data['department'] = $this->DepartmentModel->get_department_by_id($id);
    if (!$data['department']) {
      redirect('errors/error_404');
    }

    $department_edit_permission_id = $this->PermissionModel->get_permission_id('department edit');
    if (!in_array($department_edit_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->load->view('departments/edit_department', $data);
  }

  public function update($id) {
    $department_edit_permission_id = $this->PermissionModel->get_permission_id('department edit');
    if (!in_array($department_edit_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $department = $this->DepartmentModel->get_department_by_id($id);
    if (!$department) {
      show_404();
    }
  
    $this->form_validation->set_rules('name', 'Department name', "required|callback_unique_permission_name[{$id}]");
    $this->form_validation->set_rules('description', 'Description', 'required');
  
    if (!$this->form_validation->run()) {
      $data = [
        'errorMessage' => 'Failed to update department. Please fix the errors below.',
        'department' => $department,
      ];
      $this->load->view('departments/edit_department', $data);
    } else {
      $name = $this->input->post('name', true);
      $description = $this->input->post('description', true);
  
      $update = [
        'name'      => $name,
        'description'      => $description,
      ];
  
      $this->DepartmentModel->update_department($id, $update);
      
      $this->session->set_flashdata('success', 'Department updated successfully');
      redirect(ROUTE_DEPARTMENTS_LIST);
    }
  }

  public function delete($id) {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect(ROUTE_ERROR_403);
    }
    
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data['department'] = $this->DepartmentModel->get_department_by_id($id);
    if (!$data['department']) {
      redirect('errors/error_404');
    }

    $department_delete_permission_id = $this->PermissionModel->get_permission_id('department delete');
    if (!in_array($department_delete_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->DepartmentModel->delete_department($id);
    $this->session->set_flashdata('success', 'Department deleted successfully');
    redirect(ROUTE_DEPARTMENTS_LIST);
  }
}
