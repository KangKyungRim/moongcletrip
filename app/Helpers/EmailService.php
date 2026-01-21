<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
	private $mail;
	private $template;

	public function __construct()
	{
		// PHPMailer 설정
		$this->mail = new PHPMailer(true);
		$this->setupSMTP();
		$this->mail->CharSet = 'UTF-8';
		$this->mail->Encoding = 'base64';
	}

	// SMTP 설정
	private function setupSMTP()
	{
		try {
			// 서버 설정
			$this->mail->isSMTP();
			$this->mail->Host       = $_ENV['SMTP_HOST'];
			$this->mail->SMTPAuth   = true;
			$this->mail->Username   = $_ENV['SMTP_USER'];
			$this->mail->Password   = $_ENV['SMTP_PASS'];
			$this->mail->SMTPSecure = 'ssl';
			$this->mail->Port       = 465;
		} catch (Exception $e) {
			echo "SMTP 설정 실패: {$this->mail->ErrorInfo}";
		}
	}

	// 템플릿 파일 읽기 및 변수 치환
	public function loadTemplate($templatePath, $variables = [])
	{
		$absoluteTemplatePath = __DIR__ . '/' . $templatePath;

		$this->template = file_get_contents($absoluteTemplatePath);

		// 템플릿 변수를 동적으로 치환
		foreach ($variables as $key => $value) {
			$this->template = str_replace("{{{$key}}}", $value, $this->template);
		}
	}

	// 메일 전송 설정
	public function sendMail($toEmail, $toName, $subject)
	{
		try {
			// 발신자 설정
			$this->mail->setFrom('moongcletrip@honolulu.co.kr', '뭉클트립');

			$emails = explode(',', $toEmail);

			foreach ($emails as $email) {
				$email = trim($email);
				$this->mail->addAddress($email, $toName);
			}

			// 이메일 내용 설정
			$this->mail->isHTML(true);
			$this->mail->Subject = $subject;
			$this->mail->Body    = $this->template;

			// 이메일 전송
			$this->mail->send();

			return true;
		} catch (Exception $e) {
			// echo "이메일 전송 실패: {$this->mail->ErrorInfo}";
			return false;
		}
	}
}
