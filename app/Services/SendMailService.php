<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendMailService
{
	public static function sendMail($email)
	{
		// SMTP 설정 정보는 환경 변수에서 불러옴
		$smtpHost = $_ENV['SMTP_HOST'];
		$smtpPort = 465;
		$smtpUser = $_ENV['SMTP_USER'];
		$smtpPass = $_ENV['SMTP_PASS'];

		if (!$smtpUser || !$smtpPass) {
			return 'SMTP 설정이 올바르지 않습니다.';
		}

		// PHPMailer 인스턴스 생성
		$Mail_sender = new PHPMailer(true);

		try {
			$Mail_sender->isSMTP();
			$Mail_sender->SMTPDebug = 0;
			$Mail_sender->CharSet = 'UTF-8';
			$Mail_sender->Debugoutput = 'html';
			$Mail_sender->Host = $smtpHost;
			$Mail_sender->Port = $smtpPort;
			$Mail_sender->SMTPSecure = 'ssl';
			$Mail_sender->SMTPAuth = true;
			$Mail_sender->Username = $smtpUser;
			$Mail_sender->Password = $smtpPass;

			// 이메일 설정
			$Mail_sender->setFrom($email['fromEmail'], $email['fromName']);
			$Mail_sender->addAddress($email['toEmail'], $email['toName']);
			$Mail_sender->Subject = $email['subject'];
			$Mail_sender->msgHTML($email['contents']);

			// 이메일 전송
			$Mail_sender->send();

			return true;
		} catch (Exception $e) {
			return false;
		} finally {
			// 자원 정리
			$Mail_sender->clearAddresses();
			$Mail_sender->clearAttachments();
		}
	}
}
