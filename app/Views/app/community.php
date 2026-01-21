<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$reviewsV1 = $data['reviews'];
$unreadMoocledealCount = $data['unreadMoocledealCount'];
$articles = $data['articles'];

?>

<!-- Head -->
<?php 
    $page_title_01 = "여행 후기·정보 공유 리뷰";

    include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; 
?>
<!-- Head -->

<style>
    .refresh-indicator {
        top: 4.8rem !important;
    }
</style>


<body>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-top.php";
    }
    ?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/h1.php"; ?>

    <div id="mobileWrap">
        <header class="header__wrap">
            <div class="header__inner">
                <p class="header-tit__left">리뷰</p>
                <div class="btn__wrap">
                    <button type="button" class="btn-search" onclick="gotoSearch()"><span class="blind">검색</span></button>
                    <button type="button" class="btn-alarm" onclick="gotoNotification()"><span class="blind">알림</span></button>
                </div>

            </div>
        </header>

        <div class="container__wrap community__wrap">

            <div class="tab__wrap tab-line__wrap">
                <ul class="tab__inner fnStickyTop">
                    <li class="tab-line__con active"><a>뭉클한 리뷰</a></li>
                </ul>
                <div class="tab-contents__wrap">

                    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/refresh.php"; ?>

                    <div class="refresh__wrap">
                        <div class="tab-contents active">
                            <!-- 뭉클딜 리뷰 리스트 -->
                            <div class="community-list__wrap">
                                <?php foreach ($reviewsV1 as $reviewV1) : ?>
                                    <div class="community-list__con">
                                        <div class="community-top">
                                            <div class="user-wrap">
                                                <p class="img"><img src="<?= !empty($reviewV1->user_image) ? $reviewV1->user_image : '/assets/app/images/common/no_profile.jpg' ?>" alt=""></p>
                                                <div>
                                                    <p class="name"><?= $reviewV1->user_nickname; ?></p>
                                                    <div class="start">
                                                        <?php
                                                        $fullStars = floor($reviewV1->rating);
                                                        $halfStar = ($reviewV1->rating - $fullStars) >= 0.5 ? 1 : 0;
                                                        $emptyStars = 5 - $fullStars - $halfStar;
                                                        ?>
                                                        <?php for ($i = 0; $i < $fullStars; $i++) : ?>
                                                            <i class="ico ico-star"></i>
                                                        <?php endfor; ?>
                                                        <?php if ($halfStar) : ?>
                                                            <i class="ico ico-star__half"></i>
                                                        <?php endif; ?>
                                                        <?php for ($i = 0; $i < $emptyStars; $i++) : ?>
                                                            <i class="ico ico-star__empty"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (!empty($reviewV1->image_list)) : ?>
                                            <div class="splide splide__product">
                                                <div class="splide__track">
                                                    <ul class="splide__list">
                                                        <?php
                                                            $reviewImages = [];

                                                            if (!empty($reviewV1) && !empty($reviewV1->image_list)) {
                                                                $decoded = json_decode($reviewV1->image_list);

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
                                                                    <li class="splide__slide splide__list__product"><img src="<?= $reviewImage->path; ?>" alt="후기 이미지" data-images='<?= json_encode($reviewImages) ?>'></li>
                                                                <?php elseif (in_array($reviewImage->extension, $allowedVideoExtensions)) : ?>
                                                                    <li class="splide__slide splide__list__product">
                                                                        <video class="video-element" controls>
                                                                            <source src="<?= $reviewImage->origin_path; ?>" type="video/<?= $reviewImage->extension; ?>">
                                                                            현재 브라우저가 지원하지 않는 영상입니다.
                                                                        </video>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                                <div class="slide-counter">
                                                    <span class="current-slide">1</span> / <span class="total-slides"></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="community-contents">
                                            <div class="review-list__con">
                                                <div class="badge badge__lavender">숙소</div>
                                                <p class="title"><?= $reviewV1->partner_name; ?></p>
                                                <div class="review-txt">
                                                    <p class="review"><?= nl2br($reviewV1->review_content); ?></p>
                                                    <a class="btn-more">더보기</a>
                                                </div>
                                                <a href="/stay/detail/<?= $reviewV1->partner_idx; ?>" class="review-product">
                                                    <p class="img"><img src="<?= $reviewV1->partner_image_path; ?>" alt="" style="height: 100%; object-fit: cover; border-radius: 1.2rem; width: 100%;"></p>
                                                    <div class="tit__wrap">
                                                        <p class="detail-name"><?= $reviewV1->partner_name; ?></p>
                                                            <?php if ($reviewV1->partner_address1 !== null) : ?>
                                                                <p class="detail-sub"><span><?= $reviewV1->partner_address1; ?></span>
                                                            <?php endif; ?>
                                                            <?php
                                                            $stayTypes = !empty($reviewV1->partner_types)
                                                                ? explode(':-:', $reviewV1->partner_types)
                                                                : [];
                                                            ?>

                                                            <?php if (!empty($stayTypes)) : ?>
                                                                <span><?= implode(', ', $stayTypes); ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                    <i class="ico ico-stick-arrow__right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="community-bottom">
                                        <?php
                                        $createdAt = new DateTime($reviewV1->created_at);
                                        $formattedDate = $createdAt->format('y. m. d H:i');
                                        ?>
                                        <div class="community-time"><?= $formattedDate; ?></div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                        <div class="tab-contents">
                            <div class="article__wrap">
                                <!-- 아티클 리스트 -->
                                <div class="article-list__wrap">
                                    <?php if (!empty($articles[0])) : ?>
                                        <?php foreach ($articles as $article) : ?>
                                            <ul style="justify-content: flex-start;">
                                                <li>
                                                    <a href="/community/article/<?= $article->article_idx; ?>">
                                                        <img src="<?= $article->article_thumbnail_path; ?>" alt="">
                                                    </a>
                                                </li>
                                            </ul>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <!-- //아티클 리스트 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
        </div>

        <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/bottom-navigation.php"; ?>

        <div id="copyMoongcledeal" class="layerpop__wrap type-center main__popup">
            <div class="layerpop__container">
                <div class="layerpop__header">
                    <button type="button" class="btn-close fnClosePop"><i class="ico ico-close"></i></button>
                    <p class="title">
                        아래의 여행정보로 <br>
                        뭉클딜을 받아볼까요?
                    </p>
                </div>
                <div class="layerpop__contents">
                    <div id="popupTagContainer" class="select-tag__wrap" style="display: flex; flex-wrap: wrap; justify-content: center;">
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__primary fnEncodeTags" data-uri="/moongcledeal/create/02">뭉클태그 복사하기</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="allPicture" class="layerpop__wrap type-full">
        <div class="layerpop__container">
            <div class="layerpop__header">
                <button class="btn-close fnClosePop"><span class="blind">닫기</span></button>
            </div>
            <div class="layerpop__contents">
                <div class="product-detail__allpicture zoom-box">
                    <div class="splide splide__product splide__product_full_img zoom">
                        <div class="splide__track zoom-wrapper">
                            <ul class="splide__list full-size-image-list">
                                
                            </ul>
                        </div>
                        <div class="slide-counter zoom">
                            <span class="current-slide">1</span> /
                            <span class="total-slide">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layerpop__footer">
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.community-list__con .splide__product .splide__slide').forEach(el => {
            el.addEventListener('click', function () {
                showLoader();
            });
        });

        document.querySelectorAll('.review-product').forEach(el => {
            el.addEventListener('click', function () {
                showLoader();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const popup = document.getElementById('allPicture');
            const list = popup.querySelector('.full-size-image-list');
            const currentSlideSpan = popup.querySelector('.current-slide');
            const totalSlideSpan = popup.querySelector('.total-slide');
            let splide = null;

            // Splide + Panzoom 초기화
            function initSplideAndZoom() {
                const zoomSlider = document.querySelector('.splide__product_full_img');

                // 기존 슬라이더 제거
                if (splide) splide.destroy();

                splide = new Splide(zoomSlider, {
                    arrows: true,
                    pagination: false,
                    perPage: 1,
                    focus: 'center',
                    padding: 0,
                    gap: 0,
                    drag: true,
                });

                // 슬라이드 번호 갱신
                splide.on('move', function (newIndex) {
                     currentSlideSpan.textContent = newIndex + 1;
                });

                splide.mount();

                // 슬라이드 수 표시
                totalSlideSpan.textContent = zoomSlider.querySelectorAll('img').length;
                currentSlideSpan.textContent = 1;

                // Zoom 연동
                document.querySelectorAll('.zoom-image').forEach((container) => {
                    const panzoom = Panzoom(container, {
                        maxScale: 10,
                        minScale: 1,
                        contain: 'outside',
                        pinchZoom: true,
                    });

                    container.addEventListener('wheel', panzoom.zoomWithWheel);

                    let lastScale = 1;
                    let isPinching = false;

                    const setUIVisibility = (visible) => {
                        const arrows = zoomSlider.querySelectorAll('.splide__arrow');
                            arrows.forEach(arrow => {
                            arrow.style.display = visible ? '' : 'none';
                        });

                        const slideCounter = zoomSlider.querySelector('.slide-counter.zoom');
                        if (slideCounter) {
                            slideCounter.style.display = visible ? '' : 'none';
                        }
                    };

                    const updateSlideState = (enabled) => {
                        zoomSlider.style.pointerEvents = enabled ? 'auto' : 'none';
                        splide.options = {
                            ...splide.options,
                            drag: enabled,
                            swipe: enabled,
                        };
                        splide.refresh();
                        setUIVisibility(enabled);
                    };

                    container.addEventListener('touchstart', (e) => {
                        if (e.touches.length > 1) {
                            isPinching = true;
                            updateSlideState(false);
                            zoomSlider.style.touchAction = 'none';
                            zoomSlider.style.pointerEvents = 'none';
                        }
                    }, { passive: false });

                    container.addEventListener('touchmove', (e) => {
                        if (isPinching) {
                            zoomSlider.style.touchAction = 'none';
                            zoomSlider.style.pointerEvents = 'none';
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    }, { passive: false });

                    container.addEventListener('touchend', (e) => {
                        if (e.touches.length < 2) {
                            isPinching = false;
                            zoomSlider.style.touchAction = '';
                            zoomSlider.style.pointerEvents = '';
                            if (lastScale <= 1.1) updateSlideState(true);
                        }
                    });

                    container.addEventListener('panzoomchange', (e) => {
                        const scale = e.detail.scale;
                        if (scale > 1.1 && lastScale <= 1.1) {
                            updateSlideState(false);
                        } else if (scale <= 1.1 && lastScale > 1.1 && !isPinching) {
                            updateSlideState(true);
                        }
                        lastScale = scale;
                    });

                    splide.on('moved', () => {
                        panzoom.zoom(1, { animate: true });
                    });
                });
            }

            // 후기 이미지 클릭 시 팝업 열고 슬라이드 구성
            document.addEventListener('click', function (e) {
                const target = e.target.closest('img[data-images]');
                if (!target) return; 

                const images = JSON.parse(target.dataset.images || '[]');

                const list = document.querySelector('.full-size-image-list');
                const popup = document.getElementById('allPicture');

                // 이미지 세팅
                list.innerHTML = '';
                images.forEach((imgObj, index) => {
                    const src = imgObj.origin_path;

                    const li = document.createElement('li');
                    li.className = "splide__slide image-position-center zoom-container";

                    li.innerHTML = `
                    <div class="skeleton-community"></div>
                    <img src="${src}" alt="" class="zoom-image real-image">
                    `;

                    list.appendChild(li);
                });


                popup.classList.add('show');

                initSplideAndZoom(); 

                setTimeout(() => {
                    handleImageSkeletons('.real-image', '.skeleton-community');
                    showLoader();
                }, 0);
            });

            // 닫기
            popup.querySelector('.btn-close.fnClosePop').addEventListener('click', () => {
                popup.classList.remove('show');
                if (splide) splide.destroy();
            });
        });

        // 이미지 스켈레톤 처리
        function handleImageSkeletons(trackClass, loaderClass) {
            const tracks = document.querySelectorAll(trackClass);
            const loaders = document.querySelectorAll(loaderClass);

            tracks.forEach((track, index) => {
                const loader = loaders[index];

                const images = track.querySelectorAll('img');
                let loadedCount = 0;
                const totalImages = images.length;

                if (totalImages === 0) {
                    showImageTrack(track, loader);
                    return;
                }

                images.forEach((img) => {
                    if (img.complete) {
                        loadedCount++;
                        if (loadedCount === totalImages) showImageTrack(track, loader);
                    } else {
                        img.addEventListener('load', () => {
                            loadedCount++;
                            if (loadedCount === totalImages) showImageTrack(track, loader);
                        });
                    }
                });
            });
        }

        function showImageTrack(track, loader) {
            if (loader) {
                loader.classList.add('fade-out');
                setTimeout(() => { 
                    loader.remove();
                    hideLoader();
                }, 600);
            }

            if (track) {
                track.classList.add('show');
            }
        }
    </script>

    <div id="pageLoader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div>

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

        let currentPage = 1;
        let isLoading = false;

        const loadMoreReviews = () => {
            if (isLoading) return;

            isLoading = true;
            currentPage++;

            showLoader();

            fetch('/api/review/v1/loadmore', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        page: currentPage,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.data && data.data.length > 0) {
                        appendReviews(data.data);
                    } else {
                        // console.log('No more reviews to load');
                    }
                    isLoading = false;
                })
                .catch((error) => {
                    console.error('Error loading reviews:', error);
                    isLoading = false;
                }).finally(() => {
                    hideLoader();
                });
        };

        const appendReviews = (reviews) => {
            const container = document.querySelector('.community-list__wrap');

            reviews.forEach((review) => {
                const reviewElement = document.createElement('div');
                reviewElement.classList.add('community-list__con');

                const fullStars = Math.floor(review.rating);
                const halfStar = review.rating - fullStars >= 0.5 ? 1 : 0;
                const emptyStars = 5 - fullStars - halfStar;

                // 별 표시
                const stars = Array(fullStars).fill('<i class="ico ico-star"></i>').join('') +
                    (halfStar ? '<i class="ico ico-star__half"></i>' : '') +
                    Array(emptyStars).fill('<i class="ico ico-star__empty"></i>').join('');

                const reviewImages = JSON.parse(review.image_list || '[]');
                const imageElements = reviewImages.map(image => {
                    const allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    const allowedVideoExtensions = ['mov', 'mp4'];

                    const dataImagesAttr = JSON.stringify(reviewImages); 

                    if (allowedImageExtensions.includes(image.extension)) {
                        return `<li class="splide__slide splide__list__product"><img src="${image.path}" alt="" data-images='${dataImagesAttr}'></li>`;
                    } else if (allowedVideoExtensions.includes(image.extension)) {
                        return `<li class="splide__slide splide__list__product">
                                <video class="video-element" controls>
                                    <source src="${image.origin_path}" type="video/${image.extension}">
                                    현재 브라우저가 지원하지 않는 영상입니다.
                                </video>
                            </li>`;
                    }
                }).join('');

                const createdAt = new Date(review.created_at).toLocaleString('ko-KR', {
                    year: '2-digit',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                reviewElement.innerHTML = `
                    <div class="community-top">
                        <div class="user-wrap">
                            <p class="img"><img src="${review.user_image || '/assets/app/images/common/no_profile.jpg'}" alt=""></p>
                            <div>
                                <p class="name">${review.user_nickname}</p>
                                <div class="start">${stars}</div>
                            </div>
                        </div>
                    </div>
                    ${reviewImages.length ? `
                    <div class="splide splide__product">
                        <div class="splide__track">
                            <ul class="splide__list">${imageElements}</ul>
                        </div>
                        <div class="slide-counter">
                            <span class="current-slide">1</span> / <span class="total-slides">${reviewImages.length}</span>
                        </div>
                    </div>` : ''}
                    <div class="community-contents">
                        <div class="review-list__con">
                            <div class="badge badge__lavender">숙소</div>
                            <p class="title">${review.partner_name}</p>
                            <div class="review-txt">
                                <p class="review" style="white-space: pre-wrap;">${review.review_content}</p>
                                <a class="btn-more">더보기</a>
                            </div>
                            <a href="/stay/detail/${review.partner_idx}" class="review-product">
                                <p class="img"><img src="${review.partner_image_path}" alt="" style="height: 100%; object-fit: cover; border-radius: 1.2rem; width: 100%;"></p>
                                <div class="tit__wrap">
                                    <p class="detail-name">${review.partner_name}</p>
                                    <p class="detail-sub">
                                        ${review.partner_address1 ? `
                                            <span>${review.partner_address1}</span>
                                        ` : ''}
                                        ${review.partner_types ? `<span>${review.partner_types.split(':-:').join(', ')}</span>` : ''}
                                    </p>
                                </div>
                                <i class="ico ico-stick-arrow__right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="community-bottom">
                        <div class="community-time">${createdAt}</div>
                    </div>
                `;

                container.appendChild(reviewElement);
            });

            fnProductSlide();

            checkReviewLineCount();
        };

        // 3줄 체크
        function checkReviewLineCount() {
            document.querySelectorAll('.review-txt').forEach(function(el) {
                const review = el.querySelector('.review');
                const btnMore = el.querySelector('.btn-more');

                if (!review || !btnMore) return;

                const computedStyle = window.getComputedStyle(review);
                const lineHeight = parseFloat(computedStyle.lineHeight);
                const totalHeight = review.scrollHeight;
                const lineCount = Math.round(totalHeight / lineHeight);

                if (lineCount <= 3) {
                    btnMore.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('#scrollContainer');

            if (container) {
                // #scrollContainer가 존재할 때
                container.addEventListener('scroll', () => {
                    if (container.scrollHeight - container.scrollTop <= container.clientHeight + 200) {
                        loadMoreReviews();
                    }
                });
            } else {
                // #scrollContainer가 존재하지 않을 때
                document.addEventListener('scroll', () => {
                    const scrollTop = window.scrollY || document.documentElement.scrollTop; // 스크롤 위치
                    const scrollHeight = document.documentElement.scrollHeight; // 문서의 총 높이
                    const clientHeight = window.innerHeight; // 뷰포트 높이

                    if (scrollHeight - scrollTop <= clientHeight + 200) {
                        loadMoreReviews();
                    }
                });
            }


            // 더보기 노출 컨트롤
            checkReviewLineCount();
        });

        let currentEncodedTags = '';

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('fnOpenPop')) {
                const popId = event.target.getAttribute('data-name');
                currentEncodedTags = event.target.getAttribute('data-encoded-tags'); // 현재 버튼의 태그 값을 저장

                const parentElement = event.target.closest('.community-contents');
                const tagContainer = document.getElementById(popId).querySelector('.select-tag__wrap');

                // 기존 태그 초기화
                tagContainer.innerHTML = '';

                const tagElements = parentElement.querySelectorAll('.review-tag ul li');
                if (tagElements.length > 0) {
                    tagElements.forEach((tagElement) => {
                        const imgSrc = tagElement.querySelector('img').getAttribute('src');
                        const tagText = tagElement.querySelector('span').textContent;

                        // 동적으로 태그 추가
                        const tagHtml = `
                            <div class="select-tag">
                                <p class="img"><img src="${imgSrc}" alt=""></p>
                                <p class="txt">${tagText}</p>
                            </div>`;
                        tagContainer.innerHTML += tagHtml;
                    });
                } else {
                    // 태그가 없는 경우 처리
                    tagContainer.innerHTML = '<p class="no-tags">태그가 없습니다.</p>';
                }

                // 팝업 열기
                fnOpenLayerPop(popId);
            }
        });

        // fnEncodeTags 버튼 클릭 시 동작
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('fnEncodeTags')) {
                const baseUri = event.target.getAttribute('data-uri'); // 기본 URI
                if (currentEncodedTags) {
                    const destinationUrl = `${baseUri}?selected=${currentEncodedTags}`;
                    window.location.href = destinationUrl; // 링크로 이동
                } else {
                    alert('태그 정보가 없습니다.');
                }
            }
        });
    </script>

    <script>
        thirdpartyWebviewZoomFontIgnore();
    </script>

</body>

</html>