<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Room</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>

<div class="container">
    <h2>Add New Room</h2>

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

    <?php echo form_open('rooms/add'); ?>

        <div>
            <label for="room_number">Room Number:</label>
            <input type="text" name="room_number" id="room_number" value="<?php echo set_value('room_number'); ?>">
        </div>

        <div>
            <label for="status">Status:</label>
            <?php
            $status_options = array(
                '' => 'Select Status', // Added a default empty option
                'dirty' => 'Dirty',
                'clean' => 'Clean',
                'in-progress' => 'In Progress',
                'maintenance' => 'Maintenance'
            );
            // Using set_value for status, defaulting to 'dirty' if nothing is set
            echo form_dropdown('status', $status_options, set_value('status', 'dirty'), 'id="status"');
            ?>
        </div>

        <div>
            <label for="notes">Notes:</label>
            <textarea name="notes" id="notes" rows="4"><?php echo set_value('notes'); ?></textarea>
        </div>

        <div>
            <input type="submit" value="Add Room" class="btn">
            <a href="<?php echo site_url('rooms/index'); ?>" class="btn btn-default" style="margin-left: 10px;">Cancel</a>
        </div>

    <?php echo form_close(); ?>
</div>

</body>
</html>
