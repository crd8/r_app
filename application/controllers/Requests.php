<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requests extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('RequestModel');
    $this->load->model('UserModel');
    $this->load->model('DepartmentModel');
  }

  public function index() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $user_id = $this->session->userdata('user_id');
    $data['requests'] = $this->RequestModel->get_my_requests($user_id);
    $this->load->view('requests/index', $data);
  }

  public function show($id) {
    $data['request'] = $this->RequestsModel->get_by_id($id);
    if (!$data['request']) {
      show_404();
    }
    $this->load->view('requests/show', $data);
  }

  public function create() {
    $data['users'] = $this->UserModel->get_all_users();
    $data['departments'] = $this->DepartmentModel->get_all_departments();
    $this->load->view('requests/create', $data);
  }

  public function store() {
    $new_request_id = generate_uuid();
    $data = [
      'id'                => $new_request_id,
      'requester_user_id' => $this->session->userdata('user_id'),
      'receiver_user_id'  => $this->input->post('receiver_user_id'),
      'department_id'     => $this->input->post('department_id'),
      'title'             => $this->input->post('title'),
      'description'       => $this->input->post('description'),
      'status'            => 'pending',
      'deadline'          => $this->input->post('deadline')
    ];
    $this->RequestModel->create($data);
    redirect('requests');
  }

  public function edit($id) {
    $data['request'] = $this->RequestsModel->get_by_id($id);
    $data['users'] = $this->UsersModel->get_all();
    $data['departments'] = $this->DepartmentsModel->get_all();
    $this->load->view('requests/edit', $data);
  }

  public function update($id) {
    $data = [
      'receiver_user_id' => $this->input->post('receiver_user_id'),
      'department_id'    => $this->input->post('department_id'),
      'title'            => $this->input->post('title'),
      'description'      => $this->input->post('description'),
      'deadline'         => $this->input->post('deadline'),
      'status'           => $this->input->post('status')
    ];
    $this->RequestsModel->update($id, $data);
    redirect('requests/show/' . $id);
  }

  public function delete($id) {
    $this->RequestsModel->delete($id);
    redirect('requests');
  }

  public function change_status($id) {
    $new_status = $this->input->post('status');
    $user_id = $this->session->userdata('user_id');
    $this->RequestsModel->change_status($id, $new_status, $user_id);
    redirect('requests/show/' . $id);
  }

  public function add_progress($id) {
    $percent = $this->input->post('progress_percent');
    $note = $this->input->post('note');
    $user_id = $this->session->userdata('user_id');
    $this->RequestsModel->add_progress($id, $percent, $note, $user_id);
    redirect('requests/show/' . $id);
  }

  public function add_note($id) {
    $note = $this->input->post('note');
    $user_id = $this->session->userdata('user_id');
    $this->RequestsModel->add_note($id, $note, $user_id);
    redirect('requests/show/' . $id);
  }
}
