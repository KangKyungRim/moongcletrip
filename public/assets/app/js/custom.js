function previousPage() {
    const previousPage = sessionStorage.getItem("previousPage");
    if (previousPage) {
        window.location.href = previousPage;
    } else {
        window.location.href = "https://www.moongcletrip.com";
    }
}

function previousBlankPage() {
    const referrer = document.referrer;
    const mainPageURL = window.location.origin + "/";

    if (referrer === mainPageURL || referrer === "" || referrer === "/") {
        window.location.href = mainPageURL;
    } else {
        window.history.back();
    }
}

function goBack() {
    history.back();
}

function gotoMain() {
    let url = "/";

    window.location.href = url;
}

function gotoMoongcledeals() {
    let url = "/moongcledeals";

    window.location.href = url;
}

function gotoMoongcledealDetail(moongcledealIdx) {
    let url = "/moongcledeals?moongcledealIdx=" + moongcledealIdx;

    window.location.href = url;
}

function gotoReservationList() {
    let url = "/my/reservations";

    window.location.href = url;
}

function gotoNotification() {
    let url = "/notification";

    window.location.href = url;
}

function gotoMypage() {
    let url = "/mypage";

    window.location.href = url;
}

function gotoSearch() {
    let url = "/search-start";

    window.location.href = url;
}

function gotoTravelCart() {
    let url = "/travel-cart";

    window.location.href = url;
}

function gotoMypage() {
    let url = "/mypage";

    window.location.href = url;
}

function gotoLoginEmail() {
    let url = "/users/login-email";

    window.location.href = url;
}

function gotoSignupAgree() {
    let url = "/users/signup-agree";

    window.location.href = url;
}

function gotoSignupEmail() {
    let url = "/users/signup-email";

    window.location.href = url;
}

function gotoCancelDetail(idx) {
    let url = "";

    if (idx == 0) {
        url = "/my/reservations";
    } else {
        url = "/my/canceled-reservation/" + idx;
    }

    window.location.href = url;
}

function gotoReturnPage(destination) {
    const queryString = window.location.search;

    const params = new URLSearchParams(queryString);

    if (params.has("return")) {
        goBack();
    } else {
        if (destination == "mypage") {
            gotoMypage();
        }
    }
}

function goBackWithSavedMoongcledeal() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const moongcledealIdx = urlParams.get("moongcledeal_idx");

    if (moongcledealIdx) {
        const nextPageUrl = `/moongcledeal/create/02?moongcledeal_idx=${moongcledealIdx}`;
        window.location.href = nextPageUrl;
    } else {
        goBack();
    }
}

function setUserTokenCookie(token, tokenName) {
    const expires = new Date();
    expires.setTime(expires.getTime() + 60 * 60 * 1000 * 24 * 365); // 365일 만료 시간
    document.cookie = `${tokenName}=${token}; expires=${expires.toUTCString()}; path=/; secure; SameSite=Lax`;
}

function deleteCookie(cookieName) {
    document.cookie = `${cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; secure; SameSite=Lax`;
}

function logout() {
    history.replaceState(null, "", "/");
    window.location.href = "/";
}

function gotoSearchWithFilter() {
    // 현재 페이지의 URL과 쿼리스트링을 가져옴
    const queryString = window.location.search;

    // 이전 페이지 URL을 설정
    const targetUrl = `/search${queryString}`;

    // 쿼리스트링을 포함한 URL로 이동
    location.href = targetUrl;
}

/**
 *	// 사용 예시
 *	sendShareLink(
 *	'Explore Seoul!',
 *	'https://www.moongcletrip.com/images/seoul-thumbnail.jpg'
 *	);
 */
let isSharing = false;

