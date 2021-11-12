<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
$docuCage = $_GET['docu-cage']; //associate code
$data = <<<'HEY'
{
    "error": false,
    "errorMessage":{
       "1": "",
       "2": ""
    },
    "errorCode": 0,
    "result":[
        {
          "certGestion": "2001",
          "certNumero": "1081",
          "certFecApert": "27-07-2001",
          "certCanti": "1",
          "certMonto": "65.30",
          "certEstado": "VIGENTE"
       },
       {
          "certGestion": "2003",
          "certNumero": "8107",
          "certFecApert": "04-04-2003",
          "certCanti": "2",
          "certMonto": "70.0",
          "certEstado": "VIGENTE"
       },
       {
          "certGestion": "2004",
          "certNumero": "8961",
          "certFecApert": "24-03-2004",
          "certCanti": "2",
          "certMonto": "80.0",
          "certEstado": "VIGENTE"
       },
       {
          "certGestion": "2005",
          "certNumero": "9876",
          "certFecApert": "23-03-2005",
          "certCanti": "2",
          "certMonto": "80.0",
          "certEstado": "VIGENTE"
       }
    ]
}
HEY;

$dataArray = json_decode($data);

echo json_encode($dataArray);
