<head>
	<?php if ($_ENV['ANALYTICS_ENV'] == 'production' || $_ENV['ANALYTICS_ENV'] == 'staging') : ?>
		<!-- Google Tag Manager -->
		<script>
			(function(w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({
					'gtm.start': new Date().getTime(),
					event: 'gtm.js'
				});
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', 'GTM-W6RD353D');
		</script>
		<!-- End Google Tag Manager -->

		<meta name="google-site-verification" content="_n9I75ojN1eEyVQFfgC__K1pclGDpz4RHUrdRkbNvo8" />
        <meta name="naver-site-verification" content="a72faa16a410864b6faaf6a22d50de0e8c45ba90" />
	<?php endif; ?>

    <script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script> 
    <script type="text/javascript"> 
        if (!wcs_add) var wcs_add={};
        wcs_add["wa"] = "s_2744685fd307";
        if(window.wcs){
            wcs.inflow('moongcletrip.com');
        }
        wcs_do();
    </script>

    <script defer>
        window.addEventListener('load', function() {
            var images = document.getElementsByTagName('img');
            for (var i = 0; i < images.length; i++) {
                var img = images[i];
                if (!img.hasAttribute('width')) {
                    var width = img.offsetWidth;
                    img.setAttribute('width', width);
                }
                if (!img.hasAttribute('height')) {
                    var height = img.offsetHeight;
                    img.setAttribute('height', height);
                }
            }
        });
    </script>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/manage/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="/assets/manage/images/favicon.png">

	<link rel="stylesheet" href="/assets/app/css/common.css?v=<?= $_ENV['VERSION']; ?>">
	<link rel="stylesheet" href="/assets/app/css/splide.min.css?v=<?= $_ENV['VERSION']; ?>">
	<link rel="stylesheet" href="/assets/app/css/swiper.css?v=<?= $_ENV['VERSION']; ?>">
	<link rel="stylesheet" href="/assets/app/css/flatpickr.min.css?v=<?= $_ENV['VERSION']; ?>">
	<link rel="stylesheet" href="/assets/app/css/jquery.rateyo.min.css?v=<?= $_ENV['VERSION']; ?>">
	<link rel="stylesheet" href="/assets/app/css/ui.css?v=<?= $_ENV['VERSION']; ?>">
    <link rel="stylesheet" href="/assets/app/css/nouislider.min.css?v=<?= $_ENV['VERSION']; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer">

	<script src="/assets/app/js/jquery-3.6.1.min.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/splide.min.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/swiper.min.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/hammer.min.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/flatpickr.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/flatpickr.ko.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/jquery.rateyo.min.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/type-hangul.bundle.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/lottie.min.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/common.js?v=<?= $_ENV['VERSION']; ?>"></script>
	<script src="/assets/app/js/custom.js?v=<?= $_ENV['VERSION']; ?>"></script>
    <script src="/assets/app/js/nouislider.min.js?v=<?= $_ENV['VERSION']; ?>"></script>

    <script src="https://unpkg.com/@panzoom/panzoom@4.6.0/dist/panzoom.min.js"></script>

    <!-- title -->
    <?php
        if (isset($page_title_01) && isset($page_title_02)) {
            $final_title = "$page_title_01 | $page_title_02 | 뭉클트립";
        } elseif (isset($page_title_01)) {
            $final_title = "$page_title_01 | 맘 편한 여행 숙소 추천앱 | 뭉클트립";
        } else {
            $final_title = "뭉클트립 | 맘 편한 여행 숙소 추천앱";
        }
    ?>

    <meta name="title" content="<?= $final_title; ?>">
    <meta name="description" content="<?= isset($page_description) ? $page_description : '우리 아이와 함께 갈만한 좋은 숙소 대신 찾아드릴게요! 조식 제공, 수영장, 키즈존 등 내가 원하는 조건에 맞는 국내여행 숙소를 찾아드려요.' ?>">

    <title><?= $final_title ?></title>

    <!-- open graph -->
    <meta property="og:title" content="<?= $final_title; ?>">
    <meta property="og:description" content="<?= isset($page_description) ? $page_description : '우리 아이와 함께 갈만한 좋은 숙소 대신 찾아드릴게요! 조식 제공, 수영장, 키즈존 등 내가 원하는 조건에 맞는 국내여행 숙소를 찾아드려요.' ?>">
    <meta property="og:image" content="<?= isset($page_image) ? $page_image : '/assets/app/images/common/moongcle_color_807_257.png' ?>">
    <meta property="og:url" content="<?= isset($page_url) ? $page_url : '' ?>" id="meta-og-url">
    <meta property="og:type" content="website" />

    <script>
        const ogMeta = document.getElementById('meta-og-url');
        if (!ogMeta.getAttribute('content')) {
            ogMeta.setAttribute('content', window.location.origin + window.location.pathname + window.location.search);
        }
    </script>

    <!-- twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= $final_title; ?>" />
    <meta name="twitter:description" content="<?= isset($page_description) ? $page_description : '우리 아이와 함께 갈만한 좋은 숙소 대신 찾아드릴게요! 조식 제공, 수영장, 키즈존 등 내가 원하는 조건에 맞는 국내여행 숙소를 찾아드려요.' ?>" />
    <meta name="twitter:image" content="<?= isset($page_image) ? $page_image : '/assets/app/images/common/moongcle_color_807_257.png' ?>" />

	<?php if (strpos($_SERVER['REQUEST_URI'], 'payment') !== false) : ?>
		<script src="https://js.tosspayments.com/v2/standard"></script>
	<?php endif; ?>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/../app/Helpers/DataHelper.php'; ?>

    <script>
        (function() {
            // 현재 URL에서 쿼리스트링 제거
            var canonicalUrl = window.location.origin + window.location.pathname;

            var link = document.createElement('link');
            link.rel = 'canonical';
            link.href = canonicalUrl;

            document.getElementsByTagName('head')[0].appendChild(link);
        })();
    </script>
</head>