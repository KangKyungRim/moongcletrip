<?php

namespace App\Controllers\Manage;

use App\Models\Rateplan;
use App\Models\Room;
use App\Models\RoomInventory;

use App\Helpers\PartnerHelper;

use Database;

class InventoryViewController
{
    public static function inventory()
    {
        $basicData = PartnerHelper::adminDefaultProcess();

        $rooms = [];
        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');

        $sql = "
            WITH RECURSIVE date_series AS (
                SELECT :startDate1 AS room_price_date
                UNION ALL
                SELECT DATE_ADD(room_price_date, INTERVAL 1 DAY)
                FROM date_series
                WHERE room_price_date < DATE_ADD(:startDate2, INTERVAL 44 DAY)
            )
            SELECT
                rr.partner_idx,
                rr.room_idx,
                rr.rateplan_idx,
                rr.room_rateplan_idx,
                r.room_name,
                rt.rateplan_name,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'room_price_date', ds.room_price_date,
                        'room_quantity', COALESCE(ri.inventory_quantity, ''),
                        'room_sold_quantity', COALESCE(ri.inventory_sold_quantity, ''),
                        'room_price_basic', COALESCE(rp.room_price_basic, ''),
                        'room_price_sale', COALESCE(rp.room_price_sale, ''),
                        'room_price_sale_percent', COALESCE(rp.room_price_sale_percent, ''),
                        'is_closed', COALESCE(rp.is_closed, '')
                    )
                    ORDER BY ds.room_price_date ASC
                ) AS inventory
            FROM moongcletrip.room_rateplan rr
            LEFT JOIN moongcletrip.rooms r ON r.room_idx = rr.room_idx
            LEFT JOIN moongcletrip.rateplans rt ON rt.rateplan_idx = rr.rateplan_idx
            LEFT JOIN date_series ds 
                ON 1 = 1
            LEFT JOIN moongcletrip.room_prices rp 
                ON rp.room_rateplan_idx = rr.room_rateplan_idx 
                AND rp.room_price_date = ds.room_price_date
            LEFT JOIN moongcletrip.room_inventories ri 
                ON ri.room_idx = r.room_idx AND ri.rateplan_idx = 0
                AND ri.inventory_date = ds.room_price_date
            WHERE rr.partner_idx = :partnerIdx
                AND rr.room_rateplan_status = 'enabled'
                AND r.room_status = 'enabled'
            GROUP BY rr.room_rateplan_idx
            ORDER BY r.room_order;
        ";

        $bindings = [
            'partnerIdx' => $basicData['selectedPartnerIdx'],
            'startDate1' => $startDate,
            'startDate2' => $startDate
        ];

        $rates = Database::getInstance()->getConnection()->select($sql, $bindings);

        $selectedRooms = [];
        if (!empty($_GET['selectedRoom'])) {
            $selectedRoom = urldecode($_GET['selectedRoom']);
            $selectedRooms = explode('|', $selectedRoom);
        }

        $selectedRateplans = [];
        if (!empty($_GET['selectedRateplan'])) {
            $selectedRateplan = urldecode($_GET['selectedRateplan']);
            $selectedRateplans = explode('|', $selectedRateplan);
        }

        foreach ($rates as $rate) {
            if (!empty($selectedRooms)) {
                if (!in_array($rate->room_idx, $selectedRooms)) {
                    continue;
                }
            }

            $rate->inventory = json_decode($rate->inventory);

            if (empty($rooms[$rate->room_idx])) {
                $rooms[$rate->room_idx] = [
                    'room_name' => $rate->room_name,
                    'quantity' => [],
                    'soldQuantity' => [],
                    'rateplans' => []
                ];

                foreach ($rate->inventory as $inventory) {
                    $rooms[$rate->room_idx]['quantity'][$inventory->room_price_date] = $inventory->room_quantity;
                    $rooms[$rate->room_idx]['soldQuantity'][$inventory->room_price_date] = $inventory->room_sold_quantity;
                }

                if (!empty($selectedRateplans)) {
                    if (!in_array($rate->rateplan_idx, $selectedRateplans)) {
                        continue;
                    }
                }

                $rooms[$rate->room_idx]['rateplans'][$rate->rateplan_idx] = [
                    'rateplan_name' => $rate->rateplan_name,
                    'inventory' => $rate->inventory
                ];
            } else {
                if (!empty($selectedRateplans)) {
                    if (!in_array($rate->rateplan_idx, $selectedRateplans)) {
                        continue;
                    }
                }

                $rooms[$rate->room_idx]['rateplans'][$rate->rateplan_idx] = [
                    'rateplan_name' => $rate->rateplan_name,
                    'inventory' => $rate->inventory
                ];
            }
        }

        $roomList = Room::where('partner_idx', $basicData['selectedPartnerIdx'])
            ->where('room_status', 'enabled')
            ->get();

        $rateplanList = Rateplan::where('partner_idx', $basicData['selectedPartnerIdx'])
            ->where('rateplan_status', 'enabled')
            ->get();

        $appendData = array(
            'rooms' => $rooms,
            'roomList' => $roomList,
            'rateplanList' => $rateplanList,
        );

        $data = array_merge($basicData, $appendData);
        self::render('manage/partner-rateplan-calendar', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
