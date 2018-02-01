<?php

use Phinx\Migration\AbstractMigration;

class PhpList66InsertIntoUserAttribute extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_user_attribute');
        $table->insert(array(
            array(
                'id'   => 1,
                'name' => 'caixa_cliente_identificador',
                'type' => 'textline'
            ),
            array(
                'id'   => 2,
                'name' => 'caixa_cliente_nome',
                'type' => 'textline'
            ),
        ));

        $table->saveData();
    }

    public function down()
    {
        $this->execute("DELETE FROM phplist_user_attribute WHERE id = 1");
        $this->execute("DELETE FROM phplist_user_attribute WHERE id = 2");
    }
}
