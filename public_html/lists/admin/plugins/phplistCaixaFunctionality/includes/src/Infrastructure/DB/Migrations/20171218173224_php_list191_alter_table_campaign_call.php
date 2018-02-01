<?php


use Phinx\Migration\AbstractMigration;

class PhpList191AlterTableCampaignCall extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->addColumn('status', 'string', ['null' => false, 'after' => 'finish_sending']);
        $table->update();
    }

    public function down()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->removeColumn('status');
        $table->save();
    }
}
