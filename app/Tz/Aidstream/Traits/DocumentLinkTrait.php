<?php namespace App\Tz\Aidstream\Traits;

/**
 * Class DocumentLinkTrait
 * @package App\Tz\Aidstream\Traits
 */
trait DocumentLinkTrait
{

    /**
     * @param $request
     * @param $projectId
     * @return array
     */
    public function documentLinkJsonFormat($request, $projectId)
    {
        foreach($request as $data){
            $array[] = [
                'activity_id' => $projectId,
                'document_link' => $data
            ];
        }

        return $array;
    }

    /**
     * @param $documentLinks
     * @return array
     */
    public function dbDocumentLinkFormat($documentLinks)
    {
        $documents = [];
        foreach($documentLinks as $key => $documentLink){
            $documents['document_link'][$key] = $documentLink->document_link;
            $documents['document_link'][$key]['id'] = $documentLink->id;
        }
        return $documents;
    }
}