<?php
/** @var integer $messageId */
/** @var integer $userId */
/** @var string $template */
?>

<?= PageLinkButton("analyticalreport&action=view&messageId={$messageId}&userId={$userId}", 'Voltar p/ visualização detalhada do envio'); ?>

<h3>Visualização Detalhada do E-Mail Enviado</h3>

<div class='panel'>
    <div class="panel__template">
        <?= $template; ?>
    </div>
</div>

<style>
    .panel__template {
        background: #fff;
        padding: 5px 10px;
    }
</style>
