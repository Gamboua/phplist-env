
<?php use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLog;
/** @var \phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLog[] $clientsNoEmail */ ?>

<?php if (!empty($_SESSION['action_result'])): ?>
    <div class="actionresult"><?= $_SESSION['action_result']; ?></div>
    <?php unset($_SESSION['action_result']); ?>
<?php endif; ?>

<div class="panel">
	<div class="searchdiv">
    	<form method="post" action="<?= PageURL2("clientsnoemail&action=filter") ?>" id="searchform">
    		<input type="text" name="search" class="searchinputs" id="searchinput" placeholder="O que deseja buscar?">
    		<label>Tipo:</label>
    		<select name="type" name="type" id="searchTypeSelect" class="searchselect">
    			<option value="">SELECIONE</option>
    			<option value="no_cliente">NOME DO CLIENTE</option>
    			<option value="nu_modalidade">Nº FUNDO</option>
    			<option value="nu_agencia">Nº AGÊNCIA</option>
                <option value="nu_operacao">Nº OPERAÇÃO</option>
    			<option value="nu_conta">Nº CONTA</option>
    			<option value="de_email_agencia">EMAIL AGÊNCIA</option>
    			<option value="dt_referencia">DATA/REFERÊNCIA</option>
    		</select>
    		<label>Ordenar Por:</label>
    		<select name="order" id="searchOrderSelect" class="searchselect">
    			<option value="">SELECIONE</option>
    			<option value="no_cliente">NOME DO CLIENTE</option>
    			<option value="nu_modalidade">Nº FUNDO</option>
    			<option value="nu_agencia">Nº AGÊNCIA</option>
                <option value="nu_operacao">Nº OPERAÇÃO</option>
    			<option value="nu_conta">Nº CONTA</option>
    			<option value="de_email_agencia">EMAIL AGÊNCIA</option>
    			<option value="dt_referencia">DATA/REFERÊNCIA</option>
    		</select>
    		<input type="submit" value="buscar">
    	</form>
        <form method="post" action="<?= PageURL2("clientsnoemail&action=exportCSV&filter={$filter}&filed={$filed}&order={$order}") ?>" id="exportform">
            <button type="submit" id="exportBtn" > Exportar</button>
        </form>



	</div>
</div>

<hr>


<?php

$myTable = new WebblerListing('CPF/CNPJ do Cotista');
$myTable->usePanel(simplePaging('clientsnoemail', $pageRequest->getStart(), $pageRequest->getTotal(), $pageRequest->getNumberPerPage()));

foreach ($clientsNoEmail as $clientNoEmail) {
        $id = $clientNoEmail->getIdentifier();
        $myTable->addElement($clientNoEmail->getIdentifier(), '', 'CPF/CNPJ do Cotista');
        $myTable->addColumn($id, 'Nome do Cotista',  $clientNoEmail->getName());
        $myTable->addColumn($id, 'Nº Fundo', $clientNoEmail->getModalityNumber());
        $myTable->addColumn($id, 'Nº Agência', $clientNoEmail->getAgencyNumber());
        $myTable->addColumn($id, 'Nº Operação', $clientNoEmail->getOperationNumber());
        $myTable->addColumn($id, 'Nº Conta', $clientNoEmail->getAccountNumber());
        $myTable->addColumn($id, 'Email Agência', $clientNoEmail->getAgencyEmail());
        $data = new DateTime($clientNoEmail->getReferenceDate());
        $myTable->addColumn($id, 'Data', $data->format('d-m-Y'));
}
$needle = '<div class="header"><h2>CPF/CNPJ do Cotista</h2></div>';
$replace = '<div class="header"><h2>Listagem das campanhas da Caixa</h2></div>';
$tableDisplay = str_replace($needle, $replace, $myTable->display());

?>

<?= $tableDisplay; ?>

<style>
label{
	display: inline !important;
}

#searchinput{
	display: inline !important;
	width: 150px !important;
}

.searchselect{
	display: inline !important;
	width: 150px !important;
	padding: 7px !important;
}

#searchform{
	display: inline !important;
}
</style>

