<?php
$listIds = [221,231];
$parent = get_term(221);
$categories = get_term_children( 221, 'product_cat' );
$datas = array(
    221=> array(
        'B' => array(48),
        'Đ' => array(225,226),
        'D' => array(223,51,149),
        'G' => array(222),
        'L' => array(49),
        'Q' => array(66),
    ),
    635=> array(
        'B' => array(636,637),
        'L' => array(638),
        'T' => array(639),
        'V' => array(640)
    ),
    646=> array(
        'B' => array(647,648),
        'G' => array(649),
        'Q' => array(650),
        'T' => array(651)
    ),
    665=> array(
        'B' => array(666),
        'L' => array(667),
        'Q' => array(668),
    ),
    674=> array(
        'G' => array(675,676,677),
        'Q' => array(679),
        'T' => array(680),
    ),
    596=> array(
        'B' => array(597,598),
        'G' => array(599),
        'H' => array(600),
        'P' => array(601),
        'Q' => array(602),
        'T' => array(603),
    ),
    538=> array(
        'D' => array(539),
        'G' => array(540),
        'P' => array(541),
        'Q' => array(542),
        'T' => array(543),
    ),
    770=> array(
        'D' => array(771),
        'G' => array(772),
        'P' => array(773),
        'Q' => array(774),
    ),
    850=> array(
        'C' => array(851),
        'D' => array(852),
        'G' => array(853),
        'P' => array(854),
        'Q' => array(855),
        'V' => array(856),
    ),
    231=> array(
        'G' => array(255),
        'P' => array(257),
        'Q' => array(254),
        'T' => array(256),
    ),
    336=> array(
        'G' => array(337),
        'P' => array(338),
        'Q' => array(339),
        'T' => array(340,122),
    ),
    750=> array(
            'P' => array(751,752),
        'T' => array(753),
        'X' => array(754),
    ),
    894=> array(
            'B' => array(895),
            'G' => array(896),
        'Q' => array(897),
        'T' => array(898),
    ),
    361=> array(
            'B' => array(362),
            'M' => array(363),
        'P' => array(364),
        'T' => array(365),
    ),
    382=> array(
            'G' => array(383),
            'M' => array(384),
        'P' => array(385),
        'Q' => array(386),
    ),
    295=> array(
            'G' => array(296),
            'H' => array(297),
            'M' => array(298),
        'P' => array(299),
        'Q' => array(300),
    ),
    875=> array(
            'D' => array(876),
            'G' => array(877),
        'Q' => array(878),
    ),
    738=> array(
            'M' => array(739),
            'P' => array(740),
        'S' => array(741),
    ),
    720=> array(
            'D' => array(721),
            'G' => array(722),
    )
);
$chaybo = 231;
$cardio = 770;
$trekkingandhikking = 221;
?>
<div class="menu-vertical menu--animatable menu--visible" style="display: none">
    <div id="mobile_display"></div>
    <div class="app">
        <div class="app-menu">
            <div class="app-menu_level app-menu_level1">
                <div class="app-menu-header clearfix menu_logo" id="hidden-mobile"><a href="/"><img src="https://didaudodi.com/wp-content/uploads/2020/10/LOGO-white-147-47.png" title="Decathlon" alt="menu logo"></a></div>
                <div class="app-menu-content app-menu-content_level1">
                    <div class="part-1">
                        <?php foreach ($datas as $key => $value): ?>
                            <ul class="menu-categories" id ="<?= $key ?>">
                                <li class="has-children"><a>
                                        <?= get_the_category_by_ID($key) ?>
                                    </a></li>
                            </ul>
                        <?php endforeach; ?>
                    </div>
                    <div class="part-2"></div>
                </div>
            </div>
        </div>
        <div class="app-menu-z">
            <div class="favorites">
                <div class="col-sm-4 col-xs-4"><a href="<?= get_category_link(get_term($chaybo)) ?>"
                                                  class="clickable"><img class="img-responsive"
                                                                         src="<?= wp_get_attachment_url(get_woocommerce_term_meta( $chaybo, 'thumbnail_id', true )) ?>"
                                                                         alt="Chạy bộ"><span><?=get_the_category_by_ID($chaybo)?></span></a>
                </div>
                <div class="col-sm-4 col-xs-4"><a href="<?= get_category_link(get_term($cardio)) ?>"
                                                  class="clickable"><img class="img-responsive"
                                                                         src="<?= wp_get_attachment_url(get_woocommerce_term_meta( $cardio, 'thumbnail_id', true )) ?>"
                                                                         alt="Tập Cardio"><span><?=get_the_category_by_ID($cardio)?></span></a>
                </div>
                <div class="col-sm-4 col-xs-4"><a href="<?= get_category_link(get_term($trekkingandhikking)) ?>"
                                                  class="clickable"><img class="img-responsive"
                                                                         src="<?= wp_get_attachment_url(get_woocommerce_term_meta( $trekkingandhikking, 'thumbnail_id', true )) ?>"
                                                                         alt="HIKING &amp; TREKKING"><span><?=get_the_category_by_ID($trekkingandhikking)?></span></a>
                </div>
            </div>
            <div class="head-menu bg-grey">
                <div class="head-text"><input type="text" aria-label="Tìm kiếm môn thể thao của bạn"
                                              placeholder="Tìm kiếm môn thể thao của bạn"
                                              class="app-menu_filter search_sport"
                                              id="search_sport">
                    <div class="app-menu_filter_empty hidden">Xin lỗi. Chúng tôi vẫn chưa có môn thể thao
                        này
                    </div>
                </div>
            </div>
            <?php
            $show = 0;
            foreach ($datas as $idParent => $value):?>
                <div class="app-menu_level app-menu_level2 list_sport" id="cat_<?=$idParent?>" <?php if ($show):?> style="display: none;" <?php endif;?>>
                    <?php $show++ ?>
                    <?php foreach ($value as $firstWord => $items):?>
                        <div class="accordion">
                            <div class="app-menu-content app-menu-content_level3">
                                <button id="headingOne" class="btn btn-link main_title panel-title collapsed"
                                        type="button" data-toggle="collapse" data-target="#collapse<?= $firstWord.$idParent ?>"
                                        aria-expanded="false" aria-controls="collapse<?= $firstWord.$idParent ?>"><?= $firstWord ?>
                                </button>
                                <div id="collapse<?= $firstWord.$idParent ?>" class="collapse in second_title_box show"
                                     aria-labelledby="headingOne" data-parent="#accordion">
                                    <ul>
                                        <?php foreach ($items as $item):?>
                                            <li id="<?= $item ?>" class="card card-body second_title"><a
                                                    class="js-search app-menu__list app-menu__list--show"
                                                    data-name="Giày-Giày" href="<?= get_category_link(get_term($item)) ?>"><?=get_the_category_by_ID($item)?></a>
                                            </li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            <?php endforeach;?>
        </div>
        <div class="close_button_vmenu" id="hidden-mobile">
            <button class="app-menu_close">
                <i class="decashop decashop-close" aria-hidden="true">
                    <img src="https://www.decathlon.vn/modules/decashopverticalmenu/views/img/close_vmenu.svg" alt="close menu">
                </i>
                <span>Đóng</span>
            </button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".search_sport").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".list_sport li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<script>
    jQuery( document ).ready( function( $ ) {
        $(".menu-categories").click(function(event){
            $(".app-menu_level2").css('display', 'none');
            var id = '#cat_'+$(this).attr('id');
            $(id).css('display', 'block');
        });
        $(".menu-vertical-icon").click(function(event){
            $(".menu--animatable").css('display', 'block');
        });
        $(".app-menu_close").click(function(event){
            $(".menu--animatable").css('display', 'none');
        });
        $(".panel-title").click(function(event){
            var classup = $(event.target).data('target');
            if($( classup ).hasClass( "in" ) )
            {
                $( classup ).removeClass("show");
            }
            else {
                $( classup ).addClass("show");
            }
        });
    });
