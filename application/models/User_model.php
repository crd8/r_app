<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

  public function get_all_users() {
    $query = $this->db->get('users');
    return $query->result();
  }

  public function get_logged_in_users() {
    $this->db->where('is_logged_in', TRUE);
    $query = $this->db->get('users');
    return $query->result();
  }

  public function insert_user($data) {
    $this->db->insert('users', $data);
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
    $this->db->update('users', $data);
  }
  
  public function delete_user($id) {
    $this->db->where('id', $id);
    $this->db->delete('users');
  }
  
  public function update_failed_attempts($username, $attempts, $lock_time = null) {
    $this->db->where('username', $username);
    $this->db->update('users', [
      'failed_attempts' => $attempts,
      'lock_time' => $lock_time
    ]);
  }
}
?>