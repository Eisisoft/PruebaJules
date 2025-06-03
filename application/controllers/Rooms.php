<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rooms extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Room_model');
        $this->load->library('form_validation');
        $this->load->library('session'); // For flash messages
        $this->load->helper('url');
    }

    public function index() {
        $data['rooms'] = $this->Room_model->get_all_rooms();
        // View will be created in a later step
        $this->load->view('rooms/index_view', $data);
    }

    public function add() {
        // Validation rules
        // For is_unique, the table name is 'rooms' and field is 'room_number'
        $this->form_validation->set_rules('room_number', 'Room Number', 'required|is_unique[rooms.room_number]');
        $this->form_validation->set_rules('status', 'Status', 'required');
        // 'notes' and 'last_cleaned_at' are optional

        if ($this->form_validation->run() == FALSE) {
            // View will be created in a later step
            $this->load->view('rooms/add_view');
        } else {
            $data = array(
                'room_number' => $this->input->post('room_number'),
                'status' => $this->input->post('status'),
                'notes' => $this->input->post('notes'),
                // 'last_cleaned_at' is handled by model if status is 'clean', or is optional
            );
            if ($this->input->post('last_cleaned_at')) {
                $data['last_cleaned_at'] = $this->input->post('last_cleaned_at');
            }


            if ($this->Room_model->add_room($data)) {
                $this->session->set_flashdata('success', 'Room added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add room.');
            }
            redirect('rooms/index');
        }
    }

    public function edit($id) {
        $room = $this->Room_model->get_room($id);

        if (!$room) {
            $this->session->set_flashdata('error', 'Room not found.');
            redirect('rooms/index');
            return;
        }

        // Handle unique room_number validation for edit
        $original_room_number = $room['room_number'];
        $new_room_number = $this->input->post('room_number');

        if ($new_room_number != $original_room_number) {
            $this->form_validation->set_rules('room_number', 'Room Number', 'required|is_unique[rooms.room_number]');
        } else {
            $this->form_validation->set_rules('room_number', 'Room Number', 'required');
        }
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['room'] = $room;
            // View will be created in a later step
            $this->load->view('rooms/edit_view', $data);
        } else {
            $update_data = array(
                'room_number' => $this->input->post('room_number'),
                'status' => $this->input->post('status'),
                'notes' => $this->input->post('notes'),
            );
            // Only update last_cleaned_at if status is explicitly set to 'clean' during edit
            // or if a new date is provided. The model's change_status handles this more directly.
            // For a general edit, we might not want to auto-update last_cleaned_at unless status changes to clean.
            // The current model->update_room doesn't auto-set last_cleaned_at.
            // If 'last_cleaned_at' is submitted in the form, use it.
            if ($this->input->post('last_cleaned_at')) {
                $update_data['last_cleaned_at'] = $this->input->post('last_cleaned_at');
            } else {
                // If status is being changed to 'clean' and no specific date is given, set it.
                // However, if status remains 'clean', we might not want to overwrite a previous valid date.
                // This logic can be complex. The `change_status` model method is better for this.
                // For this generic edit, let's assume `last_cleaned_at` is either submitted or left alone (null if not submitted).
                 $update_data['last_cleaned_at'] = $this->input->post('last_cleaned_at') ? $this->input->post('last_cleaned_at') : NULL;

            }


            if ($this->Room_model->update_room($id, $update_data)) {
                // Special handling if status was changed to 'clean' using the main edit form
                // This is a bit redundant if `change_status` model method is preferred for status changes.
                // For now, if status is 'clean' in the main edit form, we can call the model's logic.
                if (strtolower($update_data['status']) === 'clean' && $room['status'] !== 'clean') {
                     $this->Room_model->change_status($id, 'clean'); // This will also update last_cleaned_at
                }
                $this->session->set_flashdata('success', 'Room updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update room.');
            }
            redirect('rooms/index');
        }
    }

    public function delete($id) {
        if ($this->Room_model->delete_room($id)) {
            $this->session->set_flashdata('success', 'Room deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete room.');
        }
        redirect('rooms/index');
    }

    public function update_status($id, $status) {
        // Validate status
        $allowed_statuses = array('clean', 'dirty', 'in-progress', 'maintenance');
        $validated_status = strtolower(urldecode($status)); // Decode URL encoded status and lowercase

        if (!in_array($validated_status, $allowed_statuses)) {
            $this->session->set_flashdata('error', 'Invalid status provided.');
            redirect('rooms/index');
            return;
        }

        if ($this->Room_model->change_status($id, $validated_status)) {
            $this->session->set_flashdata('success', "Room status updated to '{$validated_status}'.");
        } else {
            $this->session->set_flashdata('error', 'Failed to update room status.');
        }
        redirect('rooms/index');
    }
}
