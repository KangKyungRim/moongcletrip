    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar" style="justify-content: flex-end;">
    <?php if (!empty($_COOKIE['partner'])) : ?>
        <div class="mx-auto py-2 px-2" style="background: #6C48D510; border-radius: 5px;">
            <div>
                <span class="d-inline-block py-1 px-2 text-xs me-2" style="color: #fff; background: #6C48D5; border-radius: 5px; font-weight: 700;">
                    숙소
                </span>
                <span class="fw-bolder text-sm me-4" style="color: #6C48D5;"><?= $data['selectedPartner']->partner_name; ?></span>
                <button type="button" id="partnerDelete" name="partnerDelete" class="p-0 m-0" style="background:none; border:0px; vertical-align: middle;"><i class="fa-solid fa-xmark" style="color: #6C48D5;"></i></button>
            </div>
        </div>
    <?php endif; ?>
	<ul class="navbar-nav justify-content-end">
		<li class="nav-item px-3 d-flex align-items-center">
			<a href="javascript:;" class="nav-link text-body font-weight-bold px-0 d-flex align-items-center gap-1">
				<i class="fa fa-user me-sm-1"></i>
				<span class="d-sm-inline d-none"><?= $data['user']['partner_user_email']; ?></span>
			</a>
		</li>
        <li class="nav-item ps-3 d-flex align-items-center">
            <div class="dropdown">
                <div id="logoutDropdownMenuButton" style="cursor: pointer;" onclick="Logout();">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                <!-- <div class="" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div> -->
                <!-- <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="left: auto; right: -17px;">
                    <li><a class="dropdown-item" href="javascript:;" style="text-align: center;">로그아웃</a></li>
                </ul> -->
            </div>
		</li>
        
        <!-- 반응형 메뉴바 -->
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
			<a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
				<div class="sidenav-toggler-inner">
					<i class="sidenav-toggler-line"></i>
					<i class="sidenav-toggler-line"></i>
					<i class="sidenav-toggler-line"></i>
				</div>
			</a>
		</li>
	</ul>
</div>

<style>
    @media (min-width: 992px) {
        .dropdown .dropdown-menu:before {
            font-family: "FontAwesome";
            content: "\f0d8";
            position: absolute;
            top: 0;
            right: 28px;
            left: auto;
            font-size: 22px;
            color: #fff;
            transition: top 0.35s ease;
        }
    }
</style>

<script>
    const partnerDelete = document.getElementById("partnerDelete");

    //쿠키 지우기
    function deleteCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    };

    // 숙소 선택 해제 버튼 이벤트
    if (partnerDelete) {
        partnerDelete.addEventListener('click', function () {
            deleteCookie('partner');
            alert("선택된 숙소가 해제되었습니다.");
            window.location.href = '/partner/partner-select';
        });
    }
</script>

<script>
    // 로그아웃
    const LogoutBtn = document.querySelector('#logoutDropdownMenuButton');

    LogoutBtn.addEventListener('click', function() {
        const confirmLogout = confirm('로그아웃 하시겠습니까?');
        
        if (confirmLogout) {
            const cookiesToDelete = ["accessTokenPartner", "partner", "refreshTokenPartner"];

            cookiesToDelete.forEach(cookie => {
                document.cookie = `${cookie}=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;`;
            });

            alert('로그아웃 되었습니다.');

            setTimeout(() => {
                window.location.href = '/manage/login';
            }, 200); 
        }
    });
</script>