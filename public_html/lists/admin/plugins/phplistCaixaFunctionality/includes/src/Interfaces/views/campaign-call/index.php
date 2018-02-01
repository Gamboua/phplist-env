<?php /** @var array $communicationTypes */ ?>
<?php /** @var array $availableStatus */ ?>
<?php /** @var \phplist\Caixa\Functionality\Infrastructure\DB\PageRequest $pageRequest */ ?>
<?php /** @var \phplist\Caixa\Functionality\Domain\Model\CampaignCall[] $campaignCalls */ ?>

<?php if (!empty($_SESSION['action_result'])): ?>
    <div class="actionresult"><?= $_SESSION['action_result']; ?></div>
    <?php unset($_SESSION['action_result']); ?>
<?php endif; ?>

<div class="actions">
    <div class="fright">
        <?= PageLinkActionButton('campaigncall', 'Criar novo agendamento', 'action=create'); ?>
    </div>
    <div class="clear"></div>
</div>

<?php
$table = new WebblerListing('#');
$table->usePanel(simplePaging('campaigncall', $pageRequest->getStart(), $pageRequest->getTotal(), $pageRequest->getNumberPerPage()));
foreach ($campaignCalls as $campaignCall) {
    $table->addElement($campaignCall->getId(), PageURL2("campaigncall&action=edit&id={$campaignCall->getId()}"));
    $table->addColumn($campaignCall->getId(), 'Assunto', $campaignCall->getSubject(), PageURL2("campaigncall&action=edit&id={$campaignCall->getId()}"));
    $table->addColumn($campaignCall->getId(), 'Data Envio', date('d/m/Y H:i:s', strtotime($campaignCall->getEmbargo())));
    $table->addColumn($campaignCall->getId(), 'Tipo', $communicationTypes[$campaignCall->getCommunicationType()]);
    $table->addColumn($campaignCall->getId(), 'Edição', $availableStatus[$campaignCall->getStatus()]);
}
$needle = '<div class="header"><h2>#</h2></div>';
$replace = '<div class="header"><h2>Agendamento de envio de documentos</h2></div>';
$tableDisplay = str_replace($needle, $replace, $table->display());
?>

<?= $tableDisplay; ?>
