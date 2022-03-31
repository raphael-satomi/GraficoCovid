<?php
    $param = json_decode($_POST['param']);
    $param = explode("#", $param);

    switch ($param[0]){
        case 1:
            countries();
            break;
        case 2:
            select_country($param[1]);
            break;
        case 3:
            data_countries();
            break;
        case 4:
            grafico($param[1]);
            break;
        default:
            print_r($param);
            echo "errp";
            break;
    }

    function countries(){
        $url = 'https://covid-193.p.rapidapi.com/countries';
        $options = array(
            'http' => array(
                'method'  => 'GET',
                'header'  => "Content-Type: application/json\r\n".
                            "x-rapidapi-host: covid-193.p.rapidapi.com\r\n".
                            "x-rapidapi-key: 2a4a2c70f1mshdc1b952fb832919p12b64ajsn95f193253864\r\n",
            )
        );
        $context  = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
    
        print_r($response);
    }

    function select_country($country){
        $url = 'https://covid-193.p.rapidapi.com/statistics?country='.$country;
        $options = array(
            'http' => array(
                'method'  => 'GET',
                'header'  => "Content-Type: application/json\r\n".
                            "x-rapidapi-host: covid-193.p.rapidapi.com\r\n".
                            "x-rapidapi-key: 2a4a2c70f1mshdc1b952fb832919p12b64ajsn95f193253864\r\n",
            )
        );
        $context  = stream_context_create($options);                                                     
        $response = @file_get_contents($url, false, $context);  
        $json = '{
            "andorra" : "AD",
            "uae" : "AE",
            "afghanistan" : "AF",
            "antigua-and-barbuda" : "AG",
            "anguilla" : "AI",
            "albania" : "AL",
            "armenia" : "AM",
            "netherlands" : "NL",
            "angola" : "AO",
            "antartica" : "AQ",
            "argentina" : "AR",
            "austria" : "AT",
            "australia" : "AU",
            "aruba" : "AW",
            "barbados" : "BB",
            "bangladesh" : "BD",
            "belgium" : "BE",
            "bulgaria" : "BG",
            "bermuda" : "BM",
            "bolivia" : "BO",
            "brazil" : "BR",
            "bahamas" : "BS",
            "bhutan" : "BT",
            "bostwana" : "BW",
            "belarus" : "BY",
            "belize" : "BZ",
            "canada" : "CA",
            "congo" : "CG",
            "switzerland" : "CH",
            "chile" : "CL",
            "cameroon" : "CM",
            "china" : "CN",
            "colombia" : "CO",
            "costa-rica" : "CR",
            "cuba" : "CU",
            "germany" : "DE",
            "denmark" : "DM",
            "dominican-republic" : "DO",
            "algeria" : "DZ",
            "ecuador" : "EC",
            "thailand" : "TO",
            "usa" : "US",
            "uruguay" : "UY",
            "ukraine" : "UA",
            "uganda" : "UG",
            "chad" : "TD",
            "senegal" : "SN",
            "somalia" : "SO",
            "slovenia" : "SL",
            "slovakia" : "SK",
            "sweden" : "SE",
            "singapore" : "SG",
            "sudan" : "SD",
            "qatar" : "QA",
            "paraguay" : "PY",
            "spain" : "ES",
            "portugal" : "PT",
            "russia" : "RU",
            "france" : "FR",
            "india" : "IN"
        }';

        //print_r($response);
        print_r($json." | ".$response);
    }

    function data_countries(){
        $url = 'https://covid-193.p.rapidapi.com/statistics';
        $options = array(
            'http' => array(
                'method'  => 'GET',
                'header'  => "Content-Type: application/json\r\n".
                            "x-rapidapi-host: covid-193.p.rapidapi.com\r\n".
                            "x-rapidapi-key: 2a4a2c70f1mshdc1b952fb832919p12b64ajsn95f193253864\r\n",
            )
        );
        $context  = stream_context_create($options);                                                     
        $response = @file_get_contents($url, false, $context);  
        $json_array = json_decode($response);

        $array_countries = array();
        $array_continents = array();
        for($i = 0; $i < sizeof($json_array->response); $i++){
            if($json_array->response[$i]->continent !== $json_array->response[$i]->country){
                array_push($array_countries, $json_array->response[$i]);
            }else{
                array_push($array_continents, $json_array->response[$i]);
            }
        }
        $json_array->response = $array_countries;
        print_r(json_encode($json_array));
    }

    function grafico($country){
        $url = 'https://covid-193.p.rapidapi.com/history?country='.$country;
        $options = array(
            'http' => array(
                'method'  => 'GET',
                'header'  => "Content-Type: application/json\r\n".
                            "x-rapidapi-host: covid-193.p.rapidapi.com\r\n".
                            "x-rapidapi-key: 2a4a2c70f1mshdc1b952fb832919p12b64ajsn95f193253864\r\n",
            )
        );
        $context  = stream_context_create($options);                                                     
        $response = @file_get_contents($url, false, $context);
        print_r($response);
    }