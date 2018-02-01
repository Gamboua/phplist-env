<?php

use Phinx\Migration\AbstractMigration;

class PhpList23CreateTableCampaignCall extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_campaign_call');

        $table->addColumn('subject', 'string', array('null' => false));
        $table->addColumn('fromfield', 'string', array('null' => false));
        $table->addColumn('message', 'text', array('null' => false));
        $table->addColumn('template', 'integer', array('null' => false));

        $table->addForeignKey('template', 'phplist_template', 'id');

        $table->save();
    }

    public function down()
    {
        $this->dropTable('phplist_caixa_campaign_call');
    }
}
