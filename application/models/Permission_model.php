<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_model extends CI_Model {
  
  public function get_all_permissions() {
    $query = $this->db->get('permissions');
    return $query->result();
  }

  public function get_permission_by_name($name) {
    $this->db->where('name', $name);
    $query = $this->db->get('permissions');
    return $query->row();
  }

  public function get_permission_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('permissions');
    return $query->row();
  }

  public function has_permission($user_id, $permission_id) {
    $this->db->where('user_id', $user_id);
    $this->db->where('permission_id', $permission_id);
    $query = $this->db->get('user_permissions');
    return $query->num_rows() > 0;
  }

  public function get_user_permissions($user_id) {
    $this->db->select('permission_id');
    $this->db->where('user_id', $user_id);
    $query = $this->db->get('user_permissions');
    return $query->result_array();
  }

  public function get_permission_id($permission_name) {
    $this->db->select('id');
    $this->db->where('name', $permission_name);
    $query = $this->db->get('permissions');
    if ($query->num_rows() > 0) {
      return $query->row()->id;
    }
    return null;
  }

  public function get_permission_name($permission_id) {
    $this->db->select('name');
    $this->db->where('id', $permission_id);
    $query = $this->db->get('permissions');
    if ($query->num_rows() > 0) {
      return $query->row()->name;
    }
    return null;
  }

  public function insert_permission($data) {
    $this->db->insert('permissions', $data);
  }

  public function update_permission($id, $data) {
    $this->db->where('id', $id);
    $this->db->update('permissions', $data);
  }
  
  public function delete_permission($id) {
    $this->db->where('id', $id);
    $this->db->delete('permissions');
  }
}
?>