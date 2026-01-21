<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];
$noticeIdx = $data['noticeIdx'];

?>

<!-- Head -->
<?php 
    switch ($noticeIdx) {
        case "1":
            $page_title_01 = "뭉클트립 v2.1 베타가 곧 오픈합니다!";
            $page_description = "뭉클트립 v2.1 베타 오픈 소식과 함께 어떤 변화가 있을지 미리 만나보세요.";
            break;
        case "2":
            $page_title_01 = "📢 [공지] 뭉클트립에 오신 것을 진심으로 환영합니다!";
            $page_description = "뭉클트립이 공식 오픈했습니다! 아이와 함께하는 여행, 뭉클이 함께할게요.";
            break;
        case "3":
            $page_title_01 = "📢 [리뷰이벤트] 후기 남기면 선물이 팡팡!";
            $page_description = "💜뭉클맘들의 후기를 기다립니다!💜 뭉클과 함께한 여행 후기를 공유해 주세요💬 참여해주신 분들께 추첨을 통해 푸짐한 선물을 드립니다!";
            break;
        case "4":
            $page_title_01 = "📢 [프로모션] AI 분리수업 키캉스 끝판왕🤖 에듀캉스 패키지✨";
            $page_description = "💜전국 최초&유일!💜 놀면서 즐기는 AI 분리수업 키캉스 끝판왕🤖 뭉클 에듀캉스 패키지✨";
            break;
        case "5":
            $page_title_01 = "📢 [뭉클 단독] 2025 고려대학교 AI 로보틱스 여름캠프 OPEN!";
            $page_description = "🎓 고려대학교에서 단 60명만! AI 로보틱스 여름캠프 OPEN 👉 뭉클 단독⚡선착순 모집 중!";
            break;
        case "6":
            $page_title_01 = "📢 [뭉클X토스페이] 할인&캐시백 이벤트";
            $page_description = "2025년 8월, 토스페이 결제 고객을 위해 뭉클이 특별한 프로모션을 준비했어요! 지금 뭉클에서 토스페이로 결제하고, 할인&캐시백 혜택을 누려보세요🤗";
            break;
        case "7":
            $page_title_01 = "📢 [리뷰이벤트] 당첨자 공지사항";
            $page_description = "안녕하세요! 뭉클트립입니다 🙂 7월 한달 간 뭉클에서 처음으로 후기이벤트를 진행했었는데요!";
            break;
        case "8":
            $page_title_01 = "1분 만에 숙소 고민 끝! 뭉클트립 사용안내서";
            $page_description = "1분 만에 숙소 고민 끝! 뭉클트립 사용안내서";
            break;
    }
    $page_url = "/notice/$noticeIdx";

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



	<div id="mobileWrap">
		<header class="header__wrap">
			<div class="header__inner">
				<button class="btn-back" onclick="goBack()"><span class="blind">뒤로가기</span></button>
				<h2 class="header-tit__center">공지사항</h2>
			</div>
		</header>

		<div class="container__wrap mypage__wrap">

			<!-- 공지사항 상세보기 -->
             <?php if ($noticeIdx === "1") : ?>
                <div class="notice__wrap">
                    <div class="notice-list__wrap">
                        <ul>
                            <li>
                                <p class="tit">뭉클트립 v2.1 베타가 곧 오픈합니다!</p>
                                <p class="date">2024.12.10</p>
                            </li>
                        </ul>
                    </div>

                    <div class="notice__view">
                        <p class="txt">
                            뭉클트립 v2.1 베타가 곧 오픈합니다!
                        </p>
                    </div>
                </div>
            <?php elseif ($noticeIdx === "2") : ?>
                <div class="notice__wrap">
                    <div class="notice-list__wrap">
                        <ul>
                            <li>
                                <p class="tit">📢 [공지] 뭉클트립에 오신 것을 진심으로 환영합니다!</p>
                                <p class="date">2025.06.18</p>
                            </li>
                        </ul>
                    </div>

                    <div class="notice__view">
                        <p class="txt">
                            안녕하세요, 뭉클트립입니다 😊<br>
                            오랜 시간 준비해온 뭉클트립이 드디어 문을 열었습니다.<br>
                            아이와 함께 떠나는 여행, 이제 뭉클이 맘 편하게 도와드릴게요.<br><br>
                            아직은 부족한 점도 많지만,<br>
                            여러분의 소중한 의견을 바탕으로 매일 더 나아지는 뭉클이 되겠습니다.<br>
                            자주 찾아주시고, 주변에 소문도 많이 내주세요! 🙌<br><br>
                            🌟 뭉클트립은 이런 서비스입니다<br><br>
                            ✅ 내가 원하는 조건만 입력하면 딱 맞는 숙소 추천!<br>
                            ✅ 추천받은 숙소에서 나만을 위한 제안 도착!<br>
                            ✅ 마감된 숙소는 재오픈 알림으로 놓치지 않게<br>
                            ✅ 궁금한 건 커뮤니티에서 편하게 묻고 답해요<br><br>
                            💜 우리 아이와 함께, 뭉클에서 좋은 추억 많이 만드셨으면 좋겠습니다.<br><br>
                            늘 행복하시고, 뭉클한 하루 보내세요!
                        </p>
                    </div>
                </div>
            <?php elseif ($noticeIdx === "3") : ?>
                <div class="notice__wrap">
                    <div class="notice-list__wrap">
                        <ul>
                            <li>
                                <p class="tit">📢 [리뷰이벤트] 후기 남기면 선물이 팡팡!</p>
								<p class="date">2025.06.27</p>
                            </li>
                        </ul>
                    </div>

                    <div class="notice__view">
                        <p class="txt">
                            <img src="/assets/app/images/event_sub_01.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">
                            💜뭉클맘들의 후기를 기다립니다!💜<br><br>

                            뭉클과 함께한 여행 후기를 공유해 주세요💬<br>
                            참여해주신 분들께 추첨을 통해 푸짐한 선물을 드립니다!<br><br>

                            ✅ 이벤트 참여 방법<br><br>
                            1️⃣ 앱 내 리뷰 작성하기<br>
                            뭉클에서 예약한 숙소에서 숙박한 후에, 마이페이지에 접속해 리뷰 남기면 참여 완료!<br>
                            + SNS에도 후기를 공유하면 당첨 확률 UP<br>
                            SNS에 뭉클트립을 통해 예약했다는 내용을 포함한 포스팅을 작성해주시면 당첨 확률 UP!<br>
                            네이버 블로그 : 블로그 포스팅 내 [#뭉클, #뭉클트립] 태그 추가<br>
                            인스타그램 : 뭉클 공식 계정 (@moongcletrip) 태그 / [#뭉클, #뭉클트립] 해시태그 추가<br>

                            <img src="/assets/app/images/event_sub_02.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;  margin-top: 1rem;">
                            💝 상품 (뭉클의 전체 숙소에서 사용 가능한 쿠폰을 드려요!)<br><br>
                            💜 1등 : 뭉클한 맘 상<br>
                            ▪️20만원 숙박 쿠폰(2명)<br>
                            ▪️뭉클의 마음도 뭉클해지는 리뷰를 남겨주신 분<br><br>
                            💜 2등 : 똑소리맘 상<br>
                            ▪️10만원 숙박 쿠폰(1명)<br>
                            ▪️센스있고 알찬 리뷰를 남겨주신 분<br><br>
                            💜 3등 : 니맘내맘 상<br>
                            ▪️5만원 숙박 쿠폰(2명)<br>
                            ▪️키즈맘들이 공감할 만한 리뷰를 남겨주신 분<br><br>
                            💜 4등 : 소소맘 상<br>
                            ▪️스타벅스 5천원권(20명)<br>
                            ▪️여행에서 느낀 소소한 행복을 공유해주신 분<br><br>

                            ✅ 이벤트 기간 : ~7/31(목)<br>
                            ✅ 당첨자 발표 : 8/5(화) / 공지사항 페이지 게시 예정<br><br>

                            뭉클맘들의 많은 참여 부탁드립니다🥰<br><br><br>

                            <span style="display: inline-block; font-size: 1.4rem; color: #959595;">
                                [이벤트 참여 공지사항]<br>
                                - 당첨자 공지: 당첨자 공지는 별표표시된 아이디와 함께 8/5 (화) 공지사항에 기재될 예정입니다.<br>
                                - 당첨자 연락 안내: 선정되신 분에게는 SNS (DM 또는 쪽지) 또는 문자로 개별 연락드리겠습니다.<br>
                                - 숙박권은 실물 형태가 아니며, 쿠폰 형태이며 뭉클에서 자유롭게 사용하실 수 있는 형태로 제공됩니다.<br>
                                - 단순 복사 및 도배되는 후기일 경우 사전고지 없이 삭제되고, 이용이 제한될 수 있습니다.<br>
                                - 본인의 사진과 글만 응모 가능하며 불법 도용 적발 시 신청이 취소될 수 있습니다. 이에 대한 법적인 책임은 응모자 본인에게 있습니다.<br>
                                - 선정된 응모 후기와 사진은 마케팅 목적으로 사용될 수 있습니다.<br>
                                - 이벤트 참여를 위해 개인정보 수집 동의, 위탁 동의가 된 것으로 간주됩니다.<br>
                            </span>

                            <button type="button" class="btn-full__primary" onclick="location.href='/my/reservations'" 
                                style="
                                    margin-top: 5rem; 
                                    position: sticky;
                                    transform: translate3d(0, 0, 0);
                                    left: 0;
                                    right: 0;
                                    bottom: 2rem;
                                ">
                                후기 남기러 가기
                            </button>
                        </p>
                    </div>
                </div>
            <?php elseif ($noticeIdx === "4") : ?>
                <div class="notice__wrap">
                    <div class="notice-list__wrap">
                        <ul>
                            <li>
                                <p class="tit">📢 [프로모션] AI 분리수업 키캉스 끝판왕🤖 에듀캉스 패키지✨</p>
								<p class="date">2025.07.18</p>
                            </li>
                        </ul>
                    </div>

                    <div class="notice__view">
                        <p class="txt">
                            <img src="/assets/app/images/notice_edu_1.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">
                            💜전국 최초&유일!💜<br>
                            놀면서 즐기는 AI 분리수업 키캉스 끝판왕🤖<br>
                            뭉클 에듀캉스 패키지✨<br><br>

                            <img src="/assets/app/images/notice_edu_2.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">
                            키즈카페는 살짝 시시해 할 7~12세 아이들을 위해<br>
                            미래 유망 산업 🤖AI 로보틱스🤖를<br><br>

                            ✅ RC카 코딩<br>
                            ✅ AI 탐정<br>
                            ✅ 탱크 전투배틀<br>
                            ✅ 코딩 사격레이싱 등<br><br>

                            우리 아이 흥미 폭발할 재밌는 수업으로 접할 수 있는<br>
                            단 하루뿐인 기회!<br><br>

                            <img src="/assets/app/images/notice_edu_3.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">
                            8/3(일) 단 하루!<br>
                            라마다 앙코르 김포 한강 호텔에서<br>
                            선착순으로 만나볼 수 있는<br>
                            뭉클X아카(IAKKA) 에듀캉스 패키지를<br>
                            💜오직 뭉클에서만💜<br>
                            예약할 수 있어요!<br><br>

                            💟뭉클 단독 에듀캉스 패키지 (*쿠폰 적용가 기준)<br>
                            ▪️객실+AI 로보틱스 체험 교육(1회/2시간 30분 진행)<br>
                            ▪️8/2(토) 최저 184,000원부터~<br>
                            ▪️8/3(일) 최저 174,000원부터~<br>
                            ▪️클래스 진행 날짜 : 8/3(일)<br>
                            ✔️ 오전반 - 10:00~12:30(2시간 30분)<br>
                            ➡️ 8/2(토) 투숙 체크아웃 후 이용 또는 8/3(일) 체크인 전 이용<br>
                            ✔️ 오후반 - 16:00~18:30(2시간 30분)<br>
                            ➡️ 8/2(토) 투숙 체크아웃 후 이용 또는 8/3(일) 체크인 이후 이용<br><br>

                            ※ 룸 타입 및 날짜별로 상세 요금이 다를 수 있습니다.<br>
                            ※ 8/2~3일 투숙이 어려우신 분들의 경우<br>
                            다른 날짜 상품에 🧸키즈존 1인 무료🧸<br>
                            단독 프로모션도 진행 중이니 참고 부탁드립니다!


                            <button type="button" class="btn-full__primary" onclick="location.href='/stay/detail/14366?startDate=2025-08-03&endDate=2025-08-04&adult=2&child=0&infant=0'" 
                                style="
                                    margin-top: 5rem; 
                                    position: sticky;
                                    transform: translate3d(0, 0, 0);
                                    left: 0;
                                    right: 0;
                                    bottom: 2rem;
                                ">
                                더 알아보기
                            </button>
                        </p>
                    </div>
                </div>
            <?php elseif ($noticeIdx === "5") : ?>
                <div class="notice__wrap">
                    <div class="notice-list__wrap">
                        <ul>
                            <li>
                                <p class="tit">📢 [뭉클 단독] 2025 고려대학교 AI 로보틱스 여름캠프 OPEN!</p>
                                <p class="date">2025.07.21</p>
                            </li>
                        </ul>
                    </div>

                    <div class="notice__view">
                        <p class="txt">
                            <img src="/assets/app/images/notice_camp_1.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">
                            🎓 고려대학교에서 단 60명만!<br>
                            AI 로보틱스 여름캠프 OPEN 👉 뭉클 단독⚡선착순 모집 중!<br><br>

                            이번 여름, 우리 아이에게 AI 미래교육을 선물하세요!<br>
                            AI, VR·XR, 로보틱스까지 모두 체험할 수 있는<br>
                            프리미엄 3박 4일 캠프가<br>
                            고려대학교 안암캠퍼스에서 단독으로 열립니다🎊<br><br>

                            🔹 전국 초4~중1 대상<br>
                            🔹 뭉클트립 단독 기획/고려대·성균관대·아주대 12명의 선생님이 직접 운영<br>
                            🔹 AI 탱크배틀,VR·XR 실습,AI 코딩,포트폴리오 홈페이지 제작까지<br>
                            🔹 소수정예(60명) 집중형 체험교육<br>
                            🔹 가평 청심 청소년수련원 숙박 포함 / 식사 제공 / 안전 담당자 상주<br><br>

                            <img src="/assets/app/images/notice_camp_2.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">
                            이번 캠프에서는 단순한 체험이 아니라<br>
                            👉 실제 공학과 코딩, 디자인, AI를 통합적으로 배우며<br>
                            👉 나만의 웹사이트까지 직접 만들 수 있어요!<br>
                            이번 캠프를 통해 아이들의 미래 탐구력과 진로 감각을 확실하게 키울 수 있어요🤓<br><br>

                            💜 아래 [캠프 신청하기] 버튼을 눌러 신청해주세요!<br><br>

                            ※ 조기 마감 주의! 이번 여름방학 단 한 번 뿐인 기회를 놓치지 마세요😉

                            <button type="button" class="btn-full__primary" onclick="location.href='/stay/detail/15097?startDate=2025-08-04&endDate=2025-08-05&adult=1&child=0&infant=0&childAge=%7B%7D&infantMonth=%7B%7D'" 
                                style="
                                    margin-top: 5rem; 
                                    position: sticky;
                                    transform: translate3d(0, 0, 0);
                                    left: 0;
                                    right: 0;
                                    bottom: 2rem;
                                ">
                                캠프 신청하기
                            </button>
                        </p>
                    </div>
                </div>
            <?php elseif ($noticeIdx === "6") : ?>
                <div class="notice__wrap">
                    <div class="notice-list__wrap">
                        <ul>
                            <li>
                                <p class="tit">📢 [뭉클X토스페이] 할인&캐시백 이벤트</p>
                                <p class="date">2025.08.01</p>
                            </li>
                        </ul>
                    </div>

                    <div class="notice__view">
                        <p class="txt">
                            2025년 8월, 토스페이 결제 고객을 위해<br>
                            뭉클이 특별한 프로모션을 준비했어요!<br>
                            지금 뭉클에서 토스페이로 결제하고,<br>
                            할인&캐시백 혜택을 누려보세요🤗<br><br>
                            
                            <img src="/assets/app/images/notice_toss_1.jpg" alt="토스 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">

                            1️⃣ 결제 프로모션 (전 고객 대상)<br>
                            ▪ 대상 : 토스페이로 결제하는 누구나<br>
                            ▪ 기간 : 2025년 8월 1일~11월 30일<br>
                            ▪ 혜택 : 10만원 이상 결제 시 3천원 즉시 할인<br><br>

                            <img src="/assets/app/images/notice_toss_2.jpg" alt="토스 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">

                            2️⃣ 생애 첫 결제 프로모션<br>
                            ▪ 대상 : 토스페이를 태어나 처음으로 사용하는 고객<br>
                            ▪ 기간 : 2025년 8월 1일 ~ 2026년 1월 31일<br>
                            ▪ 혜택 : 20만원 이상 결제 시 2만원 적립<br><br>

                            지금 바로 토스페이로 결제하고, 알뜰한 혜택을 경험해보세요⚡<br><br>

                            (※ 프로모션은 사정에 따라 조기 종료 또는 변경될 수 있습니다.)
                        </p>
                    </div>
                </div>
            <?php elseif ($noticeIdx === "7") : ?>
                <div class="notice__wrap">
                    <div class="notice-list__wrap">
                        <ul>
                            <li>
                                <p class="tit">📢 [리뷰이벤트] 당첨자 공지사항</p>
                                <p class="date">2025.08.05</p>
                            </li>
                        </ul>
                    </div>

                    <div class="notice__view">
                        <p class="txt">

                        <img src="/assets/app/images/event_sub_01.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">
                        <img src="/assets/app/images/event_sub_02.png" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">

                        안녕하세요! 뭉클트립입니다 🙂<br>

                        7월 한달 간 뭉클에서 처음으로 후기이벤트를 진행했었는데요!<br>

                        모두 진심어린 후기를 정성스럽게 작성해주셔서 정말 감사드립니다.<br>

                        유난히 더운 이번 여름! 뭉클트립을 이용하셨던 모든 고객님들께서 편안하고 즐거운 투숙이 되셨길 바라며, 모처럼 떠나셨던 여행이 소중한 추억으로 간직되시길 바라겠습니다.<br>

                        당첨되신 고객님들께 진심으로 축하드리며, 혹시나 당첨이 안되신 분들에게도 진심으로 참여해주셔서 감사의 말씀을 전해 드립니다. 당첨이 안되신 분들께서는 아쉬울 수 있으시겠지만 뭉클에서 계속 좋은 이벤트 진행할 예정이오니 참고 부탁 드리겠습니다 :)<br>

                        당첨된 분들께는 별도 연락드릴 예정이며, 연락을 못 받으셨다면 카카오톡 1:1 채팅하기로 연락주시길 바랍니다.<br><br>

                        <button type="button" onclick="outLink('http://pf.kakao.com/_dEwbG/chat')">👉 뭉클트립 카카오 1:1 채널 http://pf.kakao.com/_dEwbG/chat</button><br><br>

                        <b>[당첨자 공지]</b><br><br>
                        💜 1등 : 뭉클한 맘 상<br>

                        ▪️20만원 숙박 쿠폰(2명)<br>

                        ▪️뭉클의 마음도 뭉클해지는 리뷰를 남겨주신 분<br><br>

                         ㄴ당첨자 <br>
                        1. 따뜻한 브뤼셀의 코끼리 (weeds12***@hanmail.net)<br>
                        2. 쾌활한 런단의 판다 (damin***@hanmail.net)<br><br>

                         💜 2등 : 똑소리맘 상<br>

                        ▪️10만원 숙박 쿠폰(1명)<br>

                        ▪️센스있고 알찬 리뷰를 남겨주신 분<br><br>

                        ㄴ당첨자 <br>
                        1. 고요한 알제의 올빼미 (dkfma***@gmail.com)<br><br>

                        💜 3등 : 니맘내맘 상<br>

                        ▪️5만원 숙박 쿠폰(2명)<br>

                        ▪️키즈맘들이 공감할 만한 리뷰를 남겨주신 분<br><br>

                        ㄴ당첨자 <br>
                        1. 똑똑한 멜버른의 독수리 (dfkj0***@naver.com)<br>
                        2. 사커 (wjsdl***@naver.com)<br><br>

                        💜 4등 : 소소맘 상<br>

                        ▪️스타벅스 5천원권(20명)<br>

                        ▪️여행에서 느낀 소소한 행복을 공유해주신 분<br><br>

                        ㄴ당첨자 <br>
                        1. 조용한 헬싱키의 개구리 (clareki***@naver.com)<br>
                        2. 신비로운 빈의 올빼미 (cjs23**@nate.com)<br>
                        3. 신비로운 베이루트의 고래 (giojunio***@naver.com)<br>
                        4. 재빠른 로스앤젤레스의 햄스터 (xxxm**@kakao.com)<br>
                        5. 쾌활한 시카고의 여우 (hw881***@gmail.com)<br>
                        6. 귀여운 토론토의 상어 (wndud7***@nate.com)<br>
                        7. 화려한 부다페스트의 판다 (bloom**@naver.com)<br>
                        8. 밝은 상파울루의 용 (yang**@nate.com)<br>
                        9. 고요한 빈의 돌고래 (msbh***@naver.com)<br>
                        10. 용감한 코펜하겐의 고래 (asd803***@naver.com)<br>
                        11. 비밀스러운 헬싱키의 판다 (lovely1**@hanmail.net)<br>
                        12. 재빠른 광주의 호랑이 (eunmi***@albacorp.com)<br>
                        13. 귀여운 프라하의 돌고래 (hjhm2***@hanmail.net)<br>
                        14. 밝은 바쿠의 코끼리 (loveel***@gmail.com)<br>
                        15. 똑똑한 알마티의 햄스터 (ckstlr**@naver.com)<br>
                        16. 신비로운 시드니의 늑대 (hayoun.***@kakao.com)<br>
                        17. 느긋한 카불의 여우 (suja***@hanmail.net)<br>
                        18. 느긋한 바르셀로나의 고래 (lhle0***@kakao.com)<br>
                        19. 느긋한 콜롬보의 나비 (ngu**@hanmail.net)<br>
                        20. 귀여운 성남의 햄스터 (luv***@hanmail.net)<br><br><br>
                        
                        <b>[숙박쿠폰 지급 방법]</b><br><br>

                        - 기술적인 이슈로 인해 ~8월 15일 (금)까지 후기를 남겨주신 계정ID로 발행해드릴 예정입니다. 혹시나 더 늦어진다면 별도 안내드리도록 하겠습니다 :)<br><br>

                        참여해주신 모든 고객님들께 진심으로 감사합니다.<br>

                        그럼 늘 안전하고 뭉클한 여행되시길 바라겠습니다.<br>

                        감사합니다.<br>

                        뭉클트립 드림<br>
                        </p>
                    </div>
                </div>
            <?php elseif ($noticeIdx === "8") : ?>
                <div class="notice__wrap">
                    <div class="notice-list__wrap">
                        <ul>
                            <li>
                                <p class="tit">1분 만에 숙소 고민 끝! 뭉클트립 사용안내서</p>
                                <p class="date">2025.09.18</p>
                            </li>
                        </ul>
                    </div>

                    <div class="notice__view">
                        <p class="txt">

                            <img src="/assets/app/images/notice_08.jpg" alt="리뷰 이벤트 배너" style="width: 100%; border-radius: 1rem; margin-bottom: 1rem;">

                            <button type="button" class="btn-full__primary" onclick="location.href='/moongcledeals'" 
                                style="
                                    margin-top: 5rem; 
                                    position: sticky;
                                    transform: translate3d(0, 0, 0);
                                    left: 0;
                                    right: 0;
                                    bottom: 2rem;
                                ">
                                숙소 추천 받기
                            </button>
                        </p>
                </div>
            <?php endif; ?>
			<!-- //공지사항 상세보기 -->
		</div>
	</div>

	<?php
	if ($deviceType == 'pc') {
		include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/app/blocks/pc-wrapper-bottom.php";
	}
	?>

	<script>
		thirdpartyWebviewZoomFontIgnore();
	</script>

</body>

</html>