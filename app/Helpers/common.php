<?php

function isBot() {
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

    $isBot = preg_match('/bot|crawl|slurp|spider|mediapartners/i', $userAgent);
    
    return $isBot;
}

function getDeviceType()
{
    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        return 'invalid';
    }

    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if (!empty($_COOKIE['deviceValidate'])) {
        return 'app';
    }

    if (preg_match('/MoongcleTripApp/i', $userAgent)) {
        return 'app';
    }

    if (preg_match('/mobile|android|phone|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
        return 'mobile';
    } elseif (preg_match('/ipad|tablet|kindle/i', $userAgent)) {
        return 'tablet';
    } else {
        return 'pc';
    }
}

function isMacOS()
{
    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }

    return strpos($_SERVER['HTTP_USER_AGENT'], 'Macintosh') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') === false;
}

function isIOS()
{
    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }

    return preg_match('/iPhone|iPad|iPod/', $_SERVER['HTTP_USER_AGENT']);
}

function getPlatformType()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    if (strpos($userAgent, 'iPhone') !== false) {
        return 'ios';
    } elseif (strpos($userAgent, 'Android') !== false) {
        return 'android';
    } else {
        return 'other';
    }
}

function textToTag($text)
{
    $lines = explode("\n", $text);
    $htmlOutput = "";
    $ulOpened = false;

    foreach ($lines as $line) {
        if (trim($line) == "") {
            // 줄바꿈만 있는 경우 <br> 태그 삽입
            if ($ulOpened) {
                $htmlOutput .= "</ul>";
                $ulOpened = false;
            }
            $htmlOutput .= "<br>";
        } elseif (strpos($line, '[') === 0) {
            // 대괄호로 시작하는 줄 처리
            if ($ulOpened) {
                $htmlOutput .= "</ul>";
                $ulOpened = false;
            }
            $htmlOutput .= "<div class=\"fs-6 text-bold mb-2\">" . htmlspecialchars($line) . "</div>";
        } elseif (strpos($line, '※') === 0) {
            // 대괄호로 시작하는 줄 처리
            if ($ulOpened) {
                $htmlOutput .= "</ul>";
                $ulOpened = false;
            }
            $htmlOutput .= "<div class=\"fs-6 text-bold mb-2\">" . htmlspecialchars($line) . "</div>";
        } else {
            // 리스트 항목 처리
            if (!$ulOpened) {
                $htmlOutput .= "<ul class=\"circle-list\">";
                $ulOpened = true;
            }
            $lineWithoutHyphen = ltrim($line, '- ');
            $htmlOutput .= "<li>" . htmlspecialchars($lineWithoutHyphen) . "</li>";
        }
    }

    if ($ulOpened) {
        $htmlOutput .= "</ul>";
    }

    return $htmlOutput;
}

function textToTagUser($text)
{
    $lines = explode("\n", $text);
    $html = "";
    $isNewSection = true; // 섹션의 열림 여부를 추적
    $isUlOpen = false; // <ul> 태그가 열려있는지 여부를 추적

    foreach ($lines as $line) {
        $line = str_replace(['업체소개', '업체 소개'], '숙소소개', $line);

        $trimmedLine = trim($line);

        if (strpos($trimmedLine, 'http') !== false) {
            $trimmedLine = '';
        }

        // 빈 줄 무시
        if (empty($trimmedLine)) {
            $html .= "<br>"; // 빈 줄일 경우 줄바꿈 추가
            continue;
        }

        // 새로운 섹션 시작 조건: [ ]로 시작하는 경우
        if (preg_match('/^\[(.*?)\]$/', $trimmedLine, $matches)) {
            if (!$isNewSection && $isUlOpen) {
                $html .= "</ul>"; // 이전 섹션 닫기
                $isUlOpen = false;
            }
            $html .= "<ul><li style=\"font-weight: 600; margin-bottom: 1rem;\">{$trimmedLine}</li>"; // 꺽쇠 괄호 안의 내용을 BOLD 처리
            $isNewSection = false;
            $isUlOpen = true;
        }
        // ▶로 시작하는 경우 BOLD 처리
        elseif (strpos($trimmedLine, '▶') === 0) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"font-weight: 600; margin-bottom: 1rem;\">{$trimmedLine}</li>";
        }
        // ■로 시작하는 경우 BOLD 처리
        elseif (strpos($trimmedLine, '■') === 0) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"font-weight: 600; margin-bottom: 1rem;\">{$trimmedLine}</li>";
        }
        // 숫자와 .로 시작하는 경우 BOLD 처리
        elseif (preg_match('/^\d+\.\s*(.*)/', $trimmedLine, $matches)) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"font-weight: 600; margin-bottom: 1rem;\">{$trimmedLine}</li>";
        }
        // 숫자와 )로 시작하는 경우 BOLD 처리
        elseif (preg_match('/^\d+\)\s*(.*)/', $trimmedLine, $matches)) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"font-weight: 600; margin-bottom: 1rem;\">{$trimmedLine}</li>";
        }
        // ㅁ로 시작하는 경우 불렛포인트
        elseif (preg_match('/^ㅁ\s*(.*)/u', $trimmedLine, $matches)) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"list-style: disc; margin-left: 2rem;\">{$matches[1]}</li>";
        }
        // □로 시작하는 경우 불렛포인트
        elseif (preg_match('/^□\s*(.*)/u', $trimmedLine, $matches)) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"list-style: disc; margin-left: 2rem;\">{$matches[1]}</li>";
        }
        // -로 시작하는 경우 불렛포인트
        elseif (preg_match('/^-+\s*(.*)/', $trimmedLine, $matches)) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"list-style: disc; margin-left: 2rem;\">{$matches[1]}</li>";
        }
        // ·로 시작하는 경우 불렛포인트
        elseif (preg_match('/^·+\s*(.*)/', $trimmedLine, $matches)) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"list-style: disc; margin-left: 2rem;\">{$matches[1]}</li>";
        }
        // *로 시작하는 경우 들여쓰기 불렛포인트
        elseif (preg_match('/^\*\s*(.*)/', $trimmedLine, $matches)) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"list-style: none; font-weight: 500;\">{$trimmedLine}</li>";
        }
        // ※로 시작하는 경우 들여쓰기 불렛포인트
        elseif (preg_match('/^\※\s*(.*)/', $trimmedLine, $matches)) {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"list-style: none; font-weight: 500;\">{$trimmedLine}</li>";
        }
        // 기본적인 줄의 경우 불렛포인트 처리
        else {
            if (!$isUlOpen) {
                $html .= "<ul>"; // <ul> 태그 열기
                $isUlOpen = true;
            }
            $html .= "<li style=\"list-style: disc; margin-left: 2rem;\">{$trimmedLine}</li>";
        }
    }

    // 마지막 섹션 닫기
    if ($isUlOpen) {
        $html .= "</ul>";
    }

    return $html;
}

