<?php
defined('BASEPATH') || exit('No direct script access allowed');

class RequestModel extends CI_Model {

  public function get_by_id($id) {
    $this->db->select('
      requests.*,
      requester.username AS requester,
      receiver.username AS receiver,
      departments.name   AS department
    ');
    $this->db->from('requests');
    $this->db->join('users AS requester', 'requests.requester_user_id = requester.id', 'left');
    $this->db->join('users AS receiver',  'requests.receiver_user_id  = receiver.id', 'left');
    $this->db->join('departments',        'requests.department_id     = departments.id', 'left');
    $this->db->where('requests.id', $id);
    return $this->db->get()->row();
  }

  public function get_my_requests($user_id) {
    $this->db->from('requests');
    $this->db->group_start();
    $this->db->where('requester_user_id', $user_id);
    $this->db->or_where('receiver_user_id', $user_id);
    $this->db->group_end();
    $this->db->order_by('created_at', 'DESC');
    return $this->db->get()->result();
  }

  public function get_dept_requests($dept_id) {
    $this->db->from('requests');
    $this->db->where('department_id', $dept_id);
    $this->db->order_by('created_at', 'DESC');
    return $this->db->get()->result();
  }

  public function create($data) {
    $this->db->insert('requests', $data);
  }

  public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('requests', $data);
  }

  public function delete($id) {
    $this->db->where('id', $id);
    return $this->db->delete('requests');
  }

  public function change_status($id, $new_status, $user_id) {
    $this->update($id, ['status' => $new_status]);
    $this->db->insert('req_timelines', [
      'id'               => guid(),
      'request_id'       => $id,
      'user_id'          => $user_id,
      'event_type'       => 'status_change',
      'note'             => null,
      'progress_percent' => null,
      'timeline_date'    => date('Y-m-d')
    ]);
  }

  public function add_progress($id, $percent, $note, $user_id) {
    $this->db->insert('req_timelines', [
      'id'               => guid(),
      'request_id'       => $id,
      'user_id'          => $user_id,
      'event_type'       => 'progress',
      'note'             => $note,
      'progress_percent' => $percent,
      'timeline_date'    => date('Y-m-d')
    ]);
  }

  public function add_note($id, $note, $user_id) {
    $this->db->insert('req_timelines', [
      'id'               => guid(),
      'request_id'       => $id,
      'user_id'          => $user_id,
      'event_type'       => 'note',
      'note'             => $note,
      'progress_percent' => null,
      'timeline_date'    => date('Y-m-d')
    ]);
  }
}
