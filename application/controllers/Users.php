<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
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

    $list_users_permission_id = $this->Permission_model->get_permission_id('user list');

    if (!in_array($list_users_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $data['users'] = $this->User_model->get_all_users();
    $this->load->view('users/list_user', $data);
  }

  public function create() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $list_users_permission_id = $this->Permission_model->get_permission_id('user create');

    if (!in_array($list_users_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->load->view('users/create_user');
  }

  public function store() {
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('fullname', 'Fullname', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('error', validation_errors());
      $this->load->view('users/create_user');
    } else {
      $username = $this->input->post('username');
      $fullname = $this->input->post('fullname');
      $password = $this->input->post('password');
      $email = $this->input->post('email');

      if ($this->User_model->get_user_by_username($username)) {
        $this->session->set_flashdata('error', 'Username already exists');
        $this->load->view('users/create_user');
      } elseif ($this->User_model->get_user_by_email($email)) {
        $this->session->set_flashdata('error', 'Email already exists');
        $this->load->view('users/create_user');
      } else {
        $data = array(
          'id' => generate_uuid(),
          'username' => $username,
          'fullname' => $fullname,
          'password' => password_hash($password, PASSWORD_BCRYPT),
          'email' => $email,
        );

        $this->User_model->insert_user($data);
        $this->session->set_flashdata('success', 'User created successfully');
        redirect('users/create');
      }
    }
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

      $permissions = $this->Permission_model->get_user_permissions($user->id);
      $permission_ids = array_column($permissions, 'permission_id');
      $this->session->set_userdata('permissions', $permission_ids);

      $list_users_permission_id = $this->Permission_model->get_permission_id('list_users');
      $this->session->set_userdata('list_users_permission_id', $list_users_permission_id);

      if ($remember) {
        set_cookie('remember_username', $user->username, 86400 * 30); // 30 hari
      } else {
        delete_cookie('remember_username');
      }

      redirect('dashboard');
    } else {
      $this->session->set_flashdata('error', 'Invalid username or password');
      $this->session->set_flashdata('username', $username);
      redirect('login');
    }
  }

  public function dashboard() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $this->load->view('users/dashboard');
  }

  public function logout() {
    $this->session->unset_userdata('user_id');
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('fullname');
    $this->session->sess_destroy();
    redirect('login');
  }

  public function edit_profile() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $this->load->view('users/edit_profile');
  }

  public function update_profile() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data = array(
      'fullname' => $this->input->post('fullname'),
      'email' => $this->input->post('email'),
    );

    $this->User_model->update_user($this->session->userdata('user_id'), $data);

    $this->session->set_userdata('fullname', $data['fullname']);
    $this->session->set_userdata('email', $data['email']);

    $this->session->set_flashdata('success', 'Profile updated successfully');
    redirect('profile');
  }

  public function update_password() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $current_password = $this->input->post('current_password');
    $new_password = $this->input->post('new_password');
    $confirm_password = $this->input->post('confirm_password');

    if (!$current_password || !$new_password || !$confirm_password) {
      $this->session->set_flashdata('error', 'All password fields are required');
      redirect('profile');
    }

    $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));

    if (!password_verify($current_password, $user->password)) {
      $this->session->set_flashdata('error', 'Current password is incorrect');
      redirect('profile');
    }

    if (strlen($new_password) < 6) {
      $this->session->set_flashdata('error', 'New password must be at least 6 characters');
      redirect('profile');
    }

    if ($new_password !== $confirm_password) {
      $this->session->set_flashdata('error', 'New password and confirm password do not match');
      redirect('profile');
    }

    $data['password'] = password_hash($new_password, PASSWORD_BCRYPT);

    $this->User_model->update_user($this->session->userdata('user_id'), $data);

    $this->session->set_flashdata('success', 'Password updated successfully');
    redirect('profile');
  }
}
?>