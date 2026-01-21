<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitItem extends Model
{
    // 테이블 이름 명시 (기본적으로 모델명 복수형, 'benefits')
    protected $table = 'benefit_item';

    // 기본 키 설정
    protected $primaryKey = 'benefit_item_idx';

    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'benefit_idx',
        'benefit_name',
        'item_idx',
        'item_type',
    ];
}
