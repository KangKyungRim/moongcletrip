<?php

namespace App\Controllers\Partner;

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
        $data = PartnerHelper::partnerDefaultProcess();

        $partnerFaq = PartnerFaq::where('partner_idx', $data['selectedPartnerIdx'])
            ->orderBy('faq_order', 'ASC')
            ->get();

        $data['partnerFaq'] = $partnerFaq;

        self::render('partner/partner-faq', ['data' => $data]);
    }

    public static function faqUpdate()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $partnerFaq = PartnerFaq::where('partner_idx', $data['selectedPartnerIdx'])
            ->orderBy('faq_order', 'ASC')
            ->get();

        $data['partnerFaq'] = $partnerFaq;

        self::render('partner/partner-faq-edit', ['data' => $data]);
    }

    public static function facilities()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $partnerFacilities = FacilityDetail::where('partner_idx', $data['selectedPartnerIdx'])
            ->orderBy('created_at', 'ASC')
            ->get();

        $data['partnerFacilities'] = $partnerFacilities;

        self::render('partner/partner-facilities', ['data' => $data]);
    }

    public static function facilitiesCreate()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/partner-facilities-create', ['data' => $data]);
    }

    public static function facilitiesEdit()
    {
        $data = PartnerHelper::partnerDefaultProcess();

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

        self::render('partner/partner-facilities-edit', ['data' => $data]);
    }

    public static function service()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $partnerServices = ServiceDetail::where('partner_idx', $data['selectedPartnerIdx'])
            ->orderBy('created_at', 'ASC')
            ->get();

        $data['partnerServices'] = $partnerServices;

        self::render('partner/partner-service', ['data' => $data]);
    }

    public static function serviceCreate()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/partner-service-create', ['data' => $data]);
    }

    public static function serviceEdit()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $partnerService = ServiceDetail::where('service_detail_idx', $_GET['serviceDetailIdx'])
            ->first();

        $data['partnerService'] = $partnerService;

        self::render('partner/partner-service-edit', ['data' => $data]);
    }

    public static function social()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/partner-social', ['data' => $data]);
    }

    public static function socialCreate()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/partner-social-create', ['data' => $data]);
    }

    public static function socialEdit()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/partner-social-edit', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
