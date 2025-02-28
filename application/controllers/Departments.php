<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departments extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('Department_model');
    $this->load->model('Permission_model');
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
        redirect('errors/error_403');
    }

    $department_list_permission_id = $this->Permission_model->get_permission_id('department list');
    if (!in_array($department_list_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $data['departments'] = $this->Department_model->get_all_departments();

    $this->load->view('departments/list_department', $data);
  }

  public function create() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $department_create_permission_id = $this->Permission_model->get_permission_id('department create');

    if (!in_array($department_create_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->load->view('departments/create_department');
  }

  public function store() {
    $department_create_permission_id = $this->Permission_model->get_permission_id('department create');

    if (!in_array($department_create_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->form_validation->set_rules('name', 'Department Name', 'required');
    $this->form_validation->set_rules('description', 'Description', 'required');

    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('error', validation_errors());
      $this->load->view('departments/create_department');
    } else {
      $name = $this->input->post('name', TRUE);
      $description = $this->input->post('description', TRUE);

      if ($this->Department_model->get_department_by_name($name)) {
        $this->session->set_flashdata('error', 'Department name already exists');
        $this->load->view('departments/create_department');
      } else {
        $data = array(
          'id' => generate_uuid(),
          'name' => $name,
          'description' => $description,
        );

        $this->Department_model->insert_department($data);
        $this->session->set_flashdata('success', 'Department created successfully');
        redirect('departments/list');
      }
    }
  }
}
?>