<?php
/** @var array $betterStatusNames */
/** @var array $communicationTypes */
/** @var \phplist\Caixa\Functionality\Domain\Model\SubscriptionList[] $subscriptionLists */

/** @var \phplist\Caixa\Functionality\Interfaces\Models\AnalyticalReportModel $model */

use phplist\Caixa\Functionality\Domain\Model\AnalyticalReport;

include(dirname(phplistCaixaFunctionality::DIR) . '/date.php');

$analyticalReportFormPanelContent = function () use ($betterStatusNames, $subscriptionLists, $model) {
    ob_start(); ?>

    <form class="consolidatedReportForm" method="post" action="<?= PageURL2("analyticalreport") ?>">

        <fieldset>
            <div class="consolidatedReportForm__wrap">
                <div class="consolidatedReportForm__col">
                    <label for="category">Grupo</label>
                    <select name="category">
                        <option value="">-- escolha um grupo</option>
                        <?php foreach (listCategories() as $category): ?>
                            <?= sprintf('<option value="%s" %s>%s</option>',
                                $category,
                                $model->getCategory() == $category ? 'selected="selected"' : '',
                                $category
                            ); ?>
                        <?php endforeach; ?>
                    </select>

                    <label for="status">Situação</label>
                    <select name="status">
                        <option value="0">-- escolha um status</option>
                        <?php foreach ($betterStatusNames as $betterStatusNameKey => $betterStatusNameVal): ?>
                            <?= sprintf('<option value="%s" %s>%s</option>',
                                $betterStatusNameKey,
                                $model->getStatus() == $betterStatusNameKey ? 'selected="selected"' : '',
                                $betterStatusNameVal
                            ); ?>
                        <?php endforeach; ?>
                    </select>

                    <label for="email">Email do Cotista</label>
                    <input type="text" name="email" value="<?= $model->getEmail() ?>">
                </div>

                <div class="consolidatedReportForm__col">
                    <label for="fund">Fundo </label>
                    <select name="fund">
                        <option value="0">-- escolha um fundo</option>
                        <?php foreach ($subscriptionLists as $subscriptionList): ?>
                            <?= sprintf('<option value="%s" %s>%s</option>',
                                $subscriptionList->getName(),
                                $model->getFund() == $subscriptionList->getName() ? 'selected="selected"' : '',
                                $subscriptionList->getName()
                            ); ?>
                        <?php endforeach; ?>
                    </select>

                    <label for="registration">CPF/CNPJ do Cotista </label>
                    <input type="text" name="registration" value="<?= $model->getRegistration() ?>">

                    <label for="account">Conta do Cotista</label>
                    <input type="text" name="account" value="<?= $model->getAccount() ?>">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Período de Envio</legend>

            <div class="consolidatedReportForm__wrap">
                <div class="consolidatedReportForm__col">

                    <label for="consolidatedReportMessageDateStarted">Início</label>
                    <?php
                    $rangeStarted = new date('messageDateStarted');
                    $rangeStarted->useTime = true;

                    echo $rangeStarted->showInput('messageDateStarted', '', [
                        'year' => date('Y', strtotime($model->getMessageDateStarted())),
                        'month' => date('m', strtotime($model->getMessageDateStarted())),
                        'day' => date('d', strtotime($model->getMessageDateStarted())),
                        'hour' => date('H', strtotime($model->getMessageDateStarted())),
                        'minute' => date('i', strtotime($model->getMessageDateStarted())),
                    ]);
                    ?>

                </div>
                <div class="consolidatedReportForm__col">

                    <label for="consolidatedReportMessageDateFinished">Término</label>
                    <?php
                    $rangeFinished = new date('messageDateFinished');
                    $rangeFinished->useTime = true;
                    echo $rangeFinished->showInput('messageDateFinished', '', [
                        'year' => date('Y', strtotime($model->getMessageDateFinished())),
                        'month' => date('m', strtotime($model->getMessageDateFinished())),
                        'day' => date('d', strtotime($model->getMessageDateFinished())),
                        'hour' => date('H', strtotime($model->getMessageDateFinished())),
                        'minute' => date('i', strtotime($model->getMessageDateFinished())),
                    ]);
                    ?>

                </div>
            </div>

        </fieldset>
        <input type="submit" value="Buscar">
        <input type="button" id="exportData" value="Exportar Dados">


    </form>

<!--    <form method="post"-->
<!--          action="--><?//= PageURL2("clientsnoemail&action=exportCSV&filter={$filter}&filed={$filed}&order={$order}") ?><!--"-->
<!--          id="exportform">-->
<!--        <button type="submit"> Exportar</button>-->
<!--    </form>-->

    <?php
    return ob_get_clean();
};

if (!empty($_SESSION['action_result'])): ?>
    <div class="actionresult"><?= $_SESSION['action_result']; ?></div>
    <?php unset($_SESSION['action_result']); ?>
<?php endif; ?>

<?= (new UIPanel('Relatório de Envio', $analyticalReportFormPanelContent()))->display(); ?>

<hr>


<?php

