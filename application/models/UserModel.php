<?php
defined('BASEPATH') || exit('No direct script access allowed');

class UserModel extends CI_Model {

  public function get_all_users() {
    $this->db->order_by('created_at', 'DESC');
    $query = $this->db->get('users');
    return $query->result();
  }

  public function get_all_user_permissions() {
    $this->db->select('user_permissions.user_id, permissions.name');
    $this->db->from('user_permissions');
    $this->db->join('permissions', 'permissions.id = user_permissions.permission_id');
    $query = $this->db->get();
    $results = $query->result();

    $map = [];
    foreach($results as $row) {
      $map[$row->user_id][] = $row->name;
    }
    return $map;
  }

  public function get_logged_in_users() {
    $this->db->where('is_logged_in', true);
    $query = $this->db->get('users');
    return $query->result();
  }

  public function insert_user($data) {
    return $this->db->insert('users', $data);
  }

  public function get_user_by_username($username) {
    $this->db->where('username', $username);
    $query = $this->db->get('users');
    return $query->row();
  }

  public function get_user_by_email($email) {
    $this->db->where('email', $email);
    $query = $this->db->get('users');
    return $query->row();
  }

  public function get_user_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('users');
    return $query->row();
  }

  public function update_user($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('users', $data);
  }
  
  public function delete_user($id) {
    $this->db->trans_start();

    $this->db->where('user_id', $id);
    $this->db->delete('user_permissions');

    $this->db->where('id', $id);
    $this->db->delete('users');

    $this->db->trans_complete();

    return $this->db->trans_status();
  }
  
  public function update_failed_attempts($username, $attempts, $lock_time = null) {
    $this->db->where('username', $username);
    $this->db->update('users', [
      'failed_attempts' => $attempts,
      'lock_time' => $lock_time
    ]);
  }

  public function get_user_permissions($user_id) {
    $this->db->select('permission_id');
    $this->db->from('user_permissions');
    $this->db->where('user_id', $user_id);
    $query = $this->db->get();
    
    $permissions = [];
    foreach($query->result() as $row) {
      $permissions[] = $row->permission_id;
    }
    return $permissions;
  }

  public function update_user_permissions($user_id, $permissions) {
    $this->db->where('user_id', $user_id);
    $this->db->delete('user_permissions');
    
    if (!empty($permissions)) {
      $data = [];
      foreach ($permissions as $permission_id) {
        $data[] = [
          'user_id' => $user_id,
          'permission_id' => $permission_id
        ];
      }
      $this->db->insert_batch('user_permissions', $data);
    }
  }
}
