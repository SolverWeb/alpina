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
?>
<?/*
*/
//arshow($arResult["ITEMS"][0]);
//arshow($arResult);
$navnum = $arResult["NAV_RESULT"]->NavNum;
if ($_REQUEST["PAGEN_".$navnum])
{
    $_SESSION[$APPLICATION -> GetCurDir()] = $_REQUEST["PAGEN_".$navnum];
}
?>

<div class="allBooksWrapp">
            <div class="catalogWrapper">
                <p class="titleMain"><a href="/catalog/all-books/">Все лучшие книги</a></p>
                <div class="catalogBooks">
                    <?foreach($arResult["ITEMS"] as $arItem)
                    {
                        foreach ($arItem["PRICES"] as $code => $arPrice)
                        {
                            if ($arPrice["VALUE"])
                            {
                                $pict = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>142, 'height'=>210), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                $curr_author = CIBlockElement::GetByID($arItem["PROPERTIES"]["AUTHORS"]["VALUE"][0]) -> Fetch();
                            ?>
                            <div class="bookWrapp">
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                    <div class="item_img">   
                                             <?if($pict["src"] != ''){?>
                                                <img src="<?=$pict["src"]?>">    
                                             <?}else{?>
                                                <img src="/images/no_photo.png">      
                                             <?}?>
                                            <?if(!empty($arItem["PROPERTIES"]["number_volumes"]["VALUE"])){?>
                                              <span class="volumes"><?=$arItem["PROPERTIES"]["number_volumes"]["VALUE"]?></span>
                                            <?}?>
                                    </div>
                                    <p class="bookName" title="<?=$arItem["NAME"]?>"><?=$arItem["NAME"]?></p>
                                    <p class="bookAutor"><?=$curr_author["NAME"]?></p>
                                    <p class="tapeOfPack"><?=$arItem["PROPERTIES"]["COVER_TYPE"]["VALUE"]?></p>
                                    <?
                                        if ($arPrice["DISCOUNT_VALUE_VAT"])
                                        {
                                        ?>
                                        <p class="bookPrice"><?=ceil($arPrice["DISCOUNT_VALUE_VAT"])?> <span>руб.</span></p>
                                        <?
                                        }
                                        else
                                        {
                                        ?>
                                        <p class="bookPrice"><?=ceil($arPrice["ORIG_VALUE_VAT"])?> <span>руб.</span></p>
                                        <?
                                        }
                                    ?>
                                </a>
                            </div>
                            <?      
                            } 
                        }
                    }?>
                </div>
                <a href="#" class="allBooks">Показать ещё</a>
            </div>
</div>
<?
    if (!isset($_SESSION[$APPLICATION -> GetCurDir()]))
    {
        $_SESSION[$APPLICATION -> GetCurDir()] = 1;
    }
?>
<script>
// скрипт ajax-подгрузки товаров в блоке "Все книги"
$(document).ready(function() {
        
        <?$navnum = $arResult["NAV_RESULT"]->NavNum;?>
        <?if (isset($_REQUEST["PAGEN_".$navnum])) {?>
            var page = <?=$_REQUEST["PAGEN_".$navnum]?> + 1;
        <?}else{?>
            var page = 2;
        <?}?>
        var maxpage = <?=($arResult["NAV_RESULT"]->NavPageCount)?>;
            $('.allBooks').click(function(){
                $.fancybox.showLoading();
                $.get('<?=$arResult["SECTION_PAGE_URL"]?>?PAGEN_<?=$navnum?>='+page, function(data) {
                    var next_page = $('.catalogBooks .bookWrapp', data);
                    //$('.catalogBooks').append('<br /><h3>Страница '+ page +'</h3><br />');
                    $('.catalogBooks').append(next_page);
                    page++;          
                })
                .done(function() 
                {
                    $.fancybox.hideLoading();
                    $(".bookName").each(function()
                    {
                        if($(this).length > 0)
                        {
                            $(this).html(truncate($(this).html(), 40));    
                        }    
                    });
    
                });
                if (page == maxpage) {
                    $('.allBooks').hide();
                    //$('.phpages').hide();
                }
                return false;
            });
    <?if (isset($_SESSION[$APPLICATION -> GetCurDir()]))
    {
    ?>
        var upd_page = <?=$_SESSION[$APPLICATION -> GetCurDir()]?>;
        for (i = 2; i <= upd_page; i++)
        {
            $.get('<?=$arResult["SECTION_PAGE_URL"]?>?PAGEN_<?=$navnum?>='+i, function(data) {
                    var next_page = $('.catalogBooks .bookWrapp', data);
                    //$('.catalogBooks').append('<br /><h3>Страница '+ page +'</h3><br />');
                    $('.catalogBooks').append(next_page);
                             
                })
                .done(function() 
                {
                    $(".bookName").each(function()
                    {
                        if($(this).length > 0)
                        {
                            $(this).html(truncate($(this).html(), 40));    
                        }    
                    });
    
                });
                if (upd_page == maxpage) {
                    $('.allBooks').hide();
                    //$('.phpages').hide();
                }    
        }
    <?
    }
    ?>
    });
</script>