$myTable = new WebblerListing('ID');
$myTable->usePanel(simplePaging('analyticalreport', $pageRequest->getStart(), $pageRequest->getTotal(), $pageRequest->getNumberPerPage()));

//$table->addElement("10", PageURL2("teste"));

/** @var AnalyticalReport $report */
foreach ($reports as $key => $report) {
    $myTable->addElement($key, '', 'ID');
    $myTable->addColumn($key, "CPF/CNPJ do Cotista", $report->getRegistration());
    $myTable->addColumn($key, "Grupo", $report->getGroup());
    $myTable->addColumn($key, "Fundo", $report->getFund());
    $myTable->addColumn($key, "Conta", $report->getAccount());
    $myTable->addColumn($key, "E-mail do Cotista", $report->getEmail());
    $myTable->addColumn($key, "Data Envio", (new DateTime($report->getDtSent()))->format('d/m/Y - H:m'));
    $myTable->addColumn($key, "Situação", isset($betterStatusNames[$report->getStatus()]) ? $betterStatusNames[$report->getStatus()] : $report->getStatus());
    $myTable->addColumn($key, "Ações", PageLink2("analyticalreport&action=view&messageId={$report->getMessageId()}&userId={$report->getUserId()}", 'Ver'));
}


$url = PageURL2('analyticalreport&sort=true');
?>

<div id="messagefilter" class="filterdiv fright">
    <form method="post" action="<?= $url ?>" id="messagefilterform" style="border: none">
        <div>
            <select name="orderBy" class="sortby">
                <option value="">Ordenar por</option>
                <option value="category" <?= $orderBy === 'category' ? 'selected' : '' ?>>Grupo</option>
                <option value="fund" <?= $orderBy === 'fund' ? 'selected' : '' ?>>Fundo</option>
                <option value="account" <?= $orderBy === 'account' ? 'selected' : '' ?>>Conta</option>
                <option value="registration" <?= $orderBy === 'registration' ? 'selected' : '' ?>>CPF/CNPJ</option>
                <option value="email" <?= $orderBy === 'email' ? 'selected' : '' ?>>Email</option>
                <option value="dtSent" <?= $orderBy === 'dtSent' ? 'selected' : '' ?>>Data de envio</option>
                <option value="attempts" <?= $orderBy === 'attempts' ? 'selected' : '' ?>>N tentativas</option>
                <option value="status" <?= $orderBy === 'status' ? 'selected' : '' ?>>Status</option>
                <option value="mensagem" <?= $orderBy === 'mensagem' ? 'selected' : '' ?>>Mensagem</option>
            </select>
            <select name="sort" class="sortby">
                <option value="asc" <?= $sort === 'asc' ? 'selected' : '' ?>>Ascendente</option>
                <option value="desc" <?= $sort === 'desc' ? 'selected' : '' ?>>Descendente</option>
            </select>
            <button type="submit" name="filter" id="filterbutton">Enviar</button>
        </div>
    </form>
</div>

<div class="analyticalreport-table">
    <?= $myTable->display(); ?>
</div>

<style>
    label {
        display: inline !important;
    }

    #searchform .searchinput {
        display: inline;
        width: 100px !important;
    }

    #searchform {
        display: inline !important;
    }

    .consolidatedReportForm input[type='text'],
    .consolidatedReportForm select {
        padding: 6px 2px;
        max-width: 230px;
    }

    .consolidatedReportForm fieldset {
        border: 1px solid #f3f3f3;
        padding: 10px;
    }

    .consolidatedReportForm legend {
        background: #f3f3f3;
        border-radius: 3px;
        padding: 5px 10px;
    }

    .consolidatedReportForm__wrap:before,
    .consolidatedReportForm__wrap:after {
        content: " ";
        display: table;
    }

    .consolidatedReportForm__wrap:after {
        clear: both;
    }

    .consolidatedReportForm__wrap {
        margin-left: -15px;
        margin-right: -15px;
    }

    .consolidatedReportForm__col {
        box-sizing: border-box;
        float: left;
        min-height: 1px;
        padding-left: 15px;
        padding-right: 15px;
        position: relative;
        width: 50%;
    }
    .hidden {
        display: none !important;
    }

</style>

<?php
global $pagefooter;
$pagefooter['analyticalreport'] = <<<SCRIPT
<script type="text/javascript">
    jQuery(document).ready(function($) {
        
            $('.analyticalreport-table div.panel div.header h2').html('Relatório de Envio | Resultado');
        
            removeColumId();
            insertEmptyThElement();
            
            $('#exportData').click(function() {
              var formAction = $('.consolidatedReportForm').attr("action");
              console.log(formAction);
              $('.consolidatedReportForm').attr("action", formAction+'&exportData=true');
              $('.consolidatedReportForm').submit();
              
            })
    });
    
    function removeColumId() {
         $(".listinghdname").parent().remove();
         $('.listingname').remove();
    }
    
    function insertEmptyThElement() {
        $(".listing th:last-child") .after("<th><div class='listinghdelement'></div></th>");
    }
</script>
SCRIPT;
?>
