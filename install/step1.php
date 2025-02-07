<? if (!check_bitrix_sessid()) return; ?>

<?IncludeModuleLangFile(__FILE__);?>

<p>
    <?echo GetMessage("TEST_NOTEBOOK_DROP_TABLE_NOTICE")?><br>
</p>
<form action="<?echo $APPLICATION->GetCurPage()?>" name="form1">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="hidden" name="id" value="test.notebook">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    <table cellpadding="3" cellspacing="0" border="0" width="0%">
        <tr>
            <td>&nbsp;</td>
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
    <br>
    <input type="submit" name="inst" value="<?echo GetMessage("MOD_INSTALL")?>">
</form>
