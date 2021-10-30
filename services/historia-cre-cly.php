<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
$docuCage = $_GET['docu-cage'];

if(!empty($docuCage)) {

    $data = <<<'HEY'
    {
        "error": false,
        "errorMessage":    {
            "1": "",
            "2": ""
        },
        "errorCode": 0,
        "result":    
        [
            {
                "credNumero": "100117521",
                "credFecDesem": "23/09/2011",
                "credMontoDesem": "20,000.00",
                "crediMoneda": "$US",
                "crediSaldo": "0.00",
                "crediEstado": "CANCELADO",
                "crediFecCancel": "10/06/2015"
            },
            {
                "credNumero": "130118038",
                "credFecDesem": "30/09/2013",
                "credMontoDesem": "65,000.00",
                "crediMoneda": "Bs",
                "crediSaldo": "0.00",
                "crediEstado": "CANCELADO",
                "crediFecCancel": "10/06/2015"
            },
            {
                "credNumero": "131490",
                "credFecDesem": "10/06/2015",
                "credMontoDesem": "223,000.00",
                "crediMoneda": "Bs",
                "crediSaldo": "0.00",
                "crediEstado": "CANCELADO",
                "crediFecCancel": "11/08/2017"
            },
            {
                "credNumero": "131665",
                "credFecDesem": "18/11/2016",
                "credMontoDesem": "65,000.00",
                "crediMoneda": "Bs",
                "crediSaldo": "0.00",
                "crediEstado": "CANCELADO",
                "crediFecCancel": "28/09/2018"
            },
            {
                "credNumero": "131729",
                "credFecDesem": "11/08/2017",
                "credMontoDesem": "365,000.00",
                "crediMoneda": "Bs",
                "crediSaldo": "239,014.67",
                "crediEstado": "VIGENTE",
                "crediFecCancel": "8/06/2029"
            }
        ]
    }
    HEY;
    
    $dataArray = json_decode($data);
    echo json_encode($dataArray);

} else {
    
    $data = array("status" => 0, "msg" => 'ID usuario no definido');

    echo json_encode($data);
}

?>