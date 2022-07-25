<?php

namespace Only\Site\Handlers;

class Iblock
{
    public function addLog(&$arFields)
    {
        if (!$arFields['RESULT']) {
            return;
        }

        $rs = \CIBlock::GetByID($arFields['IBLOCK_ID']);
        $elemIB = $rs->GetNext();
        if ($elemIB['CODE'] == 'LOG') {
            return;
        }

        $rs = \CIBlock::GetList([], ['CODE' => 'LOG']);
        if (!($logIB = $rs->GetNext())) {
            return;
        }

        define(dateFormat, 'd.m.Y H:i:s');

        $logEl = new \CIBlockElement();
        $rs = \CIBlockElement::GetList([], ['IBLOCK_ID' => $logIB['ID']], false, false, ['ID', 'NAME']);

        while ($logElRes = $rs->GetNext()) {
            if ($logElRes['NAME'] != $arFields['ID']) {
                continue;
            }
            $logElRes['ACTIVE_FROM'] = date(dateFormat);
            $logEl->Update($logElRes['ID'], $logElRes);
            return;
        }

        $rs = \CIBlockSection::GetList([], [
            'IBLOCK_ID' => $logIB['ID'],
            'NAME' => $elemIB['NAME'] . ' (ID: ' . $elemIB['ID'] . ')'
        ], false, ['ID']);

        if (!($logSecRes = $rs->GetNext())) {
            $logSec = new \CIBlockSection();
            $logSecRes = [];
            $logSecRes['ID'] = $logSec->Add([
                'IBLOCK_ID' => $logIB['ID'],
                'NAME' => $elemIB['NAME'] . ' (ID: ' . $elemIB['ID'] . ')'
            ]);
        }

        $rs = \CIBlockElement::GetElementGroups($arFields['ID'], false, ['ID', 'NAME']);
        $elPath = '';
        $elGroup = $rs->GetNext();

        if ($elGroup) {
            $rs = \CIBlockSection::GetNavChain($elemIB['ID'], $elGroup['ID'], ['NAME']);
            $arGroup = $rs->GetNext();
            $elPath = $arGroup['NAME'];
            while ($arGroup = $rs->GetNext()) {
                $elPath .= ' -> ' . $arGroup['NAME'];
            }
        }
        if ($elPath == '') {
            $elPath = $elemIB['NAME'] . ' -> ' . $arFields['NAME'];
        } else {
            $elPath = $elemIB['NAME'] . ' -> ' . $elPath . ' -> ' . $arFields['NAME'];
        }

        $logAdd = [
            "IBLOCK_ID" => $logIB['ID'],
            "IBLOCK_SECTION_ID" => $logSecRes['ID'],
            "NAME" => $arFields['ID'],
            "DETAIL_TEXT" => $arFields['NAME'],
            "ACTIVE_FROM" => date(dateFormat),
            "PREVIEW_TEXT" => $elPath
        ];
        $logEl->Add($logAdd);
    }
}
