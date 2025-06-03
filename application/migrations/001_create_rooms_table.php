<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_rooms_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 5, // Using 5 as per prompt example, though typically INT doesn't need constraint here
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'room_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => TRUE,
                // NOT NULL is default for fields unless 'null' => TRUE is specified by dbforge add_field
                // However, to be explicit for "Not Null" requirement:
                'null' => FALSE,
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'dirty',
                'null' => FALSE, // Explicitly Not Null
            ),
            'last_cleaned_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ),
            'notes' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            // Following the prompt's example for these two lines:
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('rooms');
    }

    public function down() {
        $this->dbforge->drop_table('rooms');
    }
}
