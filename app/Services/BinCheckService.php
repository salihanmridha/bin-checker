<?php

namespace App\Services;
use App\Models\Bin;
use App\Services\Scrapper;

class BinCheckService
{
    private Scrapper $scrapper;
    private $url = "https://bincheck.io/details/";
    public function __construct(Scrapper $scrapper)
    {
        $this->scrapper = $scrapper;
    }

    /**
     * @param string $number
     *
     * @return null
     */
    public function checkBin(string $number): ?Bin
    {
        $bin = Bin::firstWhere("bin_iin", $number);

        if ($bin){
            return $bin->makeHidden(["created_at", "updated_at", "id"]);
        }

        $getBinInfoByHtml = $this->getBinInfoByHtml($number);

        if (count($getBinInfoByHtml) > 0){
            return $this->storeBinInfo($getBinInfoByHtml)->makeHidden(["created_at", "updated_at", "id"]);
        }

        return null;
    }

    public function getBinInfoByHtml(string $number): array {
        $url = $this->url . $number;

        $getFullHtml = $this->scrapper->phpScrapperByGetMethod($url);

        if ($getFullHtml != null){
            return $this->scrapper->getBinInfoFromHtml($getFullHtml);
        }

        return [];
    }

    public function storeBinInfo(array $getBinInfoByHtml): Bin
    {
        return Bin::create([
            "bin_iin" => $getBinInfoByHtml["bin_iin"],
            "card_brand" => $getBinInfoByHtml["card_brand"],
            "card_type" => $getBinInfoByHtml["card_type"],
            "card_level" => $getBinInfoByHtml["card_level"],
            "issuer_name_bank" => $getBinInfoByHtml["issuer_name_bank"],
            "issuer_bank_website" => $getBinInfoByHtml["issuer_bank_website"],
            "issuer_bank_phone" => $getBinInfoByHtml["issuer_bank_phone"],
            "iso_country_name" => $getBinInfoByHtml["iso_country_name"],
            "iso_country_code" => $getBinInfoByHtml["iso_country_code_a2"],
        ]);
    }
}
