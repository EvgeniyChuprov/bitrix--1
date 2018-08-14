<?php


use Phinx\Migration\AbstractMigration;

/**
 * Class Init
 *
 */
class Init extends AbstractMigration
{

    private static $iBlockData = array(
        'NAME' => 'test',
        'CODE' => 'Users',
        'TYPE' => 'Users'
    );


    public static $listPropertyValues = array(
        '4' => 'Москва',
        '5' => 'Санкт-Петербург',
        '6' => 'Казань',
    );
    public static $listPropertyName = 'Город';
    public static $listPropertyCode = 'city';

    /**
     * @throws Exception
     */
    public function up()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $cIBlock = new CIBlock();
        $dbIBlock = $cIBlock->GetList(
            array(),
            array('CODE' => static::$iBlockData['CODE'])
        );
        if ($dbIBlock->Fetch()) {
            return;
        }
        $iBlockId = $cIBlock->Add(array(
            'NAME' => static::$iBlockData['NAME'],
            'CODE' => static::$iBlockData['CODE'],
            'IBLOCK_TYPE_ID' => static::$iBlockData['TYPE'],
            'VERSION' => 1,
            'SITE_ID' => array('s1'),
        ));
        if (false === $iBlockId) {
            throw new Exception($cIBlock->LAST_ERROR);
        }

        CIBlock::SetFields($iBlockId);


        $ibp = new CIBlockProperty;
        $ibpEnum = new CIBlockPropertyEnum;
        $arFields = array(
            'NAME' => self::$listPropertyName,
            'ACTIVE' => 'Y',
            'IS_REQUIRED' => 'Y',
            'SORT' => '500',
            'CODE' => self::$listPropertyCode,
            'PROPERTY_TYPE' => 'L',
            'FILTRABLE' => 'Y',
            'IBLOCK_ID' => $iBlockId,
        );
        $ibp->Add($arFields);
        $properties = CIBlockProperty::GetList(
            array(),
            array(
                'IBLOCK_ID' => $iBlockId,
                'CODE' => self::$listPropertyCode
            )
        );

        if ($propFields = $properties->GetNext()) {
            foreach (self::$listPropertyValues as $listPropertyId => $listPropertyValue) {
                $ibpEnum->Add(array(
                    'PROPERTY_ID' => $propFields['ID'],
                    'VALUE' => $listPropertyValue,
                    'XML_ID' => $listPropertyId,
                ));
            }
        }


        $ibp = new CIBlockProperty();
        $ibp->Add(array(
            'NAME' => 'Дата рождения',
            'ACTIVE' => 'Y',
            'IS_REQUIRED' => 'Y',
            'SORT' => '500',
            'CODE' => 'data',
            'PROPERTY_TYPE' => 'S',
            'FILTRABLE' => 'Y',
            'IBLOCK_ID' => $iBlockId,
        ));
        $ibp->Add(array(
            'NAME' => 'Номер телефона',
            'ACTIVE' => 'Y',
            'IS_REQUIRED' => 'Y',
            'SORT' => '500',
            'CODE' => 'phone',
            'PROPERTY_TYPE' => 'S',
            'FILTRABLE' => 'Y',
            'IBLOCK_ID' => $iBlockId,
        ));

        foreach ($this->textProperties as $propCode => $property) {
            $arFields = array(
                'NAME' => $property,
                'ACTIVE' => 'Y',
                'SORT' => '100',
                'CODE' => $propCode,
                'PROPERTY_TYPE' => 'S',
                'FILTRABLE' => 'Y',
            );
            $ibp->Add($arFields);
        }

    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */

    public function down()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $iBlock = CIBlock::GetList(array(), array('ID' => self::$iBlockId))->GetNext();
        if ($iBlock['ID']) {
            $properties = CIBlockProperty::GetList(
                array(),
                array('IBLOCK_ID' => $iBlock['ID'], 'CODE' => self::$listPropertyCode)
            );
            if ($propFields = $properties->GetNext()) {
                CIBlockProperty::Delete($propFields['ID']);
            }
        }
    }
}

