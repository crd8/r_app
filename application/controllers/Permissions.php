<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Permissions extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('PermissionModel');
    $this->load->library('session');
    $this->load->helper('cookie');
    $this->load->library('form_validation');
    $this->load->library('PermissionMiddleware');
  }

  public function index() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $permission_list_permission_id = $this->PermissionModel->get_permission_id('permission list');

    if (!in_array($permission_list_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $data['permissions'] = $this->PermissionModel->get_all_permissions();
    $this->load->view('permissions/list_permission', $data);
  }

  public function create() {
    if (!$this->session->userdata('user_id')) {
      redirect('users/login');
    }

    $permission_create_permission_id = $this->PermissionModel->get_permission_id('permission create');
    if (!in_array($permission_create_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->load->view('permissions/create_permission');
  }

  public function store() {
    $permission_create_permission_id = $this->PermissionModel->get_permission_id('permission create');
    if (!in_array($permission_create_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->form_validation->set_rules('name', 'Permission Name', 'required|callback_unique_permission_name');
    $this->form_validation->set_rules('description', 'Description', 'required');

    if (!$this->form_validation->run()) {
      $data = [
        'errorMessage'    => 'Failed to create permission. Please fix the errors below.',
      ];
      $this->load->view('permissions/create_permission', $data);
    } else {
      $name = $this->input->post('name', true);
      $description = $this->input->post('description', true);
      $data = array(
        'id' => generate_uuid(),
        'name' => $name,
        'description' => $description,
      );

      $this->PermissionModel->insert_permission($data);
      $this->session->set_flashdata('success', 'Permission created successfully');
      redirect(ROUTE_PERMISSIONS_LIST);
    }
  }

  public function unique_permission_name($name, $id = null) {
    $u = $this->PermissionModel->get_permission_by_name($name);
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

    $data['permission'] = $this->PermissionModel->get_permission_by_id($id);
    if (!$data['permission']) {
      redirect(ROUTE_ERROR_404);
    }

    $permission_edit_permission_id = $this->PermissionModel->get_permission_id('permission edit');
    if (!in_array($permission_edit_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->load->view('permissions/edit_permission', $data);
  }

  public function update($id) {
    $permission_edit_permission_id = $this->PermissionModel->get_permission_id('permission edit');
    if (!in_array($permission_edit_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $permission = $this->PermissionModel->get_permission_by_id($id);
    if (! $permission) {
      redirect(ROUTE_ERROR_404);
    }
  
    $this->form_validation->set_rules('name', 'Permission Name', "required|callback_unique_permission_name[{$id}]");
    $this->form_validation->set_rules('description', 'Description', 'required');
  
    if (!$this->form_validation->run()) {
      $data = [
        'errorMessage' => 'Failed to update permission. Please fix the errors below.',
        'permission' => $permission,
      ];
      $this->load->view('permissions/edit_permission', $data);
    } else {
      $name = $this->input->post('name', true);
      $description = $this->input->post('description', true);
  
      $update = array(
        'name' => $name,
        'description' => $description,
      );
  
      $this->PermissionModel->update_permission($id, $update);
      
      $this->session->set_flashdata('success', 'Permission updated successfully');
      redirect(ROUTE_PERMISSIONS_LIST);
    }
  }

  public function delete($id) {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect(ROUTE_ERROR_403);
    }
    
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data['permission'] = $this->PermissionModel->get_permission_by_id($id);
    if (!$data['permission']) {
      redirect(ROUTE_ERROR_404);
    }

    $permission_delete_permission_id = $this->PermissionModel->get_permission_id('permission delete');
    if (!in_array($permission_delete_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->PermissionModel->delete_permission($id);
    $this->session->set_flashdata('success', 'Permission deleted successfully');
    redirect(ROUTE_PERMISSIONS_LIST);
  }
}
