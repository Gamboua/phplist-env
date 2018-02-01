<?php

use Phinx\Migration\AbstractMigration;

class PhpList75CreateTableCustomerWithoutEmail extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_cliente_sem_email', array(
            'id' => false,
            'primary_key' => array(
                'co_identificador_cliente',
                'nu_modalidade',
            )
        ));

        $table->addColumn('co_identificador_cliente', 'string', array('null' => false));
        $table->addColumn('no_cliente', 'string', array('null' => false));
        $table->addColumn('dt_referencia', 'date', array('null' => false));
        $table->addColumn('nu_agencia', 'integer', array('null' => false));
        $table->addColumn('nu_operacao', 'integer', array('null' => false));
        $table->addColumn('nu_conta', 'integer', array('null' => false));
        $table->addColumn('de_email_agencia', 'string', array('null' => true));
        $table->addColumn('nu_modalidade', 'integer', array('null' => false));

        $table->save();
    }

    public function down()
    {
        $this->dropTable('phplist_caixa_cliente_sem_email');
    }
}
