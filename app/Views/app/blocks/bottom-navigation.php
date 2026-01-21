<div class="bottom-navi__wrap">
	<ul class="bottom-navi__list">
		<li class="navi navi-curation <?= $_SERVER['REQUEST_URI'] === '/' ? 'active' : ''; ?>">
			<a href="/">
				<i class="ico ico-curation"></i>
				<span>뭉클 추천</span>
			</a>
		</li>
        <li id="moongcledealBubble" class="navi navi-logo <?= strpos($_SERVER['REQUEST_URI'], '/moongcledeals') === 0 ? 'active' : ''; ?>">
			<a href="/moongcledeals">
				<div  class="ico ico-logo-bottom">
                    <?php if (!empty($unreadMoocledealCount)) : ?>
						<p  class="num" 
                            style="
                                right: -1.4rem;
                                top: -0.3rem;
                                width: auto;
                                height: auto;
                                box-sizing: border-box;
                                padding: 0.3rem 0.6rem;
                                border-radius: 2rem;
                            ">
                            NEW
                        </p>
					<?php endif; ?>
				</div>
                <span>찾아줘 숙소</span>
			</a>
		</li>
		<li class="navi navi-mypage <?= $_SERVER['REQUEST_URI'] === '/mypage' ? 'active' : ''; ?>">
			<a href="/mypage">
				<i class="ico ico-mypage"></i>
				<span>마이</span>
			</a>
		</li>
	</ul>
</div>

<style>
    .ico-logo-bottom {
        width: 4.8rem;
        height: 4.8rem;
        background-image: url('/assets/app/images/common/ico_plus_big.svg');
        background-size: 100% auto;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .bottom-navi__list .navi a span {
        padding-top: 0.6rem;
        font-size: 1.2rem;
    }

    #moongcledealBubble {
        position: relative;
        top: -1.2rem;
    }

    .bottom-navi__list .navi-mypage.active .ico {
        background-image: url('assets/app/images/common/ico_mypage_active.svg');
        background-size: 100% auto;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .bottom-navi__list .navi-curation.active .ico {
        background-image: url('assets/app/images/common/ico_curation_active.svg');
        background-size: 100% auto;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .bottom-navi__wrap {
        padding: 0rem 1.6rem calc(0.8rem + env(safe-area-inset-bottom, 0));
        border-radius: 0;
    }

    .bottom-navi__list .navi.active a span {
        color: #714CDC;
    }

    .bottom-navi__list .navi a .num {
        background-color: #FF0000;
        box-shadow: 0px 0.2px 2px 0px #FF0000;
    }
    
</style>