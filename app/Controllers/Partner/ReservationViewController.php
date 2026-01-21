<?php

namespace App\Controllers\Partner;

use App\Helpers\PartnerHelper;

use Database;

class ReservationViewController
{
    public static function reservations()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $searchText = $_GET['searchText'] ?? '';
        $sortColumn = $_GET['sortColumn'] ?? 'updatedAt';
        $sortOrder = $_GET['sortOrder'] ?? 'DESC';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = isset($_GET['perPage']) ? max(1, intval($_GET['perPage'])) : 20;
        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT SQL_CALC_FOUND_ROWS
                p.*,
                pi.*,
                partner.partner_thirdparty,
                partner.partner_charge
            FROM moongcletrip.payments p
            LEFT JOIN moongcletrip.payment_items pi ON pi.payment_idx = p.payment_idx 
            LEFT JOIN moongcletrip.partners partner ON partner.partner_idx = pi.partner_idx
            WHERE p.payment_status IN ('paid', 'canceled', 'partial_canceled')
                AND (
                    p.payment_error_message IS NULL 
                    OR p.payment_error_message = 'PaymentFailed'
                )
                AND pi.partner_idx = " . $data['selectedPartnerIdx'] . "
        ";

        if (!empty($searchText)) {
            $cleanSearchText = str_replace(' ', '', $searchText);

            $sql .= " AND (
                REPLACE(pi.reservation_number, ' ', '') LIKE '%" . addslashes($cleanSearchText) . "%'
                OR REPLACE(pi.reservation_confirmed_code, ' ', '') LIKE '%" . addslashes($cleanSearchText) . "%'
                OR REPLACE(p.reservation_name, ' ', '') LIKE '%" . addslashes($cleanSearchText) . "%'
                OR REPLACE(p.reservation_phone, ' ', '') LIKE '%" . addslashes($cleanSearchText) . "%'
                OR REPLACE(p.reservation_email, ' ', '') LIKE '%" . addslashes($cleanSearchText) . "%'
                OR REPLACE(pi.product_partner_name, ' ', '') LIKE '%" . addslashes($cleanSearchText) . "%'
                OR REPLACE(pi.product_detail_name, ' ', '') LIKE '%" . addslashes($cleanSearchText) . "%'
            )";
        }

        $allowedSortColumns = [
            'updatedAt' => 'p.updated_at',
            'checkin' => 'pi.start_date',
            'reservationName' => 'p.reservation_name'
        ];

        $orderByColumn = $allowedSortColumns[$sortColumn] ?? 'p.updated_at';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $sql .= " ORDER BY {$orderByColumn} {$sortOrder}";

        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        $reservations = Database::getInstance()->getConnection()->select($sql);

        $totalCountResult = Database::getInstance()->getConnection()->select("SELECT FOUND_ROWS() AS total_count");
        $totalCount = $totalCountResult[0]->total_count ?? 0;
        $totalPages = ceil($totalCount / $perPage);

        $data['reservations'] = $reservations;
        $data['pagination'] = [
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages
        ];

        // $bindings = [];
        // $bindings['partnerIdx'] = $data['selectedPartnerIdx'];

        // $sql = "
        //     SELECT
        //         p.*,
        //         pi.*
        //     FROM moongcletrip.payments p
        //     LEFT JOIN moongcletrip.payment_items pi ON pi.payment_idx = p.payment_idx 
        //     WHERE p.payment_status IN ('paid', 'canceled', 'partial_canceled')
        //         AND p.payment_error_message IS NULL
        //         AND pi.partner_idx = :partnerIdx
        //     ORDER BY p.updated_at DESC;
        // ";

        // $reservations = Database::getInstance()->getConnection()->select($sql, $bindings);

        // $data['reservations'] = $reservations;

        self::render('partner/reservations', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
