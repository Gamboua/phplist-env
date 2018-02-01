<?php
/** @var array $betterStatusNames */
/** @var array $communicationTypes */
/** @var \phplist\Caixa\Functionality\Domain\Model\SubscriptionList[] $subscriptionLists */
/** @var \phplist\Caixa\Functionality\Domain\Shared\ConsolidatedReportQueryObject $queryObject */
/** @var array $queryResult */

include(dirname(phplistCaixaFunctionality::DIR) . '/date.php');

/**
 * The consolidated report form content
 *
 * @return string
 */
$consolidatedReportFormPanelContent = function () use ($communicationTypes, $subscriptionLists, $queryObject) {
    ob_start(); ?>

    <fieldset>

        <div class="consolidatedReportForm__wrap">
            <div class="consolidatedReportForm__col">

                <label for="consolidatedReportFundGroup">Grupo</label>
                <select name="fundGroup">
                    <option value="">-- escolha um grupo</option>
                    <?php foreach (listCategories() as $category): ?>
                        <?= sprintf('<option value="%s" %s>%s</option>',
                            $category,
                            $queryObject->getFundGroup() == $category ? 'selected="selected"' : '',
                            $category
                        ); ?>
                    <?php endforeach; ?>
                </select>

                <label for="consolidatedReportCommunicationType">Tipo de Comunicado</label>
                <select id="consolidatedReportCommunicationType" name="communicationType">
                    <option value="0">-- escolha um tipo de comunicado</option>
                    <?php foreach ($communicationTypes as $communicationTypeKey => $communicationTypeValue): ?>
                        <?= sprintf('<option value="%s" %s>%s</option>',
                            $communicationTypeKey,
                            $queryObject->getCommunicationType() == $communicationTypeKey ? 'selected="selected"' : '',
                            $communicationTypeValue
                        ); ?>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="consolidatedReportForm__col">

                <label for="consolidatedReportFundName">Fundo</label>
                <select id="consolidatedReportFundName" name="fundName">
                    <option value="0">-- escolha um fundo</option>
                    <?php foreach ($subscriptionLists as $subscriptionList): ?>
                        <?= sprintf('<option value="%s" %s>%s</option>',
                            $subscriptionList->getName(),
                            $queryObject->getFundName() == $subscriptionList->getName() ? 'selected="selected"' : '',
                            $subscriptionList->getName()
                        ); ?>
                    <?php endforeach; ?>
                </select>

                <label for="consolidatedReportClientIdentifier">CPF/CNPJ do Cotista</label>
                <input type="text" id="consolidatedReportClientIdentifier" name="clientIdentifier" value="<?= $queryObject->getClientIdentifier(); ?>">

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
                    'year' => date('Y', strtotime($queryObject->getMessageDateStarted())),
                    'month' => date('m', strtotime($queryObject->getMessageDateStarted())),
                    'day' => date('d', strtotime($queryObject->getMessageDateStarted())),
                    'hour' => date('H', strtotime($queryObject->getMessageDateStarted())),
                    'minute' => date('i', strtotime($queryObject->getMessageDateStarted())),
                ]);
                ?>
            </div>
            <div class="consolidatedReportForm__col">
                <label for="consolidatedReportMessageDateFinished">Término</label>
                <?php
                $rangeFinished = new date('messageDateFinished');
                $rangeFinished->useTime = true;
                echo $rangeFinished->showInput('messageDateFinished', '', [
                    'year' => date('Y', strtotime($queryObject->getMessageDateFinished())),
                    'month' => date('m', strtotime($queryObject->getMessageDateFinished())),
                    'day' => date('d', strtotime($queryObject->getMessageDateFinished())),
                    'hour' => date('H', strtotime($queryObject->getMessageDateFinished())),
                    'minute' => date('i', strtotime($queryObject->getMessageDateFinished())),
                ]);
                ?>
            </div>
        </div>

    </fieldset>

    <style>
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

    </style>

    <?php
    return ob_get_clean();
};

/**
 * The consolidated report result content
 *
 * @return string
 */
$consolidatedReportResultPanelContent = function () use ($betterStatusNames, $queryResult) {
    ob_start(); ?>

    <ul class="consolidatedReportResult">
        <?php foreach ($queryResult as $result): ?>
            <li>
                <strong><?= array_key_exists($result['status'], $betterStatusNames) ? $betterStatusNames[$result['status']] : $result['status']; ?>:</strong>
                <?= $result['total']; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <style>
        .consolidatedReportResult {
            margin: 0;
            padding: 10px;
        }

        .consolidatedReportResult li {
            list-style: none;
            margin: 0 0 5px;

        }
    </style>

    <?php
    return ob_get_clean();
}

?>

<?= formStart('id="consolidatedReportForm" class="consolidatedReportForm"'); ?>
<?= (new UIPanel('Relatório Consolidado de Envio & Recebimento', $consolidatedReportFormPanelContent()))->display(); ?>

<br>
<?php if (isset($queryResult) && sizeof($queryResult) > 0): ?>
    <?= (new UIPanel('Resultado', $consolidatedReportResultPanelContent()))->display(); ?>
<?php endif; ?>

<br/>
<button type="submit">Buscar</button>
</form>
