<?php
/** @var array $betterStatusNames */
/** @var array $betterEditStatusNames */
/** @var array $communicationTypes */
/** @var array $templates */
/** @var integer $messageId */
/** @var integer $userId */
/** @var array $foundData */
?>

<?php

$foundDataView = new WebblerListing('');
$foundDataView->setElementHeading("Fundo {$foundData['l_name']} - {$foundData['ccc_subject']}");

$element = ucfirst('Grupo');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', $foundData['l_category']);

$element = ucfirst('Fundo');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', $foundData['l_name']);

$element = ucfirst('Assunto');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', $foundData['ccc_subject']);

$element = ucfirst('Mensagem');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', $foundData['ccc_message']);

$element = ucfirst('Template');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', isset($templates[$foundData['ccc_template']]) ? $templates[$foundData['ccc_template']]['title'] : $foundData['ccc_template']);

$element = ucfirst('Template & Mensagem');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', PageLink2("template&messageId={$messageId}&userId={$userId}", 'Ver template com o conteúdo ...'));

$element = ucfirst('Tipo de Comunicado');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', isset($communicationTypes[$foundData['ccc_communication_type']]) ? $communicationTypes[$foundData['ccc_communication_type']] : $foundData['ccc_communication_type']);

$element = ucfirst('Início do Envio da Convocação');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', (new DateTime($foundData['ccc_embargo']))->format('d/m/Y - H:m'));

$element = ucfirst('Término do Envio da Convocação');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', (new DateTime($foundData['ccc_finish_sending']))->format('d/m/Y - H:m'));

$element = ucfirst('Situação da Convocaçao');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', isset($betterEditStatusNames[$foundData['ccc_status']]) ? $betterEditStatusNames[$foundData['ccc_status']] : $foundData['ccc_status']);

$element = ucfirst('Número da Conta do Cliente');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', $foundData['clif_account_number']);

$element = ucfirst('CPF/CNPJ do Cliente');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', $foundData['uua1_value']);

$element = ucfirst('E-Mail do Cliente');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', $foundData['uu_email']);

$element = ucfirst('Data de Entrada p/ Envio');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', (new DateTime($foundData['um_entered']))->format('d/m/Y - H:m'));

$element = ucfirst('Nº de Tentativas');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', $foundData['uu_bouncecount']);

$element = ucfirst('Situação');
$foundDataView->addElement($element);
$foundDataView->addColumn($element, '', isset($betterStatusNames[$foundData['um_status']]) ? $betterStatusNames[$foundData['um_status']] : $foundData['um_status']);

?>

<?= PageLinkButton('analyticalreport', 'Voltar p/ relatório de envio'); ?>

<h3>Detalhes do Envio</h3>
<?= $foundDataView->display(); ?>
