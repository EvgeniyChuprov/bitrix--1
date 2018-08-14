<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
?>

<form action="" method="post" class="form form-block">
    <?= bitrix_sessid_post() ?>
    <? if (count($arResult['ERRORS'])): ?>
        <p><?= implode('<br/>', $arResult['ERRORS']) ?></p>
    <? elseif ($arResult['SUCCESS']): ?>
        <p>Успешная валидация</p>
    <? endif; ?>

    <div>
        <label>
            Имя<br>
            <input type="text" name="name"/>
        </label>
    </div>
    <div>
        <label>
            Город<br>
            <select name="city">
                <option value="">Выбрать</option>
                <option value="4">Москва</option>
                <option value="5">Санкт-Петербург</option>
                <option value="6">Казань</option>
            </select>
        </label>
    </div>
    <div>
        <label>
            Дата рождения<br>
            <input type="text" name="date"/>
        </label>
    </div>
    <div>
        <label>
            Номер телефона<br>
            <input type="text" name="phone"/>
        </label>
    </div>
    <div class="btn green">
        <button type="submit" name="submit">Отправить</button>
    </div>
</form>
