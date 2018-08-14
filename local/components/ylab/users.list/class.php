<?php


namespace YLab\Validation\Components;

//use Bitrix\Main\UserTable;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

/**
 * Class ValidationTestComponent
 * Компонент пример использования модуля ylab.validation в разработке
 *
 * @package YLab\Validation\Components
 */
class ValidationTestComponent extends ComponentValidation
{
    /**
     * ValidationTestComponent constructor.
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        parent::__construct($component, $sFile);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function executeComponent()
    {
        /**
         * Непосредственно валидация и действия при успехе и фейле
         */
        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            if ($this->oValidator->passes()) {
                $this->arResult['SUCCESS'] = true;


                \Bitrix\Main\Loader::includeModule('iblock');

                $el = new \CIBlockElement;
                $iIBlockId = \Bitrix\Iblock\IblockTable::getList()->fetch()['ID'];

                //Свойства
                $PROP = [
                    'NAME' => $_POST['name'],
                    'city' => $_POST['city'],
                    'data' => $_POST['date'],
                    'phone' => $_POST['phone']
                ];

                echo '<pre>';
                print_r($_POST);
                echo '<pre>';
                $fields = array(
                    "DATE_CREATE" => date("d.m.Y H:i:s"),
                    "CREATED_BY" => $GLOBALS['USER']->GetID(),
                    "IBLOCK_ID" => $iIBlockId,
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => strip_tags($_REQUEST['name']),
                    "ACTIVE" => "Y",
                );


                if ($ID = $el->Add($fields)) {
                    echo "Сохранено";
                } else {
                    echo ' Попробуйте еще раз';
                }

            } else {
                $this->arResult['ERRORS'] = ValidatorHelper::errorsToArray($this->oValidator);
            }
        }

        $this->includeComponentTemplate();
    }

    /**
     * @return array
     */
    protected function rules()
    {
        /**
         * Перед формированием массива правил валидации мы можем вытащить все необходимые данные из различных источников
         */
        return [
            'name' => 'required',
            'city' => 'required|numeric',
            'date' => 'required|date_format:d.m.Y',
            'phone' => 'required|regex:/(\+7)[0-9]{10}/'
        ];
    }
}
