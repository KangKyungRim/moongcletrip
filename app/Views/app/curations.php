<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];

//큐레이션
$topCurations = $data['topCurations'];
$buttomCurations = $data['buttomCurations'];


//리뷰
$reviews = $data['reviews'];

//배너
$heroBanners    = $data['heroBanners'];
$ribbonBanners  = $data['ribbonBanners'];
?>

<style>
    :target { scroll-margin-top: 72px; }
</style>

<!-- Head -->
<?php 
    include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; 
?>
<!-- Head -->

<body> 

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-top.php";
    }
    ?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/h1.php"; ?>

    <div id="mobileWrap" style="background: #F5F6F7;">

        <div class="container__wrap search__wrap" style="background: none;">
            <div class="bottom__content" style="padding-bottom: 2rem;">

                <div id="bottomSearchBarWrap_new" class="searchBar__wrap bottom fixed" style="margin-top: 0px; opacity: 1; z-index: 100; background: #F5F6F7;">
                    <div class="img__box">
                        <a href="/">
                            <img src="/assets/app/images/common/moongcle_color_807_257.png" alt="로고 이미지" class="logo_bar" width="64" height="20">
                        </a>
                    </div>
                    <div class="search-form__con search-input custom curation" style="background-color: #fff;">
                        <i class="ico ico-search__small"></i>
                        <div class="input__wrap">
                            <input type="text" class="input" name="searchBar" placeholder="아이와 함께 떠나볼까요?" onfocus="window.location.href='/search-start'" readonly="">
                            <button id="searchTextDeleteBtn" type="button" class="btn-input__delete"><i class="ico ico-input__delete"></i></button>
                        </div>
                    </div>
                    <div style="grid-column: 3; grid-row: 1;">
                        <button type="button" class="btn-alarm search_bar" onclick="gotoNotification()"><span class="blind">알림</span></button>
                        <!-- <button type="button" class="btn-my search_bar" onclick="gotoMypage()" style="margin-left: 0.5rem;"><span class="blind">마이페이지</span></button> -->
                    </div>
                </div> 

                <div class="bottomContentWrap" style="margin-top: 1rem;">
                    <!-- 히어로배너 -->
                    <?php if (!empty($heroBanners)): ?>
                    <div class="banner__wrap margin-bottom-30">
                        <div class="swiper-container banner">
                            <div class="swiper-wrapper">
                                <?php foreach ($heroBanners as $heroBanner): ?>
                                    <?php
                                    $linkUrl = '';
                                    $url = $heroBanner->banner_link_url;

                                    if ($heroBanner->banner_link_type == 0) {                                        
                                        $linkUrl = 'href="' . htmlspecialchars($url, ENT_QUOTES) . '"';
                                    } else {
                                        $linkUrl = 'onclick="outLink(\'' . htmlspecialchars($url, ENT_QUOTES) . '\')"';
                                    }
                                    ?>
                                    <div class="swiper-slide">
                                        <a <?= $linkUrl ?> class="banner__item" onclick="showLoader();">
                                            <div class="img__box">
                                                <img src="<?= htmlspecialchars($heroBanner->banner_image_path, ENT_QUOTES) ?>" alt="배너 이미지">
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>                                
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- top 큐레이션 -->
                    <div class="margin-top-30" style="margin-top: 6rem;">
                        <?php
                            if ($topCurations instanceof \Illuminate\Support\Collection) {
                                $topCurations->loadMissing('curationItems');
                            }
                        ?>
                        <?php foreach ($topCurations as $topCuration) : ?>
                            <?php
                                $title = $topCuration->curation_title ?? ($topCuration['curation_title'] ?? '');
                                $desc  = $topCuration->curation_description ?? ($topCuration['curation_description'] ?? '');
                                $idx = $topCuration->curation_idx ?? ($topCuration['curation_idx'] ?? '');

                                if ($topCuration instanceof \Illuminate\Database\Eloquent\Model) {
                                    if (!$topCuration->relationLoaded('curationItems')) {
                                        $topCuration->loadMissing('curationItems');
                                    }
                                    $items = $topCuration->curationItems ?? collect();
                                } else {
                                    $items = $topCuration['curation_items'] ?? [];
                                }
                            ?>
                            <div class="margin-top-30" id="<?= $idx; ?>">
                                <div class="wrap__box">
                                    <div class="curation_head">
                                        <div>
                                            <h4 class="wrap__title padding-x-20"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h4>
                                            <p  class="padding-x-20 wrap__desc"><?= htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?></p>
                                        </div>

                                        <button
                                            type="button"
                                            class="tit_more_btn"
                                            style="margin-bottom:0.8rem;"
                                            onclick="showLoader(); location.href='/curation/<?= $idx ?>';">
                                            더보기
                                        </button>
                                    </div>
                                    
                                    <div class="overflow-x-visible padding-x-20">
                                        <div class="wrap__list" style="margin-top: 2rem; padding-bottom: 1rem;">
                                            <?php if (empty($items) || (is_object($items) && $items->isEmpty())): ?>
                                                <div class="none_partner">적용 숙소가 없습니다.</div>
                                            <?php else: ?>
                                                <?php foreach ($items as $it): ?>
                                                    <?php
                                                        $thumb = $it->target_thumbnail_url ?? ($it['target_thumbnail_url'] ?? '');
                                                        $pdesc = $it->target_description   ?? ($it['target_description']   ?? '');
                                                        $pname = $it->target_name          ?? ($it['target_name']          ?? ($it->target_type ?? ($it['target_type'] ?? '')));
                                                        $ptags = $it->target_tags   ?? ($it['target_tags']   ?? []);
                                                    ?>
                                                    <div class="curation_item">
                                                        <a href="/stay/detail/<?= $it->target_idx ?? '' ?>" onclick="showLoader();">
                                                            <div class="img_box">
                                                                <img src="<?= htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8') ?>" alt="">
                                                                <?php if (!empty($pdesc)) : ?>
                                                                    <div class="curation_desc"><p><?= htmlspecialchars($pdesc, ENT_QUOTES, 'UTF-8') ?></p></div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="bottom_wrap">
                                                                <div class="tit_wrap">
                                                                    <h4 class="partner_tit"><?= htmlspecialchars($pname, ENT_QUOTES, 'UTF-8') ?></h4>
                                                                </div>
                                                                <?php if ($ptags) : ?>
                                                                    <div class="tag_wrap select__wrap type-img curation_tag">
                                                                        <ul style="justify-content: flex-start;">
                                                                            <?php foreach ($ptags as $ptag) : ?>
                                                                                <li>
                                                                                    <img src="/uploads/tags/<?= $ptag['icon']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                                                                    <span><?= $ptag['label']; ?></span>
                                                                                </li>
                                                                            <?php endforeach; ?>
                                                                        </ul>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <a class="btn-more tag">더보기</a>
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                    </div>

                    <!-- 광고 배너 -->
                    <?php if (!empty($ribbonBanners)): ?>
                    <div class="banner__wrap margin-bottom-30 margin-top-30">
                        <div class="swiper-container cta banner">
                            <div class="swiper-wrapper">
                                <?php foreach ($ribbonBanners as $ribbonBanner) : ?>
                                <div class="swiper-slide">
                                    <?php
                                    $linkUrl = '';
                                    $url = $ribbonBanner->banner_link_url;

                                    if ($ribbonBanner->banner_link_type == 0) {                                        
                                        $linkUrl = 'href="' . htmlspecialchars($url, ENT_QUOTES) . '"';
                                    } else {
                                        $linkUrl = 'onclick="outLink(\'' . htmlspecialchars($url, ENT_QUOTES) . '\')"';
                                    }
                                    ?>
                                    <a <?=$linkUrl ?> class="banner__item">
                                        <div class="img__box">
                                            <img src="<?=$ribbonBanner->banner_image_path ?>" alt="배너 이미지">
                                        </div>
                                    </a>
                                </div>  
                                <?php endforeach; ?>             
                            </div>
                            <div class="cta swiper-pagination"></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- 뭉클맘 리얼 후기 -->
                    <div class="margin-bottom-30 margin-top-30 wrap__box" style="margin-top: 6rem;">
                        <div class="tit_wrap d-flex padding-x-20">
                            <h4 class="wrap__title">뭉클맘 리얼 후기</h4>
                            <button
                                type="button"
                                class="tit_more_btn"
                                style="margin-bottom:0.8rem;"
                                onclick="showLoader(); location.href='/community';">
                                더보기
                            </button>
                        </div>

                        <div class="overflow-x-visible main__wrap padding-x-20" style="background: none;">
                            <ul class="review_slide overflow_slide community-list__con">
                                <?php foreach (array_slice($reviews, 0, 15) as $review) : ?>
                                    <?php if (!empty($review->image_list)) : ?>
                                    <li class="review-list__con">
                                        <div class="community-top">
                                            <div class="user-wrap">
                                                <p class="img"><img src="/assets/app/images/common/no_profile.jpg" alt=""></p>
                                                <div style="max-width: 90%;">
                                                    <div class="profile_top">
                                                        <p class="name">
                                                            <?= $review->user_nickname; ?>
                                                        </p>
                                                        <p class="rating">
                                                            <i class="ico ico-star"></i>
                                                            <?= $review->rating; ?>
                                                        </p>
                                                    </div>
                                                    <div class="community-bottom">
                                                        <?php
                                                            $createdAt = new DateTime($review->created_at);
                                                            $formattedDate = $createdAt->format('y. m. d H:i');
                                                        ?>
                                                        <div class="community-time"><?= $formattedDate; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="img_box">
                                            <div class="splide splide__product">
                                                <div class="splide__track">
                                                    <ul class="splide__list">
                                                        <?php
                                                            $reviewImages = [];

                                                            if (!empty($review) && !empty($review->image_list)) {
                                                                $decoded = json_decode($review->image_list);

                                                                if (is_array($decoded) || is_object($decoded)) {
                                                                    $reviewImages = $decoded;
                                                                }
                                                            }
                                                        ?>
                                                        <?php if (!empty($reviewImages)) : ?>
                                                            <?php foreach ($reviewImages as $reviewImage) : ?>
                                                                <?php
                                                                $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                                $allowedVideoExtensions = ['mov', 'mp4'];
                                                                ?>
                                                                <?php if (in_array($reviewImage->extension, $allowedImageExtensions)) : ?>
                                                                    <li class="splide__slide splide__list__product"><img src="<?= $reviewImage->path; ?>" alt=""></li>
                                                                <?php elseif (in_array($reviewImage->extension, $allowedVideoExtensions)) : ?>
                                                                    <li class="splide__slide splide__list__product">
                                                                        <video class="video-element" controls>
                                                                            <source src="<?= $reviewImage->origin_path; ?>" type="video/<?= $reviewImage->extension; ?>">
                                                                            현재 브라우저가 지원하지 않는 영상입니다.
                                                                        </video>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <li class="splide__slide splide__list__product">
                                                                <img src="/assets/app/images/demo/moongcle-noimg.png" alt="">
                                                            </li>
                                                        <?php endif; ?> 
                                                    </ul>
                                                </div>
                                                <?php if (!empty($reviewImages)) : ?>
                                                    <div class="slide-counter">
                                                        <span class="current-slide">1</span> / <span class="total-slides"></span>
                                                    </div>
                                                <?php endif; ?> 
                                            </div>
                                        </div>
                                        <div class="review-list__con">
                                            <?php
                                            $content = $review->review_content;
                                            $limit = 100; 

                                            $isLong = mb_strlen(strip_tags($content)) > $limit;
                                            ?>
                                            <div class="review-txt">
                                                <p class="review" style="white-space: pre-line;"><?= htmlspecialchars($content); ?></p>
                                                <?php if ($isLong): ?>
                                                    <a class="btn-more">더보기</a>
                                                <?php else: ?>
                                                    <a style="height: 1.4rem; display: block; margin-top: 0.8rem;"></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <a href="/stay/detail/<?= $review->partner_idx; ?>" class="review-product" onclick="showLoader();">
                                            <?php if ($review->partner_image_path === null) : ?>
                                                <p class="img"><img src="/assets/app/images/demo/moongcle-noimg.png" alt="" style="height: 100%; object-fit: cover; border-radius: 1.2rem;"></p>
                                            <?php else : ?>
                                                <p class="img"><img src="<?= $review->partner_image_path; ?>" alt="" style="height: 100%; object-fit: cover; border-radius: 1.2rem;"></p>
                                            <?php endif; ?>
                                            <div class="tit__wrap">
                                                <p class="detail-name"><?= $review->partner_name; ?></p>
                                                <?php if ($review->partner_address1 !== null) : ?>
                                                    <p class="detail-sub"><span><?= $review->partner_address1; ?></span>
                                                <?php endif; ?>
                                                    <?php $curationTypes = explode(':-:', $review->partner_types); ?>
                                                    <?php if (!empty($curationTypes)) : ?>
                                                        <span>
                                                            <?php foreach ($curationTypes as $tagKey => $curationType) : ?>
                                                                <?= !empty($curationTypes[$tagKey + 1]) ? $curationType . ', ' : $curationType; ?>
                                                            <?php endforeach; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                            <i class="ico ico-stick-arrow__right"></i>
                                        </a>
                                        
                                    </li>
                                    <?php endif; ?>                                
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- buttom 큐레이션 -->
                    <div class="margin-top-30" style="margin-top: 1rem;">
                        <?php
                            if ($buttomCurations instanceof \Illuminate\Support\Collection) {
                                $buttomCurations->loadMissing('curationItems');
                            }
                        ?>

                        <?php foreach ($buttomCurations as $buttomCuration) : ?>
                            <?php
                                $title = $buttomCuration->curation_title ?? ($buttomCuration['curation_title'] ?? '');
                                $desc  = $buttomCuration->curation_description ?? ($buttomCuration['curation_description'] ?? '');
                                $idx = $buttomCuration->curation_idx ?? ($buttomCuration['curation_idx'] ?? '');

                                if ($buttomCuration instanceof \Illuminate\Database\Eloquent\Model) {
                                    if (!$buttomCuration->relationLoaded('curationItems')) {
                                        $buttomCuration->loadMissing('curationItems');
                                    }
                                    $items = $buttomCuration->curationItems ?? collect();
                                } else {
                                    $items = $buttomCuration['curation_items'] ?? [];
                                }
                            ?>
                            <div class="margin-top-30" id="<?= $idx; ?>">
                                <div class="wrap__box">
                                    <div class="curation_head">
                                        <div>
                                            <h4 class="wrap__title padding-x-20"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h4>
                                            <p  class="padding-x-20 wrap__desc"><?= htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?></p>
                                        </div>

                                        <button
                                            type="button"
                                            class="tit_more_btn"
                                            style="margin-bottom:0.8rem;"
                                            onclick="showLoader(); location.href='/curation/<?= $idx ?>';">
                                            더보기
                                        </button>
                                    </div>
                                    
                                    <div class="overflow-x-visible padding-x-20">
                                        <div class="wrap__list" style="margin-top: 2rem; padding-bottom: 1rem;">
                                            <?php if (empty($items) || (is_object($items) && $items->isEmpty())): ?>
                                                <div class="none_partner">적용 숙소가 없습니다.</div>
                                            <?php else: ?>
                                                <?php foreach ($items as $it): ?>
                                                    <?php
                                                        $thumb = $it->target_thumbnail_url ?? ($it['target_thumbnail_url'] ?? '');
                                                        $pdesc = $it->target_description   ?? ($it['target_description']   ?? '');
                                                        $pname = $it->target_name          ?? ($it['target_name']          ?? ($it->target_type ?? ($it['target_type'] ?? '')));
                                                        $ptags = $it->target_tags   ?? ($it['target_tags']   ?? []);
                                                    ?>
                                                    <div class="curation_item">
                                                        <a href="/stay/detail/<?= $it->target_idx ?? '' ?>" onclick="showLoader();">
                                                            <div class="img_box">
                                                                <img src="<?= htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8') ?>" alt="">
                                                                <?php if (!empty($pdesc)) : ?>
                                                                    <div class="curation_desc"><p><?= htmlspecialchars($pdesc, ENT_QUOTES, 'UTF-8') ?></p></div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="bottom_wrap">
                                                                <div class="tit_wrap">
                                                                    <h4 class="partner_tit"><?= htmlspecialchars($pname, ENT_QUOTES, 'UTF-8') ?></h4>
                                                                </div>
                                                                <?php if ($ptags) : ?>
                                                                    <div class="tag_wrap select__wrap type-img curation_tag">
                                                                        <ul style="justify-content: flex-start;">
                                                                            <?php foreach ($ptags as $ptag) : ?>
                                                                                <li>
                                                                                    <img src="/uploads/tags/<?= $ptag['icon']; ?>.png<?= '?v=' . $_ENV['VERSION']; ?>" alt="">
                                                                                    <span><?= $ptag['label']; ?></span>
                                                                                </li>
                                                                            <?php endforeach; ?>
                                                                        </ul>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <a class="btn-more tag">더보기</a>
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                    </div>

                    <!-- loadmore -->
                    <div class="margin-top-30 bottomList" style="margin-top: 6rem;"></div>
                </div>
            </div>

            <!-- 하단바 변경 -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

        </div>
    </div>

    <div id="pageLoader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div>

    <div id="moreLoader" class="more-loader" style="display: none; bottom: 5rem;"></div>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
    }
    ?>

    <script>
        sessionStorage.setItem('previousPage', window.location.href);
    </script>

    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // 페이지가 캐시에서 복원된 경우
                hideLoader();
            } else {
                hideLoader(); // 페이지가 새로 로드된 경우에도 처리
            }
        });
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bar   = document.getElementById('bottomSearchBarWrap_new');
            const head  = document.querySelector('.header__wrap');
            const scEl  = document.getElementById('scrollContainer');
            const useWindow = !scEl;
            const target = useWindow ? window : scEl;

            if (!bar) return;

            let anchor = bar.previousElementSibling;
            if (!anchor || !anchor.classList || !anchor.classList.contains('bottombar-anchor')) {
                anchor = document.createElement('div');
                anchor.className = 'bottombar-anchor';
                anchor.style.cssText = 'height:0;margin:0;padding:0;';
                bar.parentNode.insertBefore(anchor, bar);
            }

            const getScrollTop = () => useWindow
                ? (window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0)
                : (scEl.scrollTop || 0);

            const headerH = () => head ? head.getBoundingClientRect().height : 0;

            const topWithin = (el, ancestor) => {
                let t = 0, n = el;
                while (n && n !== ancestor) { t += n.offsetTop; n = n.offsetParent; }
                return t;
            };

            let threshold = 0;
            const compute = () => {
                threshold = useWindow
                ? (anchor.getBoundingClientRect().top + getScrollTop() - headerH() + 1)
                : (topWithin(anchor, scEl) - headerH() + 1);
            };

            const onScroll = () => {
                const y = getScrollTop();
                if (y >= threshold) bar.classList.add('is-compact');
                else                bar.classList.remove('is-compact');
            };

            compute();
            onScroll();

            target.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', () => { compute(); onScroll(); });
            window.addEventListener('load',   () => { compute(); onScroll(); });
        });
    </script>

    <script>
        // 배너 Swiper
        var swiper1 = new Swiper('.swiper-container.banner', {
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 10000,
                disableOnInteraction: false,
            },
            loop: true,
            slidesPerView: 1,
            spaceBetween: 0,
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
            const opened = wrap.classList.contains('open');
            btn.setAttribute('aria-expanded', opened);
            btn.textContent = opened ? '더보기' : '더보기';
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const baseHeight = parseFloat(
                getComputedStyle(document.documentElement).fontSize
            ) * 8; // .curation_tag { height: 8rem; }

            const initItem = (item) => {
                const wrap = item.querySelector('.curation_tag');
                const btn  = item.querySelector('.btn-more.tag');
                if (!btn) return;

                if (!wrap) { btn.style.display = 'none'; return; }

                const ul = wrap.querySelector('ul');
                    if (!ul || ul.scrollHeight <= baseHeight) {
                    btn.style.display = 'none';
                    return;
                }

                const opened = wrap.classList.contains('open');
                btn.style.display = '';
                btn.setAttribute('aria-expanded', opened);
                btn.textContent = opened ? '접기' : '더보기';
            };

            document.querySelectorAll('.curation_item').forEach(initItem);

            window.addEventListener('load', () => {
                document.querySelectorAll('.curation_item').forEach(initItem);
            });
        });
    </script>

    <script>        
        // 큐레이션 더보기
        let currentPage = 1;
        let isLoading = false;
        let hasMoreData = true;

        async function loadMoreCurationResult(nextPage) {
            const res = await fetch(`/api/curations?page=${nextPage}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const json = await res.json();             

            if (json?.header?.success !== true) throw new Error(json?.header?.message || 'API 실패');

            // 화면에 붙이기 (너의 렌더 함수)
            const appended = appendCurationResult(json.body || []);

            // 다음 페이지 가능 여부 계산
            const { currPage, pageRows, totalRows } = json.page || {};
            hasMoreData = (currPage * pageRows) < totalRows;

            return { appended, currPage };
        }

        function appendCurationResult(curations) {
                if (!Array.isArray(curations) || !curations.length) return;
                if (curations.length === 0) return 0;
                const container = document.querySelector('.bottomList');

                if (!container) return;

                // ------- 작은 유틸(함수 내부에서만 사용) -------
                const esc = s => String(s ?? '').replace(/[&<>"']/g, m => ({
                    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
                }[m]));

                const renderTags = (tags) => {
                    const list = Array.isArray(tags) ? tags : [];
                    if (!list.length) return '';
                    const li = list.map(t => {
                    const label = typeof t === 'string' ? t : (t.label ?? t.tag_name ?? '');
                    const icon  = (t.icon ? `/uploads/tags/${esc(t.icon)}.png` :
                                            '/uploads/tags/character_rooms_available.png') + (window.ENV_VERSION ? `?v=${esc(window.ENV_VERSION)}`:'');
                    return `<li><img src="${icon}" alt=""><span>${esc(label)}</span></li>`;
                    }).join('');
                    return `
                    <div class="tag_wrap select__wrap type-img curation_tag">
                        <ul style="justify-content:flex-start;">${li}</ul>
                    </div>
                    <a href="#none" class="btn-more tag">더보기</a>
                    `;
                };

                const renderItem = (it) => {
                    const thumb = it.target_thumbnail_url || '';
                    const pdesc = it.target_description   || '';
                    const pname = it.target_name || it.target_type || '';
                    const ptags = it.target_tags || [];
                    const pidx = it.target_idx || '';
                    return `
                    <div class="curation_item">
                        <a href="/stay/detail/${esc(pidx)}" onclick="showLoader();">
                        <div class="img_box">
                            <img src="${esc(thumb)}" alt="">
                            ${pdesc ? `<div class="curation_desc"><p>${esc(pdesc)}</p></div>` : ''}
                        </div>
                        <div class="bottom_wrap">
                            <div class="tit_wrap">
                            <h4 class="partner_tit">${esc(pname)}</h4>
                            </div>
                            ${renderTags(ptags)}
                        </div>
                        </a>
                    </div>
                    `;
                };

                const renderCuration = (c) => {
                   
                    const title = c.curation_title || '';
                    const desc  = c.curation_description || '';
                    const items = Array.isArray(c.curation_items) ? c.curation_items : [];
                    const idx = c.curation_idx || '';
                    const itemsHTML = items.length
                    ? items.map(renderItem).join('')
                    : `<div class="none_partner">적용 숙소가 없습니다.</div>`;
                    return `
                    <div class="margin-top-30" id="${esc(idx)}">
                        <div class="wrap__box">
                        <div class="curation_head">
                            <div>
                                <h4 class="wrap__title padding-x-20">${esc(title)}</h4>
                                <p  class="padding-x-20 wrap__desc">${esc(desc)}</p>
                            </div>
                            <button
                                type="button"
                                class="tit_more_btn"
                                style="margin-bottom:0.8rem;"
                                onclick="showLoader(); location.href='/curation/${esc(idx)}';">
                                더보기
                            </button>
                        </div>
                        <div class="overflow-x-visible padding-x-20">
                            <div class="wrap__list" style="margin-top: 2rem; padding-bottom: 1rem;">
                            ${itemsHTML}
                            </div>
                        </div>
                        </div>
                    </div>
                    `;
                };

            // ------- 1) HTML 생성 & DOM으로 변환해 append -------
            const html = curations.map(renderCuration).join('');
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html;

            // append 전/후 어디에 붙였는지 추적하려고 fragment 사용
            const frag = document.createDocumentFragment();
            while (wrapper.firstChild) frag.appendChild(wrapper.firstChild);
            container.appendChild(frag);

            // ------- 2) 태그 '더보기' 버튼 초기화 (방금 붙인 영역만) -------
            // rem 기준(8rem) 유지
            const rootRem = parseFloat(getComputedStyle(document.documentElement).fontSize) || 16;
            const limitPx = 8 * rootRem;

            // 방금 append한 블록들 안에서만 조회
            const newBlocks = container.querySelectorAll('.margin-top-30:last-child, .margin-top-30 ~ .margin-top-30'); // container 전체에서 새것만 걸러내기 어려우면 전체 초기화도 OK

            const btns  = container.querySelectorAll('.btn-more.tag');
            const wraps = container.querySelectorAll('.curation_tag');

            for (let i = 0; i < btns.length; i++) {
                const btn  = btns[i];
                const wrap = wraps[i];
                if (!wrap) { btn.style.display = 'none'; continue; }

                const ul = wrap.querySelector('ul');
                    if (!ul || ul.scrollHeight <= limitPx) {
                    btn.style.display = 'none';
                    continue;
                }

                // 중복 바인딩 방지: 이미 init된 버튼이면 skip
                if (btn.dataset.inited === '1') continue;
                btn.dataset.inited = '1';

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    // 자신의 짝 wrap 찾기: 같은 parent(.bottom_wrap) 기준으로 조회
                    const localWrap = this.closest('.bottom_wrap')?.querySelector('.curation_tag') || wrap;
                    if (!localWrap) return;

                    localWrap.classList.toggle('open');
                    this.classList.toggle('active');
                    this.textContent = localWrap.classList.contains('open') ? '접기' : '더보기';
                });
            }

            return curations.length;
        }
    
        document.addEventListener('DOMContentLoaded', () => {

        // scroll
        const container = document.querySelector('#scrollContainer');
        const moreLoader = document.querySelector('#moreLoader');
        let isLoading = false;
        let lastFetchedPage = 1;

        const showMoreLoader = () => {
            requestAnimationFrame(() => {
                moreLoader.style.display = 'grid';
            });
        };

        const hideMoreLoader = () => {
            requestAnimationFrame(() => {
                moreLoader.style.display = 'none';
            });
        };

        function loadPage(nextPage) {
            if (!hasMoreData || isLoading) return;
            isLoading = true;
            showMoreLoader();

            loadMoreCurationResult(nextPage)
                .then(({ appended, currPage }) => {
                if (appended > 0) lastFetchedPage = currPage;
                if (appended === 0) hasMoreData = false; 
                })
                .catch(err => console.error('loadPage error:', err))
                .finally(() => { isLoading = false; hideMoreLoader(); });
        }

        // 바닥 1회 트리거용
        let armed = true;

        // 옵션
        const threshold = 80;

        function throttle(func, limit) {
            let lastFunc, lastRan;
            return function (...args) {
                const ctx = this;
                if (!lastRan) {
                    func.apply(ctx, args);
                    lastRan = Date.now();
                } else {
                    clearTimeout(lastFunc);
                    lastFunc = setTimeout(() => {
                        if (Date.now() - lastRan >= limit) {
                        func.apply(ctx, args);
                        lastRan = Date.now();
                    }
                    }, Math.max(0, limit - (Date.now() - lastRan)));
                }
            };
        }

        const handleScroll = () => {
            if (isLoading || !hasMoreData) return;

            let scrollHeight, scrollTop, clientHeight;

            if (container) {
                scrollHeight = container.scrollHeight;
                scrollTop    = container.scrollTop;
                clientHeight = container.clientHeight;
            } else {
                // window 스크롤
                const doc = document.documentElement;
                scrollHeight = Math.max(document.body.scrollHeight, doc.scrollHeight);
                scrollTop    = window.pageYOffset || doc.scrollTop || 0;
                clientHeight = window.innerHeight || doc.clientHeight;
            }

            const atBottom = (scrollHeight - scrollTop - threshold) <= clientHeight;

            if (atBottom && armed) {
                armed = false; 
                const nextPage = lastFetchedPage + 1;
                loadPage(nextPage).finally(() => {});
            } else if (!atBottom) {
                armed = true;
            }
        };

        const throttleScroll = throttle(handleScroll, 200);

        if (container) {
            container.addEventListener('scroll', throttleScroll, { passive: true });
        } else {
            window.addEventListener('scroll', throttleScroll, { passive: true });
        }

    });
        
    </script>

</body>

</html>