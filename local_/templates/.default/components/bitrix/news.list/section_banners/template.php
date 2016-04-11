<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
/*?>
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
    <?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
            <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
                        class="preview_picture"
                        border="0"
                        src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                        width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                        height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                        alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                        title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                        style="float:left"
                        /></a>
            <?else:?>
                <img
                    class="preview_picture"
                    border="0"
                    src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                    width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                    height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                    alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                    title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                    style="float:left"
                    />
            <?endif;?>
        <?endif?>
        <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
            <span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
        <?endif?>
        <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
            <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
            <?else:?>
                <b><?echo $arItem["NAME"]?></b><br />
            <?endif;?>
        <?endif;?>
        <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
            <?echo $arItem["PREVIEW_TEXT"];?>
        <?endif;?>
        <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
            <div style="clear:both"></div>
        <?endif?>
        <?foreach($arItem["FIELDS"] as $code=>$value):?>
            <small>
            <?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
            </small><br />
        <?endforeach;?>
        <?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
            <small>
            <?=$arProperty["NAME"]?>:&nbsp;
            <?if(is_array($arProperty["DISPLAY_VALUE"])):?>
                <?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
            <?else:?>
                <?=$arProperty["DISPLAY_VALUE"];?>
            <?endif?>
            </small><br />
        <?endforeach;?>
    </p>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div><?/*?>
<div class="slideWrapp">
    <ul class="roundSlider">
        <?foreach($arResult["ITEMS"] as $key => $arItem)
        {?>
        <li class="firstSlide" style="background-image: url(<?=$arItem["DETAIL_PICTURE"]["SRC"]?>)">
        <div class="catalogWrapper">
            <p class="titleSlide">
            <?=$arItem["PREVIEW_TEXT"]?>
            </p>
            <p class="textSlide">
            <?=$arItem["DETAIL_TEXT"]?>
            </p>
            <?foreach($arResult["ITEMS"] as $item_key => $item_value)
            {?>
            <!-- определение текущего слайда и присвоение класса к соответствующей кнопке слайда с соответствующей анимацией -->
            <div class="<?if ($item_key==$key){?>circle circle<?=($item_key+1)?><?}else{?>buttons" data-number="<?=($item_key+1)?><?}?>">
            <?if($item_key==$key) {?><strong><?=($item_key+1)?></strong><?} else {?><p><?=($item_key+1)?></p><?}?>
            </div>
            <?}?>
        </div>
        </li>
        <?}?>
    </ul>
</div><?*/?>

<?
    if($arResult['ELEMENTS']){?>
        <div class="roundSlideWrapp">
                    <ul class="roundSlider">
                        <?foreach($arResult["ITEMS"] as $key => $arItem)
                        {?>
                        <li class="firstSlide">
                            <?if ($arItem["PROPERTIES"]["SECT_BANNER_LINK"]["VALUE"]){?>
                                <a href="<?=$arItem["PROPERTIES"]["SECT_BANNER_LINK"]["VALUE"]?>">
                                <?}?>
                                    <div class="catalogWrapper">
                                        <img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" class="roundCatBack">
                                        <p class="titleSlide"><?=$arItem["PREVIEW_TEXT"]?></p>
                                        <p class="textSlide"><?=$arItem["DETAIL_TEXT"]?></p>
                                        <?foreach($arResult["ITEMS"] as $item_key => $item_value)
                                        {?>
                                        <!-- определение текущего слайда и присвоение класса к соответствующей кнопке слайда с соответствующей анимацией -->
                                        <div class="<?if ($item_key==$key){?>circle circle<?=($item_key+1)?><?}else{?>buttons" data-number="<?=($item_key+1)?><?}?>">
                                        <?if($item_key==$key) {?><strong><?=($item_key+1)?></strong><?} else {?><p><?=($item_key+1)?></p><?}?>
                                        </div>
                                        <?}?>
                                    </div>
                                <?if ($arItem["PROPERTIES"]["SECT_BANNER_LINK"]["VALUE"]){?>
                                    </a>
                                <?}?>
                        </li>
                        <?}?>
                    </ul>    
                </div>    
    <?}
?>
<script>
    $(document).ready(function(){
        if($('.roundSlideWrapp').length == 0){
            $('.categoryWrapper .titleMain').css({"margin-bottom":"-42px"});
             $(".wrapperCategor").css("height", $(".wrapperCategor").height() - 360 + "px");
             $(".contentWrapp").css("height", $(".contentWrapp").height() - 360 + "px");
        }
    })
</script>


          
