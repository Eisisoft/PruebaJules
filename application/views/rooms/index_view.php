<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Management</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>

<div class="container">
    <h2>Room List</h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="flash-message flash-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="flash-message flash-error"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <p><a href="<?php echo site_url('rooms/add'); ?>" class="btn">Add New Room</a></p>

    <?php if (!empty($rooms)): ?>
        <table>
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Status</th>
                    <th>Last Cleaned At</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?php echo html_escape($room['room_number']); ?></td>
                        <td><?php echo ucfirst(html_escape($room['status'])); // Capitalize status ?></td>
                        <td><?php echo $room['last_cleaned_at'] ? html_escape(date('Y-m-d H:i:s', strtotime($room['last_cleaned_at']))) : 'N/A'; // Format date ?></td>
                        <td><?php echo nl2br(html_escape($room['notes'])); ?></td>
                        <td>
                            <a href="<?php echo site_url('rooms/edit/' . $room['id']); ?>" class="btn btn-default" style="background-color: #f0ad4e; margin-right: 5px;">Edit</a>
                            <a href="<?php echo site_url('rooms/delete/' . $room['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this room?');" style="margin-right: 5px;">Delete</a>
                            <br style="margin-bottom: 5px;"> <!-- Quick break for status links -->
                            Quick Status:
                            <a href="<?php echo site_url('rooms/update_status/' . $room['id'] . '/clean'); ?>">Clean</a> |
                            <a href="<?php echo site_url('rooms/update_status/' . $room['id'] . '/dirty'); ?>">Dirty</a> |
                            <a href="<?php echo site_url('rooms/update_status/' . $room['id'] . '/in-progress'); ?>">In Prog.</a> |
                            <a href="<?php echo site_url('rooms/update_status/' . $room['id'] . '/maintenance'); ?>">Maint.</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No rooms found.</p>
    <?php endif; ?>
</div>

</body>
</html>