function sendShareLink(title, image) {
    // 현재 페이지의 URL 가져오기
    const currentUrl = window.location.href;

    // 공유 중인지 확인
    if (isSharing) {
        return; // 중복 호출 방지
    }

    // URL을 파싱하여 path와 params로 분리
    const url = new URL(currentUrl);
    const path = url.pathname; // 현재 경로 (/stay/detail/12345 등)
    const params = Object.fromEntries(url.searchParams.entries()); // 쿼리스트링 파라미터

    isSharing = true;

    const data = {
        id: "share",
        path: path,
        params: params,
        title: title,
        image: image,
    };

    if (window.ReactNativeWebView) {
        window.ReactNativeWebView.postMessage(JSON.stringify(data));
    } else {
        const queryString = new URLSearchParams(params).toString();
        const fullUrl = `${url.origin}${path}?${queryString}`; // 전체 URL 재구성

        if (navigator.share) {
            navigator
                .share({
                    title: title || "Check this out!",
                    url: fullUrl,
                })
                .then(() => {
                    console.log("Shared successfully!");
                })
                .catch((err) => {
                    if (err.name === "AbortError") {
                    } else {
                        console.error("Error sharing:", err);
                    }
                })
                .finally(() => {
                    isSharing = false;
                });
        } else {
            // Web Share API 미지원 시, 클립보드 복사로 대체
            navigator.clipboard
                .writeText(fullUrl)
                .then(() => {
                    console.log("Link copied to clipboard!");
                    alert("주소를 클립보드에 복사했습니다!");
                })
                .catch((err) => {
                    console.error("Error copying to clipboard:", err);
                })
                .finally(() => {
                    isSharing = false;
                });
        }
    }
}

function getCookieHeader(serverCookies) {
    // 쿠키를 "key=value" 형식으로 변환
    const cookieHeader = Object.entries(serverCookies)
        .map(
            ([key, value]) =>
                `${encodeURIComponent(key)}=${encodeURIComponent(value)}`
        )
        .join("; ");

    return cookieHeader;
}

function toggleFavorite(
    userIdx,
    partnerIdx,
    moongcleofferIdx = 0,
    target = null
) {
    if (userIdx < 1) {
        fnOpenLayerPop("loginLikePopup");
        return false;
    } else {
        fnLikeBtn();
    }
    if (!partnerIdx) return false;

    try {
        if (!target) {
            fetch("/api/my/favorite-partner", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    userIdx: userIdx,
                    partnerIdx: partnerIdx,
                }),
            });
        } else {
            if (target == "moongcleoffer") {
                fetch("/api/my/favorite-moongcleoffer", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        userIdx: userIdx,
                        partnerIdx: partnerIdx,
                        moongcleofferIdx: moongcleofferIdx,
                        target: target,
                    }),
                });
            }
        }
    } catch (error) {
        console.error("에러:", error);
    }
}

function outLink(link, id) {
    var dataToSend = {
        id: "outLink",
        url: link,
    };
    // React Native WebView가 있을 때만 실행
    if (window.ReactNativeWebView) {
        window.ReactNativeWebView.postMessage(JSON.stringify(dataToSend));
    } else {
        // React Native WebView가 없는 경우 웹링크로 연결
        window.location.href = link;
    }
}

function preventEvent(event) {
    event.preventDefault();
}

function showLoader() {
    document.getElementById("pageLoader").style.display = "flex";
}

function hideLoader() {
    document.getElementById("pageLoader").style.display = "none";
}

function thirdpartyWebviewZoomFontIgnore() {
    const isThirdpartyWebView = /KAKAOTALK|Instagram/i.test(
        navigator.userAgent
    );
    if (isThirdpartyWebView) {
        const defaultFontSize = "10px";

        const testElement = document.createElement("div");
        testElement.style.fontSize = "1rem";
        testElement.style.visibility = "hidden"; // 화면에 보이지 않도록 설정
        document.body.appendChild(testElement);

        // 계산된 폰트 크기 가져오기
        const computedFontSize = parseFloat(
            window.getComputedStyle(testElement).fontSize
        );

        if (computedFontSize > 10) {
            // HTML과 BODY에 직접 적용
            document.documentElement.style.fontSize = defaultFontSize;
            document.body.style.fontSize = defaultFontSize;

            // !important 적용을 위한 스타일 태그 추가
            const styleSheet = document.createElement("style");
            styleSheet.type = "text/css";
            styleSheet.innerHTML = `
				body * {
					font-size: ${defaultFontSize} !important;
				}
			`;
            document.head.appendChild(styleSheet);
        }
    }
}

function saveToRecentHotels(hotel) {
    const storageKey = "RECENT_VIEWED_HOTELS";
    const maxItems = 10;

    let recent = JSON.parse(localStorage.getItem(storageKey)) || [];

    recent = recent.filter((item) => item.id !== hotel.id);

    recent.unshift(hotel);

    if (recent.length > maxItems) {
        recent = recent.slice(0, maxItems);
    }

    localStorage.setItem(storageKey, JSON.stringify(recent));
}
