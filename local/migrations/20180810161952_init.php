<?php


use Phinx\Migration\AbstractMigration;

/**
 * Class Init
 *
 */
class Init extends AbstractMigration
{

    public function up()
    {
        $sTypeIBlockID = 'TypeUsersIB';
        $sIBlockID = 'Пользователи';
        $sNameRu = 'Тип ИБ Пользователи';
        $sNameEn = 'TypeIBUsers';
        $sCode = 'Users';
        $iIBlockId = 0;
        $aIBlock = [];
        $aPropIBlock = [];


        if (CModule::IncludeModule("iblock")) {

            $db_iblock_type = CIBlockType::GetList(
                array(),
                array(
                    "ID" => $sTypeIBlockID
                )
            );

            if (empty($db_iblock_type->arResult)) {
                $obIBlockType = new CIBlockType;
                $arFields = Array(
                    "ID" => $sTypeIBlockID,
                    "SECTIONS" => "Y",
                    'LANG' => Array(
                        'en' => Array(
                            'NAME' => $sNameEn
                        ),
                        'ru' => Array(
                            'NAME' => $sNameRu
                        )
                    )
                );
                $obIBlockType->Add($arFields);
            }

            $oUsersIBlock = CIBlock::GetList(
                Array(),
                Array(
                    'TYPE' => $sTypeIBlockID,
                    'ACTIVE' => 'Y',
                ), true
            );
            while ($ar_res = $oUsersIBlock->Fetch()) {
                $aIBlock = $ar_res;
            }

            if (empty($aIBlock)) {
                $oIblock = new CIBlock;
                $aFields = Array(
                    "NAME" => $sIBlockID,
                    "CODE" => $sCode,
                    "ACTIVE" => "Y",
                    "IBLOCK_TYPE_ID" => $sTypeIBlockID,
                    "SITE_ID" => 's1'
                );
                $oIblock->Add($aFields);
            }

            $res = CIBlock::GetList(
                Array(),
                Array(
                    'TYPE' => $sTypeIBlockID,
                    'SITE_ID' => 's1',
                    'ACTIVE' => 'Y',
                    "CNT_ACTIVE" => "Y",

                ), true
            );

            while ($ar_res = $res->Fetch()) {
                $iIBlockId = $ar_res['ID'];
            }

            $oProperties = CIBlockProperty::GetList(
                Array(),
                Array(
                    'IBLOCK_ID' => $iIBlockId,
                    'ACTIVE' => 'Y',
                ), true
            );
            while ($ar_res = $oProperties->Fetch()) {
                $aPropIBlock = $ar_res;
            }

            if (empty($aPropIBlock)) {
                $aFieldsCity = Array(
                    "NAME" => "Город",
                    "ACTIVE" => "Y",
                    "CODE" => "city",
                    "IS_REQUIRED" => "Y",
                    "PROPERTY_TYPE" => "L",
                    "IBLOCK_ID" => $iIBlockId
                );
                $aFieldsCity["VALUES"][0] = Array(
                    "VALUE" => "Москва",
                    "DEF" => "N",
                );

                $aFieldsCity["VALUES"][1] = Array(
                    "VALUE" => "Санкт-Петербург",
                    "DEF" => "N",
                );

                $aFieldsCity["VALUES"][2] = Array(
                    "VALUE" => "Казань",
                    "DEF" => "N",
                );

                $aFieldsDate = Array(
                    "NAME" => "Дата рождения",
                    "ACTIVE" => "Y",
                    "CODE" => "data",
                    "IS_REQUIRED" => "Y",
                    "PROPERTY_TYPE" => "S",
                    "IBLOCK_ID" => $iIBlockId
                );

                $aFieldsPhone = Array(
                    "NAME" => "Номер телефона",
                    "ACTIVE" => "Y",
                    "CODE" => "phone",
                    "IS_REQUIRED" => "Y",
                    "PROPERTY_TYPE" => "S",
                    "IBLOCK_ID" => $iIBlockId
                );

                $ibp = new CIBlockProperty;
                $ibp->Add($aFieldsCity);
                $ibp->Add($aFieldsDate);
                $ibp->Add($aFieldsPhone);
            }
        }

    }


    public function down()
    {
        $sTypeIBlockID = 'TypeUsersIB';
        $iIBlockId = 0;
        if (CModule::IncludeModule("iblock")) {
            $res = CIBlock::GetList(
                Array(),
                Array(
                    'TYPE' => $sTypeIBlockID,
                    'SITE_ID' => 's1',
                    'ACTIVE' => 'Y',
                    "CNT_ACTIVE" => "Y",

                ), true
            );

            while ($ar_res = $res->Fetch()) {
                $iIBlockId = $ar_res['ID'];
                Composer\Exception($iIBlockId);
            }
            CIBlock::Delete($iIBlockId);

            CIBlockType::Delete($sTypeIBlockID);
        }


    }
}
