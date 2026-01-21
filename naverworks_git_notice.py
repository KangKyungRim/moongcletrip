import base64
from Crypto.PublicKey import RSA
from Crypto.Util import asn1
import datetime
import jwt
from datetime import datetime, timedelta
from urllib.parse import quote
import requests
import os

# 네이버웍스 API PRIVATE KEY
PRIVATE_KEY = """-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCZf1N+r2MXjX9T
v+CQIw1KK9RL4mBHRAJ0qkbIj5GNV5PkrTNL8BWBy4wI9WQeB+C8uTq7IcTAy/Nz
mxo+Vn0UdoOAja5t4borqIhnFOTeM/6qRg1ZMfK7BEvgHJGCyoHir7eWcXcQ685u
ze0+5QS/cqORtQ2FXOFDS+C2TonlFmDf+grAMZwuX5K3uXZhoJ6JqdKHEdOWZvbN
J7fe7k8MEEnDlUDy6Ey6nQSFQg82eWKdIXlgF7iqVZ/LQvn6e3bOzdIquT5EeeC1
cJawgEi3tbATQ7xB+WEZtsdsFv6DHz+MJe3+FBZ4IbTwcT/sOQvtNeXGIXftLzx/
j8Az1frhAgMBAAECggEAB4f7QUJ6squjHSlTa88EdmirHGQJhEWedznpiiqdIhmz
bGthEm8/puQlRUVA+cY6LRhLfOX9wNXg5oGsKnXU4j2kWxjYf5StLToyIxif2BUm
ABW6zu8H8UwXKvJL42ZrNAOaFtwPUvm24bHh242iC4Ck7N+8v5fch5BAAMHeuyon
98dpANqsTp0xioOYjMvFFXZb/mMnTzdpCZYn/3PzxJaUgqM+WfJFyHjkkwjC+WHa
QVh3lpGSwoQKXXl5yxUfkRFh7qZrpHQf5uyRIK4FW0AnlrOiUUYcLPhQlLLVqpIo
DfbneHxIb9bWbWL1orFPsgn3pDmSUTrgxcEywgZUtwKBgQDHfc1TaCzJn//ow9Wc
L6fzEc83QGcvqyOWZ/xn3SyGo1ey8ul8fX+tKNy5Ky48/GI9R2wcKU2b7hRaiUJr
uxrAnjIr9Y5WDPcnqwUCKIELknKw1Fh2+mNeF5wgui3FtrraPWDt23rGWhwg4MKf
EVcLYCIU4gK2ih3LXZ2w8npX+wKBgQDE+j9xYa2NY2HC5TC3A6ZwDrAYkcVgBK63
X8eg7Rnbyr0KKLRJeqdC9VWv3u1TtU60qileFkER3GUvpC3qHi9rI1szj3ZHU4N9
ms2pNhoQ5e121obgGNutp74YbEuEPZP+wTft1SdZ5m3aGqNcdEOeR3ER4YQFg0vg
8L8GmXG10wKBgBmp3nM/cJuToMliACXyn4mOnfi+qUehnZdo0zjNZEAB3XNg+paQ
Uo+FNTgBDRa9ZSn6+TZ6Pci0jRvEkq3nSR7EezHaijAZxOXug1tuuIMzmNuUxbiw
p33gTa6MJP1Nb6ZJxSF0XcwMpZe5MnBiO69z8456MN0o0YJrDbFI0GJnAoGACkRg
+i/1M+FyPw5jM6blDgI1+5Hdj9uun7BGX4T8TBCNOfJVM19JSJwxEzGzfSF+MLyN
23GdgqjSPWF5YzKflh4Jqu5XY/BecjWXcrJ3OVpCvmcAML4a9TpBFlTOut3a0PSR
TbxGiNER+MRar37/50giBKSUlRT654ak7lshKjECgYEAhSiuK6IxJy+fSspzRQrC
AKms6rQTdebcYNu486nuF5ui8tOgSLrybNOyOMmNRQ/LFCepTwnWVuwOn9hiS2/o
mUlFByfDnxmzLdzrcUpBjv54iwNxf4iDcJ/lfhNfM6yNC6+HYhY0jX36X64tObMi
ffbM0UkN32Wz7rbCpiF2eVg=
-----END PRIVATE KEY-----
"""

# 네이버 웍스 채널 ID
#CHANNEL_ID = "c576adb2-28fc-aa7f-87f8-08a0b1382e55"
CHANNEL_IDS = [
    "c576adb2-28fc-aa7f-87f8-08a0b1382e55", #annie
    "7d474661-032b-2bf8-1ff6-e48b25c03be7"  #luna
]

# 네이버 웍스 봇 ID
BOT_ID = "10648574"

# 네이버 웍스 Client ID
CLIENT_ID = "DUEQkI0nS0ii9EvPXltV"

# 네이버 웍스 Client Secret
CLIENT_SECRET = "6lxJn8kivt"


# 네이버 웍스 API 서버 토큰 발급
def get_server_token():
    headers = {
        "alg": "RS256",
        "typ": "JWT"
    }
    iat = datetime.utcnow()
    exp = iat + timedelta(minutes=10)

    assertion = jwt.encode({
        "iss": "DUEQkI0nS0ii9EvPXltV",
        "sub": "y4toa.serviceaccount@honolulu.co.kr",
        "iat": iat,
        "exp": exp
    }, PRIVATE_KEY, algorithm="RS256", headers=headers)

    return assertion


