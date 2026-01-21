<?php

namespace App\Controllers\Partner;

use App\Helpers\PartnerHelper;

use App\Models\Partner;
use App\Models\Room;
use App\Models\Tag;
use App\Models\MoongcleTagConnection;
use App\Models\MoongcleTagConnectionDraft;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\Image;
use App\Models\ImageDraft;
use App\Models\MoongcleTag;

use App\Services\PartnerTagService;

use Database;

class RoomViewController
{
    public static function roomList()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if ($basicData['selectedPartnerIdx'] === -1) {
            header('Location: /partner/dashboard');
        }

        $sql = "
            SELECT
                r.*,
                rd.room_is_approved
            FROM
                rooms r
            LEFT JOIN rooms_draft rd ON r.room_idx = rd.room_idx
            WHERE
                r.partner_idx = :partnerIdx
            ORDER BY r.room_order ASC, r.room_idx ASC
        ";

        $bindings = [
            'partnerIdx' => $basicData['selectedPartnerIdx']
        ];

        $rooms = Database::getInstance()->getConnection()->select($sql, $bindings);

        foreach($rooms as &$room) {
            if(empty($room->room_bed_type)) {
                $room->review_status = 'first_review';
            } else {
                if($room->room_is_approved) {
                    $room->review_status = 'reviewed';
                } else {
                    $room->review_status = 'nth_review';
                }
            }
        }

        $appendData = array(
            'rooms' => $rooms
        );

        $data = array_merge($basicData, $appendData);
        self::render('partner/partner-room-list', ['data' => $data]);
    }

    public static function roomInfo()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        if ($basicData['selectedPartnerIdx'] == -1 || empty($_GET['roomIdx'])) {
            header('Location: /404');
        }

        $room = Room::find($_GET['roomIdx']);
        $roomDraft = $room->draft;

        if(empty($room->room_bed_type)) {
            $room->review_status = 'first_review';
        } else {
            if($room->room_is_approved) {
                $room->review_status = 'reviewed';
            } else {
                $room->review_status = 'nth_review';
            }
        }

        $tagData = MoongcleTag::select('tag_idx', 'tag_machine_name')
            ->get()
            ->keyBy('tag_machine_name')
            ->toArray();

        $roomtypeTags = PartnerTagService::getRoomtypeTags($tagData);
        $viewTags = PartnerTagService::getViewTags($tagData);
        $amenityTags = PartnerTagService::getAmenityTags($tagData);
        $barrierfreeRoomTags = PartnerTagService::getBarrierfreeRoomTags($tagData);

        // Draft
        $roomtypeDraft = MoongcleTagConnectionDraft::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'roomtype')
            ->get();

        $amenityDraft = MoongcleTagConnectionDraft::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'room_amenity')
            ->get();

        $viewDraft = MoongcleTagConnectionDraft::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'view')
            ->get();

        $bfRoomDraft = MoongcleTagConnectionDraft::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'barrierfree_room')
            ->get();

        $basicImageDraft = ImageDraft::where('image_entity_id', $room->room_idx)
            ->where('image_entity_type', 'room')
            ->where('image_type', 'basic')
            ->orderBy('image_order', 'asc')
            ->get();

        // Published
        $roomtype = MoongcleTagConnection::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'roomtype')
            ->get();

        $amenity = MoongcleTagConnection::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'room_amenity')
            ->get();

        $view = MoongcleTagConnection::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'view')
            ->get();

        $bfRoom = MoongcleTagConnection::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'barrierfree_room')
            ->get();

        $basicImage = Image::where('image_entity_id', $room->room_idx)
            ->where('image_entity_type', 'room')
            ->where('image_type', 'basic')
            ->orderBy('image_order', 'asc')
            ->get();

        $appendData = array(
            'publish' => [
                'room' => $room,
                'roomtype' => $roomtype,
                'view' => $view,
                'amenity' => $amenity,
                'barrierfreeRoom' => $bfRoom,
                'basicImage' => $basicImage,
            ],
            'draft' => [
                'room' => $roomDraft,
                'roomtype' => $roomtypeDraft,
                'view' => $viewDraft,
                'amenity' => $amenityDraft,
                'barrierfreeRoom' => $bfRoomDraft,
                'basicImage' => $basicImageDraft,
            ],
            'tags' => [
                'roomtypeTags' => $roomtypeTags,
                'viewTags' => $viewTags,
                'amenityTags' => $amenityTags,
                'barrierfreeRoomTags' => $barrierfreeRoomTags,
            ]
        );

        $data = array_merge($basicData, $appendData);
        self::render('partner/partner-room-info', ['data' => $data]);
    }

    public static function editRoomInfo()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        $room = Room::find($_GET['roomIdx']);
        $roomDraft = $room->draft;

        $tagData = MoongcleTag::select('tag_idx', 'tag_machine_name')
            ->get()
            ->keyBy('tag_machine_name')
            ->toArray();

        $roomtypeTags = PartnerTagService::getRoomtypeTags($tagData);
        $viewTags = PartnerTagService::getViewTags($tagData);
        $amenityTags = PartnerTagService::getAmenityTags($tagData);
        $barrierfreeRoomTags = PartnerTagService::getBarrierfreeRoomTags($tagData);

        // Draft
        $roomtypeDraft = MoongcleTagConnectionDraft::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'roomtype')
            ->get();

        $amenityDraft = MoongcleTagConnectionDraft::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'room_amenity')
            ->get();

        $viewDraft = MoongcleTagConnectionDraft::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'view')
            ->get();

        $bfRoomDraft = MoongcleTagConnectionDraft::where('item_idx', $room->room_idx)
            ->where('item_type', 'room')
            ->where('connection_type', 'barrierfree_room')
            ->get();

        $basicImageDraft = ImageDraft::where('image_entity_id', $room->room_idx)
            ->where('image_entity_type', 'room')
            ->where('image_type', 'basic')
            ->orderBy('image_order', 'asc')
            ->get();

        $appendData = array(
            'room' => $roomDraft,
            'roomtype' => $roomtypeDraft,
            'view' => $viewDraft,
            'amenity' => $amenityDraft,
            'barrierfreeRoom' => $bfRoomDraft,
            'basicImage' => $basicImageDraft,
            'tags' => [
                'roomtypeTags' => $roomtypeTags,
                'viewTags' => $viewTags,
                'amenityTags' => $amenityTags,
                'barrierfreeRoomTags' => $barrierfreeRoomTags,
            ]
        );

        $data = array_merge($basicData, $appendData);
        self::render('partner/partner-room-info-edit', ['data' => $data]);
    }

    public static function createRoomInfo()
    {
        $basicData = PartnerHelper::partnerDefaultProcess();

        $tagData = MoongcleTag::select('tag_idx', 'tag_machine_name')
            ->get()
            ->keyBy('tag_machine_name')
            ->toArray();

        $roomtypeTags = PartnerTagService::getRoomtypeTags($tagData);
        $viewTags = PartnerTagService::getViewTags($tagData);
        $amenityTags = PartnerTagService::getAmenityTags($tagData);
        $barrierfreeRoomTags = PartnerTagService::getBarrierfreeRoomTags($tagData);

        $appendData = array(
            'tags' => [
                'roomtypeTags' => $roomtypeTags,
                'viewTags' => $viewTags,
                'amenityTags' => $amenityTags,
                'barrierfreeRoomTags' => $barrierfreeRoomTags,
            ]
        );

        $data = array_merge($basicData, $appendData);

        self::render('partner/partner-room-info-create', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
