<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!$USER->IsAdmin()) {
    LocalRedirect('/');
}
\Bitrix\Main\Loader::includeModule('iblock');
define(IBLOCK_ID, 11);

$el = new CIBlockElement;
$listProps = [];

$rsProps = CIBlockPropertyEnum::GetList(
    ["SORT" => "ASC", "VALUE" => "ASC"],
    ['IBLOCK_ID' => IBLOCK_ID]
);
while ($listProp = $rsProps->Fetch()) {
    $listKey = trim($listProp['VALUE']);
    $listProps[$listProp['PROPERTY_CODE']][$listKey] = $listProp['ID'];
}

$rsElements = CIBlockElement::GetList([], ['IBLOCK_ID' => IBLOCK_ID], false, false, ['ID']);
while ($del = $rsElements->GetNext()) {
    CIBlockElement::Delete($del['ID']);
}

if (($handle = fopen("vacancy.csv", "r")) !== false) {
    fgetcsv($handle, 1000, ",");
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {

        $iblProps['ACTIVITY'] = $data[9];
        $iblProps['FIELD'] = $data[11];
        $iblProps['OFFICE'] = $data[1];
        $iblProps['LOCATION'] = $data[2];
        $iblProps['REQUIRE'] = $data[4];
        $iblProps['DUTY'] = $data[5];
        $iblProps['CONDITIONS'] = $data[6];
        $iblProps['EMAIL'] = $data[12];
        $iblProps['DATE'] = date('d.m.Y');
        $iblProps['TYPE'] = $data[8];
        $iblProps['SALARY_TYPE'] = '';
        $iblProps['SALARY_VALUE'] = $data[7];
        $iblProps['SCHEDULE'] = $data[10];
        
        foreach ($iblProps as $key => &$value) {
            $value = trim($value);
            $value = str_replace('\n', '', $value);

            if (stripos($value, '•') !== false) {
                $value = explode('•', $value);
                array_splice($value, 0, 1);
                foreach ($value as &$str) {
                    $str = trim($str);
                }
            } elseif($listProps[$key]) {
                $maxSim = -1;
                foreach ($listProps[$key] as $propKey => $propVal) {
                    if ($key == 'OFFICE') {
                        $value = strtolower($value);
                        if ($value == 'центральный офис') {
                            $value .= 'свеза ' . $data[2];
                        } elseif ($value == 'лесозаготовка') {
                            $value = 'свеза ресурс ' . $value;
                        } elseif ($value == 'свеза тюмень') {
                            $value = 'свеза тюмени';
                        }
                        $sim = similar_text($value, $propKey);
                        if($sim > $maxSim)
                        {
                            $maxSim = $sim;
                            $simValue = $propVal;
                        }
                    }
                    if (stripos($propKey, $value) !== false) {
                        $value = $propVal;
                        break;
                    }
                    if (similar_text($propKey, $value) > 50) {
                        $maxSim = $sim;
                        $value = $propVal;
                    }
                }
                if ($key == 'OFFICE' && !is_numeric($value)) {
                    $value = $simValue;
                }
            }
        }
        if ($iblProps['SALARY_VALUE'] == '-') {
            $iblProps['SALARY_VALUE'] = '';
        } elseif ($iblProps['SALARY_VALUE'] == 'по договоренности') {
            $iblProps['SALARY_VALUE'] = '';
            $iblProps['SALARY_TYPE'] = $listProps['SALARY_TYPE']['Договорная'];
        } else {
            $arSalary = explode(' ', $iblProps['SALARY_VALUE']);
            
            if ($arSalary[0] == 'от' || $arSalary[0] == 'до') {
                $iblProps['SALARY_TYPE'] = $listProps['SALARY_TYPE'][mb_strtoupper($arSalary[0])];
                array_splice($arSalary, 0, 1);
                $iblProps['SALARY_VALUE'] = implode(' ', $arSalary);
            } else {
                $iblProps['SALARY_TYPE'] = $listProps['SALARY_TYPE']['='];
            }
        }

        $arLoadProductArray = [
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => IBLOCK_ID,
            "PROPERTY_VALUES" => $iblProps,
            "NAME" => $data[3],
            "ACTIVE" => end($data) ? 'Y' : 'N',
        ];
        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } else {
            echo "Error: " . $el->LAST_ERROR . '<br>';
        }
    }
    fclose($handle);
}