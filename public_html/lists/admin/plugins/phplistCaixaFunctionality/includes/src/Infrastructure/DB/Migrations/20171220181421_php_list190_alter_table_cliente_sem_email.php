<?php

use Phinx\Migration\AbstractMigration;

class PhpList190AlterTableClienteSemEmail extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_cliente_sem_email');
        $table->addColumn('de_email_cliente', 'string', ['null' => true, 'after' => 'nu_modalidade']);
        $table->update();
    }

    public function down()
    {
        $table = $this->table('phplist_caixa_cliente_sem_email');
        $table->removeColumn('de_email_cliente');
        $table->save();
    }
}
