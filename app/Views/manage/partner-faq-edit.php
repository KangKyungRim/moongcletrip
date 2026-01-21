<!DOCTYPE html>
<html lang="ko">

<?php

$selectedPartnerIdx = $data['selectedPartnerIdx'];
$selectedPartner = $data['selectedPartner'];

?>

<!-- Head -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/head.php"; ?>
<!-- Head -->

<body class="g-sidenav-show  bg-gray-100">

    <!-- Side Menu -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/sidemenu.php"; ?>
    <!-- Side Menu -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-14 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">숙소 관리</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">자주 묻는 질문</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">자주 묻는 질문</h6>
                </nav>

                <!-- Navigation Bar -->
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/navbar.php"; ?>
                <!-- Navigation Bar -->

            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12 mx-auto">
                    <di class="card card-body p-5">

                        <!-- Card header -->
                        <div class="pb-0">
                            <div>
                                <h6 class="mb-0">자주 묻는 질문 생성 및 수정</h6>
                                <p class="text-sm mb-0">우리 숙소만의 자주 묻는 질문을 입력해 보세요.</p>
                            </div>
                        </div>
                        
                        <div class="addFaqWrap">
                            <?php if (count($data['partnerFaq']) === 0) : ?>
                                <div class="addFaq" data-index="0">
                                    <hr class="horizontal gray-light my-3 mb-4">
                                    <div class="d-flex align-items-center gap-6">
                                        <div class="form-group w-80">
                                            <div class="form-group d-flex align-items-center gap-3">
                                                <label for="question-0">Q</label>
                                                <input type="text" class="form-control" id="question-0" name="question[]" placeholder="질문을 입력해 주세요">
                                            </div>
                                            <div class="form-group d-flex align-items-center gap-3 mb-0">
                                                <label for="answer-0">A</label>
                                                <textarea class="form-control" rows="7" id="answer-0" name="answer[]" placeholder="질문에 맞는 답변을 입력해 주세요"></textarea>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-danger btn-sm removeFaqBtn">삭제</button>
                                    </div>
                                </div>
                            <?php else : ?>
                                <?php foreach ($data['partnerFaq'] as $indx => $partnerFaq) : ?>
                                    <div class="addFaq" data-index="<?= $index ?>">
                                        <hr class="horizontal gray-light my-3 mb-4">

                                        <div class="d-flex align-items-center gap-6">
                                            <div class="form-group w-80">
                                                <div class="form-group d-flex align-items-center gap-3">
                                                    <label for="question-<?= $index ?>">Q</label>
                                                    <input type="text" class="form-control" id="question-<?= $index ?>" name="question[]" placeholder="질문을 입력해 주세요" value="<?= $partnerFaq->question; ?>">
                                                </div>
                                                <div class="form-group d-flex align-items-center gap-3 mb-0">
                                                    <label for="answer-<?= $index ?>">A</label>
                                                    <textarea class="form-control" rows="7" id="answer-<?= $index ?>" name="answer[]" placeholder="질문에 맞는 답변을 입력해 주세요"><?= $partnerFaq->answer; ?></textarea>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-danger btn-sm removeFaqBtn">삭제</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn bg-gradient-info mt-3" id="addFaqBtn">+ FAQ 추가</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="button" id="cancelForm" name="cancelForm" class="btn btn-light m-0" onclick="location.href='/manage/partner-faq'">취소</button>
                        <button type="button" id="submitForm" name="submitForm" class="btn bg-gradient-primary m-0 ms-2">저장하기</button>
                    </div>
                </div>
            </div>

            <footer class="footer py-5">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © 2025,
                                <a href="https://www.moongcletrip.com" class="font-weight-bold" target="_blank">Honolulu Company</a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://www.moongcletrip.com" class="nav-link text-muted" target="_blank">뭉클트립</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.honolulu.co.kr/channels/L2NoYW5uZWxzLzE5NQ/pages/home" class="nav-link text-muted" target="_blank">호놀룰루컴퍼니</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.instagram.com/moongcletrip/" class="nav-link text-muted" target="_blank">instagram</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.moongcletrip.com" class="nav-link pe-0 text-muted" target="_blank">License</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/../app/Views/manage/blocks/loading.php"; ?>

    <!--   Core JS Files   -->
    <script src="/assets/manage/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/manage/js/core/popper.min.js"></script>
    <script src="/assets/manage/js/core/bootstrap.min.js"></script>
    <script src="/assets/manage/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/manage/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/assets/manage/js/plugins/choices.min.js"></script>
    <script src="/assets/manage/js/plugins/quill.min.js"></script>
    <script src="/assets/manage/js/plugins/flatpickr.min.js"></script>
    <script src="/assets/manage/js/plugins/dropzone.min.js"></script>

    <!-- Kanban scripts -->
    <script src="/assets/manage/js/plugins/dragula/dragula.min.js"></script>
    <script src="/assets/manage/js/plugins/jkanban/jkanban.js"></script>
    <script src="/assets/manage/js/plugins/chartjs.min.js"></script>
    <script src="/assets/manage/js/plugins/threejs.js"></script>
    <script src="/assets/manage/js/plugins/orbit-controls.js"></script>

    <script src="/assets/manage/js/soft-ui-dashboard.js?v=1.0.0"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // faq 추가 & 삭제
            const addFaqBtn = document.getElementById('addFaqBtn');
            const addFaqWrap = document.querySelector('.addFaqWrap');

            // 초기 데이터 개수
            let faqIndex = document.querySelectorAll('.addFaq').length;

            let existingFaqs = <?php echo json_encode($data['partnerFaq'] ?? []); ?>;

            // 새 항목 추가
            addFaqBtn.addEventListener('click', function() {
                const newFaq = document.createElement('div');
                newFaq.classList.add('addFaq');
                newFaq.setAttribute('data-index', faqIndex);

               const faqContent = `
                    <hr class="horizontal gray-light my-3 mb-4">

                    <div class="d-flex align-items-center gap-6">
                        <div class="form-group w-80">
                            <div class="form-group d-flex align-items-center gap-3">
                                <label for="question-${faqIndex}">Q</label>
                                <input type="text" class="form-control" id="question-${faqIndex}" name="question[]" placeholder="질문을 입력해 주세요">
                            </div>
                            <div class="form-group d-flex align-items-center gap-3 mb-0">
                                <label for="answer-${faqIndex}">A</label>
                                <textarea class="form-control" rows="7" id="answer-${faqIndex}" name="answer[]" placeholder="질문에 맞는 답변을 입력해 주세요"></textarea>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm removeFaqBtn">삭제</button>
                    </div>
                `;

                newFaq.innerHTML = faqContent;

                addFaqWrap.appendChild(newFaq);
                faqIndex++;
            });

            // FAQ 삭제 
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('removeFaqBtn')) {
                    const faqElement = event.target.closest('.addFaq');
                    const index = Number(faqElement.getAttribute('data-index'));

                    // FAQ 항목이 하나 남은 경우
                    if (document.querySelectorAll('.addFaq').length === 1) {
                        alert('최소 1개 이상의 질문이 등록되어 있어야 합니다.');
                        return;
                    }

                    const questionInput = faqElement.querySelector('input[type="text"]');
                    const answerTextarea = faqElement.querySelector('textarea');

                    // 삭제 확인
                    if (questionInput.value.trim() !== "" || answerTextarea.value.trim() !== "") {
                        const userConfirmed = confirm('입력한 질문이 사라집니다. 정말 삭제하시겠습니까?');
                        if (!userConfirmed) return;
                    }

                    if (document.querySelectorAll('.addFaq').length > 1) {
                        if (existingFaqs[index]) {
                            existingFaqs.splice(index, 1); 
                        }

                        // FAQ 항목 삭제
                        faqElement.remove(); 
                    } else {
                        // 마지막 항목 삭제
                        faqElement.remove();
                        existingFaqs.splice(index, 1);
                    }
                }
            });

            // 저장하기
            document.getElementById('submitForm').addEventListener('click', async function() {
                const selectedPartnerIdx = Number(<?php echo json_encode($selectedPartnerIdx); ?>);

                let updatedFaqs = [];

                document.querySelectorAll('.addFaq').forEach((faq, index) => {
                    const questionInput = faq.querySelector(`[id^="question-"]`);
                    const answerInput = faq.querySelector(`[id^="answer-"]`);

                    if (!questionInput || !answerInput) {
                        console.warn(`FAQ ${index + 1}의 입력 요소를 찾을 수 없습니다.`);
                        return;
                    }

                    const question = questionInput.value.trim();
                    const answer = answerInput.value.trim();
                    
                    if (question && answer) {
                        if (existingFaqs[index]) {
                            existingFaqs[index].question = question;
                            existingFaqs[index].answer = answer;
                        } else {
                            existingFaqs.push({ question, answer });
                        }
                    }
                });

                updatedFaqs = existingFaqs.filter(faq => faq.question && faq.answer);

                const formData = {
                    partnerIdx: selectedPartnerIdx, // index
                    faqs: updatedFaqs // faq
                };

                try {
                    const response = await fetch('/api/partner/edit-faqs', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    });

                    // 응답이 JSON인지 확인
                    const contentType = response.headers.get('content-type');
                    let result;

                    if (contentType && contentType.includes('application/json')) {
                        result = await response.json();
                    } else {
                        result = await response.text();
                        console.warn('응답이 JSON 형식이 아닙니다:', result);
                    }

                    if (response.ok) {
                        alert('저장되었습니다.');
                        loading.style.display = 'flex'; 
                        window.location.href = '/manage/partner-faq';
                    } else {
                        alert(result?.error || '저장 중 문제가 발생했습니다.');
                    }
                } catch (error) {
                    console.error('저장 중 오류 발생:', error);
                    alert('저장 중 오류가 발생했습니다.');
                }
            });
        });
    </script>

</body>
</html>