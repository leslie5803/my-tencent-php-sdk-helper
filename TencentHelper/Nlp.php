<?php

namespace TencentHelper;

use TencentCloud\Nlp\V20190408\Models\{
    AutoSummarizationRequest,
    KeywordsExtractionRequest,
    TextCorrectionRequest
};

/**
 * NLP
 *
 * @date       2021-12-28 16:09:46
 */
class Nlp extends AbstractNlp
{
    /**
     * 文本纠错
     *
     * @param [type] $text
     *
     * @return string
     * @date       2021-12-28 16:09:57
     */
    public function aiCheckCorrection($text): string
    {
        $req = new TextCorrectionRequest();
        $params = [
            'Text' => $text
        ];

        $req->fromJsonString(json_encode($params));
        $resp = $this->client->TextCorrection($req);

        return $resp->toJsonString();
    }

    /**
     * 摘要提取
     *
     * @param [type] $text
     * @param int $length
     *
     * @return string
     * @date       2021-12-28 16:10:22
     */
    public function aiSummary($text, $length = 200): string
    {
        $req = new AutoSummarizationRequest();
    
        $params = array(
            "Text" => $text,
            "Length" => $length
        );
        $req->fromJsonString(json_encode($params));

        $resp = $this->client->AutoSummarization($req);

        return $resp->toJsonString();
    }

    /**
     * 关键词提取
     *
     * @param [type] $text
     * @param int $num
     *
     * @return string
     * @date       2021-12-28 16:10:40
     */
    public function aiKeywords($text, $num = 5): string
    {
        $req = new KeywordsExtractionRequest();

        $params = [
            'Text' => $text,
            'Num' => $num
        ];
        
        $req->fromJsonString(json_encode($params));
        $resp = $this->client->KeywordsExtraction($req);

        return $resp->toJsonString();
    }
}
