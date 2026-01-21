<div class="mooongcledeal_load_wrap">
    <div id="lottie-container-load" class="ico ico-logo"></div>

    <div class="load_text_box">
        <!-- text -->
        <p class="moongcledeal_load_text">고객님의 요청 사항이 접수되어<br>분석 중입니다</p>
        <p class="moongcledeal_load_text">조건에 맞는 숙소를<br>매칭하고 있습니다</p>
        <p class="moongcledeal_load_text">숙소의 숨겨진 특별 제안을<br>준비 중입니다</p>
        <p class="moongcledeal_load_text">뭉클이 최저가 네고를<br>시도하고 있습니다</p>
        <p class="moongcledeal_load_text">최적의 숙소 매칭이<br>완료되었습니다</p>
    </div>

    <div class="moongcledeal_spinner">
        <div class="spinner"></div>
    </div>

    <div class="desc">
        평균 약 1 ~ 2분 정도 소요됩니다<br>
        다른 페이지를 둘러보고 와도 괜찮아요
    </div>
</div>

<script>
    const moongcledealLoading = document.getElementById('moongcledealLoading');

	var animation = lottie.loadAnimation({
		container: document.getElementById('lottie-container-load'), // 애니메이션을 렌더할 HTML 요소
		renderer: 'svg', // SVG 형태로 렌더링
		loop: true, // 반복 재생 여부
		autoplay: true, // 자동 재생 여부
		path: '/assets/app/json/moongcleAnimation.json' // Lottie JSON 파일의 경로
	});

    // 텍스트 애니메이션
    const texts = document.querySelectorAll('.moongcledeal_load_text');
    let currentIndex = 0;

    function showNextText() {
        texts.forEach((el, i) => el.classList.remove('show')); 
        texts[currentIndex].classList.add('show'); 

        currentIndex = (currentIndex + 1) % texts.length;
    }

    showNextText(); 

    setInterval(showNextText, 2500);
</script>