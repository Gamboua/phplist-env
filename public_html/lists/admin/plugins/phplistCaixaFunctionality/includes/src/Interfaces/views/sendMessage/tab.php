<?php
/** @var \phplist\Caixa\Functionality\Interfaces\Shared\MessageTab $messageTab */
?>

<input type="hidden" name="caixaTabSubmitted" value="yes">

<div class="alwaysSendToNotViewed">
    <label>Reenvio de mensagens não vistas</label>
    <input type="checkbox" name="alwaysSendToNotViewed" <?= boolval($messageTab->alwaysSendToNotViewed) ? 'checked' : ''; ?>>
    Sim, eu quero que sejam reenviadas mensagens não vistas
</div>

<hr>