</script>
<style>
    header#header .menu-vertical-icon {
        margin-top: 0;
    }
    .pull-left {
        float: left!important;
    }
    .menu-vertical.menu--visible .app-menu::-webkit-scrollbar {
        width: 0;
        height: 0;
    }
    .menu-vertical-icon {
        position: relative;
        display: inline-block;
        float: left;
        text-transform: uppercase;
        font-family: "Roboto Condensed",Arial;
        font-size: 12px;
        color: #fafafa;
        background-color: #333;
        border: none;
        height: 77px;
        width: 170px;
        margin-right: 25px;
        background-image: url('https://www.decathlon.vn/modules/decashopverticalmenu/views/img/menu.svg');
        background-repeat: no-repeat;
        background-position: 15% 50%;
        padding: 0 45px 0 70px;
        background-size: 40px 25px;
        text-align: left;
        line-height: 1.2;
        font-weight: 200;
        font-style: italic;
    }
    .menu-vertical.menu--visible .close_button_vmenu {
        z-index: 160;
    }
    .menu-vertical .close_button_vmenu .app-menu_close {
        position: absolute;
        cursor: pointer;
        width: 25px;
        padding: 0;
        border: none;
        background: no-repeat;
        left: 675px;
        top: 25px;
        z-index: 162;
    }

    .menu-vertical .app-menu-z .app-menu-content_level3 button.panel-title::after {
        content: "\f107";
        color: #EDEDED;
        right: 5%;
        position: absolute;
        font-family: "FontAwesome";
        font-size: 25px;
        font-weight: lighter;
        top: 0;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 button.panel-title[aria-expanded="true"]::after {
        content: "\f106"!important;
    }
    .menu-vertical.menu--visible {
        cursor: pointer;
        pointer-events: auto;
        display: block;
    }
    .menu-vertical {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: 1002;
        font-family: "Roboto Condensed",Arial;
        overflow: hidden;
        display: none;
    }
    .menu-vertical .app {
        max-width: 650px;
    }
    .menu-vertical.menu--visible.menu--animatable .app-menu {
        left: 0;
        transition: all 330ms ease-out;
    }
    .menu-vertical.menu--animatable .app-menu {
        left: -103%;
        transition: all 330ms ease-in;
    }
    .menu-vertical.menu--visible .app-menu {
        left: 0;
    }
    .menu-vertical .app-menu {
        position: fixed;
        max-width: 200px;
        width: 90%;
        height: 100%;
        box-shadow: 0 3px 6px rgba(0,0,0,.22);
        left: -103%;
        display: flex;
        flex-direction: column;
        will-change: transform;
        z-index: 161;
        pointer-events: auto;
        overflow-y: auto;
        text-transform: uppercase;

        background-color: #2b9b2d;
        background-repeat: repeat;
        background-attachment: scroll;
        background-position: left top;
        background-size: cover;
    }
    .menu-vertical .app-menu .app-menu_level1 {
        font-size: 17px;
        width: 100%;
        height: 100%;
    }
    #hidden-mobile {
        display: block;
    }
    .menu-vertical .app-menu .app-menu-header {
        height: 75px;
        top: 0;
        text-align: center;
        padding-top: 20px;
    }
    .menu-vertical .app-menu .app-menu_level1 .app-menu-content_level1 {
        padding-left: 0;
        padding-right: 0;
        font-weight: 700;
        margin-top: 0;
    }
    .menu-vertical .app-menu .app-menu_level1 .app-menu-content_level1 .part-1 {
        margin-bottom: 40px;
        border-bottom: 1px solid rgba(219,229,236,.4);
        margin-right: 20px;
        margin-left: 20px;
        padding-bottom: 30px;
    }
    .menu-vertical .app-menu .app-menu_level1 .app-menu-content_level1 .part-1 .menu-categories {
        padding: 0 0 0 0;
        list-style: none;
    }
    .nochild-menu, .menu-vertical .app-menu .app-menu_level1 .app-menu-content_level1 .part-1 .menu-categories .has-children {
        border: 1px solid rgba(255,255,255,.4);
        border-radius: 4px;
        line-height: 45px;
        margin-bottom: 25px;
    }
    .menu-vertical .app-menu .app-menu_level1 li a {
        color: #fff;
        display: block;
        text-decoration: none;
        position: relative;
        padding-right: 25px;
        font-family: 'Roboto Condensed',sans-serif;
        font-weight: 300;
        font-size: 13px;
        letter-spacing: 2px;
        padding-left: 15px;
        line-height: 25px;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .app-menu .app-menu_level1 .app-menu-content_level1 .part-2 {
        margin-right: 20px;
        margin-left: 20px;
    }
    .menu-vertical .app-menu-z {
        position: fixed;
        max-width: 450px;
        width: 100%;
        height: 100%;
        left: 200px;
        display: flex;
        flex-direction: column;
        will-change: transform;
        z-index: 160;
        pointer-events: auto;
        overflow-y: auto;
        text-transform: uppercase;

        background-color: #008000;
        background-repeat: repeat;
        background-attachment: scroll;
        background-position: left top;
        background-size: cover;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 {
        background-image: linear-gradient(to right,#008000,#33aa36);
        background-image: -webkit-linear-gradient(to right,#227CB3,#33A09E);
        background-image: -o-linear-gradient(to right,#227CB3,#33A09E);
        margin-bottom: 0;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 button.panel-title {
        position: relative;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 .main_title {
        background: none;
        border: none;
        width: 100%;
        margin: 0;
        box-shadow: none;
        text-align: left;
        padding-left: 30px;
        color: #EBEBEB;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 button {
        text-decoration: none;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 .second_title_box {
        background: #fff;
    }
    .collapse.in {
        display: block;
    }
    .collapse {
        display: none;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 .second_title:last-child {
        margin-bottom: 0;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 .second_title {
        border: none;
        color: #555;
        font-size: 13px;
        letter-spacing: 2px;
        margin-bottom: 0;
        display: flex;
    }
    .menu-vertical .app-menu-z .app-menu-content_level3 a {
        text-decoration: none;
        color: #555;
        padding: 25px 25px 25px 30px;
        margin-right: 20%;
        border-bottom: 1px solid rgba(149,152,154,.23);
        border-top: none;
        border-right: none;
        border-left: none;
        line-height: 20px;
        width: 100%;
    }
    .menu-vertical ul, .menu-vertical li {
        list-style: none;
    }
    .menu-vertical .app-menu-z .favorites [class^='col-sm'] a {
        font-size: 12px;
        text-align: center;
    }
    .menu-vertical .app-menu-z .favorites [class^='col-sm'] a img {
        margin: 0 auto;
        width: 70%;
        display: block;
        height: auto;
    }
    .menu-vertical .app-menu-z .favorites [class^='col-sm'] a span {
        color: #e3e3e3;
        display: block;
        line-height: 1.6;
        padding-top: 0;
    }
    .menu-vertical .app-menu-z .head-menu {
        position: relative;
        margin-top: 85px;
    }
    .bg-grey {
        background-color: #FAFAFA;
    }
    .menu-vertical .app-menu-z .head-text {
        z-index: 2;
        position: relative;
        background: #fff;
        min-height: 150px;
    }
    .menu-vertical .app-menu-z .head-text input {
        width: 85%;
        min-height: 60px;
        margin: 40px 0 0 30px;
        border: 1px solid rgba(6,67,165,.22);
        border-radius: 8px;
        -moz-box-shadow: 0 0 3px 0 rgba(0,0,0,.1);
        -webkit-box-shadow: 0 0 3px 0 rgba(0,0,0,.1);
        box-shadow: 0 0 3px 0 rgba(0,0,0,.1);
    }
    .menu-vertical .app-menu-z .app-menu_filter {
        width: 100%;
        outline: none;
        color: rgba(128,128,128,.55);
        font-size: 16px;
        background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNy4xMTEgMjYuNzM0Ij4KICA8ZGVmcz4KICAgIDxzdHlsZT4KICAgICAgLmNscy0xIHsKICAgICAgICBvcGFjaXR5OiAwLjY3OwogICAgICB9CgogICAgICAuY2xzLTIgewogICAgICAgIGZpbGw6ICM1NzVkNWU7CiAgICAgIH0KICAgIDwvc3R5bGU+CiAgPC9kZWZzPgogIDxnIGlkPSJzZWFyY2giIGNsYXNzPSJjbHMtMSI+CiAgICA8ZyBpZD0iQ2FscXVlXzIiIGRhdGEtbmFtZT0iQ2FscXVlIDIiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMCkiPgogICAgICA8cGF0aCBpZD0iUGF0aF8zIiBkYXRhLW5hbWU9IlBhdGggMyIgY2xhc3M9ImNscy0yIiBkPSJNMjYuNjA2LDI0LjksMTguMDgsMTYuMzdhMTAuMjI1LDEwLjIyNSwwLDEsMC0xLjI3NCwxLjM0M0wyNS4zLDI2LjJhLjkyNi45MjYsMCwxLDAsMS4zMTEtMS4zWk00LjIsMTUuOTY3YTguMzM2LDguMzM2LDAsMSwxLDUuOSwyLjQ1NCw4LjMzNiw4LjMzNiwwLDAsMS01LjktMi40NTRaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjE3MSAwLjE5MSkiLz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPgo=);
        background-repeat: no-repeat;
        background-size: 7%;
        background-position: center left 5%;
        padding-left: 75px;
    }
    .menu-vertical .app-menu-z .app-menu_filter_empty {
        text-transform: none;
        line-height: 1.2;
        font-weight: 400;
        margin: 15px 0 15px 30px;
        font-size: 16px;
    }
    .hidden {
        display: none!important;
    }
</style>
