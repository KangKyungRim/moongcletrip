<!DOCTYPE html>
<html lang="ko">

<?php

$deviceType = $data['deviceType'];
$user = $data['user'];

?>

<!-- Head -->
<?php 
    $page_title_01 = "자주 묻는 질문";

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
				<h2 class="header-tit__center">자주 묻는 질문</h2>
			</div>
		</header>

		<div class="container__wrap mypage__wrap">
            <div class="tab__wrap tab-round__wrap type-circle">
                <div class="overflow-x-visible">
                    <ul class="tab__inner capsule-btns padding-x-20" style="padding-bottom: 0rem;">
                        <li class="tab-round__con active">
                            <a href="#none">뭉클딜</a>
                        </li>
                        <li class="tab-round__con">
                            <a href="#none">이용 문의</a>
                        </li>
                        <li class="tab-round__con">
                            <a href="#none">예약 문의 (공통)</a>
                        </li>
                        <li class="tab-round__con">
                            <a href="#none">쿠폰/포인트</a>
                        </li>
                        <li class="tab-round__con">
                            <a href="#none">결제/영수증</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-contents__wrap">
                    <!-- 뭉클딜 -->
                    <div class="tab-contents active">
                        <div class="faq_page faq__wrap  accordion__wrap">
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">뭉클딜 받기(숙소 추천 서비스)는 무료인가요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    네, 무료입니다.<br>
                                    좋은 숙소 추천 받기 서비스부터 간편한 예약까지, 맘 편히 이용하실 수 있습니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">뭉클딜은 무엇인가요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    숙소 추천 서비스(뭉클딜)은 고객님의 숙소 조건에 맞는 최적의 여행을 알아서 찾아주는 서비스입니다. 호텔과 숙박업 전문가의 손길이 닿은 빅데이터 기반을 작동되며 원하시는 조건에 최적 숙소를 매칭해드립니다. 추천서비스 매칭 알고리즘은 지속적으로 개선되고 있습니다. 감사합니다. <br>
                                    <br>
                                    [추천 받는 방법]<br>
                                    1. ‘홈 또는 뭉클딜 화면에서 등록하기’ 클릭<br>
                                    2. 숙소 조건 (인원/날짜/도시/선호 조건)을 간편하게 등록 (30초 소요)<br>
                                    3. 나에게 맞는 추천 숙소 도착<br>
                                    4. 원하는 숙소를 선택하고 예약 완료!<br>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">뭉클딜(숙소 추천 서비스)로 예약하면 무엇이 좋나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    뭉클 숙소 추천 서비스(뭉클딜)은 고객님께 최적화된 여행을 더 저렴하고, 맞춤 혜택으로 제공해드립니다. 뭉클은 고객님의 요청사항을 바탕으로 숙소 파트너와 협상하여 더 좋은 가격과 혜택을 제안합니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">숙소 추천은 몇 개까지 받을 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    숙소 추천은 한번에 최대 12개까지 받을 수 있습니다. 조건 편집을 진행하시면 다시 새롭게 12개까지 수신 받으실 수 있습니다. (약 1~2분 소요) 
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">뭉클딜을 지인에게 공유해도 되나요? </p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    네, 가능합니다. 나만의 추천 숙소(뭉클딜)은 주변 지인에게 공유 가능합니다. 나만의 뭉클딜 상세 페이지에서 공유 버튼을 누르시면 공유가 가능합니다. 
                                    <br>
                                    뭉클딜에 따라서 수량의 제한이 있을 수도 있으며, 사전 고지 없이 제안받은 뭉클딜이 종료되실 수 있는 점 참고 부탁드립니다.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 이용 문의 -->
                    <div class="tab-contents">
                        <div class="faq_page faq__wrap  accordion__wrap">
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[계정] 예약을 했는데 어떤 아이디로 예약 했는지 기억이 안나요</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    뭉클은 고객님의 편의를 위해 다양한 방식의 회원가입 및 로그인 방식을 지원합니다. 이메일 회원가입 또는 카카오톡 간편로그인, 애플 간편로그인등이 가능합니다.<br>
                                    <br>
                                    많은 고객님께서 카카오 간편 로그인을 선호하고 계시며, 여러 카카오톡 ID를 사용하실 경우 다양한 아이디를 통해 확인 부탁드립니다.<br>
                                    <br>
                                    예약내역이 쉽게 조회가 되지 않으신다면 아래의 카카오톡 1:1 채팅을 통해 고객님의 성함과 예약하셨던 호텔명과 날짜 또는 예약번호 또는 핸드폰번호를 알려주시면 안내드리도록 하겠습니다.<br>
                                    <br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">뭉클 카카오톡 상담: <a href="https://pf.kakao.com/_dEwbG">https://pf.kakao.com/_dEwbG</a></li>
                                        <li style="list-style-type:disc">뭉클 고객센터 이메일: <a href="mailto:moongcletrip@honolulu.co.kr">moongcletrip@honolulu.co.kr</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[계정] 아이디와 비밀번호를 잃어버렸어요</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    [아이디 찾기]<br>
                                    1. 이메일 가입 회원: 가입 시 사용한 이메일 주소나 닉네임 일부를 고객센터에 요청하여 조회 가능합니다.<br>
                                    2. SNS 계정 가입 회원: 카카오톡, 페이스북, 네이버, 애플, 구글로 가입한 경우, 해당 플랫폼에 접속하여 확인하거나 해당 계정 관리 업체의 고객센터에 문의하세요.<br>
                                    <br>
                                    [비밀번호 찾기]<br>
                                    ※ 이메일로 가입한 경우에만 해당됩니다.<br>
                                    1. 로그인 화면에서 비밀번호 재설정 선택<br>
                                    2. 가입 시 입력한 이메일 주소 입력<br>
                                    3. 인증 방법 선택 (휴대폰 번호 또는 이메일 주소)<br>
                                    4. 발송된 인증번호를 입력한 후 새 비밀번호 설정<br>
                                    <br>
                                    위 방법으로도 문제가 해결되지 않는다면, 추가적인 도움을 위해 고객센터로 문의해 주세요.
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">뭉클 카카오톡 상담: <a href="https://pf.kakao.com/_dEwbG">https://pf.kakao.com/_dEwbG</a></li>
                                        <li style="list-style-type:disc">뭉클 고객센터 이메일: <a href="mailto:moongcletrip@honolulu.co.kr">moongcletrip@honolulu.co.kr</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[계정] 여러개의 아이디를 만들 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    네, 가능합니다만 1개의 아이디를 주ID로 이용해주실 것을 추천드립니다. 쿠폰, 포인트 등은 양도가 되지 않기 때문입니다. 동일한 이메일 주소로는 여러 계정을 생성할 수는 없습니다
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[계정] 계정이 정지되었어요</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    불쾌한 사진이나 혐오감을 주는 사진 또는 글을 게시하면 이용자의 계정이 정지될 수 있어요. 만약 규정을 위반하지 않았음에도 정지되었다면 이메일을 통해 접수해주세요. (이메일: <a href="mailto:moongcletrip@honolulu.co.kr">moongcletrip@honolulu.co.kr</a>)<br>
                                    <br>
                                    아래 기준을 위반한 경우 사전 경고 없이 계정이 정지될 수 있습니다.<br>
                                    <br>
                                    [서비스 이용 관련]<br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">고객 센터 담당자에게 언어적 폭력, 욕설 및 폭언을 할 경우 (관련 법에 따라 민형사상 책임을 져야할 수 있습니다.)</li>
                                        <li style="list-style-type:disc">허위 정보로 회원가입한 경우</li>
                                        <li style="list-style-type:disc">타인의 명의나 개인정보를 도용해 회원가입 후 서비스를 이용한 경우</li>
                                        <li style="list-style-type:disc">타인의 결제정보를 도용하거나 부정 거래를 한 경우</li>
                                        <li style="list-style-type:disc">타인의 서비스 이용을 방해하거나 회사 서비스 제공에 지장을 초래한 경우</li>
                                        <li style="list-style-type:disc">회사의 데이터나 시스템을 훼손, 변경, 또는 위조하거나 이를 시도한 경우</li>
                                        <li style="list-style-type:disc">약관이나 서비스 이용 방법을 위반하거나 이를 우회해 부정 이용한 경우</li>
                                        <li style="list-style-type:disc">관련 법령을 위반하거나 범죄에 이용, 교사, 또는 방조한 경우</li>
                                    </ul>
                                    <br>
                                    [후기 관련]<br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">욕설, 선정적인 표현이 포함된 경우</li>
                                        <li style="list-style-type:disc">특정 회원 또는 제3자를 비방하거나 명예를 훼손하는 내용인 경우</li>
                                        <li style="list-style-type:disc">장소 또는 공간 이용과 관련 없는 내용인 경우</li>
                                        <li style="list-style-type:disc">실제로 장소나 공간을 이용하지 않고 작성된 경우우</li>
                                        <li style="list-style-type:disc">개인정보(실명, 연락처 등)가 노출되거나 노출 우려가 있는 경우</li>
                                        <li style="list-style-type:disc">저작권을 침해하거나 장소와 무관한 사진이 포함된 경우</li>
                                        <li style="list-style-type:disc">도배성 게시물</li>
                                        <li style="list-style-type:disc">정보통신망 관련 법률을 위반하는 내용</li>
                                        <li style="list-style-type:disc">긍정적인 리뷰 작성을 조건으로 금전적 보상, 할인, 환불 등을 약속받아 작성된 리뷰</li>
                                        <li style="list-style-type:disc">회사로부터 사전 승인받지 않은 상업광고나 판촉 내용을 포함하는 경우</li>
                                        <li style="list-style-type:disc">불법적이거나 위해한 행위를 지지 또는 조장하는 경우뷰</li>
                                        <li style="list-style-type:disc">외설적, 폭력적, 위협적 내용을 포함한 경우</li>
                                        <li style="list-style-type:disc">근거 자료를 통해 허위로 판단될 수 있는 내용</li>
                                        <li style="list-style-type:disc">회사 또는 제3자의 저작권 등 기타 권리를 침해하는 내용</li>
                                        <li style="list-style-type:disc">정치적, 종교적 견해를 포함한 경우</li>
                                        <li style="list-style-type:disc">회사의 게시물 작성 원칙에 위배되거나 게시 위치의 성격에 부합하지 않는 경우</li>
                                        <li style="list-style-type:disc">권리자(사업주)의 게시물 삭제 요청이 있는 경우</li>
                                        <li style="list-style-type:disc">기타 관계법령에 위배된다고 판단되는 내용</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[후기] 직접 작성한 후기가 왜 삭제되었나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    뭉클트립은 리뷰가 게시되기 전 사전 검토 과정을 거치지 않습니다. 사용자들에게 솔직하고 투명한 리뷰 작성을 권장합니다.<br>
                                    <br>
                                    다만, 리뷰는 반드시 실제 장소 이용 경험을 바탕으로 작성되어야 하며, 아래 기준을 위반한 경우 삭제될 수 있습니다.<br>
                                    <br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">욕설, 선정적인 표현이 포함된 경우</li>
                                        <li style="list-style-type:disc">특정 회원 또는 제3자를 비방하거나 명예를 훼손하는 내용인 경우</li>
                                        <li style="list-style-type:disc">장소 또는 공간 이용과 관련 없는 내용인 경우</li>
                                        <li style="list-style-type:disc">실제로 장소나 공간을 이용하지 않고 작성된 경우</li>
                                        <li style="list-style-type:disc">개인정보(실명, 연락처 등)가 노출되거나 노출 우려가 있는 경우</li>
                                        <li style="list-style-type:disc">저작권을 침해하거나 장소와 무관한 사진이 포함된 경우</li>
                                        <li style="list-style-type:disc">도배성 게시물</li>
                                        <li style="list-style-type:disc">정보통신망 관련 법률을 위반하는 내용</li>
                                        <li style="list-style-type:disc">긍정적인 리뷰 작성을 조건으로 금전적 보상, 할인, 환불 등을 약속받아 작성된 리뷰</li>
                                        <li style="list-style-type:disc">회사로부터 사전 승인받지 않은 상업광고나 판촉 내용을 포함하는 경우</li>
                                        <li style="list-style-type:disc">불법적이거나 위해한 행위를 지지 또는 조장하는 경우</li>
                                        <li style="list-style-type:disc">외설적, 폭력적, 위협적 내용을 포함한 경우</li>
                                        <li style="list-style-type:disc">근거 자료를 통해 허위로 판단될 수 있는 내용</li>
                                        <li style="list-style-type:disc">회사 또는 제3자의 저작권 등 기타 권리를 침해하는 내용</li>
                                        <li style="list-style-type:disc">정치적, 종교적 견해를 포함한 경우</li>
                                        <li style="list-style-type:disc">회사의 게시물 작성 원칙에 위배되거나 게시 위치의 성격에 부합하지 않는 경우</li>
                                        <li style="list-style-type:disc">권리자(사업주)의 게시물 삭제 요청이 있는 경우</li>
                                        <li style="list-style-type:disc">기타 관계법령에 위배된다고 판단되는 내용</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[국내 숙소 파트너] 뭉클에 운영중인 숙소를 등록하고 싶어요</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                        뭉클 입점을 원하실 경우 뭉클 파트너센터로 문의해주시면 친절하게 안내드리도록 하겠습니다.<br>
                                    <br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">뭉클 입점신청 퀵링크: <a href="https://tally.so/r/nWEqpk">https://tally.so/r/nWEqpk</a></li>
                                        <li style="list-style-type:disc">뭉클 파트너센터 이메일: <a href="mailto:moongcletrip@honolulu.co.kr">moongcletrip@honolulu.co.kr</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[여행 상품 파트너] 뭉클에 운영중인 투어 / 액티비티 / 티켓 / 패키지 상품을 등록하고 싶어요</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    뭉클은 숙박상품 뿐만 아니라 투어 / 액티비티 / 티켓 / 패키지 투어 의 상품도 판매 가능할 예정이며, 준비중입니다. 해당 서비스 오픈 후 뭉클 입점을 원하실 경우 뭉클 파트너센터로 문의해주시면 친절하게 안내드리도록 하겠습니다. <br>
                                    <br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">뭉클 입점신청 퀵링크: <a href="https://tally.so/r/nWEqpk">https://tally.so/r/nWEqpk</a></li>
                                        <li style="list-style-type:disc">뭉클 파트너센터 이메일: <a href="mailto:moongcletrip@honolulu.co.kr">moongcletrip@honolulu.co.kr</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 예약 문의 (공통) -->
                    <div class="tab-contents">
                        <div class="faq_page faq__wrap  accordion__wrap">
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 취소] 예약을 취소하고 싶어요.</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    예약취소는 [앱/웹] > [예약 내역 확인]에서 직접 가능합니다.<br>
                                    예약 진행 당시 안내된 취소/환불 규정에 따라 처리되며, 숙소의 규정에 따라 취소수수료가 발생할 경우 취소수수료를 차감한 금액으로 환불 처리됩니다.<br>
                                    숙소의 취소 및 환불 규정은 상품페이지와 예약안내 이메일, 카카오 알림톡을 통해 안내되며, 예약 진행시 취소 및 환불규정에 동의 체크박스를 선택해야만 예약진행이 가능하오니 사전에 취소 및 환불 규정을 반드시 숙지해주시기 바랍니다. 감사합니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 취소] 취소/환불규정은 어떻게 되나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    취소/환불 규정은 '입실일'을 기준으로 적용되며, 취소 수수료는 ‘판매 금액’을 기준으로 계산됩니다.<br><br>
                                    숙소의 규정에 따라 취소/환불 규정이 상이합니다.숙소의 취소 및 환불 규정은 상품페이지와 예약안내 이메일, 카카오 알림톡을 통해 안내되며, 예약 진행시 취소 및 환불규정에 동의 체크박스를 선택해야만 예약진행이 가능하오니 사전에 취소 및 환불 규정을 반드시 숙지해주시기 바랍니다. 감사합니다.
                                    <br>    
                                    <br>    
                                    ※노쇼(입실일에 방문하지 않고 일정이 지난경우) 또는 입실 후에는 취소가 불가합니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 취소] 무료취소기한이 지나서 취소할 경우 왜 위약금이 발생하는 건가요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    무료 취소 기한은 고객님과 판매자와의 약속입니다. 노쇼 및 당일 취소/투숙일이 임박하여 취소한 경우, 환불이 어렵습니다. 이는 숙소에서 고객님의 투숙 객실을 판매하지 않고 다른 고객님의 예약을 받지않기 때문입니다. 따라서, 노쇼 및 당일취소, 임박하여 취소는 어려운 점 깊은 양해 부탁드립니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">취소수수료 계산 기준은 어떻게 되나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    취소수수료는 포인트를 포함한 최종 결제 금액 기준으로 계산되며, 실 결제금액에서 취소수수료 발생 비중 (%)를 공제한 후 환불이 진행됩니다.
                                    <br><br>
                                    결제 시 포인트를 사용한 경우, 포인트가 우선 차감된 후 잔여 금액이 환불 처리됩니다.
                                    <br><br>
                                    취소 및 환불 조건은 조회 시점과 실제 환불 진행 시점에 따라 달라질 수 있으니, 예약/결제 시 안내된 취소 및 환불 규정을 반드시 확인해 주시기 바랍니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 취소] 천재지변/감염병으로 인한 예약취소는 어떻게 하나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    천재지변(기상악화)으로 숙소 이용이 불가할 경우 뭉클트립 고객센터로 예약내역 및 증빙서류(결항증명서, E-티켓 등)를 보내주시면 확인 후 전액 환불이 가능한지 검토하여 안내해 드립니다. 
                                    <br><br>
                                    질병, 사고 등 불가피한 사유로 예약 취소가 필요하다면 뭉클트립 고객센터를 통해 문의해 주세요. 진단서, 가족관계 증명서 등 관련 증빙 서류를 요청드릴 수 있으며, 숙소의 정책에 따라 취소 수수료가 발생하거나 취소가 제한될 수 있습니다.
                                    <br><br>
                                    단, 숙소에서 현지 사정상 여행할 수 없다고 먼저 알려오는 경우 증빙 서류 제출 없이 환불에 대해 안내해드립니다.
                                    <br><br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">뭉클트립 고객센터 이메일: <a href="mailto:moongcletrip@honolulu.co.kr">moongcletrip@honolulu.co.kr</a></li>
                                        <li style="list-style-type:disc">카카오톡 상담: <a href="https://pf.kakao.com/_dEwbG">https://pf.kakao.com/_dEwbG</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 취소] 숙소의 사정으로 인한 취소시 환불 받을 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    숙소의 사정으로 예약하신 숙소가 취소된 경우에는 결제 취소 및 환불이 이뤄지게 됩니다. 카드사 영업일에 따라 3~5일정도 소요됩니다. 예약취소 후 이메일 혹은 카카오알림톡으로 예약취소 알림을 받으실 수 있으며, 예약내역에서 취소를 꼭 확인해주세요. 3~5일 영업일이 지났는데도 카드사에서 환불이 되지 않았을 경우에는 뭉클트립 고객센터로 문의주시기 바랍니다.
                                    (<a href="mailto:moongcletrip@honolulu.co.kr">moongcletrip@honolulu.co.kr</a> / 카카오톡 1:1 상담 <a href="https://pf.kakao.com/_dEwbG">https://pf.kakao.com/_dEwbG</a>) 감사합니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 문의] 예약 확정은 언제되나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    대부분의 상품은 예약확정은 예약 즉시 진행됩니다. 예약 직후 1분이내에 카카오알림톡 및 이메일, 앱푸쉬알림 등으로 예약확정 문자를 수신받게 됩니다. 
                                    <br><br>
                                    단, 일부 상품은 대기 예약 방식으로 진행이 되며, 상품 페이지 또는 상품명에 대기예약인 부분이 기재가 되어 있습니다. 
                                    <br><br> 
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">대기예약 상품은 접수 후 영업일 1~2일 이내 예약 가능시 예약확정 안내 알림톡 또는 문자메시지를 발송해드립니다.</li>
                                        <li style="list-style-type:disc">예약 진행 과정시 부득이하게 객실이 마감된 경우에는 예약이 불가할 수 있으며, 이 경우 결제는 수수료없이 환불 처리됩니다.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 문의] 대기 예약은 무엇인가요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    일부 상품은 판매자의 특별한 요청에 의하여 대기 예약 방식으로 진행이 되며, 상품 페이지 또는 상품명에 대기예약인 부분이 기재가 되어 있습니다. 
                                    <br><br> 
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">대기예약 상품은 접수 후 영업일 1~2일 이내 예약 가능시 예약확정 안내 알림톡 또는 문자메시지를 발송해드립니다.</li>
                                        <li style="list-style-type:disc">예약 진행 과정시 부득이하게 객실이 마감된 경우에는 예약이 불가할 수 있으며, 이 경우 결제는 수수료없이 환불 처리됩니다.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 문의] 방 2개를 예약하려면 어떻게 해야하나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    2개 이상의 객실을 예약하시려면 객실 선택을 하실 때 추가 수량을 선택하실 수 있습니다.
                                    <br><br> 
                                    단, 해당 객실이 1객실만 남아있다면 해당 동일한 객실 타입으로 2객실 예약은 어려우십니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 변경] 이용날짜/객실타입을 변경할 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    이용날짜 및 상품 옵션 변경은 불가하시며, 취소 후 다시 예약해주셔야 합니다. 다만 임박해서 취소하실 경우 기존 적용된 요금과 포함사항 혜택 등을 이용하지 못하실 수도 있으시며, 상황에 따라 요금의 변동이 생기실 수 있습니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[예약 변경] 체크인 날짜를 잘못 예약했어요. 일정을 변경할 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    예약 후 예약 정보는 변경이 불가합니다.
                                    일정을 변경하시려면 기존 예약을 취소한 후 새로 예약을 진행해 주셔야 합니다.
                                    <br><br> 
                                    단, 취소 시점에 따라 취소 수수료가 발생할 수 있으니, 예약 시 안내된 취소/환불 규정을 꼭 확인해 주세요.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[이용 관련] 입실 시 모바일 신분증도 사용 가능한가요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">
                                            국내로 여행을 떠나실 경우<br>
                                            허용 가능한 모바일 신분증에 한하여 사용이 가능합니다.
                                            <br>
                                            [허용 가능한 모바일 신분증]<br>
                                            <ul style="padding-left: 2rem;">
                                                <li style="list-style-type:disc">PASS 앱을 통한 ‘주민등록증 모바일 확인서비스’</li>
                                                <li style="list-style-type:disc">정부24 앱을 통한 ‘주민등록증 모바일 확인서비스’</li>
                                                <li style="list-style-type:disc">모바일 신분증 앱을 통한 ‘모바일 운전면허증(경찰청발행)”</li>
                                            </ul>
                                            이 외에는 허용이 불가하며, 모바일 신분증 캡쳐 및 사진 등도 사용이 불가합니다.
                                        </li>
                                        <li style="list-style-type:disc">
                                            해외로 여행을 떠나실 경우<br>
                                            해외로 여행을 떠나실 경우 체크인 또는 여행 상품 이용 시 여권과 같은 공식적인 신분증의 증명이 필요하실 수 있습니다.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[이용 관련] 미성년자도 예약이 가능한가요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    만 19세 미만의 미성년자는 청소년 보호법에 따라 일부 숙박 시설의 이용이 제한될 수 있습니다.<br><br>
                                    숙소 입실 시 신분증 확인을 요구할 수 있으며, 미성년자의 입실이 불가한 경우 취소나 환불이 어려우니 예약 전 숙소에 미성년자 이용 가능 여부를 반드시 확인해 주시기 바랍니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[이용 관련] 조식, 인원추가비용, 부대시설 비용 지불은 어떻게 하나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    상품 예약 시 진행하시고, 추가적인 서비스(조식, 추가 인원, 침구, 엑스트라 베드 등) 요금은 현장결제로 진행됩니다.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 쿠폰/포인트 -->
                    <div class="tab-contents">
                        <div class="faq_page faq__wrap  accordion__wrap">
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[쿠폰] 쿠폰은 어떻게 사용이 가능한가요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    쿠폰은 예약 시 쿠폰 선택을 통해 사용하실 수 있습니다. 쿠폰은 할인권과 이용권 쿠폰 두 가지 형태가 있습니다.<br><br>
                                    할인권 쿠폰은 예약 진행 중 결제 화면에서 선택하여 적용이 가능하며, 이용권 쿠폰은 쿠폰 종류에 따라 현장에서 직원에게 제시하여 사용할 수 있는 경우도 있으니 참고 부탁드립니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[쿠폰] 예약을 취소하면 사용한 쿠폰을 돌려받을 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    [반환 가능한 쿠폰]<br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">
                                            예약 취소 시점에 유효기간이 남아있는 경우
                                        </li>
                                    </ul>
                                    [반환 불가능한 쿠폰]<br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">
                                            취소 시점 기준 유효기간이 만료된 경우
                                        </li>
                                    </ul>
                                    <br>
                                    단, 실제 결제 금액과 포인트 합계가 취소 수수료 보다 부족할 경우 일부 금액이 쿠폰으로 대체 처리되며, 해당 쿠폰은 사용된 것으로 간주되어 반환된지 않습니다.<br>
                                    ※ 선착순 쿠폰은 반환 대상에서 제외됩니다.
                                    ※ 반환된 쿠폰의 유효기간은 원래 상태와 동일하게 유지됩니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[쿠폰] 사용하지 않은 쿠폰은 소멸되나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    미사용 쿠폰은 유효기간이 만료되면 쿠폰함에서 자동으로 삭제됩니다.<br>
                                    앱 하단 [마이페이지] > [쿠폰함]에서 유효기간을 사전에 확인하시길 바랍니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[포인트] 예약 취소하면 사용한 포인트도 반환이 되나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">취소 수수료가 없을 경우, 사용된 포인트는 전액 반환됩니다.</li>
                                        <li style="list-style-type:disc">취소 수수료는 현금 결제 금액(PG사 결제) → 포인트 사용 금액 → 쿠폰 또는 제휴사 포인트 순서로 차감됩니다.</li>
                                    </ul>
                                    <br>
                                    ※ 단, 취소 수수료가 PG 결제 금액을 초과하는 경우, 초과된 금액은 반환되지 않으며 잔여 포인트만 반환됩니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[쿠폰] 소멸 예정 포인트를 확인할 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    네, 소멸 예정 포인트는 [마이페이지] > [포인트]에서 확인 가능합니다.<br>
                                    만약 30일이내 소멸 예정 포인트가 없다면 별도의 안내는 제공되지 않습니다.<br>
                                    또한, 상세 내역을 통해 소멸 예정 포인트의 금액, 건수, 사용 기한 등을 자세히 확인할 수 있습니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">[쿠폰] 사용하지 않은 포인트는 자동소멸되나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    네, 포인트는 사용 기한 내에 사용하지 않으면 자동으로 소멸됩니다..<br>
                                    적립일에 따라 사용 기한이 다를 수 있으니 사전에 확인해 주세요.<br>
                                    30일 이내 소멸 예정 포인트는 [마이페이지] > [포인트]에서 확인 가능합니다.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 결제/영수증 -->
                    <div class="tab-contents">
                        <div class="faq_page faq__wrap  accordion__wrap">
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">취소가 완료되었는데 환불은 언제되나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    환불 처리는 예약 취소 후 결제 대행사(PG사)의 승인 절차에 따라 진행되며, 주말과 공휴일을 제외한 영업일 기준으로 최대 3~5일이 소요될 수 있습니다. 양해 부탁드립니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">카드영수증을 발급해줄 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    카드영수증은 예약 진행시 기재해주신 예약자님 이메일을 통해 뭉클 결제대행사 (PG사)에서 자동으로 영수증을 발송해드립니다. 이메일의 결제 내역 내용 중 [매출전표] 버튼을 클릭하시면 카드사 영수증을 조회 및 출력이 가능하십니다. 만약 이메일을 수신받지 못하셨다면 카드사 홈페이지 또는 카드사 앱에서 카드전표 조회 및 출력이 가능합니다. 
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">현금영수증 발행이 가능한가요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">
                                            네이버페이<br>
                                            결제 단계에서 신청 시 자동 발행<br>
                                            ▶ 네이버페이 > 결제내역 > 주문건 > 주문상세정보 > 영수증 발급내역 확인
                                        </li>
                                        <li style="list-style-type:disc">
                                            간편계좌이체 / TOSS / PAYCO<br>
                                            결제 단계에서 신청 시 자동 발행<br>
                                            ▶ 신청 누락 시 뭉클 고객센터를 통해 요청 가능
                                        </li>
                                        <li style="list-style-type:disc">
                                            카카오페이<br>
                                            카카오페이머니 결제 시 자동 발행<br>
                                            ▶ 카카오톡 > Pay > 나의 카카오페이 > 이용내역 > 결제 상세내역 확인
                                        </li>
                                    </ul>
                                    <br>
                                    ※ 결제 이후 현금영수증 발행을 누락한 경우, 국세청 홈택스에서 자진발급을 신청하실 수 있습니다.<br>
                                    ※ 추가 문의는 고객센터로 연락 부탁드립니다.
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">카카오톡 상담: <a href="https://pf.kakao.com/_dEwbG">https://pf.kakao.com/_dEwbG</a></li>
                                        <li style="list-style-type:disc">뭉클트립 고객센터 이메일: <a href="mailto:moongcletrip@honolulu.co.kr">moongcletrip@honolulu.co.kr</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">계좌이체방식 결제 후 은행에서 문자가 발송되었어요.</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    간편계좌이체나 TOSS 계좌 등록 시, 은행에서 자동이체 서비스 등록 알림 문자가 전송될 수 있습니다.<br>
                                    이는 매월 정기적으로 금액이 출금되는 것이 아니라, 결제 시 비밀번호 인증을 통해 이체가 이루어지는 방식이니 안심하고 사용하시길 바랍니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">법인카드로 숙소 예약을 할 수 있나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    법인카드를 사용하여 숙소 예약이 가능하며, 법인카드와 일반카드의 결제 방식은 동일합니다. 다만, 카드사 정책에 따라 일정 금액 이상 결제 시 추가 인증 절차가 요구될 수 있습니다.<br>
                                    인증 기준과 방법은 각 카드사 및 법인카드 종류에 따라 달라질 수 있습니다.<br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">
                                            법인 공용 카드: 회사 인증서 및 카드사 인증 필요
                                        </li>
                                        <li style="list-style-type:disc">
                                            법인 개인 카드: 개인 인증서 및 카드사 인증 필요 (개인 공인/공동인증서를 카드사에 사전 등록)
                                        </li>
                                    </ul>
                                    <br>
                                    ※ 경우에 따라 법인카드와 카드사 간의 정책으로 결제가 제한될 수 있으니, 자세한 사항은 해당 카드사 고객센터로 문의해 주시기 바랍니다.
                                </div>
                            </div>
                            <div class="accordion__list">
                                <div class="accordion__tit">
                                    <p class="ft-default">카드 청구서에는 회사명이 뜨나요, 업체명이 뜨나요?</p>
                                    <a class="btn-arrow"><i class="ico ico-arrow__down"></i></a>
                                </div>
                                <div class="accordion__con">
                                    카드 매출전표와 청구서에는 서비스명인 '뭉클트립', 회사명인 '(주)호놀룰루컴퍼니' 또는 '주식회사 호놀룰루컴퍼니'로 표시됩니다.<br>
                                    다만, 일부 PG사(결제 대행사) 또는 간편결제사를 이용한 경우, '결제 PG사명'으로 표기될 수 있습니다.<br>
                                    예시)<br>
                                    <ul style="padding-left: 2rem;">
                                        <li style="list-style-type:disc">
                                            삼성카드 매출전표: 'PG사명'
                                        </li>
                                        <li style="list-style-type:disc">
                                            하나카드 매출전표: '호놀룰루_'PG사명'
                                        </li>
                                    </ul>
                                    <br>
                                    예약 후 PG사를 통해 수신되는 결제내역 이메일과 카드전표에는 구매상품명이 표시되거나 생략될 수 있습니다. 구매한 상품명을 확인하려면 [마이페이지] > [예약 내역 확인]에서 이용한 상품명, 이용 날짜, 예약 번호 등을 확인해 주세요.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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