<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    use Bitrix\Sale\DiscountCouponsManager;
    use Bitrix\Main;
    use Bitrix\Main\Loader;
    use Bitrix\Main\Localization\Loc;
    use Bitrix\Sale\Internals;

    $bDelayColumn  = false;
    $bDeleteColumn = false;
    $bWeightColumn = false;
    $bPropsColumn  = false;
    $bPriceType    = false;

    if ($normalCount > 0):
    ?>
<script>
	function getBookInfo(id, rec) {
		$.ajax({
			type: "POST",
			url: "/ajax/book_info_inbasket.php",
			data: {id: id, rec:rec}
		}).done(function(strResult) {
			$("#bookInfo").append(strResult);
			$("body").css('overflow','hidden');
			NProgress.done();
		});
	}
	function closeInfo() {
		$('#bookInfo').empty();
		$("body").css('overflow','auto');
	}
	$(document).ready(function() {
		$('.stopProp').click(function(e) {
			e.stopPropagation();
		});
	});
</script>                                                                                              
    <div id="basket_items_list">                       
        <div class="yourBooks" id="cardBlock1" <?if($onlyPreorder || $_REQUEST['preorder']){ echo 'style="display:none"'; }?>>
            <table id="basket_items">
                <thead>
                    <tr>
                        <td></td>
                        <?
                            foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
                                $arHeader["name"] = (isset($arHeader["name"]) ? (string)$arHeader["name"] : '');
                                if ($arHeader["name"] == '')
                                    $arHeader["name"] = GetMessage("SALE_".$arHeader["id"]);
                                $arHeaders[] = $arHeader["id"];

                                // remember which values should be shown not in the separate columns, but inside other columns
                                if (in_array($arHeader["id"], array("TYPE")))
                                {
                                    $bPriceType = true;
                                    continue;
                                }
                                elseif ($arHeader["id"] == "PROPS")
                                {
                                    $bPropsColumn = true;
                                    continue;
                                }
                                elseif ($arHeader["id"] == "DELAY")
                                {
                                    $bDelayColumn = true;
                                    continue;
                                }
                                elseif ($arHeader["id"] == "DELETE")
                                {
                                    $bDeleteColumn = true;
                                    continue;
                                }
                                elseif ($arHeader["id"] == "WEIGHT")
                                {
                                    $bWeightColumn = true;
                                }

                                if ($arHeader["id"] == "NAME"):
                                ?>
                                <td class="item" id="col_<?=$arHeader["id"];?>">
                                <?
                                    elseif ($arHeader["id"] == "PRICE"):
                                ?>
                                <td class="price counTitle" id="col_<?=$arHeader["id"];?>">
                                <?
                                    elseif ($arHeader["id"] == "SUM"):  continue;
                                ?>
                                <?
                                    elseif ($arHeader["id"] == "QUANTITY"):
                                ?>
                                <td class="price quanTitle" id="col_<?=$arHeader["id"];?>">
                                <?
                                    else:
                                ?>
                                <td class="custom" id="col_<?=$arHeader["id"];?>">
                                    <?
                                        endif;
                                ?>
                                <?=$arHeader["name"]; ?>
                            </td>
                            <?
                                endforeach;

                            if ($bDeleteColumn || $bDelayColumn):
                            ?>
                            <td class="custom"></td>
                            <?
                                endif;
                        ?>
                    </tr>
                </thead>

                <tbody>
                    <?
                        $totalQuantity = 0; //общее количество товаров в корзине

						/* для инструментов аналитики */
						$itemsForCriteo = Array();
						$itemsForFloctory = Array();
						$itemsForRetailRocket = array();
						$gtmEnchECommerceCheckout = Array();
						$retailRocketRecs = '';
						$is = 0;
						$gdeslon = '';
						/* конец */

                        foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):     
                            if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"): 
                            $totalQuantity += $arItem["QUANTITY"];
							array_push($gtmEnchECommerceCheckout,"'name': '".$arItem['NAME']."','id': '".$arItem["PRODUCT_ID"]."','category': '".$parentSectionName."','price': '".$arItem["PRICE"]."','quantity': '".$arItem["QUANTITY"]."'"); // Google Analytics Items
							array_push($itemsForCriteo,"'id': '".$arItem["PRODUCT_ID"]."','price': '".$arItem["PRICE"]."','quantity': '".$arItem["QUANTITY"]."'"); // Criteo Items
							if ($is < 15)
								$retailRocketRecs .= $arItem["PRODUCT_ID"].',';
							$is++;
							if ($arItem["QUANTITY"] > 1) {
								for ($gi = 0; $gi < $arItem["QUANTITY"]; $gi++) {
									$gdeslon .= $arItem["PRODUCT_ID"].':'.$arItem["PRICE"].',';
								}
							} else {
								$gdeslon .= $arItem["PRODUCT_ID"].':'.$arItem["PRICE"].',';
							}

                            ?>
                            <tr id="<?=$arItem["ID"]?>">
                                <?
                                    foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

                                        if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE"))) // some values are not shown in the columns in this template
                                            continue;

                                        if ($arHeader["id"] == "NAME"):
                                        ?>
                                        <td class="bookImg ">
                                            <?
                                                if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
                                                    $url = $arItem["PREVIEW_PICTURE_SRC"];
                                                    elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
                                                    $url = $arItem["DETAIL_PICTURE_SRC"];
                                                    else:
                                                    $url = $templateFolder."/images/no_photo.png";
                                                    endif;
                                            ?>
											<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>" onclick="dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'mainProductClick'});<?if (!checkMobile()) echo 'getBookInfo('.$arItem["PRODUCT_ID"].',0);return false';?>"><?endif;?>
												<img src="<?=$url?>">
											<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
                                        </td>
                                        <td class="item bookNameWrap">
                                            <p class="nameOfBook">
												<a href="<?=$arItem["DETAIL_PAGE_URL"] ?>" onclick="dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'mainProductClick'});<?if (!checkMobile()) echo 'getBookInfo('.$arItem["PRODUCT_ID"].',0);return false';?>" >
													<?=$arItem["NAME"]?>
												</a>
                                            </p>
                                            <?
                                            $curr_author = CIBlockElement::GetByID($arItem["PROPERTY_AUTHORS_VALUE"]) -> Fetch();
                                            if ($curr_author)
                                            {?>
                                                <p class="nameOfAutor"><?=$curr_author["NAME"]?></p>
                                            <?}?>
                                            <p class="nameOfType"><?=$arItem["PROPERTY_COVER_TYPE_VALUE"]?></p>
                                            <div class="bx_ordercart_itemart">
                                                <?
                                                    if ($bPropsColumn):
                                                        foreach ($arItem["PROPS"] as $val):

                                                            if (is_array($arItem["SKU_DATA"]))
                                                            {
                                                                $bSkip = false;
                                                                foreach ($arItem["SKU_DATA"] as $propId => $arProp)
                                                                {
                                                                    if ($arProp["CODE"] == $val["CODE"])
                                                                    {
                                                                        $bSkip = true;
                                                                        break;
                                                                    }
                                                                }
                                                                if ($bSkip)
                                                                    continue;
                                                            }

                                                            echo "<p class='nameOfAutor'>".$val["VALUE"]."</p><br/>";
                                                            endforeach;
                                                        endif;
                                                ?>
                                            </div>

                                        </td>
                                        <?
                                            elseif ($arHeader["id"] == "QUANTITY"):
                                        ?>
                                        <td class="custom quantityInp">
                                        	<? if ($arItem['PRODUCT_PROVIDER_CLASS'] != "GiftProductProvider") { // для подарков sailplay не выводим +-?>
	                                            <div>
	                                                <?
	                                                    $ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
	                                                    $max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
	                                                    $useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
	                                                    $useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
	                                                ?>

	                                                <?
	                                                    if (!isset($arItem["MEASURE_RATIO"]))
	                                                    {
	                                                        $arItem["MEASURE_RATIO"] = 1;
	                                                    }

	                                                    if (
	                                                        floatval($arItem["MEASURE_RATIO"]) != 0
	                                                    ):
	                                                    ?>
	                                                    <a href="javascript:void(0);" class="minus" onclick="setQuantity(<?=$arItem["ID"]?>, <?=$arItem["MEASURE_RATIO"]?>, 'down', <?=$useFloatQuantityJS?>);dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'minusOne'});">-</a>
	                                                    <?endif;?>
	                                                <input
	                                                    class="quantityField"
	                                                    type="text"
	                                                    size="3"
	                                                    id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
	                                                    name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
	                                                    size="2"
	                                                    maxlength="18"
	                                                    min="0"
	                                                    <?=$max?>
	                                                    step="<?=$ratio?>"
	                                                    style="max-width: 50px"
	                                                    value="<?=$arItem["QUANTITY"]?>"
	                                                    onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', <?=$ratio?>, <?=$useFloatQuantityJS?>);dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'updateQuantity'});"
	                                                    >
	                                                <? if (floatval($arItem["MEASURE_RATIO"]) != 0):?>
	                                                    <a href="javascript:void(0);" class="plus" onclick="setQuantity(<?=$arItem["ID"]?>, <?=$arItem["MEASURE_RATIO"]?>, 'up', <?=$useFloatQuantityJS?>);dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'plusOne'});">+</a>
	                                                    <?endif;?>
	                                                <input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
	                                            </div>
                                            <? } else { ?>
												<span class="sailplay_basket_quantity"><?= $arItem["QUANTITY"] ?></span>
                                            <? } ?>
                                        </td>
                                        <?
                                            elseif ($arHeader["id"] == "PRICE"):
                                        ?>
                                        <td class="price priceOfBook">
                                            <p class="current_price costOfBook" id="current_price_<?=$arItem["ID"]?>">
                                                <?=$arItem["PRICE"]?><span class='rubsign'></span>
                                            </p>
                                            <p class="old_price costOfBook" id="old_price_<?=$arItem["ID"]?>">
                                                <?if (floatval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0):?>
                                                    <?=$arItem["FULL_PRICE"]?><span class='rubsign'></span>
                                                    <?endif;?>
                                            </p>
                                        </td>
                                        <?
                                            elseif ($arHeader["id"] == "DISCOUNT"):
                                        ?>
                                        <td class="custom price priceOfBook">
                                            <p id="discount_value_<?=$arItem["ID"]?>" class="costOfBook"><?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?></p>
                                        </td>
                                        <?
                                            elseif ($arHeader["id"] == "SUM"):  continue;
                                        ?>
                                        <?
                                            else:
                                        ?>
                                        <td class="custom">
                                            <span><?=$arHeader["name"]; ?>:</span>
                                            <?echo $arItem[$arHeader["id"]];?>
                                        </td>
                                        <?
                                            endif;
                                        endforeach;

                                    if ($bDelayColumn || $bDeleteColumn):
                                    ?>
                                    <td class="control cancelBook">
                                        <?
                                            if ($bDeleteColumn):
                                            ?>
											<?if($USER->IsAuthorized()){?>
												<a class="bookDelay" href="#" onclick="addToWishList(<?=$arItem["PRODUCT_ID"]?>, <?=$arItem["ID"]?>);dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'addToWishList'});"><?=GetMessage("SALE_DELAY")?></a>
											<?}?>
                                            <a class="bookDelete" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" onclick="dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'deleteBook'});"><?=GetMessage("SALE_DELETE")?></a>
                                            <?endif;?>
                                    </td>
                                    <?
                                        endif;
                                ?>
                            </tr>
                            <?
                                endif;
                            endforeach;
                    ?>
                </tbody>
            </table>


            <div class="grayDownLine"></div>
            <?
	        $psum = $arResult['allSum'];
	        $pdiscabs = $arResult['DISCOUNT_PRICE_ALL'];
	        $pdiscrel = round(((100*$pdiscabs)/($pdiscabs+$psum)), 0);
            $discount_user = CCatalogDiscountSave::GetDiscount(array('USER_ID' => $USER->GetID()));
	        if ($psum < 2000) {
		        $printDiscountText = "<a href='/catalog/crossbooks/' target='_blank'>Добавьте товаров</a> на " . round((2000 - $psum), 2) ."<span class='rubsign'></span> и получите БЕСПЛАТНУЮ доставку";
	        } elseif ($psum < 3000 && $pdiscrel < 10) {
		        $printDiscountText = "<a href='/catalog/crossbooks/' target='_blank'>Добавьте товаров</a> на " . round((3000 - $psum), 2)."<span class='rubsign'></span> и получите скидку 10%";
	        } elseif ($psum < 10000 && $pdiscrel < 20) {
		        $printDiscountText = "<a href='/catalog/crossbooks/' target='_blank'>Добавьте товаров</a> на " . round((10000 - $psum), 2)."<span class='rubsign'></span> и получите скидку 20%";
	        }?>
			<div id="discountMessageWrap" style="color: #353535;font-family: 'Walshein_regular';font-size: 15px;text-aling: right;text-align: right;padding: 10px 30px;">
				<span id="discountMessage" style="background:#fff9b7"><span class='sale_price'><?=$printDiscountText?></span></span>
			</div>

            <p class="finalCost"><span id="allSum_FORMATED"><?=str_replace(" ", "&nbsp;", $arResult["allSum"])?><b class="rubsign"></b></span></p>
            <p class="finalQuant">Кол-во: <span id="totalQuantity"><?=$totalQuantity?></span></p>
            <p class="finalText">Итого</p>
            <?
                //TODO сделать автоматический расчет суммы, оставшейся до скидки, основываясь на правилах работы с корзиной
                //получаем правила работы с корзиной
                $filterCoup=array();
                $discountIteratorCoup = Internals\DiscountTable::getList(array(
                    'filter' => $filterCoup
                ));
                $arDiscount = $discountIteratorCoup->fetch();    
            ?>
            <?/*
            <p class="finalDiscount">Вам не хватает 770<span class='rubsign'></span> и получите скидку 10%</p>
            */?>

            <p class="promoWrap"><span class="promocode" onclick="$('#coupon, #acceptCoupon').toggle();dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'promoCodeToggle'});">Есть промо-код/сертификат?<span></p>

            <div class="bx_ordercart_order_pay_left" id="coupons_block">
                <div class="bx_ordercart_coupon">
                    <input type="text" id="coupon" class="couponInput" name="COUPON" value="" style="margin-right:12px;"><br /><a href="#" id="acceptCoupon" onclick="enterCouponCustom();dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'promoCodeApply'});return false;">Применить</a>
                    <input type="hidden" id="priceBasketToCoupon" value="<?=$arResult["allSum"]?>">       
                </div><?
                    if (!empty($arResult['COUPON_LIST']))
                    {
                        foreach ($arResult['COUPON_LIST'] as $oneCoupon)
                        {
                            $couponClass = 'disabled';
                            switch ($oneCoupon['STATUS'])
                            {
                                case DiscountCouponsManager::STATUS_NOT_FOUND:
                                case DiscountCouponsManager::STATUS_FREEZE:
                                    $couponClass = 'bad';
                                    break;
                                case DiscountCouponsManager::STATUS_APPLYED:
                                    $couponClass = 'good';
                                    break;
                            }
                        ?><div class="bx_ordercart_coupon"><input disabled readonly type="text" name="OLD_COUPON[]" value="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" class="<? echo $couponClass; ?>"><span class="<? echo $couponClass; ?>" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>"></span><div class="bx_ordercart_coupon_notes"><?
                                if (isset($oneCoupon['CHECK_CODE_TEXT']))
                                {
                                    echo (is_array($oneCoupon['CHECK_CODE_TEXT']) ? implode('<br>', $oneCoupon['CHECK_CODE_TEXT']) : $oneCoupon['CHECK_CODE_TEXT']);
                                }
                            ?></div></div><?
                        }
                        unset($couponClass, $oneCoupon);
                    }
                ?>
            </div>
                        <p class="nextPageWrap">
                        	<? if ($arResult['allSum']) { ?>
                        		<a href="javascript:void(0)" onclick="checkOut();dataLayer.push({event: 'EventsInCart', action: '1st Step', label: 'nextStepButtonClick'});$('.nextPageWrap').html('<div id=\'nprogresss\'><div class=\'spinner\'><div class=\'spinner-icon\'></div></div></div>');" class="nextPage"><?=GetMessage("SALE_ORDER")?></a>
                        	<? } else { ?>
                        		<span class="basket_zero_cost"><?= GetMessage("SALE_ZERO_COST") ?></span>
                        	<? } ?>
                        </p> 
        </div>

        <input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
        <input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
        <input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
        <input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
        <input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
        <input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
        <input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
        <input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />
		
		<!-- gdeslon -->
		<script type="text/javascript" src="https://www.gdeslon.ru/landing.js?mode=basket&amp;codes=<?=substr($gdeslon,0,-1)?>&amp;mid=79276"></script>
		<?$_SESSION['gtmEnchECommerceCheckout'] = $gtmEnchECommerceCheckout;?>
		<?$_SESSION['itemsForCriteo']			= $itemsForCriteo;?>
		<?$_SESSION['retailRocketRecs']			= $retailRocketRecs;?>
		<?$_SESSION['gdeslon']					= substr($gdeslon,0,-1);?>
		


        <div class="bx_ordercart_order_pay">
            <?/*
                <div class="bx_ordercart_order_pay_right">
                <table class="bx_ordercart_order_sum">
                <?if ($bWeightColumn && floatval($arResult['allWeight']) > 0):?>
                <tr>
                <td class="custom_t1"><?=GetMessage("SALE_TOTAL_WEIGHT")?></td>
                <td class="custom_t2" id="allWeight_FORMATED"><?=$arResult["allWeight_FORMATED"]?>
                </td>
                </tr>
                <?endif;?>
                <?if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y"):?>
                <tr>
                <td><?echo GetMessage('SALE_VAT_EXCLUDED')?></td>
                <td id="allSum_wVAT_FORMATED"><?=$arResult["allSum_wVAT_FORMATED"]?></td>
                </tr>
                <?if (floatval($arResult["DISCOUNT_PRICE_ALL"]) > 0):?>
                <tr>
                <td class="custom_t1"></td>
                <td class="custom_t2" style="text-decoration:line-through; color:#828282;" id="PRICE_WITHOUT_DISCOUNT">
                <?=$arResult["PRICE_WITHOUT_DISCOUNT"]?>
                </td>
                </tr>
                <?endif;?>
                <?
                if (floatval($arResult['allVATSum']) > 0):
                ?>
                <tr>
                <td><?echo GetMessage('SALE_VAT')?></td>
                <td id="allVATSum_FORMATED"><?=$arResult["allVATSum_FORMATED"]?></td>
                </tr>
                <?
                endif;
                ?>
                <?endif;?>
                <tr>
                <td class="fwb"><?=GetMessage("SALE_TOTAL")?></td>
                <td class="fwb" ><?=str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"])?></td>
                </tr>


                </table>
                <div style="clear:both;"></div>
                </div>
            */?>

            <div style="clear:both;"></div>
            <?if ($arParams["USE_PREPAYMENT"] == "Y" && strlen($arResult["PREPAY_BUTTON"]) > 0):?>
                <?=$arResult["PREPAY_BUTTON"]?>
                <span><?=GetMessage("SALE_OR")?></span>
                <?endif;?>

        </div>
    </div>
    <?
        else:
    ?>
    <div id="basket_items_list">
        <div class="yourBooks" id="cardBlock1" <?if($onlyPreorder){ echo 'style="display:none"'; }?>>
            <table>
                <tbody>
                    <tr>
                        <td colspan="<?=$numCells?>" style="text-align:center; width: 300px;">
                            <div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?
        endif;
?>
<div id="bookInfo"></div>