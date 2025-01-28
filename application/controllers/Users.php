<?php
class Users extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library('session');
    $this->load->helper('cookie');
  }

  public function create() {
    $this->load->view('users/create_user');
  }

  public function store() {
    $data = array(
      'id' => generate_uuid(),
      'username' => $this->input->post('username'),
      'fullname' => $this->input->post('fullname'),
      'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
      'email' => $this->input->post('email'),
    );

    $this->User_model->insert_user($data);
    redirect('users');
  }

  public function login() {
    $this->load->view('users/login_user');
  }

  public function authenticate() {
    $username = $this->input->post('username');
    $password = $this->input->post('password');
    $remember = $this->input->post('remember');

    $user = $this->User_model->get_user_by_username($username);

    if ($user && password_verify($password, $user->password)) {
      $this->session->set_userdata('user_id', $user->id);
      $this->session->set_userdata('username', $user->username);
      $this->session->set_userdata('fullname', $user->fullname);
      $this->session->set_userdata('email', $user->email);

      if ($remember) {
        set_cookie('remember_username', $user->username, 86400 * 30); // 30 days
      } else {
        delete_cookie('remember_username');
      }

      redirect('users/dashboard');
    } else {
      $this->session->set_flashdata('error', 'Invalid username or password');
      $this->session->set_flashdata('username', $username);
      redirect('users/login');
    }
  }

  public function dashboard() {
    if (!$this->session->userdata('user_id')) {
      redirect('users/login');
    }

    $this->load->view('users/dashboard');
  }

  public function logout() {
    $this->session->unset_userdata('user_id');
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('fullname');
    $this->session->sess_destroy();
    redirect('users/login');
  }

  public function edit_profile() {
    if (!$this->session->userdata('user_id')) {
      redirect('users/login');
    }

    $this->load->view('users/edit_profile');
  }

  public function update_profile() {
    if (!$this->session->userdata('user_id')) {
      redirect('users/login');
    }

    $data = array(
      'fullname' => $this->input->post('fullname'),
      'email' => $this->input->post('email'),
    );

    $this->User_model->update_user($this->session->userdata('user_id'), $data);

    $this->session->set_userdata('fullname', $data['fullname']);
    $this->session->set_userdata('email', $data['email']);

    $this->session->set_flashdata('success', 'Profile updated successfully');
    redirect('users/edit_profile');
  }

  public function update_password() {
    if (!$this->session->userdata('user_id')) {
      redirect('users/login');
    }

    $current_password = $this->input->post('current_password');
    $new_password = $this->input->post('new_password');
    $confirm_password = $this->input->post('confirm_password');

    if (!$current_password || !$new_password || !$confirm_password) {
      $this->session->set_flashdata('error', 'All password fields are required');
      redirect('users/edit_profile');
    }

    $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));

    if (!password_verify($current_password, $user->password)) {
      $this->session->set_flashdata('error', 'Current password is incorrect');
      redirect('users/edit_profile');
    }

    if (strlen($new_password) < 6) {
      $this->session->set_flashdata('error', 'New password must be at least 6 characters');
      redirect('users/edit_profile');
    }

    if ($new_password !== $confirm_password) {
      $this->session->set_flashdata('error', 'New password and confirm password do not match');
      redirect('users/edit_profile');
    }

    $data['password'] = password_hash($new_password, PASSWORD_BCRYPT);

    $this->User_model->update_user($this->session->userdata('user_id'), $data);

    $this->session->set_flashdata('success', 'Password updated successfully');
    redirect('users/edit_profile');
  }
}
?>