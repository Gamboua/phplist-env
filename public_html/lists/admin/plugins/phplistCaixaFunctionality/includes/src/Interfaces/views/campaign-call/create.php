<?php
/** @var array $errors */
/** @var array $templates */
/** @var array $communicationTypes */
/** @var array $availableStatus */
/** @var \phplist\Caixa\Functionality\Domain\Model\CampaignCall $campaignCall */

include(dirname(phplistCaixaFunctionality::DIR) . '/date.php');

global $pagefooter;
$pagefooter['campaigncall_create'] = <<<SCRIPT
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('body.campaigncall').find('.ui-tabs-panel li').each(function(){
            var li = $(this);
            listify(li);
        });
        
        function listify(selector) 
        {
            $(selector).each(function(index, val) {
                // Give all checkboxes the same ID as the name attribute
                var cbx_name = $(this).find('input[type=checkbox]').attr('name');
                $(this).find('input[type=checkbox]').attr('id', cbx_name);
    
                // Wrap the contents of the <li> with a <label>
                var content = $(this).html().replace('(<span', '<span');
                content = content.replace('span>)','span><small>');
                content = content + "</small>";
                $(this).html('<label for="' + cbx_name + '">' + content + '</label>');
    
                // Pop the checkbox out of the label (for CSS selecting reasons)
                var cbx = $(this).find('input[type=checkbox]');
                $(this).prepend(cbx);
            });
            $('li input[type=checkbox]').hide();
        }

    });
</script>
SCRIPT;

/**
 * @param $current
 * @param $fieldname
 *
 * @return string
 */
$listSelectHTML = function ($current, $fieldname) {
    $GLOBALS['systemTimer']->interval();
    $categoryhtml = ListofLists($current, $fieldname, '');

    $tabno = 1;
    $listindex = $listhtml = '';
    $some = count($categoryhtml);

    if (array_key_exists('all', $categoryhtml)) {
        unset($categoryhtml['all']);
    }

    if ($some > 0) {
        foreach ($categoryhtml as $category => $content) {
            if ($category == 'all') {
                $category = '@';
            }
            if ($some > 1) { //# don't show tabs, when there's just one
                $listindex .= sprintf('<li><a href="#%s%d">%s</a></li>', $fieldname, $tabno, $category);
            }
            if ($fieldname == 'targetlist') {
                // Add select all checkbox in every category to select all lists in that category.
                if ($category == 'selected') {
                    $content = sprintf('<li class="selectallcategory"><input type="checkbox" name="all-lists-' . $fieldname . '-cat-' . str_replace(' ',
                                '-',
                                strtolower($category)) . '" checked="checked">' . s('Select all') . '</li>') . $content;
                } elseif ($category != '@') {
                    $content = sprintf('<li class="selectallcategory"><input type="checkbox" name="all-lists-' . $fieldname . '-cat-' . str_replace(' ',
                                '-', strtolower($category)) . '">' . s('Select all') . '</li>') . $content;
                }
            }
            $listhtml .= sprintf('<div class="%s" id="%s%d"><ul>%s</ul></div>',
                str_replace(' ', '-', strtolower($category)), $fieldname, $tabno, $content);
            ++$tabno;
        }
    }

    $html = '<div class="tabbed"><ul>' . $listindex . '</ul>';
    $html .= $listhtml;
    $html .= '</div>';

    if (!$some) {
        $html = s('There are no lists available');
    }

    return $html;
};

/**
 * The Campaign Call Panel Content
 *
 * @return string
 */
