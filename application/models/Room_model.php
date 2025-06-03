<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load database library
    }

    public function get_all_rooms() {
        $this->db->order_by('room_number', 'ASC');
        $query = $this->db->get('rooms');
        return $query->result_array();
    }

    public function get_room($id) {
        $query = $this->db->get_where('rooms', array('id' => $id));
        return $query->row_array();
    }

    public function get_room_by_number($room_number) {
        $query = $this->db->get_where('rooms', array('room_number' => $room_number));
        return $query->row_array();
    }

    public function add_room($data) {
        // The 'created_at' and 'updated_at' fields are handled by the database
        // (DEFAULT CURRENT_TIMESTAMP and ON UPDATE CURRENT_TIMESTAMP respectively)
        // as per the migration. So, no need to set them here explicitly
        // unless we want to override them with a specific value.
        // For simplicity and to rely on DB defaults, we can remove manual setting.
        // If the migration did not set these defaults, then the PHP code would be needed.
        // Given the migration does set them, this model will rely on that.

        // $this->db->insert() returns TRUE on success, FALSE on failure.
        // insert_id() should be called after a successful insert.
        $this->db->insert('rooms', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id > 0 ? $insert_id : FALSE; // Return ID or FALSE
    }

    public function update_room($id, $data) {
        // The 'updated_at' field is handled by the database
        // (ON UPDATE CURRENT_TIMESTAMP) as per the migration.
        // No need to manually set it here if relying on DB default.
        $this->db->where('id', $id);
        return $this->db->update('rooms', $data); // Returns TRUE/FALSE
    }

    public function delete_room($id) {
        return $this->db->delete('rooms', array('id' => $id)); // Returns TRUE/FALSE
    }

    public function change_status($id, $status) {
        $data = array('status' => $status);
        if (strtolower($status) === 'clean') { // Use strict comparison
            $data['last_cleaned_at'] = date('Y-m-d H:i:s');
        }
        // The 'updated_at' field is handled by the database.
        $this->db->where('id', $id);
        return $this->db->update('rooms', $data); // Returns TRUE/FALSE
    }
}
