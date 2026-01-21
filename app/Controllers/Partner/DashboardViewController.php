<?php

namespace App\Controllers\Partner;

use App\Models\CityDateAnalytics;
use App\Models\CompanionAnalytics;
use App\Models\TasteAnalytics;
use App\Models\MoongcleTag;

use App\Helpers\PartnerHelper;

use App\Services\TagService;

use Carbon\Carbon;
use Database;

class DashboardViewController
{
    public static function dashboard()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $myCityAndDate = [];
        $cityAndDate = [];

        $endMonth = Carbon::now()->addMonths(2)->format('Y-m');
        $startMonth = Carbon::now()->subMonths(2)->format('Y-m');

        $tagService = new TagService();
        $tags = $tagService->getMoongcledealTags();
        $cities = $tags['cityTags'];

        $cityMachineName = null;
        $searchName = $data['selectedPartner']['partner_address1'] ?? '';

        foreach ($cities as $tag) {
            if (strpos($searchName, $tag['tag_name']) !== false) {
                $cityMachineName = $tag['tag_machine_name'];
                break;
            }
        }

        $relatedCityTags = [];

        foreach ($cities as $tag) {
            if (strpos($searchName, $tag['tag_name']) !== false) {
                $relatedCityTags[] = $tag['tag_machine_name'];
            }
        }

        $cityAndDateData = CityDateAnalytics::where(function ($query) use ($startMonth, $endMonth) {
            $query->whereBetween('month_key', [$startMonth, $endMonth])
                ->orWhere('month_key', 'always');
        })
            ->whereIn('city_tag', $relatedCityTags)
            ->get();

        foreach ($cityAndDateData as $entry) {
            $city = $cityMachineName;
            $month = $entry->month_key;
            $count = $entry->count;

            if (!isset($myCityAndDate[$city])) {
                $myCityAndDate[$city] = [];
            }

            if (empty($myCityAndDate[$city][$month])) {
                $myCityAndDate[$city][$month] = $count;
            } else {
                $myCityAndDate[$city][$month] += $count;
            }
        }

        $cityAndDateData = CityDateAnalytics::where(function ($query) use ($startMonth, $endMonth) {
            $query->whereBetween('month_key', [$startMonth, $endMonth])
                ->orWhere('month_key', 'always');
        })
            ->get();

        foreach ($cityAndDateData as $entry) {
            $city = $entry->city_tag;
            $month = $entry->month_key;
            $count = $entry->count;

            if (!isset($cityAndDate[$city])) {
                $cityAndDate[$city] = [];
            }

            $cityAndDate[$city][$month] = $count;
        }

        $companionData = CompanionAnalytics::all();

        $companions = [
            'all' => [], // 모든 도시 합산
        ];
        $myRegionCompanions = [
            $cityMachineName => []
        ];

        foreach ($companionData as $entry) {
            $city = $entry->city_tag;
            $type = $entry->companion_tag . ':' . $entry->personnel;
            $count = $entry->count;

            if (!isset($companions['all'][$type])) {
                $companions['all'][$type] = 0;
            }
            $companions['all'][$type] += $count;

            if (!isset($companions[$city])) {
                $companions[$city] = [];
            }

            if (!isset($companions[$city][$type])) {
                $companions[$city][$type] = 0;
            }

            $companions[$city][$type] += $count;

            if (in_array($city, $relatedCityTags)) {
                if (!isset($myRegionCompanions[$cityMachineName][$type])) {
                    $myRegionCompanions[$cityMachineName][$type] = 0;
                }
                $myRegionCompanions[$cityMachineName][$type] += $count;
            }
        }

        $tasteData = TasteAnalytics::all();

        $tastes = [
            'all' => [],
        ];
        $myRegionTastes = [
            $cityMachineName => []
        ];

        foreach ($tasteData as $entry) {
            $city = $entry->city_tag;
            $type = $entry->taste_tag;
            $count = $entry->count;

            if (!isset($tastes['all'][$type])) {
                $tastes['all'][$type] = 0;
            }
            $tastes['all'][$type] += $count;

            if (!isset($tastes[$city])) {
                $tastes[$city] = [];
            }

            if (!isset($tastes[$city][$type])) {
                $tastes[$city][$type] = 0;
            }

            $tastes[$city][$type] += $count;

            if (in_array($city, $relatedCityTags)) {
                if (!isset($myRegionTastes[$cityMachineName][$type])) {
                    $myRegionTastes[$cityMachineName][$type] = 0;
                }
                $myRegionTastes[$cityMachineName][$type] += $count;
            }
        }

        $moongcleTags = MoongcleTag::all()
            ->pluck('tag_name', 'tag_machine_name')
            ->toArray();

        $data['myCityAndDate'] = $myCityAndDate;
        $data['cityAndDate'] = $cityAndDate;
        $data['companions'] = $companions;
        $data['myRegionCompanions'] = $myRegionCompanions;
        $data['tastes'] = $tastes;
        $data['myRegionTastes'] = $myRegionTastes;
        $data['moongcleTags'] = $moongcleTags;

        $sortColumn = $_GET['sortColumn'] ?? 'createdAt';
        $sortOrder = $_GET['sortOrder'] ?? 'DESC';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = isset($_GET['perPage']) ? max(1, intval($_GET['perPage'])) : 5;
        $offset = ($page - 1) * $perPage;

        $bindings = [];

        $sql = "
            SELECT SQL_CALC_FOUND_ROWS
                sm.*,
                rt.*
            FROM moongcletrip.stay_moongcleoffers sm
            LEFT JOIN moongcletrip.rateplans rt ON rt.rateplan_idx = sm.rateplan_idx 
            WHERE sm.partner_idx = :partnerIdx
        ";

        $bindings['partnerIdx'] = $data['selectedPartnerIdx'];

        $allowedSortColumns = [
            'createdAt' => 'sm.created_at',
            'saleStartDate' => 'sm.sale_start_date',
            'stayStartDate' => 'sm.stay_start_date'
        ];

        $orderByColumn = $allowedSortColumns[$sortColumn] ?? 'sm.created_at';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $sql .= " ORDER BY {$orderByColumn} {$sortOrder}";

        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        $moongcleoffers = Database::getInstance()->getConnection()->select($sql, $bindings);

        $totalCountResult = Database::getInstance()->getConnection()->select("SELECT FOUND_ROWS() AS total_count");
        $totalCount = $totalCountResult[0]->total_count ?? 0;
        $totalPages = ceil($totalCount / $perPage);

        foreach ($moongcleoffers as &$moongcleoffer) {
            $moongcleoffer->benefits = json_decode($moongcleoffer->benefits, true);
            $moongcleoffer->rooms = json_decode($moongcleoffer->rooms, true);
            $moongcleoffer->tags = json_decode($moongcleoffer->tags, true);
            $moongcleoffer->curated_tags = json_decode($moongcleoffer->curated_tags, true);

            $moongcleoffer->tag_list = MoongcleTag::whereIn('tag_machine_name', $moongcleoffer->tags)->get();
        }

        $data['moongcleoffers'] = $moongcleoffers;
        $data['pagination'] = [
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages
        ];

        self::render('partner/dashboard', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
