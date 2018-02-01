<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<?php

$table = <<<TABLE
    <table class="userAdd" border="1" id="tableUserFunds">
        <tbody>
            <tr class="nodrag">
                <th class="dataname">Data de referência</th>
                <th class="dataname">Número da agência</th>
                <th class="dataname">Número de operação</th>
                <th class="dataname">Número da conta</th>
                <th class="dataname">Email da agência</th>
                <th class="dataname">Número da modalidade</th>
            </tr>
        </tbody>
TABLE;

foreach ($userfunds as $userfund) {

    $table .= "<tr>";
    $table .= "<td><input type='text' data-user-id=" . $userfund['user_id'] . " data-list-id=" . $userfund['list_id'] . " name='reference_date' value=" . $userfund['reference_date'] . "></td>";
    $table .= "<td><input type='text' data-user-id=" . $userfund['user_id'] . " data-list-id=" . $userfund['list_id'] . " name='agency_number' value=" . $userfund['agency_number'] . "></td>";
    $table .= "<td><input type='text' data-user-id=" . $userfund['user_id'] . " data-list-id=" . $userfund['list_id'] . " name='operation_number' value=" . $userfund['operation_number'] . "></td>";
    $table .= "<td><input type='text' data-user-id=" . $userfund['user_id'] . " data-list-id=" . $userfund['list_id'] . " name='account_number' value=" . $userfund['account_number'] . "></td>";
    $table .= "<td><input type='text' data-user-id=" . $userfund['user_id'] . " data-list-id=" . $userfund['list_id'] . " name='agency_number' value=" . $userfund['agency_number'] . "></td>";
    $table .= "<td><span>".$userfund['modality_number']."</span></td>";
//    $table .= '<a href="help/?topic=campaigntitle" class="helpdialog" target="_blank">?</a>';
//    $table .= "<td class='listingelement' colspan='3'>
//                <span class='listingelement'>
//                    <span class='edit-list'>
//                        <a class='button btnSave helpdialog' target='_blank' title='Edit this list'></a>
//                    </span>
//                </span>
//              </td>";
    $table .= "</tr>";
}
$table .= "</form>";

$table .= "</table>";

echo($table);

global $pagefooter;

$pagefooter['phplist_caixa_userfunds_update'] = <<<SCRIPT

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script type="text/javascript">

    jQuery(document).ready(function($) {
        
        var delay = (function(){
          var timer = 0;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();
        
        var timer;
        $('#tableUserFunds input').keyup(function() {
            var self = $(this);
            delay(function(){
              var json = {
                name: self.attr('name'),
                value: self.val(),
                userId: self.attr('data-user-id'),
                listId: self.attr('data-list-id')
                };
              
                var url = "./?page=site&pi=phplistCaixaFunctionality&action=userfunds&ajaxed=true";
    
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        toastr.success('Registro salvo com sucesso!', '', {timeOut: 2500});
                    },
                    error: function() {
                      toastr.error('Não foi possível salvar o registro!', '', {timeOut: 2500});
                    },
                    data: json
                });
            }, 600 );
            
            });
        
    });
</script>
SCRIPT;

?>

