<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_user extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'user_id'    => array(
                'type'              => 'INT',
                'constraint'        => 12,
                'unsigned'          => true,
                'auto_increment'    => true
            ),
            'nickname'   => array(
                'type'              => 'VARCHAR',
                'constraint'        => 30,
                'null'              => false
            ),
            'password'   => array(
                // use password_had with PASSWORD_BCRYPT
                'type'              => 'CHAR',
                'constraint'        => 60,
                'null'              => false
            ),
            'last_login' => array(
                'type'              => 'DATETIME',
                'null'              => true
            ),
            'mail'       => array(
                'type'              => 'TEXT',
                'constraint'        => 256,
                'null'              => true
            ),
        ));
        $this->dbforge->add_key('user_id', true);
        $this->dbforge->create_table('user');
    }

    public function down()
    {
        $this->dbforge->drop_table('user');
    }
}
