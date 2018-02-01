<?php


use Phinx\Migration\AbstractMigration;

class PhpList10AlterTableCampaignCall extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->addColumn('user_id', 'integer', ['null' => true, 'after' => 'status']);
        $table->addColumn('user_email', 'string', ['null' => true, 'after' => 'user_id']);
        $table->update();
    }

    public function down()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->removeColumn('user_id');
        $table->removeColumn('user_email');
        $table->save();
    }
}
