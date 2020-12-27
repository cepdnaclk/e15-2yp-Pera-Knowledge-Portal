<?php

class communication
{

    private $authUser = "";
    private $authPass = "";
    private $manageServer = "localhost:8080/rest/users/";
    private $searchServer = "localhost:8080/rest/search_engine/";

    public function __construct()
    {
        // Nothing to do in here yet
    }

    public function insertDocument($docId)
    {
        $endPoint = $this->manageServer . "insert/" . $docId;
        $resp = $this->getRequest($endPoint);

        return $resp; //json_encode($resp);
    }

    public function viewDocument($docId)
    {
        $endPoint = $this->manageServer . "view/$docId";
        return $this->getRequest($endPoint);
    }

    public function updateDocument($docId, $field, $value)
    {

        $endPoint = $this->manageServer . "view/$docId/$field/$value";
        return $this->getRequest($endPoint);
    }

    public function deleteDocument($docId)
    {

        $endPoint = $this->manageServer . "delete/$docId";
        return $this->getRequest($endPoint);
    }


    public function searchDocument($keyword)
    {

        // http://localhost:8080/rest/search_engine/search/Nuwan

        $keyword = str_replace(" ", "%20", $keyword);
        $endPoint = $this->searchServer . "searchAll/$keyword";

        //echo $endPoint."<br>";

        $response = $this->getRequest($endPoint);

        $resAr = json_decode($response, true);
        //if(isset($resAr['status']))

        return json_decode($response, true);
    }

    public function searchDocumentBy($option, $keyword)
    {
        $keyword = str_replace(" ", "%20", $keyword);

        if ($option == "author") {
            $endPoint = $this->searchServer . "searchByAuthor/$keyword";

        } else if ($option == "adviser") {
            $endPoint = $this->searchServer . "searchByAdviser/$keyword";

        } else if ($option == "user") {
            $endPoint = $this->searchServer . "searchByUserId/$keyword";

        } else {
            $endPoint = $this->searchServer . "searchAll/$keyword";
        }


        $response = $this->getRequest($endPoint);
        return json_decode($response, true);
    }


    public function test()
    {


        $endPoint = "localhost:9200/";
    }

    private function getRequest($endPoint)
    {
        $curl = curl_init($endPoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // true

        $resp = curl_exec($curl);
        curl_close($curl);

        return $resp;//json_decode($resp, true);
    }

    private function postRequest($endPoint, $json)
    {
        $ch = curl_init($endPoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }


}

?>


