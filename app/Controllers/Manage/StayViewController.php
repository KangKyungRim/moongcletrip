<?php

namespace App\Controllers\Manage;

use App\Helpers\PartnerHelper;

use App\Models\Partner;
use App\Models\Tag;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\CancelRule;
use App\Models\CancelRuleDraft;
use App\Models\Image;
use App\Models\ImageDraft;

class StayViewController
{
    public static function stayInfo()
    {
        $basicData = PartnerHelper::adminDefaultProcess();

        if ($basicData['selectedPartnerIdx'] != -1) {
            $stay = $basicData['selectedPartner']->partnerDetail();
            $stayDraft = $stay->draft;
        }

        $amenities = [];

        $barrierfreePublic = [];

        $barrierfreeRoom = [];

        $selectedAmenitiesDraft = TagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'amenity')
            ->pluck('tag_idx')->toArray();

        $selectedBfPublicDraft = TagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_public')
            ->pluck('tag_idx')->toArray();

        $selectedBfRoomDraft = TagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_room')
            ->pluck('tag_idx')->toArray();

        // Published
        $selectedAmenities = TagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'amenity')
            ->pluck('tag_idx')->toArray();

        $selectedBfPublic = TagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_public')
            ->pluck('tag_idx')->toArray();

        $selectedBfRoom = TagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_room')
            ->pluck('tag_idx')->toArray();

        $cancelRules = CancelRule::where('partner_idx', $basicData['selectedPartnerIdx'])
            ->orderBy('cancel_rules_order', 'asc')
            ->get();

        $formattedCancelRules = [];
        if (!empty($cancelRules)) {
            $formattedCancelRules = $cancelRules->map(function ($rule) {
                // 시간 포맷을 00:00으로 변환
                $rule->cancel_rules_time = substr($rule->cancel_rules_time, 0, 5);
                return $rule;
            });
        }

        $mainImage = Image::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'basic')
            ->get();

        $subImage = Image::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'sub')
            ->orderBy('image_order', 'asc')
            ->get();

        $bfPublicImage = Image::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'barrierfree_public')
            ->orderBy('image_order', 'asc')
            ->get();

        $bfRoomImage = Image::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'barrierfree_room')
            ->orderBy('image_order', 'asc')
            ->get();

        // Draft
        $cancelRulesDraft = CancelRuleDraft::where('partner_idx', $basicData['selectedPartnerIdx'])
            ->orderBy('cancel_rules_order', 'asc')
            ->get();

        $formattedCancelRulesDraft = [];
        if (!empty($cancelRulesDraft)) {
            $formattedCancelRulesDraft = $cancelRulesDraft->map(function ($rule) {
                // 시간 포맷을 00:00으로 변환
                $rule->cancel_rules_time = substr($rule->cancel_rules_time, 0, 5);
                return $rule;
            });
        }

        $mainImageDraft = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'main')
            ->get();

        $subImageDraft = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'sub')
            ->orderBy('image_order', 'asc')
            ->get();

        $bfPublicImageDraft = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'barrierfree_public')
            ->orderBy('image_order', 'asc')
            ->get();

        $bfRoomImageDraft = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'barrierfree_room')
            ->orderBy('image_order', 'asc')
            ->get();

        $appendData = array(
            'stay' => $stay,
            'stayDraft' => $stayDraft,
            'amenities' => $amenities,
            'barrierfreePublic' => $barrierfreePublic,
            'barrierfreeRoom' => $barrierfreeRoom,
            'publish' => [
                'selectedAmenities' => $selectedAmenities,
                'selectedBfPublic' => $selectedBfPublic,
                'selectedBfRoom' => $selectedBfRoom,
                'cancelRules' => $formattedCancelRules,
                'mainImage' => $mainImage,
                'subImage' => $subImage,
                'bfPublicImage' => $bfPublicImage,
                'bfRoomImage' => $bfRoomImage,
            ],
            'draft' => [
                'selectedAmenities' => $selectedAmenitiesDraft,
                'selectedBfPublic' => $selectedBfPublicDraft,
                'selectedBfRoom' => $selectedBfRoomDraft,
                'cancelRules' => $formattedCancelRulesDraft,
                'mainImage' => $mainImageDraft,
                'subImage' => $subImageDraft,
                'bfPublicImage' => $bfPublicImageDraft,
                'bfRoomImage' => $bfRoomImageDraft,
            ],
        );

        $data = array_merge($basicData, $appendData);
        self::render('manage/partner-stay-info', ['data' => $data]);
    }

    public static function stayInfoForm()
    {
        $basicData = PartnerHelper::adminDefaultProcess();

        if ($basicData['selectedPartnerIdx'] != -1) {
            $stay = $basicData['selectedPartner']->partnerDetail();
            $stayDraft = $stay->draft;
        }

        $amenities = [];

        $barrierfreePublic = [];

        $barrierfreeRoom = [];

        $selectedAmenities = TagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'amenity')
            ->pluck('tag_idx')->toArray();

        $selectedBfPublic = TagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_public')
            ->pluck('tag_idx')->toArray();

        $selectedBfRoom = TagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_room')
            ->pluck('tag_idx')->toArray();

        $cancelRulesDraft = CancelRuleDraft::where('partner_idx', $basicData['selectedPartnerIdx'])
            ->orderBy('cancel_rules_order', 'asc')
            ->get();

        $formattedCancelRulesDraft = $cancelRulesDraft->map(function ($rule) {
            // 시간 포맷을 00:00으로 변환
            $rule->cancel_rules_time = substr($rule->cancel_rules_time, 0, 5);
            return $rule;
        });

        $mainImage = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'main')
            ->get();

        $subImage = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'sub')
            ->orderBy('image_order', 'asc')
            ->get();

        $bfPublicImage = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'barrierfree_public')
            ->orderBy('image_order', 'asc')
            ->get();

        $bfRoomImage = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'barrierfree_room')
            ->orderBy('image_order', 'asc')
            ->get();

        $appendData = array(
            'stay' => $stay,
            'stayDraft' => $stayDraft,
            'amenities' => $amenities,
            'barrierfreePublic' => $barrierfreePublic,
            'barrierfreeRoom' => $barrierfreeRoom,
            'selectedAmenities' => $selectedAmenities,
            'selectedBfPublic' => $selectedBfPublic,
            'selectedBfRoom' => $selectedBfRoom,
            'cancelRulesDraft' => $formattedCancelRulesDraft,
            'mainImage' => $mainImage,
            'subImage' => $subImage,
            'bfPublicImage' => $bfPublicImage,
            'bfRoomImage' => $bfRoomImage,
        );

        $data = array_merge($basicData, $appendData);
        self::render('manage/partner-stay-info-form', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
