<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$isGuest = $data['isGuest'];
$page_title_01 = $data['page_title_01'];

?>

<style>
    :target { scroll-margin-top: 72px; }
    #scrollContainer {
        background: #f5f6f7;
    }
</style>

<!-- Head -->
<?php 
    $page_title_01 = $page_title_01 ?? '큐레이션 상세';
    include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; 
?>

<!-- Head -->
<script type="text/javascript" src="/assets/app/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/assets/app/js/underscore-min.1.13.7.js"></script>
<script type="text/javascript" src="/assets/app/js/moment.2.30.1.js"></script>
<script type="text/javascript" src="/assets/app/js/commonNew.js?v=<?= $_ENV['VERSION']; ?>"></script>
<script type="text/javascript" src="/assets/app/js/commonWeb.js?v=<?= $_ENV['VERSION']; ?>"></script>

<body> 

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-top.php";
    }
    ?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/h1.php"; ?>

    <div id="mobileWrap" class="curationDetail__wrap">
        <header class="header__wrap">
			<div class="header__inner" id="curationTitle"></div>
		</header>
        
        <div class="curation_detail_wrap">
            <div class="container__wrap" style="background-color: transparent;">
                <div class="curation_tit_box" id="curationInfo"></div>
                
                <div class="curations_parnter_list" id="curationItems"></div>
            </div>

            <!-- 토스트팝업 -->
            <div id="toastPopupLike" class="toast__wrap" style="margin-left: 0;">
                <div class="toast__container">
                    <i class="ico ico-info"></i>
                    <p></p>
                </div>
            </div>
            <!-- //토스트팝업 -->
        </div>

        <!-- 찜하기 로그인 팝업 -->
        <div id="loginLikePopup" class="layerpop__wrap type-center mobileweb-popup">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <i class="ico ico-logo__big"></i>
                    <p class="ft-xxl" style="word-break: keep-all;">
                        로그인이 필요해요.<br>
                        아래의 방법으로 간편하게 로그인 해보시겠나요?
                    </p>
                </div>
                <div class="layerpop__footer" style="display: flex; align-items: center; gap: 1rem;">
                    <button class="btn-full__primary btn-sns__kakao" onclick="location.href='/auth/kakao/redirect?return=' + encodeURIComponent(window.location.href)" style="white-space: nowrap; font-size: 1.2rem;">카카오 1초 로그인</button>
                    <button type="button" class="btn-full__line__primary" onclick="gotoLoginEmail()" style="font-size: 1.2rem;">이메일로 계속하기</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 페이지 로딩 -->
    <div id="pageLoader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div>
    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
    }
    ?>

    <!-- 큐레이션 타이틀 템플릿 -->
    <script id="tmplCurationTitle" type="text/template">
        <button class="btn-back" onclick="previousBlankPage()"><span class="blind">뒤로가기</span></button>
        <h2 class="header-tit__center">
            <@=curation.curationTitle@>
        </h2>
        
        <div class="btn__wrap">
            <button class="btn-share" onclick="sendShareLink('뭉클트립에서 <@=curation.curationTitle@>의 뭉클 정보를 공유했어요. 자세한 내용은 아래 링크를 확인해보세요.', '<?= $_ENV['APP_HTTP'] . '/assets/app/images/common/moongcle_color_807_257.png' ?>')"><span class="blind">공유하기</span></button>
        </div>
    </script>

    <!-- 큐레이션 템플릿 -->
    <script id="tmplCuration" type="text/template">

        <@ if (curation.curationVisibleTo && new Date(curation.curationVisibleTo) < new Date()) { @>
            <span class="end_curation">
                📢 &nbsp;&nbsp;본 추천 리스트는 마감 되었습니다.
            </span>
        <@ } @>

        <h3>
            <@=curation.curationTitle@>
        </h3>
        <p>
            <@=curation.curationDescription@>
        </p>
    </script>

    <!-- 숙소 템플릿 -->
    <script id="tmplCurationItems" type="text/template">
        <p class="all">
            총 <span class="point"><@= curationItems.length @></span>개 추천 숙소
        </p>

        <div class="wrap__list curation_detail" style="margin-top: 2rem; padding-bottom: 1rem;">
            <@ _.each(curationItems, function(item, i) { @>   
            <div class="curation_item detail">
                
                    <div class="img_box">
                        <a href="/stay/detail/<@= item.targetIdx @>" onclick="showLoader();">
                            <div class="splide splide__product">
                                <div class="splide__track" style="height: 100%;">
                                    <ul class="splide__list">
                                        <@ if (item.images && item.images.length) { @>
                                            <@ _.each(item.images, function(src) { @>
                                            <li class="splide__slide splide__list__product curation_img">
                                                <img src="<@= src @>" alt="숙소 이미지">
                                            </li>
                                            <@ }); @>
                                        <@ } else { @>
                                            <li class="splide__slide splide__list__product">
                                                <img src="/assets/app/images/demo/moongcle-noimg.png" alt="숙소 이미지">
                                            </li>
                                        <@ } @>
                                    </ul>
                                </div>
                                <@ if (item.images && item.images.length) { @>
                                    <div class="slide-counter">
                                        <span class="current-slide">1</span> / <span class="total-slides"></span>
                                    </div>
                                <@ } @>  
                            </div>

                            <!-- 한 줄 설명 -->
                            <@ if (item.targetDescription) { @>
                                <div class="curation_desc">
                                    <p><@= item.targetDescription @></p>
                                </div>
                            <@ } @>
                        
                            <!-- 뭉클딜 뱃지 -->
                            <div class="badge-group left-top">
                                <@ if (item.badgeInfo.moongcleofferSaleEndDate) { @>
                                    <div class="badge range"
                                        data-end-date="<@= item.badgeInfo.moongcleofferSaleEndDate @>">
                                        <i><img src="/assets/app/images/common/ico_cir_time.svg"></i>
                                        뭉클딜 <span class="remain"><@= getRemainTime(item.badgeInfo.moongcleofferSaleEndDate) @></span>
                                    </div>
                                <@ } else if (item.badgeInfo.moongcleofferCount > 0 && !item.badgeInfo.moongcleofferSaleEndDate) { @>
                                    <div class="badge live">
                                        <i><img src="/assets/app/images/common/ico_cir_new.svg"></i>
                                        실시간 뭉클딜
                                    </div>
                                <@ } @> 
                            </div> 
                        </a>
                    </div>
                    <div class="bottom_wrap">
                        <div class="tit_wrap">
                            <div class="partner_info">
                                <h4 class="partner_tit"><@= item.targetName @></h4>
                                <div class="address">
                                    <@ if (item.targetRegion) { @>
                                        <span><@= item.targetRegion @></span>
                                    <@ } @>
                                    <@ if (item.targetCity) { @>
                                        <span><@= item.targetCity @></span>
                                    <@ } @>

                                    <@ if (!(item.targetCity) || !(item.targetRegion) && item.targetAddress1) { @>
                                        <span><@= item.targetAddress1 @></span>
                                    <@ } @>
                                </div>
                            </div>
                            <button
                                type="button"
                                class="btn-product__like type-black <@= item.isFavorited ? 'active' : '' @>"
                                style="bottom: auto; right: 0;"
                                onclick="toggleFavorite(
                                    <?= !empty($user->user_idx) && !$isGuest ? $user->user_idx : 0 ?>,
                                    <@= item.targetIdx ? item.targetIdx : 0 @>
                                )">
                                <span class="blind">찜하기</span>
                            </button>
                        </div>
                        <@ if (item.targetTags && item.targetTags.length) { @>
                            <div class="tag_wrap select__wrap type-img curation_tag" style="height: 3rem;">
                                <ul style="justify-content: flex-start;">
                                    <@ _.each(item.targetTags, function(tag, i) { @>   
                                        <li>
                                            <img src="/uploads/tags/<@= tag.icon @>.png?v=<?= $_ENV['VERSION'] ?>" 
                                                alt="" width="28" height="28">
                                            <span><@= tag.label @></span>
                                        </li>
                                    <@ }); @>
                                </ul>
                            </div>
                        <@ } @>
                    </div>
                    <a class="btn-more tag">더보기</a>

                    <@ if (item.priceInfo) { @>
                        <div class="price_wrap">
                            <div class="price_box">
                                <div class="price_info"
                                        data-basic="<@= item.priceInfo.minPriceBasic || 0 @>"
                                        data-sale ="<@= item.priceInfo.minPriceSale  || 0 @>">
                                    <@ if (item.priceInfo.minPriceSale !== item.priceInfo.minPriceBasic) { @>
                                        <div>
                                            <p class="sale-percent"></p>
                                            <p class="default-price"><@= Number(item.priceInfo.minPriceBasic||0).toLocaleString('ko-KR') @>원</p>
                                        </div>
                                    <@ } @>
                                    <p class="sale-price"><@= Number(item.priceInfo.minPriceSale||0).toLocaleString('ko-KR') @>원~</p>
                                </div>
                                <button type="button" class="btn-md__black" onclick="location.href='/stay/detail/<@=item.targetIdx@>'">더 알아보기</button>
                            </div>
                        </div>
                    <@ } @>
                
            </div>

            <@ }); @>
        </div>
    </script>

    <script>
        sessionStorage.setItem('previousPage', window.location.href);
        
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // 페이지가 캐시에서 복원된 경우
                hideLoader();
            } else {
                hideLoader(); // 페이지가 새로 로드된 경우에도 처리
            }
        });
        thirdpartyWebviewZoomFontIgnore();
    </script>

    <script>
        $(function() {

            const match = location.pathname.match(/\/curation\/(\d+)/);
            Global.curationIdx = match ? match[1] : null;

            if(Global.curationIdx) {
                fnCurationDetail(true);
            }

            // 큐레이션 정보 조회
            function fnCurationDetail (useCurationIdx) {
                var data = {};
                if (useCurationIdx) {
                    Api.call({
                        url : '/api/getCuration/'+Global.curationIdx,
                        success : function(data) {
                            var curation = data.body.curation;
                            var curationItems = data.body.curationItems;

                            Tmpl.insert('#curationTitle', '#tmplCurationTitle', {curation: curation});		
                            Tmpl.insert('#curationInfo', '#tmplCuration', {curation: curation});		
                            Tmpl.insert('#curationItems', '#tmplCurationItems', {curationItems: curationItems});		

                            requestAnimationFrame(() => {
                                initCurationItems();   
                                fnProductSlide();      
                                startRemainTicker();
                                fillSalePercents();
                            });
                        }
                    });
                }
            }
        });
    </script>
    
    <script>
        // 할인율 계산
        function fillSalePercents() {
            document.querySelectorAll('.price_info').forEach(el => {
                    const basic = Number(el.dataset.basic || 0);
                    const sale  = Number(el.dataset.sale  || 0);
                    const p = el.querySelector('.sale-percent');
                    if (p && basic > 0 && sale > 0) {
                    p.textContent = Math.round(((basic - sale) / basic) * 100) + '%';
                }
            });
        }
    </script>

    <script>
        function getRemainTime(endDateString) {
            const end = new Date(endDateString.replace(/-/g, '/'));
            const now = new Date();
            let diff = end - now;

            const days  = Math.floor(diff / 86400000);      diff -= days  * 86400000;
            const hours = Math.floor(diff / 3600000);       diff -= hours * 3600000;
            const mins  = Math.floor(diff / 60000);

            return `${days}일 ${hours}시간 ${mins}분 남음`;
        }

        function startRemainTicker() {
            const tick = () => {
                document.querySelectorAll('.badge.range').forEach(el => {
                    const end = el.dataset.endDate;
                    const remainEl = el.querySelector('.remain');
                    if (remainEl) remainEl.textContent = getRemainTime(end);
                });
            };
            tick();
            if (window.__remainTimer) clearInterval(window.__remainTimer);
            window.__remainTimer = setInterval(tick, 60 * 1000); 
        }
    </script>

    <script>
        // 헤더 스크롤
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.querySelector('.header__wrap');
            const threshold = 50;

            const getY = () => {
                let y = window.pageYOffset || 0;
                const els = [
                    document.scrollingElement,
                    document.documentElement,
                    document.body,
                    document.getElementById('scrollContainer'),
                    ...document.querySelectorAll('.scroll-container,[data-scroll-root]')
                ];
                els.forEach(el => { if (el && el.scrollTop != null) y = Math.max(y, el.scrollTop); });
                return y;
            };

            const update = () => {
                header.classList.toggle('scrolled', getY() > threshold);
            };

            const candidates = [
                window,
                document,
                document.scrollingElement,
                document.documentElement,
                document.body,
                document.getElementById('scrollContainer'),
                ...document.querySelectorAll('.scroll-container,[data-scroll-root]')
            ].filter(Boolean);

            candidates.forEach(el => el.addEventListener && el.addEventListener('scroll', update, { passive: true }));

            // window.addEventListener('resize', update, { passive: true });
            // window.addEventListener('load', update, { passive: true });
            update();
        });
    </script>

    <script>
        // 태그 더보기
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.btn-more.tag');
            if (!btn) return;

            e.preventDefault();

            const card = btn.closest('.curation_item');
            if (!card) return;

            const wrap = card.querySelector('.curation_tag');
            if (!wrap) { btn.style.display = 'none'; return; }

            wrap.classList.toggle('open');
            btn.classList.toggle('active');
            const opened = wrap.classList.contains('open');
            btn.setAttribute('aria-expanded', opened);
            btn.textContent = opened ? '더보기' : '더보기';
        });
    </script>

    <script>
        function initCurationItems() {
            const baseH = parseFloat(getComputedStyle(document.documentElement).fontSize) * 3; // 8rem
            document.querySelectorAll('.curation_item').forEach(item => {
                const wrap = item.querySelector('.curation_tag');
                const btn  = item.querySelector('.btn-more.tag');
                const ul   = wrap && wrap.querySelector('ul');
                if (!btn) return;

                if (!wrap || !ul || ul.scrollHeight <= baseH) {
                    btn.style.display = 'none';
                } else {
                    btn.style.display = '';
                    btn.setAttribute('aria-expanded', 'false');
                    btn.textContent = '더보기';
                }
            });

            document.querySelectorAll('#curationItems img').forEach(img => {
                if (!img.complete) {
                    img.addEventListener('load', initCurationItems, { once: true });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => { 
            initCurationItems();
        });
    </script>
</body>

</html>