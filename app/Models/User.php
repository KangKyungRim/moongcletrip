<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // 테이블 이름 설정 (기본값은 모델명 복수형, 'users')
    protected $table = 'users';

    // 기본 키 설정
    protected $primaryKey = 'user_idx';

    // 자동으로 관리되는 타임스탬프 필드 (created_at, updated_at)
    public $timestamps = false;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'user_id',
        'user_is_guest',
        'user_nickname',
        'user_password',
        'user_name',
        'user_email',
        'user_phone_number',
        'user_login_type',
        'user_status',
        'user_level',
        'user_heartbeat',
        'user_app_version',
        'user_points',
        'user_birthday',
        'user_image',
        'user_referral_code',
        'user_customer_key',
        'reservation_name',
        'reservation_email',
        'reservation_phone',
        'user_agree_age',
        'user_agree_terms',
        'user_agree_privacy',
        'user_agree_location',
        'user_agree_marketing',
        'user_created_at',
        'user_updated_at',
        'user_last_login_date',
    ];

    // 모델에서 사용되는 타임스탬프 필드의 커스텀 설정
    const CREATED_AT = 'user_created_at';
    const UPDATED_AT = 'user_updated_at';

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'user_birthday',
        'user_created_at',
        'user_updated_at',
        'user_last_login_date',
    ];

    // 유저의 기본 포인트를 0으로 설정하는 메서드
    public function initializeUserPoints()
    {
        $this->user_points = 0;
    }

    public function tokens()
    {
        return $this->hasMany(Token::class, 'user_idx', 'user_idx');
    }

    public function moongcleDeals()
    {
        return $this->hasMany(MoongcleDeal::class, 'user_idx', 'user_idx');
    }

    // 포인트 적립
    public function addPoints($points, $description = null)
    {
        // 포인트 적립 내역 기록
        Point::create([
            'user_idx' => $this->user_idx,
            'point_type' => 'earn',
            'point_amount' => $points,
            'description' => $description,
        ]);

        // 최종 포인트 업데이트
        $this->user_points += $points;
        $this->save();
    }

    // 포인트 사용
    public function usePoints($points, $description = null)
    {
        if ($this->user_points >= $points) {
            // 포인트 사용 내역 기록
            Point::create([
                'user_idx' => $this->user_idx,
                'point_type' => 'use',
                'point_amount' => $points,
                'description' => $description,
            ]);

            // 최종 포인트 업데이트
            $this->user_points -= $points;
            $this->save();

            return true;
        }

        return false;
    }

    // 포인트 내역 조회
    public function pointsHistory()
    {
        return $this->hasMany(Point::class, 'user_idx', 'user_idx');
    }

    // 유저가 관심을 지정한 파트너들과의 관계 설정
    public function favoritePartners()
    {
        return $this->hasMany(PartnerFavorite::class, 'user_idx', 'user_idx');
    }

    // 유저가 신청한 재오픈 알림과의 관계 설정
    public function restockNotifications()
    {
        return $this->hasMany(RestockNotification::class, 'user_idx', 'user_idx');
    }

    // 유저가 찜한 리뷰들과의 관계 설정
    public function favoriteReviews()
    {
        return $this->hasMany(ReviewFavorite::class, 'user_idx', 'user_idx');
    }
}
