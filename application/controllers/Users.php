<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->model('Department_model');
    $this->load->library('session');
    $this->load->helper('cookie');
    $this->load->library('form_validation');
    $this->load->model('Permission_model');
  }

  public function index() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $userPermissions = $this->session->userdata('permissions');
    if (!$userPermissions || !is_array($userPermissions)) {
      redirect('errors/error_403');
    }

    $user_list_permission_id = $this->Permission_model->get_permission_id('user list');
    if (!in_array($user_list_permission_id, $userPermissions)) {
      redirect('errors/error_403');
    }

    $data['users'] = $this->User_model->get_all_users();

    $departments = $this->Department_model->get_all_departments();
    $dept_map = [];
    if (!empty($departments)) {
      foreach ($departments as $dept) {
        $dept_map[$dept->id] = $dept->name;
      }
    }
    $data['departments'] = $dept_map;
    $data['user_permissions_map'] = $this->User_model->get_all_user_permissions();

    $this->load->view('users/list_user', $data);
  }

  public function login() {
    if ($this->session->userdata('user_id')) {
      redirect('dashboard');
    }

    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
    $this->output->set_header('Pragma: no-cache');

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

      $session_data = [
        'session_id' => session_id(),
        'user_id'    => $user->id,
        'username'   => $user->username,
        'fullname'   => $user->fullname,
        'email'      => $user->email,
      ];

      $this->session->set_userdata($session_data);

      $this->db->where('id', session_id())->update('ci_sessions', ['user_id' => $user->id]);

      // $this->db->where('user_id IS NULL', null, false)->delete('ci_sessions');

      $permissions = $this->Permission_model->get_user_permissions($user->id);
      $this->session->set_userdata('permissions', array_column($permissions, 'permission_id'));

      // $list_users_permission_id = $this->Permission_model->get_permission_id('list_users');
      // $this->session->set_userdata('list_users_permission_id', $list_users_permission_id);

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
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect('login');
    }
    
    $user_id = $this->session->userdata('user_id');
    if (!$user_id) {
      redirect('login');
    }

    $this->db->where('user_id', $user_id)->delete('ci_sessions');
    // $this->db->where('user_id IS NULL', null, false)->delete('ci_sessions');

    $this->User_model->update_user($user_id, ['is_logged_in' => FALSE]);

    $this->session->sess_destroy();

    redirect('login');
  }

  public function force_logout($id) {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect('dashboard');
    }
    
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $current_user_id = $this->session->userdata('user_id');
    $force_logout_permission_id = $this->Permission_model->get_permission_id('force logout');
    
    if ($current_user_id != $id && !in_array($force_logout_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $user = $this->User_model->get_user_by_id($id);
    if (!$user) {
      show_404();
    }
    if ($user->is_logged_in == FALSE) {
      $this->session->set_flashdata('error', 'User is already logged out or does not exist.');
      redirect('dashboard');
    }

    $this->db->where('user_id', $id)->delete('ci_sessions');
    // $this->db->where('user_id IS NULL', null, false)->delete('ci_sessions');

    $this->User_model->update_user($id, ['is_logged_in' => FALSE]);

    if ($current_user_id == $id) {
      $this->session->sess_destroy();
    }

    $this->session->set_flashdata('success', 'User has been forcibly logged out.');

    redirect('dashboard');
  }

  public function create() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $user_create_permission_id = $this->Permission_model->get_permission_id('user create');
    if (!in_array($user_create_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $data['all_permissions'] = $this->Permission_model->get_all_permissions();
    $data['user_permissions'] = [];
    $data['departments'] = $this->Department_model->get_all_departments(); 

    $this->load->view('users/create_user', $data);
  }

  public function store() {
    $user_create_permission_id = $this->Permission_model->get_permission_id('user create');
    if (!in_array($user_create_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|callback_unique_username');
    $this->form_validation->set_rules('fullname', 'Fullname', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_unique_email');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
    $this->form_validation->set_rules('department', 'Department Name');

    if ($this->form_validation->run() == FALSE) {
      $selected_permissions = $this->input->post('permissions') ?: [];
      $data = [
        'errorMessage'    => 'Failed to create user account. Please fix the errors below.',
        'all_permissions' => $this->Permission_model->get_all_permissions(),
        'user_permissions'=> $selected_permissions,
        'departments'     => $this->Department_model->get_all_departments(),
      ];
      $this->load->view('users/create_user', $data);
    } else {
      $username = $this->input->post('username', TRUE);
      $fullname = $this->input->post('fullname', TRUE);
      $password = $this->input->post('password', TRUE);
      $email = $this->input->post('email', TRUE);
      $department_id = $this->input->post('department', TRUE) ?: NULL;

      $new_user_id = generate_uuid();
      $data = array(
        'id' => $new_user_id,
        'username' => $username,
        'fullname' => $fullname,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'email' => $email,
        'department_id' => $department_id,
      );

      $this->User_model->insert_user($data);
      $permissions = $this->input->post('permissions') ?: [];
      $this->User_model->update_user_permissions($new_user_id, $permissions);
      $this->session->set_flashdata('success', 'User account created successfully');
      redirect('users/list');
    }
  }

  public function unique_username($username, $id = null) {
    $u = $this->User_model->get_user_by_username($username);
    if ($u && $u->id != $id) {
      $this->form_validation->set_message(
        'unique_username',
        'Username already exists'
      );
      return FALSE;
    }
    return TRUE;
  }

  public function unique_email($email, $id = null) {
    $u = $this->User_model->get_user_by_email($email);
    if ($u && $u->id != $id) {
      $this->form_validation->set_message(
        'unique_email',
        'Email already exists'
      );
      return FALSE;
    }
    return TRUE;
  }

  public function edit($id) {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data['user'] = $this->User_model->get_user_by_id($id);
    if (!$data['user']) {
      redirect('errors/error_404');
    }

    $user_edit_permission_id = $this->Permission_model->get_permission_id('user edit');
    if (!in_array($user_edit_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $data['all_permissions'] = $this->Permission_model->get_all_permissions();
    $data['user_permissions'] = $this->User_model->get_user_permissions($id);
    $data['departments'] = $this->Department_model->get_all_departments();
    $this->load->view('users/edit_user', $data);
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

  public function update($id) {
    $user_edit_permission_id = $this->Permission_model->get_permission_id('user edit');
    if (!in_array($user_edit_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $user = $this->User_model->get_user_by_id($id);
    if (! $user) {
      show_404();
    }
  
    $this->form_validation->set_rules('username', 'Username', "required|alpha_numeric|callback_unique_username[{$id}]");
    $this->form_validation->set_rules('fullname', 'Fullname', 'required');
    $this->form_validation->set_rules('email', 'Email', "required|valid_email|callback_unique_email[{$id}]");
    $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');
    $this->form_validation->set_rules('department', 'Department Name');

    if ($this->form_validation->run() == FALSE) {
      $data = [
        'errorMessage'    => 'Failed to update user. Please fix the errors below.',
        'user'            => $user,
        'all_permissions' => $this->Permission_model->get_all_permissions(),
        'user_permissions'=> array_column($this->User_model->get_user_permissions($id),'permission_id'),
        'departments'     => $this->Department_model->get_all_departments(),
      ];
      $this->load->view('users/edit_user', $data);
    } else {
      $username      = $this->input->post('username', TRUE);
      $fullname      = $this->input->post('fullname', TRUE);
      $email         = $this->input->post('email', TRUE);
      $department_id = $this->input->post('department', TRUE) ?: NULL;
      $password      = $this->input->post('password', TRUE);
      $permissions   = $this->input->post('permissions') ?: [];

      $update = [
        'username'      => $username,
        'fullname'      => $fullname,
        'email'         => $email,
        'department_id' => $department_id,
      ];
  
      if (! empty($password)) {
        $update['password'] = password_hash($password, PASSWORD_BCRYPT);
      }

      $this->User_model->update_user($id, $update);
      $this->User_model->update_user_permissions($id, $permissions);
      $this->session->set_flashdata('success', 'User updated successfully');

      redirect('users/list');
    }
  }

  public function delete($id) {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect('errors/error_403');
    }
    
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data['user'] = $this->User_model->get_user_by_id($id);
    if (!$data['user']) {
      redirect('errors/error_404');
    }

    $user_delete_permission_id = $this->Permission_model->get_permission_id('user delete');
    if (!in_array($user_delete_permission_id, $this->session->userdata('permissions'))) {
      redirect('errors/error_403');
    }

    $this->User_model->delete_user($id);
    $this->session->set_flashdata('success', 'User deleted successfully');

    redirect('users/list');
  }
}
?>