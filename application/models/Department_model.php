<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department_model extends CI_Model {

  public function get_all_departments() {
    $query = $this->db->get('departments');
    return $query->result();
  }

  public function get_department_by_name($name) {
    $this->db->where('name', $name);
    $query = $this->db->get('departments');
    return $query->row();
  }

  public function insert_department($data) {
    $this->db->insert('departments', $data);
  }
}
?>