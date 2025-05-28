<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Users extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model('UserModel');
    $this->load->model('DepartmentModel');
    $this->load->library('session');
    $this->load->helper('cookie');
    $this->load->library('form_validation');
    $this->load->model('PermissionModel');
  }

  public function index() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $userPermissions = $this->session->userdata('permissions');
    if (!$userPermissions || !is_array($userPermissions)) {
      redirect(ROUTE_ERROR_403);
    }

    $user_list_permission_id = $this->PermissionModel->get_permission_id('user list');
    if (!in_array($user_list_permission_id, $userPermissions)) {
      redirect(ROUTE_ERROR_403);
    }

    $data['users'] = $this->UserModel->get_all_users();

    $departments = $this->DepartmentModel->get_all_departments();
    $dept_map = [];
    if (!empty($departments)) {
      foreach ($departments as $dept) {
        $dept_map[$dept->id] = $dept->name;
      }
    }
    $data['departments'] = $dept_map;
    $data['user_permissions_map'] = $this->UserModel->get_all_user_permissions();

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
    $username = $this->input->post('username', true);
    $password = $this->input->post('password', true);
    $remember = $this->input->post('remember', true);

    $user = $this->UserModel->get_user_by_username($username);

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
        $this->UserModel->update_user($user->id, ['failed_attempts' => 0, 'lock_time' => null]);
      }
    }

    if (password_verify($password, $user->password)) {
      $this->UserModel->update_user($user->id, ['failed_attempts' => 0, 'lock_time' => null]);

      $session_data = [
        'session_id' => session_id(),
        'user_id'    => $user->id,
        'username'   => $user->username,
        'fullname'   => $user->fullname,
        'email'      => $user->email,
      ];

      $this->session->set_userdata($session_data);

      $this->db->where('id', session_id())->update('ci_sessions', ['user_id' => $user->id]);

      $permissions = $this->PermissionModel->get_user_permissions($user->id);
      $this->session->set_userdata('permissions', array_column($permissions, 'permission_id'));

      if ($remember) {
        set_cookie('remember_username', $user->username, 86400 * 30);
      } else {
        delete_cookie('remember_username');
      }

      $this->UserModel->update_user($user->id, ['is_logged_in' => true]);

      redirect('dashboard');
    } else {
      $attempts = $user->failed_attempts + 1;
      $lock_time = ($attempts >= 5) ? time() : null;

      $this->UserModel->update_user($user->id, ['failed_attempts' => $attempts, 'lock_time' => $lock_time]);

      $this->session->set_flashdata('error', "Invalid username or password. Attempt $attempts of 5.");
      redirect('login');
    }
  }

  public function dashboard() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }
  
    $data['logged_in_users'] = $this->UserModel->get_logged_in_users();

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

    $this->UserModel->update_user($user_id, ['is_logged_in' => false]);

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
    $force_logout_permission_id = $this->PermissionModel->get_permission_id('force logout');
    
    if ($current_user_id != $id && !in_array($force_logout_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $user = $this->UserModel->get_user_by_id($id);
    if (!$user) {
      show_404();
    }
    if (!$user->is_logged_in) {
      $this->session->set_flashdata('error', 'User is already logged out or does not exist.');
      redirect('dashboard');
    }

    $this->db->where('user_id', $id)->delete('ci_sessions');

    $this->UserModel->update_user($id, ['is_logged_in' => false]);

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

    $user_create_permission_id = $this->PermissionModel->get_permission_id('user create');
    if (!in_array($user_create_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $data['all_permissions'] = $this->PermissionModel->get_all_permissions();
    $data['user_permissions'] = [];
    $data['departments'] = $this->DepartmentModel->get_all_departments();

    $this->load->view('users/create_user', $data);
  }

  public function store() {
    $user_create_permission_id = $this->PermissionModel->get_permission_id('user create');
    if (!in_array($user_create_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|callback_unique_username');
    $this->form_validation->set_rules('fullname', 'Fullname', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_unique_email');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('confirm_password', TEXT_CONFIRM_PASSWORD, 'required|matches[password]');
    $this->form_validation->set_rules('department', 'Department Name');

    if (!$this->form_validation->run()) {
      $selected_permissions = $this->input->post('permissions') ?: [];
      $data = [
        'errorMessage' => 'Failed to create user account. Please fix the errors below.',
        'all_permissions' => $this->PermissionModel->get_all_permissions(),
        'user_permissions' => $selected_permissions,
        'departments' => $this->DepartmentModel->get_all_departments(),
      ];
      $this->load->view('users/create_user', $data);
    } else {
      $username = $this->input->post('username', true);
      $fullname = $this->input->post('fullname', true);
      $password = $this->input->post('password', true);
      $email = $this->input->post('email', true);
      $department_id = $this->input->post('department', true) ?: null;

      $new_user_id = generate_uuid();
      $data = array(
        'id' => $new_user_id,
        'username' => $username,
        'fullname' => $fullname,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'email' => $email,
        'department_id' => $department_id,
      );

      $this->UserModel->insert_user($data);
      $permissions = $this->input->post('permissions') ?: [];
      $this->UserModel->update_user_permissions($new_user_id, $permissions);
      $this->session->set_flashdata('success', 'User account created successfully');
      redirect(ROUTE_USERS_LIST);
    }
  }

  public function unique_username($username, $id = null) {
    $u = $this->UserModel->get_user_by_username($username);
    if ($u && $u->id != $id) {
      $this->form_validation->set_message(
        'unique_username',
        'Username already exists'
      );
      return false;
    }
    return true;
  }

  public function unique_email($email, $user_id = null) {
    $u = $this->UserModel->get_user_by_email($email);
    if ($u && $u->id != $user_id) {
      $this->form_validation->set_message(
        'unique_email',
        'Email already exists'
      );
      return false;
    }
    return true;
  }

  public function validate_current_password($password)
  {
    $user = $this->UserModel->get_user_by_id($this->session->userdata('user_id'));
    if (! password_verify($password, $user->password)) {
      $this->form_validation->set_message('validate_current_password', 'Current password is incorrect');
      return false;
    }
    return true;
  }

  public function edit($id) {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data['user'] = $this->UserModel->get_user_by_id($id);
    if (!$data['user']) {
      redirect('errors/error_404');
    }

    $user_edit_permission_id = $this->PermissionModel->get_permission_id('user edit');
    if (!in_array($user_edit_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $data['all_permissions'] = $this->PermissionModel->get_all_permissions();
    $data['user_permissions'] = $this->UserModel->get_user_permissions($id);
    $data['departments'] = $this->DepartmentModel->get_all_departments();
    $this->load->view('users/edit_user', $data);
  }

  public function edit_profile() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $user_id = $this->session->userdata('user_id');

    $this->form_validation->set_rules('fullname', 'Fullname', 'trim|required|min_length[3]');
    $this->form_validation->set_rules(
      'email',
      'Email',
      'trim|required|valid_email|callback_unique_email['.$user_id.']'
    );

    if ($this->form_validation->run() === false) {
      $data = [
        'errorMessage' => 'Failed update user account. Please fix the errors below.',
        'success_profile' => $this->session->flashdata('success_profile'),
        'success_password' => $this->session->flashdata('success_password'),
      ];
      $this->load->view(VIEW_EDIT_PROFILE, $data);
      return;
    }

    $upd = [
      'fullname' => $this->input->post('fullname', true),
      'email'    => $this->input->post('email', true),
    ];
    $this->UserModel->update_user($user_id, $upd);
    $this->session->set_userdata('fullname', $upd['fullname']);
    $this->session->set_userdata('email',    $upd['email']);
    $this->session->set_flashdata('success_profile', 'Profile updated successfully');

    redirect('profile');
  }

  public function update_profile() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $this->form_validation->set_rules('fullname', 'Fullname', 'trim|required|min_length[3]');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_unique_email['.$this->session->userdata('user_id').']');

    if ($this->form_validation->run() === false) {
      $data['errors'] = [
        'fullname' => form_error('fullname'),
        'email' => form_error('email'),
      ];
      $data['old'] = [
        'fullname' => set_value('fullname'),
        'email'    => set_value('email'),
      ];
      $data['errorToast'] = 'Failed update user account. Please fix the errors below';
      
      $data['success_profile'] = $this->session->flashdata('success_profile');
      $this->load->view(VIEW_EDIT_PROFILE, $data);
      return;
    }
    $upd = [
      'fullname' => $this->input->post('fullname', true),
      'email'    => $this->input->post('email', true),
    ];
    $this->UserModel->update_user($this->session->userdata('user_id'), $upd);
    $this->session->set_userdata($upd);
    $this->session->set_flashdata('success_profile', 'Profile updated successfully');
    redirect('profile');
  }

  public function update_password() {
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $this->form_validation->set_rules('current_password', 'Current Password', 'required|callback_validate_current_password');
    $this->form_validation->set_rules('new_password',     'New Password',     'required|min_length[6]');
    $this->form_validation->set_rules('confirm_password', TEXT_CONFIRM_PASSWORD, 'required|matches[new_password]');

    if ($this->form_validation->run() === false) {
      $data['password_errors'] = [
        'current_password' => form_error('current_password'),
        'new_password'     => form_error('new_password'),
        'confirm_password' => form_error('confirm_password'),
      ];
      $data['errorToast']      = 'Failed update password. Please fix the errors below';
      // agar success_profile tetap tersedia
      $data['success_profile']  = $this->session->flashdata('success_profile');
      $data['success_password'] = '';
      $this->load->view(VIEW_EDIT_PROFILE, $data);
      return;
    }
    $new = $this->input->post('new_password', true);
    $this->UserModel->update_user($this->session->userdata('user_id'), [
      'password' => password_hash($new, PASSWORD_BCRYPT)
    ]);
    $this->session->set_flashdata('success_password', 'Password successfully updated');
    redirect('profile');
  }

  public function update($id) {
    $user_edit_permission_id = $this->PermissionModel->get_permission_id('user edit');
    if (!in_array($user_edit_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $user = $this->UserModel->get_user_by_id($id);
    if (! $user) {
      show_404();
    }
  
    $this->form_validation->set_rules('username', 'Username', "required|alpha_numeric|callback_unique_username[{$id}]");
    $this->form_validation->set_rules('fullname', 'Fullname', 'required');
    $this->form_validation->set_rules('email', 'Email', "required|valid_email|callback_unique_email[{$id}]");
    $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
    $this->form_validation->set_rules('confirm_password', TEXT_CONFIRM_PASSWORD, 'matches[password]');
    $this->form_validation->set_rules('department', 'Department Name');

    if (!$this->form_validation->run()) {
      $data = [
        'errorMessage'    => 'Failed to update user. Please fix the errors below.',
        'user'            => $user,
        'all_permissions' => $this->PermissionModel->get_all_permissions(),
        'user_permissions'=> array_column($this->UserModel->get_user_permissions($id),'permission_id'),
        'departments'     => $this->DepartmentModel->get_all_departments(),
      ];
      $this->load->view('users/edit_user', $data);
    } else {
      $username      = $this->input->post('username', true);
      $fullname      = $this->input->post('fullname', true);
      $email         = $this->input->post('email', true);
      $department_id = $this->input->post('department', true) ?: null;
      $password      = $this->input->post('password', true);
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

      $this->UserModel->update_user($id, $update);
      $this->UserModel->update_user_permissions($id, $permissions);
      $this->session->set_flashdata('success', 'User updated successfully');

      redirect(ROUTE_USERS_LIST);
    }
  }

  public function delete($id) {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect(ROUTE_ERROR_403);
    }
    
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $data['user'] = $this->UserModel->get_user_by_id($id);
    if (!$data['user']) {
      redirect('errors/error_404');
    }

    $user_delete_permission_id = $this->PermissionModel->get_permission_id('user delete');
    if (!in_array($user_delete_permission_id, $this->session->userdata('permissions'))) {
      redirect(ROUTE_ERROR_403);
    }

    $this->UserModel->delete_user($id);
    $this->session->set_flashdata('success', 'User deleted successfully');
    redirect(ROUTE_USERS_LIST);
  }
}
