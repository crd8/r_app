<?php
defined('BASEPATH') || exit('No direct script access allowed');

class DepartmentModel extends CI_Model {

  public function get_all_departments() {
    $this->db->order_by('created_at', 'DESC');
    $query = $this->db->get('departments');
    return $query->result();
  }

  public function get_department_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('departments');
    return $query->row();
  }

  public function get_department_by_name($name) {
    $this->db->where('name', $name);
    $query = $this->db->get('departments');
    return $query->row();
  }

  public function insert_department($data) {
    $this->db->insert('departments', $data);
  }

  public function update_department($id, $data) {
    $this->db->where('id', $id);
    $this->db->update('departments', $data);
  }

  public function delete_department($id) {
    $this->db->where('id', $id);
    $this->db->delete('departments');
  }
}
