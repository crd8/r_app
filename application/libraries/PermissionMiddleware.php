<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionMiddleware {

  public function __construct() {
    $this->CI =& get_instance();
    $this->CI->load->model('Permission_model');
  }

  public function check_permission($permission_id) {
    $user_id = $this->CI->session->userdata('user_id');
    if (!$this->CI->Permission_model->has_permission($user_id, $permission_id)) {
      show_error('You do not have permission to access this page.', 403, 'Forbidden');
    }
  }
}