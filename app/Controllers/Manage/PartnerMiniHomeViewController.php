<?php

namespace App\Controllers\Manage;

use App\Helpers\PartnerHelper;

use App\Models\Partner;
use App\Models\PartnerFaq;
use App\Models\FacilityDetail;
use App\Models\FacilityImage;
use App\Models\ServiceDetail;

use Database;

class PartnerMiniHomeViewController
{
    public static function faq()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $partnerFaq = PartnerFaq::where('partner_idx', $data['selectedPartnerIdx'])
            ->orderBy('faq_order', 'ASC')
            ->get();

        $data['partnerFaq'] = $partnerFaq;

        self::render('manage/partner-faq', ['data' => $data]);
    }

    public static function faqUpdate()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $partnerFaq = PartnerFaq::where('partner_idx', $data['selectedPartnerIdx'])
            ->orderBy('faq_order', 'ASC')
            ->get();

        $data['partnerFaq'] = $partnerFaq;

        self::render('manage/partner-faq-edit', ['data' => $data]);
    }

    public static function facilities()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $partnerFacilities = FacilityDetail::where('partner_idx', $data['selectedPartnerIdx'])
            ->orderBy('created_at', 'ASC')
            ->get();

        $data['partnerFacilities'] = $partnerFacilities;

        self::render('manage/partner-facilities', ['data' => $data]);
    }

    public static function facilitiesCreate()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/partner-facilities-create', ['data' => $data]);
    }

    public static function facilitiesEdit()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $sql = "
            SELECT
                fd.*,
                (
                    SELECT CONCAT('[', GROUP_CONCAT(
                        JSON_OBJECT(
                            'image_origin_name', img.image_origin_name,
                            'image_origin_path', img.image_origin_path,
                            'image_origin_size', img.image_origin_size
                        ) ORDER BY img.image_order ASC SEPARATOR ','), ']')
                    FROM moongcletrip.facility_images img
                    WHERE img.facility_detail_idx = fd.facility_detail_idx
                ) AS images
            FROM facility_detail fd
            WHERE fd.facility_detail_idx = :facilityDetailIdx
        ";

        $bindings = [
            'facilityDetailIdx' => $_GET['facilityDetailIdx']
        ];

        $partnerFacility = Database::getInstance()->getConnection()->select($sql, $bindings);

        $data['partnerFacility'] = $partnerFacility[0];

        self::render('manage/partner-facilities-edit', ['data' => $data]);
    }

    public static function service()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $partnerServices = ServiceDetail::where('partner_idx', $data['selectedPartnerIdx'])
            ->orderBy('created_at', 'ASC')
            ->get();

        $data['partnerServices'] = $partnerServices;

        self::render('manage/partner-service', ['data' => $data]);
    }

    public static function serviceCreate()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/partner-service-create', ['data' => $data]);
    }

    public static function serviceEdit()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $partnerService = ServiceDetail::where('service_detail_idx', $_GET['serviceDetailIdx'])
            ->first();

        $data['partnerService'] = $partnerService;

        self::render('manage/partner-service-edit', ['data' => $data]);
    }

    public static function social()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/partner-social', ['data' => $data]);
    }

    public static function socialCreate()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/partner-social-create', ['data' => $data]);
    }

    public static function socialEdit()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/partner-social-edit', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
