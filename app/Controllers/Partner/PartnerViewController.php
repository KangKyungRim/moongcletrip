<?php

namespace App\Controllers\Partner;

use App\Models\Partner;
use App\Models\PartnerDraft;
use App\Models\MoongcleTag;
use App\Models\MoongcleTagConnection;
use App\Models\MoongcleTagConnectionDraft;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\CancelRule;
use App\Models\CancelRuleDraft;
use App\Models\Image;
use App\Models\ImageDraft;

use App\Helpers\PartnerHelper;

use App\Services\PartnerTagService;

use Database;

class PartnerViewController
{
    public static function selectPartner()
    {
        $page = !empty($_GET['page']) ? (int)$_GET['page'] : 0;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $search = !empty($_GET['search']) ? trim($_GET['search']) : null;

        $data = PartnerHelper::partnerDefaultProcess();

        $countQuery = Partner::query();
        if (!empty($search)) {
            $cleanedSearch = str_replace(' ', '', $search);
            $countQuery->whereRaw("REPLACE(partner_name, ' ', '') LIKE ?", ['%' . $cleanedSearch . '%']);
        }
        $totalCount = $countQuery->count();

        $query = Partner::query();
        if (!empty($search)) {
            $cleanedSearch = str_replace(' ', '', $search);
            $query->whereRaw("REPLACE(partner_name, ' ', '') LIKE ?", ['%' . $cleanedSearch . '%']);
        }

        $partners = $query->orderBy('partner_created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $totalPages = ceil($totalCount / $perPage);

        $data['partners'] = $partners;
        $data['totalCount'] = $totalCount;
        $data['totalPages'] = $totalPages;

        self::render('partner/partner-select', ['data' => $data]);
    }

    public static function basicInfo()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if ($basicData['selectedPartnerIdx'] == -1) {
            self::render('partner/partner-basic-info', ['data' => $basicData]);

            return;
        }

        $partner = Partner::find($basicData['selectedPartnerIdx']);

        $stay = $partner->partnerDetail();

        $stayTypeDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'stay_type')
            ->get();

