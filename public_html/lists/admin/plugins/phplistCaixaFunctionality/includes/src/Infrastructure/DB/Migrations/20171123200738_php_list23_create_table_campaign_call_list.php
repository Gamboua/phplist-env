<?php

use Phinx\Migration\AbstractMigration;

class PhpList23CreateTableCampaignCallList extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_campaign_call_list', array(
            'id' => false,
            'primary_key' => array(
                'campaign_call_id',
                'list_id',
            )
        ));

        $table->addColumn('campaign_call_id', 'integer', array('null' => false));
        $table->addColumn('list_id', 'integer', array('null' => false));
        $table->addColumn('message_id', 'integer', array('null' => true));

        $table->addForeignKey('campaign_call_id', 'phplist_caixa_campaign_call', 'id');
        $table->addForeignKey('list_id', 'phplist_list', 'id');

        $table->addIndex(['message_id'], array('unique' => true));

        $table->save();
    }

    public function down()
    {
        $this->dropTable('phplist_caixa_campaign_call_list');
    }
}
