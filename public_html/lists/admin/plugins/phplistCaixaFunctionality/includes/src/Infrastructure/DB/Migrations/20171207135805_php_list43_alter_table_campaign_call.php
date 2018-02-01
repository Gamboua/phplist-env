<?php


use Phinx\Migration\AbstractMigration;

class PhpList43AlterTableCampaignCall extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->addColumn('embargo', 'datetime', ['null' => true, 'after' => 'communication_type']);
        $table->addColumn('finish_sending', 'datetime', ['null' => true, 'after' => 'embargo']);
        $table->update();
    }

    public function down()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->removeColumn('embargo');
        $table->removeColumn('finish_sending');
        $table->save();
    }
}
