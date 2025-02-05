<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('Permission_model');
    $this->load->library('session');
    $this->load->helper('cookie');
    $this->load->library('form_validation');
  }

  public function create() {
    if (!$this->session->userdata('user_id')) {
      redirect('users/login');
    }

    $this->load->view('permissions/create_permission');
  }

  public function store() {
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
        redirect('permissions/create');
      }
    }
  }
}
?>