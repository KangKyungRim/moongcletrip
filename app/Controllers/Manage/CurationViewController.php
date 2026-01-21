<?php

namespace App\Controllers\Manage;

use App\Models\MoongcleTag;

use App\Helpers\PartnerHelper;

use App\Services\PartnerTagService;

/**
 * 파트너 > 앱 관리 > 큐레이션 관리
 */
class CurationViewController
{
    /**
     * 큐레이션 목록
     */
    public static function curations()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/curations', ['data' => $data]);
    }

    /**
     * 큐레이션 등록
     */
    public static function curationCreate()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $tagData = MoongcleTag::select('tag_idx', 'tag_machine_name')
            ->get()
            ->keyBy('tag_machine_name')
            ->toArray();

        $searchBadgeTags = PartnerTagService::getSearchBadgeTags($tagData);
        
        $data['tags'] = [];
        $data['tags']['searchBadgeTags'] = $searchBadgeTags;

        self::render('manage/curation-create', ['data' => $data]);
    }

    /**
     * 큐레이션 수정
     */
    public static function curationEdit()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $tagData = MoongcleTag::select('tag_idx', 'tag_machine_name')
            ->get()
            ->keyBy('tag_machine_name')
            ->toArray();

        $searchBadgeTags = PartnerTagService::getSearchBadgeTags($tagData);
        
        $data['tags'] = [];
        $data['tags']['searchBadgeTags'] = $searchBadgeTags;
        
        self::render('manage/curation-edit', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
