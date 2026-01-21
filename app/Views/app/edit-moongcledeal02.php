<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$moongcledeal = $data['moongcledeal'];
$selected = $moongcledeal->selected;

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/head.php"; ?>
<!-- Head -->

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
                <button class="btn-back" onclick="goBackWithSavedMoongcledeal()"><span class="blind">뒤로가기</span></button>
                <p class="process-txt">마지막 단계예요!</p>
            </div>
        </header>

        <div class="container__wrap mkcle-create__wrap">
            <!-- 프로그레스바 -->
            <div class="progress__wrap fnStickyTop">
                <div class="progress__inner" style="width:100%"></div>
            </div>
            <!-- //프로그레스바 -->

            <section class="layout__wrap pd-big">
                <div class="tit__wrap">
                    <h2 class="ft-xl">추천 우선 순위를 정해주세요!</h2>
                </div>

                <div class="type-gray" style="padding: 1rem 0 2rem;">
                    <div style="margin-bottom: 1rem; color: #00CB9C;">* 선택하신 날짜, 인원, 도시는 최우선 순위로 적용돼요.</div>
                </div>

                <hr class="divide__small">

                <div class="select__wrap type-gray select-tags">
                    <ul>
                        <?php foreach ($selected['taste'] as $row) : ?>
                            <?php if (!empty($row['tag_name'])) : ?>
                                <li data-machine-name="<?= $row['tag_machine_name']; ?>">
                                    <a>
                                        <span class="num"></span>
                                        <span class="tag">#<?= $row['tag_name']; ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>

            <!-- 순위 바텀 시트 -->
            <div id="scrollPad" class="select-bottom__wrap">
                <div class="select-bottom__con">
                    <ul class="select-bottom__list">
                        <li>
                            <i class="ico ico-medal__gold"></i>
                            <div class="select-item">
                                <span></span> <a class="select-item__delete"><i class="ico ico-delete__circle"></i></a>
                            </div>
                        </li>
                        <li>
                            <i class="ico ico-medal__silver"></i>
                            <div class="select-item">
                                <span></span> <a class="select-item__delete"><i class="ico ico-delete__circle"></i></a>
                            </div>
                        </li>
                        <li>
                            <i class="ico ico-medal__bronze"></i>
                            <div class="select-item">
                                <span></span> <a class="select-item__delete"><i class="ico ico-delete__circle"></i></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- //순위 바텀 시트 -->

            <!-- 하단 버튼 영역 -->
            <div id="pendingButtonContainer" class="bottom-fixed__wrap">
                <div class="btn__wrap">
                    <button class="btn-full__primary disabled" disabled>우선 순위를 정해주세요</button>
                </div>
            </div>
            <div id="confirmButtonContainer" class="bottom-fixed__wrap hidden">
                <div class="btn__wrap">
                    <button id="confirmButton" class="btn-full__primary">좋은 숙소 찾아줘</button>
                </div>
            </div>
            <!-- //하단 버튼 영역 -->
        </div>

        <!-- 바텀 팝업 -->
        <div id="alert" class="layerpop__wrap type-alert mkcle-complete__pop">
            <div class="layerpop__container">
                <div class="layerpop__contents">
                    <p class="logo"><img src="/assets/app/images/common/logo.png" alt=""></p>
                    <div class="title__wrap">
                        <p class="ft-xxxl">
                            두근두근! 뭉클딜 등록 완료 <br>
                            어떤 뭉클딜이 도착할까요?
                        </p>
                    </div>
                    <div class="box-gray__wrap">
                        <div class="ranking__wrap">
                        </div>
                    </div>
                </div>
                <div class="layerpop__footer">
                    <div class="btn__wrap">
                        <button class="btn-full__primary" onclick="location.href='/moongcledeals'">나만의 뭉클딜 바로가기</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- //바텀 팝업 -->

        <div id="moongcledealLoading" class="complete__wrap loading" style="display: none;">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/moongcledeal-loader.php"; ?>

            <div class="btn__wrap">
                <button class="btn-full__primary" onclick="location.href='/moongcledeals?moongcledealIdx=<?= $moongcledeal->moongcledeal_idx; ?>'">나만의 뭉클딜 바로가기</button>
            </div>
        </div>
    </div>

    <div id="pageLoader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div>

    <?php
    if ($deviceType == 'pc') {
        include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
    }
    ?>

    <script>
        showLoader();

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // 페이지가 캐시에서 복원된 경우
                hideLoader();
            } else {
                hideLoader(); // 페이지가 새로 로드된 경우에도 처리
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const pendingButtonContainer = document.getElementById("pendingButtonContainer");
            const confirmButtonContainer = document.getElementById("confirmButtonContainer");
            const scrollPad = document.getElementById("scrollPad");

            let originalBodyPaddingBottom = scrollPad.style.paddingBottom || "";

            let selectedItems = []; // 저장되는 데이터: [{tag_name, tag_machine_name}]

            // 초기 설정: 숫자 숨기기
            document.querySelectorAll('.select-tags .num').forEach(num => {
                num.style.display = 'none';
            });

            // 태그 선택 처리
            document.querySelectorAll(".select-tags li").forEach(tag => {
                tag.addEventListener("click", function(e) {
                    e.preventDefault();

                    const tagName = this.querySelector('.tag').textContent.replace('#', ''); // 태그 이름
                    const machineName = this.getAttribute('data-machine-name'); // 머신 네임
                    const numElement = this.querySelector('.num');

                    // 하단 섹션이 보이지 않으면 표시
                    document.querySelector('.select-bottom__wrap').classList.add('show');

                    if (this.classList.contains('active')) {
                        // 항목이 이미 선택되어 있다면 제거
                        selectedItems = selectedItems.filter(item => item.tag_machine_name !== machineName);
                        this.classList.remove('active');
                        numElement.style.display = 'none';
                        this.querySelector('.tag').textContent = `#${tagName}`;
                    } else if (selectedItems.length < 3) {
                        // 제한 이하라면 항목을 선택
                        selectedItems.push({
                            tag_name: tagName,
                            tag_machine_name: machineName
                        });
                        this.classList.add('active');
                        numElement.style.display = 'inline';
                        numElement.textContent = selectedItems.length; // 순번 표시
                    }

                    updateSelectedList();
                    updateButtonState();
                });
            });

            // 버튼 상태 업데이트 함수
            function updateButtonState() {
                if (selectedItems.length > 0) {
                    // 하나라도 선택된 경우
                    pendingButtonContainer.classList.add("hidden");
                    pendingButtonContainer.disabled = true;

                    confirmButtonContainer.classList.remove("hidden");
                    confirmButtonContainer.disabled = false;

                    const conHeight = document.querySelector('.select-bottom__con').offsetHeight;
                    scrollPad.style.paddingBottom = `${conHeight}px`;
                } else {
                    // 아무것도 선택되지 않은 경우
                    pendingButtonContainer.classList.remove("hidden");
                    pendingButtonContainer.disabled = false;

                    confirmButtonContainer.classList.add("hidden");
                    confirmButtonContainer.disabled = true;

                    document.querySelector('.select-bottom__wrap').classList.remove('show');

                    scrollPad.style.paddingBottom = originalBodyPaddingBottom;
                }
            }

            // 선택 목록 업데이트
            function updateSelectedList() {
                const selectItems = document.querySelectorAll('.select-bottom__list .select-item');

                // 하단 목록 업데이트
                selectItems.forEach((item, index) => {
                    if (selectedItems[index]) {
                        item.querySelector('span').textContent = selectedItems[index].tag_name;
                        item.style.display = 'block';
                    } else {
                        item.querySelector('span').textContent = '';
                        item.style.display = 'none';
                    }
                });

                // 태그 순번 업데이트
                document.querySelectorAll(".select-tags li").forEach(tag => {
                    const machineName = tag.getAttribute('data-machine-name');
                    const numElement = tag.querySelector('.num');

                    const selectedIndex = selectedItems.findIndex(item => item.tag_machine_name === machineName);
                    if (selectedIndex !== -1) {
                        numElement.style.display = 'inline';
                        numElement.textContent = selectedIndex + 1; // 1 기반 인덱스
                    } else {
                        numElement.style.display = 'none';
                    }
                });
            }

            // 삭제 버튼 클릭 처리
            document.querySelectorAll(".select-item__delete").forEach(deleteButton => {
                deleteButton.addEventListener("click", function(e) {
                    e.preventDefault();

                    const listItem = this.closest('li');
                    const index = Array.from(listItem.parentNode.children).indexOf(listItem);
                    const removedItem = selectedItems[index];

                    selectedItems.splice(index, 1); // 선택된 항목 제거

                    // 태그에서 제거된 항목 업데이트
                    document.querySelectorAll(".select-tags li").forEach(tag => {
                        const machineName = tag.getAttribute('data-machine-name');
                        if (machineName === removedItem.tag_machine_name) {
                            tag.classList.remove('active');
                            tag.querySelector('.num').style.display = 'none';
                            tag.querySelector('.tag').textContent = `#${removedItem.tag_name}`; // 원래 텍스트 복원
                        }
                    });

                    updateSelectedList(); // 선택 목록 재업데이트
                    updateButtonState();
                });
            });

            document.getElementById("confirmButton").addEventListener("click", async function(e) {
                try {
                    let moongcledealIdx = <?= $moongcledeal->moongcledeal_idx ?>;

                    const response = await fetch('/api/moongcledeal/priority', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            moongcledealIdx: moongcledealIdx,
                            priority: selectedItems,
                        })
                    });

                    // 응답 처리
                    if (!response.ok) {
                        throw new Error('API 요청 실패: ' + response.statusText);
                    }

                    const result = await response.json();

                    if (result.moongcledealIdx) {
                        // 로딩
                        moongcledealLoading.style.display = 'flex';

                        // 5초 후에 페이지 이동
                        setTimeout(() => {
                            window.location.href = '/moongcledeals?moongcledealIdx=' + moongcledealIdx;
                        }, 5000);

                        scrollPad.style.display = 'none';

                        // const popupRankingWrap = document.querySelector("#alert .ranking__wrap");
                        // const popupTitle = document.querySelector("#alert .title__wrap .ft-xxxl");

                        // // 기존 랭킹 내용 초기화
                        // popupRankingWrap.innerHTML = '';

                        // // 선택한 항목이 없을 경우
                        // if (selectedItems.length === 0) {
                        //     popupTitle.innerHTML = "아직 선택된 뭉클딜이 없습니다! <br> 태그를 선택해주세요.";
                        // } else {
                        //     // 선택된 항목을 기반으로 랭킹 생성
                        //     const medalClasses = ['ico-medal__gold', 'ico-medal__silver', 'ico-medal__bronze'];
                        //     selectedItems.forEach((item, index) => {
                        //         const medalClass = medalClasses[index] || '';
                        //         const rankingDiv = document.createElement("div");
                        //         rankingDiv.className = 'ranking';

                        //         rankingDiv.innerHTML = `
                        //         <i class="ico ${medalClass}"></i>
                        //         <p>${item.tag_name}</p>
                        //     `;
                        //         popupRankingWrap.appendChild(rankingDiv);
                        //     });

                        //     // 팝업 제목 업데이트
                        //     popupTitle.innerHTML = "두근두근! 뭉클딜 등록 완료 <br> 어떤 뭉클딜이 도착할까요?";
                        // }

                        // hideLoader();

                        // // 팝업 열기
                        // fnOpenLayerPop('alert');
                    } else {
                        hideLoader();
                        console.error('idx 값이 응답에 포함되지 않았습니다.');
                    }
                } catch (error) {
                    hideLoader();
                    console.error('API 요청 중 오류 발생:', error);
                    throw error;
                }
            });
        });

        // $(document).ready(function() {
        //     항공권
        //     if ($('#radio1').is(':checked')) {
        //         $('.tab-round__wrap').show();
        //     } else {
        //         $('.tab-round__wrap').hide();
        //     }
        //     $('input[name="flight_radio"]').change(function() {
        //         if ($('#radio1').is(':checked')) {
        //             $('.tab-round__wrap').show();
        //         } else if ($('#radio2').is(':checked')) {
        //             $('.tab-round__wrap').hide();
        //         }
        //     });
        // });
    </script>

</body>



</html>