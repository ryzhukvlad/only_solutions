<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) 
    die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?php if (isset($arResult["ITEMS"]["SECTION_ID"])): ?>
    <?php $arSection = $arResult["ITEMS"]["SECTION_ID"]?>
    <form name="<?=$arResult["FILTER_NAME"]."_form"?>" action="<?=$arResult["FORM_ACTION"]?>" method="get">
        <table class="data-table" cellspacing="0" cellpadding="2">
        <tbody>
            <tr>
                <td valign="top"><select  name="<?=$arSection["INPUT_NAME"]?>" id="<?=$arSection["INPUT_NAME"]?>">
                    <option value="">Все новости</option>
                    <?php foreach ($arSection["LIST"] as $idSection => $valueSection): ?>
                        <?if ($idSection == 0) continue; ?>
                        <option value="<?=$idSection?>" 
                                <?= ($arSection["INPUT_VALUE"] == $idSection) ? ('selected') : ('') ?>
                        >
                        <?=str_replace(' . ', '', $valueSection)?></option>
                    <?php endforeach?>
                </select><br></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <input type="submit" name="set_filter" value="Показать" /><input type="hidden" name="set_filter" value="Y" />&nbsp;&nbsp;<input type="submit" name="del_filter" value="Сбросить" /></td>
            </tr>
        </tfoot>
        </table>
    </form>
<?php endif;?>