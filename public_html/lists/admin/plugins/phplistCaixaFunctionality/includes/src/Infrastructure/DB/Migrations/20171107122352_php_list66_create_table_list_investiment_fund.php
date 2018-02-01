<?php

use Phinx\Migration\AbstractMigration;

class PhpList66CreateTableListInvestimentFund extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_list_investment_fund', array(
            'id' => false,
            'primary_key' => array(
                'user_id',
                'list_id',
            )
        ));

        $table->addColumn('user_id', 'integer', array('null' => false));
        $table->addColumn('list_id', 'integer', array('null' => false));
        $table->addColumn('reference_date', 'date', array('null' => false));
        $table->addColumn('agency_number', 'integer', array('null' => false));
        $table->addColumn('operation_number', 'integer', array('null' => false));
        $table->addColumn('account_number', 'integer', array('null' => false));
        $table->addColumn('agency_email', 'string', array('null' => true));
        $table->addColumn('modality_number', 'integer', array('null' => false));

        $table->addForeignKey('user_id', 'phplist_user_user', 'id');
        $table->addForeignKey('list_id', 'phplist_list', 'id');

        $table->save();
    }

    public function down()
    {
        $this->dropTable('phplist_caixa_list_investment_fund');
    }
}
