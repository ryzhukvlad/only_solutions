<?php

CModule::IncludeModule("dev.site");
spl_autoload_call("\\Dev\\Site\\Handlers\\Iblock");
spl_autoload_call("\\Dev\\Site\\Agents\\Iblock");

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", ["\\Only\\Site\\Handlers\\Iblock", "addLog"]);
AddEventHandler("iblock", "OnAfterIBlockElementAdd", ["\\Only\\Site\\Handlers\\Iblock", "addLog"]);





