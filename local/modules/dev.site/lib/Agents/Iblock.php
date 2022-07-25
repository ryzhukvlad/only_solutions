<?php

namespace Only\Site\Agents;


class Iblock
{
    public static function clearOldLogs()
    {
        $fs = fopen($_SERVER['DOCUMENT_ROOT'] . '/test.txt', 'w');
        fwrite($fs, 'Агент');
        fclose($fs);
        \CModule::IncludeModule("iblock");
        $rs = \CIBlock::GetList([], ['CODE' => 'LOG']);
        if (!($logIB = $rs->GetNext())) {
            return;
        }
        $rs = \CIBlockElement::GetList(['TIMESTAMP_X' => 'DESC'], ['IBLOCK_ID' => $logIB['ID']], false, false, ['ID']);
        for ($i = 0; $i < 10; $i++) {
            if(!($logElTime = $rs->GetNext())) {
                return;
            }
        }
        while ($logElTime = $rs->GetNext()) {
            \CIBlockElement::Delete($logElTime['ID']);
        }
        return 'Only\Site\Agents\Iblock\clearOldLogs();';
    }
}
