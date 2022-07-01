<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
<?=$arResult["FORM_NOTE"]?>
<?if ($arResult["isFormNote"] != "Y")
{
?>
<div class="contact-form">
    <div class="contact-form__head">
        <div class="contact-form__head-title"><?=$arResult["FORM_TITLE"]?></div>
        <div class="contact-form__head-text">Наши сотрудники помогут выполнить подбор услуги и&nbsp;расчет цены с&nbsp;учетом ваших требований</div>
    </div>
    <?=str_replace('<form', '<form class="contact-form__form', $arResult["FORM_HEADER"])?>
    	<?
    		$arQuestion = [];
    		foreach($arResult["QUESTIONS"] as $FIELD_SID)
    		{
    			array_push($arQuestion, $FIELD_SID);
    		}
    		$arQuestion = array_combine(["NAME", "COMPANY", "EMAIL", "PNUMBER", "REPORT"], $arQuestion);
    	?>
        <div class="contact-form__form-inputs">
            <div class="input contact-form__input"><label class="input__label" for="medicine_name">
                <div class="input__label-text"><?=$arQuestion["NAME"]["CAPTION"].str_repeat('*', $arQuestion["NAME"]["REQUIRED"] == 'Y')?></div>
                <?=str_replace('class="inputtext"', 'class="input__input" id="medicine_name" minlength="3"'.(($arQuestion["NAME"]["REQUIRED"] == 'Y') ? (' required=""') : ('')), $arQuestion["NAME"]["HTML_CODE"]);?>
                <div class="input__notification">Поле должно содержать не менее 3-х символов</div>
            </label></div>
            <div class="input contact-form__input"><label class="input__label" for="medicine_company">
                <div class="input__label-text"><?=$arQuestion["COMPANY"]["CAPTION"].str_repeat('*', $arQuestion["COMPANY"]["REQUIRED"] == 'Y')?></div>
                <?=str_replace('class="inputtext"', 'class="input__input" id="medicine_company" minlength="3"'.(($arQuestion["COMPANY"]["REQUIRED"] == 'Y') ? (' required=""') : ('')), $arQuestion["COMPANY"]["HTML_CODE"]);?>
                <div class="input__notification">Поле должно содержать не менее 3-х символов</div>
            </label></div>
            <div class="input contact-form__input"><label class="input__label" for="medicine_email">
                <div class="input__label-text"><?=$arQuestion["EMAIL"]["CAPTION"].str_repeat('*', $arQuestion["EMAIL"]["REQUIRED"] == 'Y')?></div>
                <?=str_replace('type="text"  class="inputtext"', 'type="email" class="input__input" id="medicine_email"'.(($arQuestion["EMAIL"]["REQUIRED"] == 'Y') ? (' required=""') : ('')), $arQuestion["EMAIL"]["HTML_CODE"]);?>
                <div class="input__notification">Неверный формат почты</div>
            </label></div>
            <div class="input contact-form__input"><label class="input__label" for="medicine_phone">
                <div class="input__label-text"><?=$arQuestion["PNUMBER"]["CAPTION"].str_repeat('*', $arQuestion["PNUMBER"]["REQUIRED"] == 'Y')?></div>
                <?=str_replace('class="inputtext"', 'class="input__input" type="tel" id="medicine_phone"'."
                       data-inputmask='mask': '+79999999999', 'clearIncomplete': 'true'".'maxlength="12"
                       x-autocompletetype="phone-full"'.(($arQuestion["PNUMBER"]["REQUIRED"] == 'Y') ? (' required=""') : ('')), $arQuestion["PNUMBER"]["HTML_CODE"]);?></label></div>
        </div>
        <div class="contact-form__form-message">
            <div class="input"><label class="input__label" for="medicine_message">
                <div class="input__label-text"><?=$arQuestion["REPORT"]["CAPTION"].str_repeat('*', $arQuestion["REPORT"]["REQUIRED"] == 'Y')?></div>
                <?=str_replace('cols="40" rows="5" class="inputtextarea"', 'class="input__input" id="medicine_message"'.(($arQuestion["REPORT"]["REQUIRED"] == 'Y') ? (' required=""') : ('')), $arQuestion["REPORT"]["HTML_CODE"]);?>
                <div class="input__notification">Оставьте сообщение</div>
            </label></div>
        </div>
        <div class="contact-form__bottom">
            <div class="contact-form__bottom-policy">Нажимая &laquo;Отправить&raquo;, Вы&nbsp;подтверждаете, что
                ознакомлены, полностью согласны и&nbsp;принимаете условия &laquo;Согласия на&nbsp;обработку персональных
                данных&raquo;.
            </div>
            <input class="form-button contact-form__bottom-button form-button__title" data-success="Отправлено"
                    data-error="Ошибка отправки" <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="Оставить заявку" />
        </div>
    <?=$arResult["FORM_FOOTER"]?>
	<?
	}?>
</div>