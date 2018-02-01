<?php

use Phinx\Migration\AbstractMigration;

class PhpList23CreateTableCampaignCallAttribute extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_campaign_call_attribute', array(
            'id' => false,
            'primary_key' => array(
                'campaign_call_id',
                'list_id',
                'name',
            )
        ));

        $table->addColumn('campaign_call_id', 'integer', array('null' => false));
        $table->addColumn('list_id', 'integer', array('null' => false));
        $table->addColumn('name', 'string', array('null' => false));
        $table->addColumn('value', 'string', array('null' => true));

        $table->addForeignKey('campaign_call_id', 'phplist_caixa_campaign_call', 'id');
        $table->addForeignKey('list_id', 'phplist_list', 'id');

        $table->save();
    }

    public function down()
    {
        $this->dropTable('phplist_caixa_campaign_call_attribute');
    }
}
