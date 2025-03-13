<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('Permission_model');
    $this->load->library('session');
    $this->load->helper('cookie');
    $this->load->library('form_validation');
    $this->load->library('PermissionMiddleware');
  }

  public function index() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $permission_list_permission_id = $this->Permission_model->get_permission_id('permission list');

    if (!in_array($permission_list_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $data['permissions'] = $this->Permission_model->get_all_permissions();
    $this->load->view('permissions/list_permission', $data);
  }

  public function create() {
    if (!$this->session->userdata('user_id')) {
      redirect('users/login');
    }

    $permission_create_permission_id = $this->Permission_model->get_permission_id('permission create');

    if (!in_array($permission_create_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->load->view('permissions/create_permission');
  }

  public function store() {
    $permission_create_permission_id = $this->Permission_model->get_permission_id('permission create');

    if (!in_array($permission_create_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('description', 'Description', 'required');

    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('error', validation_errors());
      $this->load->view('permissions/create_permission');
    } else {
      $name = $this->input->post('name');
      $description = $this->input->post('description');

      if ($this->Permission_model->get_permission_by_name($name)) {
        $this->session->set_flashdata('error', 'Permission already exists');
        $this->load->view('permissions/create_permission');
      } else {
        $data = array(
          'id' => generate_uuid(),
          'name' => $name,
          'description' => $description,
        );

        $this->Permission_model->insert_permission($data);
        $this->session->set_flashdata('success', 'Permission created successfully');
        redirect('permissions/list');
      }
    }
  }

  public function delete($id) {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect('errors/error_403');
    }
    
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data['permission'] = $this->Permission_model->get_permission_by_id($id);
    if (!$data['permission']) {
      redirect('errors/error_404');
    }

    $permission_delete_permission_id = $this->Permission_model->get_permission_id('permission delete');
    if (!in_array($permission_delete_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->Permission_model->delete_permission($id);
    $this->session->set_flashdata('success', 'Permission deleted successfully');
    redirect('permissions/list');
  }
}
?>