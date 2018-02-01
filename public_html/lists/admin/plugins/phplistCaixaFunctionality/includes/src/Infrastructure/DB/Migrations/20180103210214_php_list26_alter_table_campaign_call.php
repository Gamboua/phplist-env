<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class PhpList26AlterTableCampaignCall extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->addColumn('template_content', 'blob', array('limit' => MysqlAdapter::BLOB_LONG));
        $table->update();
    }

    public function down()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->removeColumn('template_content');
        $table->save();
    }
}