function generateRandomCode($length = 80)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomKey = '';

    for ($i = 0; $i < $length; $i++) {
        $randomKey .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomKey;
}

function generateRandomKey($length = 100)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-=@.';
    $charactersLength = strlen($characters);
    $randomKey = '';

    for ($i = 0; $i < $length; $i++) {
        $randomKey .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomKey;
}

function generateReservationCode($length = 34)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomKey = '';

    for ($i = 0; $i < $length; $i++) {
        $randomKey .= $characters[random_int(0, $charactersLength - 1)];
    }

    return date('YmdHis') . '_' . $randomKey;
}

function generateRandomOrderId($length = 50)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_=';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function generateRandomReservationNumber($length = 5)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return date('Ymd') . $randomString;
}

function parentGotoNewUrl($url)
{
    echo '<script type="text/javascript">parent.document.location.href = "' . $url . '"</script>';
    exit;
}

function gotoNewUrl($url)
{
    echo '<script type="text/javascript">location.href = "' . $url . '"</script>';
    exit;
}

function getRandomTags(array $allTags, int $count): array
{
    shuffle($allTags);
    return array_slice($allTags, 0, $count);
}

function formatKoreanDate($dateString)
{
    // DateTime 객체 생성
    $date = new DateTime($dateString);

    // 요일 배열
    $daysOfWeek = ['일', '월', '화', '수', '목', '금', '토'];

    // 날짜 포맷 변환
    $formattedDate = $date->format('Y년 m월 d일');
    $dayOfWeek = $daysOfWeek[$date->format('w')]; // 0(일요일)부터 6(토요일)까지 요일 반환

    // 최종 출력
    return "{$formattedDate} ({$dayOfWeek})";
}

function textToTagBeauty($text)
{
    $lines = explode("\n", $text);
    $htmlOutput = "";
    $ulOpened = false;

    foreach ($lines as $line) {
        if (trim($line) == "") {
            // 줄바꿈만 있는 경우 <br> 태그 삽입
            if ($ulOpened) {
                $htmlOutput .= "</ul>";
                $ulOpened = false;
            }
            $htmlOutput .= "<br>";
        } elseif (strpos($line, '[') === 0) {
            // 대괄호로 시작하는 줄 처리
            if ($ulOpened) {
                $htmlOutput .= "</ul>";
                $ulOpened = false;
            }
            $htmlOutput .= "<div style=\"font-size: 16px; font-weight: 600; margin-bottom: 1rem;\">" . htmlspecialchars($line) . "</div>";
        } elseif (strpos($line, '※') === 0) {
            // 대괄호로 시작하는 줄 처리
            if ($ulOpened) {
                $htmlOutput .= "</ul>";
                $ulOpened = false;
            }
            $htmlOutput .= "<div style=\"font-size: 16px; font-weight: 600; margin-bottom: 1rem;\">" . htmlspecialchars($line) . "</div>";
        } else {
            // 리스트 항목 처리
            if (!$ulOpened) {
                $htmlOutput .= "<ul style=\"list-style: disc; margin-left: 2rem; font-size: 16px;\">";
                $ulOpened = true;
            }
            $lineWithoutHyphen = ltrim($line, '- ');
            $htmlOutput .= "<li>" . htmlspecialchars($lineWithoutHyphen) . "</li>";
        }
    }

    if ($ulOpened) {
        $htmlOutput .= "</ul>";
    }

    return $htmlOutput;
}

function xmlToArray($xml)
{
    $array = [];

    foreach ($xml->attributes() as $attributeName => $attributeValue) {
        $array['@attributes'][$attributeName] = (string) $attributeValue;
    }

    foreach ($xml->children() as $childName => $childXml) {
        $childArray = xmlToArray($childXml);
        if (isset($array[$childName])) {
            if (is_array($array[$childName]) && isset($array[$childName][0])) {
                $array[$childName][] = $childArray;
            } else {
                $array[$childName] = [$array[$childName], $childArray];
            }
        } else {
            $array[$childName] = $childArray;
        }
    }

    if (empty($array)) {
        $array = (string) $xml;
    }

    return $array;
}
