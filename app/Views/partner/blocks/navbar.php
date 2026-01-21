<div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar" style="justify-content: flex-end;">
	
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
                window.location.href = '/partner/login';
            }, 200); 
        }
    });
</script>