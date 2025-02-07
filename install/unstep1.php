<?php

IncludeModuleLangFile(__FILE__);

?>
<form action="<?=$APPLICATION->GetCurPage(); ?>">
    <?=bitrix_sessid_post(); ?>
    <input type="hidden" name="lang" value="<?=LANG; ?>">
    <input type="hidden" name="id" value="test.notebook">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <table>
        <tr>
            <td><?echo GetMessage("TEST_NOTEBOOK_DROP_TABLE_NOTICE")?></td>
        </tr>
        <tr>
            <td>
                <table cellpadding="3" cellspacing="0" border="0">
                    <tr>
                        <td><input type="checkbox" name="drop_tables" id="drop_tables" value="Y" checked></td>
                        <td><p><label for="drop_tables"><?echo GetMessage("TEST_NOTEBOOK_DROP_TABLE")?></label></p></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="submit" name="inst" value="<?=GetMessage('MOD_UNINST_DEL'); ?>">
</form>
