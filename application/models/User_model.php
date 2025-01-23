<?php
class User_model extends CI_Model {

  public function insert_user($data) {
    $this->db->insert('users', $data);
  }

  public function get_user_by_username($username) {
    $this->db->where('username', $username);
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
}
?>