$campaignCallPanelContent = function () use ($templates, $communicationTypes, $availableStatus, $listSelectHTML, $campaignCall) {
    ob_start(); ?>

    <fieldset>
        <legend>Geral</legend>

        <label for="campaignCallSubject">Assunto<?= Help('subject'); ?></label>
        <input type="text" id="campaignCallSubject" name="subject" value="<?= $campaignCall->getSubject(); ?>">

        <label for="campaignCallFromField">De<?= Help('from'); ?></label>
        <input type="text" id="campaignCallFromField" name="fromField" value="<?= $campaignCall->getFromField(); ?>">

        <label for="campaignCallMessage">Mensagem<?= Help('message'); ?></label>
        <textarea id="campaignCallMessage" name="message"><?= $campaignCall->getMessage(); ?></textarea>

        <label for="campaignCallCommunicationType">Tipo de Comunicado</label>
        <select id="campaignCallCommunicationType" name="communicationType">
            <option value="0">-- selecione um</option>
            <?php foreach ($communicationTypes as $communicationTypeKey => $communicationTypeValue): ?>
                <?= sprintf('<option value="%s" %s>%s</option>',
                    $communicationTypeKey,
                    $campaignCall->getCommunicationType() == $communicationTypeKey ? 'selected="selected"' : '',
                    $communicationTypeValue
                ); ?>
            <?php endforeach; ?>
        </select>

        <label for="campaignCallTemplate">Modelo<?= Help('usetemplate'); ?></label>
        <select id="campaignCallTemplate" name="template">
            <option value="0">-- selecione um</option>
            <?php foreach ($templates as $template): ?>
                <?= sprintf('<option value="%d" %s>%s</option>',
                    $template['id'],
                    $campaignCall->getTemplate() == $template['id'] ? 'selected="selected"' : '',
                    $template['title']
                ); ?>
            <?php endforeach; ?>
        </select>

        <label for="campaignCallStatus">Edição</label>
        <select id="campaignCallStatus" disabled>
            <option value="0"><?= $availableStatus[$campaignCall->getStatus()]; ?></option>
        </select>
    </fieldset>

    <fieldset>
        <legend>Agendamento</legend>

        <label for="campaignCallEmbargo">Iniciar em?<?= Help('embargo'); ?></label>
        <?php
        $embargo = new date('embargo');
        $embargo->useTime = true;
        echo $embargo->showInput('embargo', '', [
            'year' => date('Y', strtotime($campaignCall->getEmbargo())),
            'month' => date('m', strtotime($campaignCall->getEmbargo())),
            'day' => date('d', strtotime($campaignCall->getEmbargo())),
            'hour' => date('H', strtotime($campaignCall->getEmbargo())),
            'minute' => date('i', strtotime($campaignCall->getEmbargo())),
        ]);
        ?>

        <label for="campaignCallFinishSending">Parar de enviar em?<?= Help('finishsending'); ?></label>
        <?php
        $finishSending = new date('finishSending');
        $finishSending->useTime = true;
        echo $finishSending->showInput('finishSending', '', [
            'year' => date('Y', strtotime($campaignCall->getFinishSending())),
            'month' => date('m', strtotime($campaignCall->getFinishSending())),
            'day' => date('d', strtotime($campaignCall->getFinishSending())),
            'hour' => date('H', strtotime($campaignCall->getFinishSending())),
            'minute' => date('i', strtotime($campaignCall->getFinishSending())),
        ]);
        ?>
    </fieldset>

    <fieldset>
        <legend>Importação</legend>

        <label for="campaignCallImportCSV">Metadados da Convocação</label>
        <input type="file" id="campaignCallImportCSV" name="importCSV">
        <br><br>

        <div id="listselection">
            <label for="campaignCallTargetLists">Listas</label>
            <?= $listSelectHTML([], 'targetlist'); ?>
        </div>
    </fieldset>

    <style>
        .campaignCallForm fieldset {
            border: 1px solid #f3f3f3;
            padding: 10px;
        }

        .campaignCallForm legend {
            background: #f3f3f3;
            border-radius: 3px;
            padding: 5px 10px;
        }

        .campaignCallForm [class*="@"] li:last-child {
            display: none;
        }

        #campaignCallStatus:disabled {
            background: #f3f3f3;
        }
    </style>

    <?php
    return ob_get_clean();
};

?>

<?php if (!empty($errors)): ?>
    <div class="actionresult actionresult--error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <style>
        .actionresult--error {
            border-color: darkred;
            color: darkred;
        }

        .actionresult--error ul,
        .actionresult--error li {
            margin-bottom: 0;
        }
    </style>
<?php endif; ?>

<?= formStart('id="campaignCallCreate" class="campaignCallForm campaignCallForm--create" enctype="multipart/form-data"'); ?>
<?= (new UIPanel('Novo agendamento de envio', $campaignCallPanelContent()))->display(); ?>
<br/>
<button type="submit" name="campaignCallSave">Salvar</button>
</form>