# 네이버 웍스 API ACCESS TOKEN 발급
def get_access_token():
    access_token_data = {'assertion': get_server_token(),
                         'grant_type': 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                         'client_id': CLIENT_ID,
                         'client_secret': CLIENT_SECRET,
                         'scope': 'bot'}
    req = requests.post('https://auth.worksmobile.com/oauth2/v2.0/token', data=access_token_data)
    return req.json().get('access_token')


# 커밋 메세지 생성
def get_commit_message(arr):
    message = [{
        "type": "text",
        "text": arr[0],
        "weight": "bold",
        "size": "md",
        "align": "start",
        "wrap": True
    }, ]  # 커밋 설명
    if len(arr) > 1:
        for description in arr[2:]:
            message.append({
                "type": "text",
                "text": description,
                "weight": "regular",
                "size": "xs",
                "align": "start",
                "margin": "sm",
                "wrap": True
            })
    return message


# 네이버 웍스 채널 메세지 생성
def get_chat_message(repository, commit_summarize, commit_message, branch_name, pushed_by, commit_sha, commit_link):
    return {
        "content": {
            "type": "flex",
            "altText": f'[{repository}]\n {commit_summarize}',
            "contents": {
                "type": "carousel",
                "contents": [
                    {
                        "type": "bubble",
                        "size": "mega",
                        "header": {
                            "type": "box",
                            "layout": "horizontal",
                            "contents": [
                                {
                                    "type": "text",
                                    "text": repository,
                                    "size": "lg",
                                    "color": "#00c73c",
                                    "weight": "bold"
                                }
                            ],
                            "backgroundColor": "#ffffff"
                        },
                        "body": {
                            "type": "box",
                            "layout": "vertical",
                            "contents": [
                                {
                                    "type": "box",
                                    "layout": "vertical",
                                    "contents": commit_message,
                                    "margin": "xl"
                                },
                                {
                                    "type": "box",
                                    "layout": "vertical",
                                    "contents": [
                                        {
                                            "type": "box",
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "type": "text",
                                                    "text": "Branch",
                                                    "size": "sm",
                                                    "color": "#858f89",
                                                    "flex": 3,
                                                    "margin": "md"
                                                },
                                                {
                                                    "type": "text",
                                                    "text": branch_name,
                                                    "size": "sm",
                                                    "margin": "md",
                                                    "flex": 5
                                                }
                                            ]
                                        },
                                        {
                                            "type": "box",
                                            "layout": "baseline",
                                            "contents": [
                                                {
                                                    "type": "text",
                                                    "text": "Author",
                                                    "size": "sm",
                                                    "color": "#858f89",
                                                    "flex": 3,
                                                    "margin": "md"
                                                },
                                                {
                                                    "type": "text",
                                                    "text": pushed_by,
                                                    "size": "sm",
                                                    "margin": "md",
                                                    "flex": 5
                                                }
                                            ],
                                            "margin": "sm"
                                        },
                                        {
                                            "layout": "baseline",
                                            "type": "box",
                                            "contents": [
                                                {
                                                    "text": "Commit",
                                                    "type": "text",
                                                    "flex": 3,
                                                    "margin": "md",
                                                    "size": "sm",
                                                    "color": "#858f89"
                                                },
                                                {
                                                    "text": commit_sha,
                                                    "action": {
                                                        "type": "uri",
                                                        "label": "",
                                                        "uri": commit_link,
                                                    },
                                                    "type": "text",
                                                    "flex": 5,
                                                    "margin": "md",
                                                    "size": "sm",
                                                    "decoration": "underline",
                                                }
                                            ],
                                            "margin": "sm"
                                        }
                                    ],
                                    "margin": "xxl",
                                    "width": "260px",
                                    "alignItems": "center",
                                    "paddingAll": "6px"
                                }
                            ]
                        }
                    }
                ]
            }
        }
    }


if __name__ == '__main__':
    access_token = get_access_token()  # ACCESS TOKEN

    branch_name = os.environ['BRANCH_NAME']  # 브랜치 이름
    commit_arr = os.environ['COMMIT_MESSAGE'].split('\n')  # 커밋 배열
    commit_message = get_commit_message(commit_arr)  # 커밋 메세지
    pushed_by = os.environ['PUSHED_BY']  # 푸시한 사람
    repository = os.environ['REPOSITORY'].split('/')[-1]  # 레포지토리 이름
    commit_sha = os.environ['COMMIT_SHA'][: 6]  # 커밋 SHA
    commit_link = os.environ['COMMIT_LINK']  # 커밋 링크

    chat_headers = {
        'Content-Type': 'application/json',
        'Authorization': f'Bearer {access_token}'
    }

    branch_full_name = '/'.join(branch_name.split('/')[-2:])
    chat_data = get_chat_message(repository, commit_arr[0], commit_message, branch_full_name , pushed_by,
                            commit_sha, commit_link)

	# 메세지 전송
    for channel_id in CHANNEL_IDS:
        requests.post(f'https://www.worksapis.com/v1.0/bots/{BOT_ID}/channels/{channel_id}/messages', headers=chat_headers, json=chat_data)