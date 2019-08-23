
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_game_players extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'game_player_id'    => array(
                'type'              => 'INT',
                'constraint'        => 12,
                'unsigned'          => true,
                'auto_increment'    => true
            ),
            'user_id'           => array(
                'type'              => 'INT',
                'constraint'        => 12,
                'unsigned'          => true,
            ),
            'game_id'              => array(
                'type'              => 'INT',
                'constraint'        => 8,
                'unsigned'          => true
            ),
            'player_id'         => array(
                'type'              => 'VARCHAR',
                'constraint'        => 30
            ),
            'game_nickname'     => array(
                'type'              => 'VARCHAR',
                'constraint'        => 30
            ),
            'group_id'          => array(
                'type'              => 'INT',
                'constraint'        => 12,
                'unsigned'          => true,
            )
        ));
        $this->dbforge->add_key('game_player_id', true);
        $this->dbforge->create_table('user');
    }

    public function down()
    {
        $this->dbforge->drop_table('user');
    }
}
