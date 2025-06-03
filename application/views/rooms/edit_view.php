<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>

<div class="container">
    <h2>Edit Room (ID: <?php echo isset($room['id']) ? html_escape($room['id']) : ''; ?>)</h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="flash-message flash-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="flash-message flash-error"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <?php if (validation_errors()): ?>
        <div class="flash-message flash-error">
            <?php echo validation_errors(); ?>
        </div>
    <?php endif; ?>

    <?php
    $room_id = isset($room['id']) ? $room['id'] : '';
    $room_number = isset($room['room_number']) ? $room['room_number'] : '';
    $current_status = isset($room['status']) ? $room['status'] : 'dirty';
    $notes = isset($room['notes']) ? $room['notes'] : '';
    $last_cleaned_at = isset($room['last_cleaned_at']) ? $room['last_cleaned_at'] : '';

    echo form_open('rooms/edit/' . $room_id);
    ?>

        <div>
            <label for="room_number">Room Number:</label>
            <input type="text" name="room_number" id="room_number" value="<?php echo set_value('room_number', $room_number); ?>">
        </div>

        <div>
            <label for="status">Status:</label>
            <?php
            $status_options = array(
                'dirty' => 'Dirty',
                'clean' => 'Clean',
                'in-progress' => 'In Progress',
                'maintenance' => 'Maintenance'
            );
            echo form_dropdown('status', $status_options, set_value('status', $current_status), 'id="status"');
            ?>
        </div>

        <div>
            <label for="notes">Notes:</label>
            <textarea name="notes" id="notes" rows="4"><?php echo set_value('notes', $notes); ?></textarea>
        </div>

        <div>
            <label for="last_cleaned_at">Last Cleaned At (YYYY-MM-DD HH:MM:SS or leave blank to keep current):</label>
            <input type="text" name="last_cleaned_at" id="last_cleaned_at" value="<?php echo set_value('last_cleaned_at', $last_cleaned_at ? date('Y-m-d H:i:s', strtotime($last_cleaned_at)) : ''); ?>">
            <small>Current: <?php echo $last_cleaned_at ? html_escape(date('Y-m-d H:i:s', strtotime($last_cleaned_at))) : 'Not set'; ?></small><br>
            <small>If changing status to 'Clean', this will be auto-updated if left blank.</small>
        </div>

        <div>
            <input type="submit" value="Update Room" class="btn">
            <a href="<?php echo site_url('rooms/index'); ?>" class="btn btn-default" style="margin-left: 10px;">Cancel</a>
        </div>

    <?php echo form_close(); ?>
</div>

</body>
</html>
