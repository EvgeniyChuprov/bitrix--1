<?php

/**
 * Class UsersListComponent
 */
class UsersListComponent extends \CBitrixComponent
{
    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        $this->arResult = $this->getUsersList();

        $this->includeComponentTemplate();
    }

    /**
     * @return array
     */
    protected function getUsersList()
    {

        if (CModule::IncludeModule("iblock")) {
            $arInfo = [];

            $oRes = CIBlock::GetList(
                Array(),
                Array(
                    'TYPE' => 'Users',
                    'SITE_ID' => SITE_ID,
                    'ACTIVE' => 'Y',
                    'CNT_ACTIVE' => 'Y'
                ), true
            );
            while ($arRes = $oRes->Fetch()) {
                $arInfo = $arRes;
            }

            if ($arInfo['IBLOCK_TYPE_ID'] == 'Users' && $arInfo['NAME'] == 'users') {
                $dbProps = CIBlockElement::GetProperty(1, 'users', "sort", "asc", array());
                $arRezult = [];
                $iNum = 0;
                while ($arProps = $dbProps->Fetch()) {
                    $arRezult[$iNum]["ID"] = +$arProps['ID'];
                    $arRezult[$iNum]["NAME"] = $arProps['NAME'];
                    $iNum++;
                }
                return $arRezult;
            } else {
                return ['Инфоблок не найден.'];
            }
        }
        return ['Инфоблоки не найдены.'];
    }
}