        $stayTypeDetailDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'stay_type_detail')
            ->get();

        $facilityDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'facility')
            ->get();

        $attractionDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'attraction')
            ->get();

        $serviceDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'service')
            ->get();

        $petDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'pet')
            ->get();

        $bfPublicDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_public')
            ->get();

        $bfRoomDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_room')
            ->get();

        // Published
        $stayType = MoongcleTagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'stay_type')
            ->get();

        $stayTypeDetail = MoongcleTagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'stay_type_detail')
            ->get();

        $facility = MoongcleTagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'facility')
            ->get();

        $attraction = MoongcleTagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'attraction')
            ->get();

        $service = MoongcleTagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'service')
            ->get();

        $pet = MoongcleTagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'pet')
            ->get();

        $bfPublic = MoongcleTagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_public')
            ->get();

        $bfRoom = MoongcleTagConnection::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_room')
            ->get();

        $cancelRules = CancelRule::where('partner_idx', $basicData['selectedPartnerIdx'])
            ->orderBy('cancel_rules_order', 'asc')
            ->get();

        $formattedCancelRules = [];
        if (!empty($cancelRules)) {
            $formattedCancelRules = $cancelRules->map(function ($rule) {
                $rule->cancel_rules_time = substr($rule->cancel_rules_time, 0, 5);
                return $rule;
            });
        }

        $basicImage = Image::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'basic')
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

        $partnerDraft = $partner->draft;

        $stayDraft = $stay->draft;

        $basicImageDraft = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'basic')
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
            'publish' => [
                'partner' => $partner,
                'stay' => $stay,
                'stayType' => $stayType,
                'stayTypeDetail' => $stayTypeDetail,
                'facility' => $facility,
                'attraction' => $attraction,
                'service' => $service,
                'pet' => $pet,
                'barrierfreePublic' => $bfPublic,
                'barrierfreeRoom' => $bfRoom,
                'cancelRules' => $formattedCancelRules,
                'basicImage' => $basicImage,
                'bfPublicImage' => $bfPublicImage,
                'bfRoomImage' => $bfRoomImage,
            ],
            'draft' => [
                'partner' => $partnerDraft,
                'stay' => $stayDraft,
                'stayType' => $stayTypeDraft,
                'stayTypeDetail' => $stayTypeDetailDraft,
                'facility' => $facilityDraft,
                'attraction' => $attractionDraft,
                'service' => $serviceDraft,
                'pet' => $petDraft,
                'barrierfreePublic' => $bfPublicDraft,
                'barrierfreeRoom' => $bfRoomDraft,
                'cancelRules' => $formattedCancelRulesDraft,
                'basicImage' => $basicImageDraft,
                'bfPublicImage' => $bfPublicImageDraft,
                'bfRoomImage' => $bfRoomImageDraft,
            ],
        );

        $data = array_merge($basicData, $appendData);

        $tagData = MoongcleTag::select('tag_idx', 'tag_machine_name')
            ->get()
            ->keyBy('tag_machine_name')
            ->toArray();

        $stayTypes = PartnerTagService::getStayTypes($tagData);
        $stayTypeDetail = PartnerTagService::getStayTypeDetail($tagData);
        $facilityTags = PartnerTagService::getStayFacilityTags($tagData);
        $attractionTags = PartnerTagService::getStayAttractionTags($tagData);
        $serviceTags = PartnerTagService::getStayServiceTags($tagData);
        $petTags = PartnerTagService::getStayPetTags($tagData);
        $barrierfreePublicTags = PartnerTagService::getBarrierfreePublicTags($tagData);
        $newStayTasteTags = PartnerTagService::getNewStayTasteTags($tagData);
        $searchBadgeTags = PartnerTagService::getSearchBadgeTags($tagData);

        $data['tags'] = [];
        $data['tags']['stayTypes'] = $stayTypes;
        $data['tags']['stayTypeDetail'] = $stayTypeDetail;
        $data['tags']['facilityTags'] = $facilityTags;
        $data['tags']['attractionTags'] = $attractionTags;
        $data['tags']['serviceTags'] = $serviceTags;
        $data['tags']['petTags'] = $petTags;
        $data['tags']['barrierfreePublicTags'] = $barrierfreePublicTags;
        $data['tags']['newStayTasteTags'] = $newStayTasteTags;
        $data['tags']['searchBadgeTags'] = $searchBadgeTags;

        self::render('partner/partner-basic-info', ['data' => $data]);
    }

    public static function editBasicInfo()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        $partner = Partner::find($basicData['selectedPartnerIdx']);

        $stay = $partner->partnerDetail();

        $stayTypeDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'stay_type')
            ->get();

        $stayTypeDetailDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'stay_type_detail')
            ->get();

        $facilityDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'facility')
            ->get();

        $attractionDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'attraction')
            ->get();

        $serviceDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'service')
            ->get();

        $petDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'pet')
            ->get();

        $bfPublicDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_public')
            ->get();

        $bfRoomDraft = MoongcleTagConnectionDraft::where('item_idx', $stay->stay_idx)
            ->where('item_type', 'stay')
            ->where('connection_type', 'barrierfree_room')
            ->get();

        $cancelRulesDraft = CancelRuleDraft::where('partner_idx', $partner->partner_idx)
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

        $partnerDraft = $partner->draft;

        $stayDraft = $stay->draft;

        $basicImageDraft = ImageDraft::where('image_entity_id', $stay->stay_idx)
            ->where('image_entity_type', 'stay')
            ->where('image_type', 'basic')
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
            'partner' => $partnerDraft,
            'stay' => $stayDraft,
            'stayType' => $stayTypeDraft,
            'stayTypeDetail' => $stayTypeDetailDraft,
            'facility' => $facilityDraft,
            'attraction' => $attractionDraft,
            'service' => $serviceDraft,
            'pet' => $petDraft,
            'barrierfreePublic' => $bfPublicDraft,
            'barrierfreeRoom' => $bfRoomDraft,
            'cancelRules' => $formattedCancelRulesDraft,
            'basicImage' => $basicImageDraft,
            'bfPublicImage' => $bfPublicImageDraft,
            'bfRoomImage' => $bfRoomImageDraft,
        );

        $data = array_merge($basicData, $appendData);

        $tagData = MoongcleTag::select('tag_idx', 'tag_machine_name')
            ->get()
            ->keyBy('tag_machine_name')
            ->toArray();

        $stayTypes = PartnerTagService::getStayTypes($tagData);
        $stayTypeDetail = PartnerTagService::getStayTypeDetail($tagData);
        $facilityTags = PartnerTagService::getStayFacilityTags($tagData);
        $attractionTags = PartnerTagService::getStayAttractionTags($tagData);
        $serviceTags = PartnerTagService::getStayServiceTags($tagData);
        $petTags = PartnerTagService::getStayPetTags($tagData);
        $barrierfreePublicTags = PartnerTagService::getBarrierfreePublicTags($tagData);
        $newStayTasteTags = PartnerTagService::getNewStayTasteTags($tagData);
        $searchBadgeTags = PartnerTagService::getSearchBadgeTags($tagData);

        $data['tags'] = [];
        $data['tags']['stayTypes'] = $stayTypes;
        $data['tags']['stayTypeDetail'] = $stayTypeDetail;
        $data['tags']['facilityTags'] = $facilityTags;
        $data['tags']['attractionTags'] = $attractionTags;
        $data['tags']['serviceTags'] = $serviceTags;
        $data['tags']['petTags'] = $petTags;
        $data['tags']['barrierfreePublicTags'] = $barrierfreePublicTags;
        $data['tags']['newStayTasteTags'] = $newStayTasteTags;
        $data['tags']['searchBadgeTags'] = $searchBadgeTags;

        self::render('partner/partner-basic-info-edit', ['data' => $data]);
    }

    public static function basicInfoCreate()
    {
        $data = PartnerHelper::partnerDefaultProcess();



        self::render('partner/partner-basic-info-create', ['data' => $data]);
    }

    public static function curatedImages()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $bindings = [];
        $bindings['partnerIdx'] = $data['selectedPartnerIdx'];

        $sql = "
            SELECT
                p.*,
                s.*,
                (
                    SELECT CONCAT('[', GROUP_CONCAT(
                        JSON_OBJECT(
                            'image_origin_name', img.image_origin_name,
                            'image_origin_path', img.image_origin_path,
                            'image_origin_size', img.image_origin_size
                        ) ORDER BY img.image_order ASC SEPARATOR ','), ']')
                    FROM moongcletrip.curated_images img
                    WHERE img.image_entity_id = p.partner_detail_idx
                    AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
                ) AS image_paths
            FROM partners p
            LEFT JOIN stays s ON p.partner_detail_idx = s.stay_idx
            WHERE p.partner_category = 'stay'
                AND p.partner_idx = :partnerIdx
        ";

        $partner = Database::getInstance()->getConnection()->select($sql, $bindings);

        $data['partner'] = $partner[0];

        self::render('partner/curated-images', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
