<?php

use Phinx\Migration\AbstractMigration;

class PhpList44AlterTableCampaignCall extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->addColumn('communication_type', 'string', ['after' => 'template']);
        $table->update();
    }

    public function down()
    {
        $table = $this->table('phplist_caixa_campaign_call');
        $table->removeColumn('communication_type');
        $table->save();
    }
}
