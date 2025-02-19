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
      $username = $this->input->post('username', TRUE);
      $fullname = $this->input->post('fullname', TRUE);
      $password = $this->input->post('password', TRUE);
      $email = $this->input->post('email', TRUE);

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
        redirect('users/list');
      }
    }
  }

  public function edit($id) {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $edit_users_permission_id = $this->Permission_model->get_permission_id('user edit');

    if (!in_array($edit_users_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $data['user'] = $this->User_model->get_user_by_id($id);
    $this->load->view('users/edit_user', $data);
  }

  public function update($id) {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }
  
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('fullname', 'Fullname', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
  
    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('error', validation_errors());
      redirect('users/edit/' . $id);
    } else {
      $username = $this->input->post('username', TRUE);
      $fullname = $this->input->post('fullname', TRUE);
      $email = $this->input->post('email', TRUE);
      $password = $this->input->post('password', TRUE);
      $confirm_password = $this->input->post('confirm_password');
  
      $data = array(
        'username' => $username,
        'fullname' => $fullname,
        'email' => $email,
      );
  
      if (!empty($password)) {
        if ($password !== $confirm_password) {
          $this->session->set_flashdata('error', 'Password and Confirm Password do not match');
          redirect('users/edit/' . $id);
        }
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
      }
  
      $this->User_model->update_user($id, $data);
      $this->session->set_flashdata('success', 'User updated successfully');
      redirect('users/list');
    }
  }

  public function delete($id) {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $delete_users_permission_id = $this->Permission_model->get_permission_id('user delete');

    if (!in_array($delete_users_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->User_model->delete_user($id);
    $this->session->set_flashdata('success', 'User deleted successfully');
    redirect('users');
  }

  public function login() {
    $this->load->view('users/login_user');
  }

  public function authenticate() {
    $username = $this->input->post('username', TRUE);
    $password = $this->input->post('password', TRUE);
    $remember = $this->input->post('remember', TRUE);

    $user = $this->User_model->get_user_by_username($username);

    if (!$user) {
      $this->session->set_flashdata('error', 'Invalid username or password');
      redirect('login');
    }

    if ($user->failed_attempts >= 5) {
      $lock_duration = 120;
      $remaining_time = ($user->lock_time + $lock_duration) - time();

      if ($remaining_time > 0) {
        $this->session->set_flashdata('error', 'Your account is locked. Try again in ' . ceil($remaining_time / 60) . ' minutes.');
        redirect('login');
      } else {
        $this->User_model->update_user($user->id, ['failed_attempts' => 0, 'lock_time' => null]);
      }
    }

    if (password_verify($password, $user->password)) {
      $this->User_model->update_user($user->id, ['failed_attempts' => 0, 'lock_time' => null]);

      $this->session->set_userdata([
        'user_id' => $user->id,
        'username' => $user->username,
        'fullname' => $user->fullname,
        'email' => $user->email
      ]);

      $permissions = $this->Permission_model->get_user_permissions($user->id);
      $this->session->set_userdata('permissions', array_column($permissions, 'permission_id'));

      $list_users_permission_id = $this->Permission_model->get_permission_id('list_users');
      $this->session->set_userdata('list_users_permission_id', $list_users_permission_id);

      if ($remember) {
        set_cookie('remember_username', $user->username, 86400 * 30);
      } else {
        delete_cookie('remember_username');
      }

      $this->User_model->update_user($user->id, ['is_logged_in' => TRUE]);

      redirect('dashboard');
    } else {
      $attempts = $user->failed_attempts + 1;
      $lock_time = ($attempts >= 5) ? time() : null;

      $this->User_model->update_user($user->id, ['failed_attempts' => $attempts, 'lock_time' => $lock_time]);

      $this->session->set_flashdata('error', "Invalid username or password. Attempt $attempts of 5.");
      redirect('login');
    }
  }


  public function dashboard() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }
  
    $data['logged_in_users'] = $this->User_model->get_logged_in_users();
    $this->load->view('users/dashboard', $data);
  }

  public function logout() {
    $user_id = $this->session->userdata('user_id');
    $this->session->unset_userdata('user_id');
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('fullname');
    $this->session->sess_destroy();
  
    $this->User_model->update_user($user_id, ['is_logged_in' => FALSE]);
  
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
      'fullname' => $this->input->post('fullname', TRUE),
      'email' => $this->input->post('email', TRUE),
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

    $current_password = $this->input->post('current_password', TRUE);
    $new_password = $this->input->post('new_password', TRUE);
    $confirm_password = $this->input->post('confirm_password', TRUE);

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