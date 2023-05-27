<?php

namespace App\Services;

class Scrapper
{
    public function phpScrapperByPostMethod(string $url, string $inputValue, string $inputFieldName)
    {
        $request = array(
            'http' => array(
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'method' => 'POST',
                'content' => http_build_query(array(
                    $inputFieldName => $inputValue
                ))
            )
        );

        $context = stream_context_create($request);

        $html = null;

        try {
            $html = file_get_contents($url, false, $context);
        } catch (\Exception $e) {
            $html = response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "success" => false,
                "message" => "The IMO Code that you entered does not exist in our database. Please try again.",
                "data" => [],
            ]);
        }

        return $html;
    }

    /**
     * @param string $url
     *
     * @return false|string|null
     */
    public function phpScrapperByGetMethod(string $url)
    {
        $context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
        );

        $html = null;

        try {
            $html = file_get_contents($url, false, $context);
        } catch (\Exception $e) {
            $html = null;
        }

        return $html;
    }

    public function getBinInfoFromHtml(string $html)
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_use_internal_errors(false);

        $xpath = new \DOMXPath($doc);

        $data = [];

        $table1 = $xpath->query('//table')->item(0);

        $rows = $xpath->query('.//tr', $table1);

        // Define the keys manually
        $keys = [
            'bin_iin',
            'card_brand',
            'card_type',
            'card_level',
            'issuer_name_bank',
            'issuer_bank_website',
            'issuer_bank_phone',
        ];

        foreach ($rows as $index => $row) {
            $valueCell = $xpath->query('.//td[2]', $row)->item(0);
            $value = trim($valueCell->nodeValue);

            // Use the defined key for the corresponding row
            $key = $keys[$index];

            $data[$key] = $value;
        }

        $table2 = $xpath->query('//table')->item(1); // Get the second table
        $rows = $xpath->query('.//tr', $table2);

        foreach ($rows as $row) {
            $keyCell = $xpath->query('.//td[1]', $row)->item(0);
            $valueCell = $xpath->query('.//td[2]', $row)->item(0);
            $key = trim($keyCell->nodeValue);
            $key = strtolower(str_replace(' ', '_', $key));
            $value = trim($valueCell->nodeValue);
            $data[$key] = $value;
        }

        return $data;
    }

}
