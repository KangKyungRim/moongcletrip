<div class="refresh-indicator" id="refresh-indicator">
    <div id="lottie-container-refresh" class="ico"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('.refresh__wrap');
        const refreshIndicator = document.querySelector('.refresh-indicator');

        let startY = 0;
        let pulling = false;
        const threshold = 80;       // 새로고침 실제 작동 기준
        const minPullStart = 50;    // ✅ 이 높이 이상 당겨야만 화면이 당겨짐
        const maxTranslateRem = 10; // 최대 translateY (rem)

        if (!container || !refreshIndicator) return;

        document.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                pulling = true;
            }
        });

        document.addEventListener('touchmove', (e) => {
            if (!pulling) return;

            const distance = e.touches[0].clientY - startY;

            if (distance < minPullStart) return;
            e.preventDefault();

            const translateY = Math.min((distance - minPullStart) / 10, maxTranslateRem);
            container.style.transform = `translateY(${translateY}rem)`;

            if (distance > threshold) {
                refreshIndicator.classList.add('show');
            } else {
                refreshIndicator.classList.remove('show');
            }
        }, { passive: false });

        document.addEventListener('touchend', () => {
            if (!pulling) return;
            pulling = false;

            container.style.transform = ''; 

            const shouldRefresh = refreshIndicator.classList.contains('show');
            refreshIndicator.classList.remove('show');

            if (shouldRefresh) {
                setTimeout(() => {
                    showLoader();
                    location.reload();
                }, 300);
            }
        });
    });

    var animation = lottie.loadAnimation({
		container: document.getElementById('lottie-container-refresh'), // 애니메이션을 렌더할 HTML 요소
		renderer: 'svg', // SVG 형태로 렌더링
		loop: true, // 반복 재생 여부
		autoplay: true, // 자동 재생 여부
		path: '/assets/app/json/loadingGray.json' // Lottie JSON 파일의 경로
	});
</script>