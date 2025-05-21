<?php

define( "API_TH", "MjyzWrB6wkzPoipFeubsMzeE1sCePxL%2frjWVKZQsBeZFAwSYdKTyByDHdCzBXWSODS%2flAUJPQkCdFWpRg91uYvoXx3V7iRphyo9nYYeRTkPT3KdD6f%2fKwxNboao2ZtuH" );
function ceo_get_gifts($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=dp_gifts&params=@donor_id=9",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);

        $gifts = json_decode($json,TRUE);
        if($gifts && isset($gifts['record'])) {
            return $gifts['record'];
        } else {
            return [];
        }

    }

}

//Get if the donor is a member
function ceo_is_member($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27MEM%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27MEM%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);

        $dp_other = json_decode($json,TRUE);

        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

//Get if the donor is a spouse
function ceo_is_spouse($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27SP%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27SP%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);

        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

//It is a pathway 2021
function ceo_is_pathway_2021($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=Select%20%2A%20from%20dpusermultivalues%20%20where%20field_name%20=%20%20%27PATHWAYS_2021%27%20AND%20matching_id%20=%20$donor_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 05116212-3a19-4ff0-ba81-3c04c6141c25",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_pathways = json_decode($json,TRUE);


        foreach ($dp_pathways as $a) {
            foreach ($a as $b) {
                foreach ($b as $c) {
                    foreach ($c as $d) {
                        if (isset($d['value'])) {
                            if ( $d['value'] == 'A' ) {
                                return 1;
                            }
                        } else {
                            foreach ($d as $e) {
                                if (is_array($e)) {
                                    if (isset($e['value'])) {
                                        if ( $e['value'] == 'A' ) {
                                            return 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return 0;
    }

}

//It is a sli discipler
function ceo_is_sli_discipler($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27SLID%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27SLID%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);
        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

function ceo_is_staff($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27ST%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27ST%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);
        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

function ceo_otherinfo_from_dp_ministry_partner($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27MP%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.relationship_type%20=%20%27MP%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);
        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

function ceo_otherinfo_from_dp_slif_2024($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLIF%27%20AND%20%0A%09o.program_year%20=%20%272024%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLIF%27%20AND%20%0A%09o.program_year%20=%20%272024%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);
        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

function ceo_otherinfo_from_dp_sli_2024($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLI%27%20AND%20%0A%09o.program_year%20=%20%272024%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLI%27%20AND%20%0A%09o.program_year%20=%20%272024%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);
        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

function ceo_otherinfo_from_dp_sli_2022($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLI%27%20AND%20%0A%09o.program_year%20=%20%272022%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLI%27%20AND%20%0A%09o.program_year%20=%20%272022%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);
        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

function ceo_otherinfo_from_dp_sling_2021($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLING%27%20AND%20%0A%09o.program_year%20=%20%272021%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLING%27%20AND%20%0A%09o.program_year%20=%20%272021%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);
        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}

function ceo_otherinfo_from_dp_sling_2025($donor_id){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=SELECT%20o.%2A%0A%09FROM%20dpotherinfo%20a%0A%09INNER%20JOIN%20dpotherinfoudf%20o%0A%09ON%20a.other_id%20=%20o.other_id%0A%09WHERE%20%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLING%27%20AND%20%0A%09o.program_year%20=%20%272025%27%20AND%20%20o.r_end_date%20IS%20NULL%29%0A%09OR%0A%09%28a.donor_id%20=%20$donor_id%20AND%0A%09o.program_name%20=%20%27SLING%27%20AND%20%0A%09o.program_year%20=%20%272025%27%20AND%20%20o.r_end_date%20%3E%20GETDATE%28%29%29",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 0e337cf4-2890-47e2-8239-a1c4e7f5d6a3",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_other = json_decode($json,TRUE);
        if ( $dp_other ) {
            return 1;
        } else {
            return 0;
        }
    }
}



function ceo_get_profile($donor_id){
    $curl = curl_init();
    $url = "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=Select%20%2A%20from%20dp%20d%20inner%20join%20dpudf%20u%20on%20d.donor_id%20=%20u.donor_id%20where%20d.donor_id%20=%20" . intval($donor_id);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 8b6267f1-6398-488a-bc56-f1c049b51d88",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {

        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $dp_profile = json_decode($json, TRUE);


        if( isset($dp_profile['record']) ){
            return $dp_profile['record'];
        }else{
            return null;
        }

    }
}

function ceo_format_currency_no_decimals($amount, $symbol = '$') {
    return $symbol . number_format((float) $amount, 0);
}

function ceo_update_user_roles($wp_user_id) {

    $donor_id = get_user_meta($wp_user_id, 'user_registration_donor_id', true);

    if(!$donor_id){
        ceo_log_message('User with wp id#. ' . $wp_user_id . '  was not updated, donor id is not set.');
        return;
    }

    $profile = ceo_get_profile($donor_id);

    if(empty($profile)){
        ceo_log_message('User with wp id#. ' . $wp_user_id . '  was not updated, donor profile could not be fetched.');
        return;
    }

    //Save all the profile  field inside of the $data array
    $data = [];
    if(isset($profile['field'])) {
        foreach ($profile['field'] as $field) {
            $attr = $field['@attributes'];
            $name = isset($attr['name']) ? $attr['name'] : '';
            $value = isset($attr['value']) ? $attr['value'] : '';
            if($name && in_array($name, array('donor_id', 'first_name', 'last_name', 'email', 'EMPLOYER', 'city', 'state', 'SLI_COHORT', 'SLI_VERSION', 'AREA', 'AREA_2', 'MEMBER_YN', 'GENDER_MAIN', 'OPT_IN_DIR'))){
                $data[$name] = $value;
            }
        }
    }

    $disciple = $data['sli_discipliner'] = ceo_is_sli_discipler($donor_id);
    $staff = $data['is_staff'] = ceo_is_staff($donor_id);
    $spouse = $data['is_spouse'] = ceo_is_spouse($donor_id);
    $ministry_partner = $data['is_partner'] = ceo_otherinfo_from_dp_ministry_partner($donor_id);
    $sli_2022 = $data['is_sli_2022'] = ceo_otherinfo_from_dp_sli_2022($donor_id);
    $sli_2024 = $data['is_sli_2024'] = ceo_otherinfo_from_dp_sli_2024($donor_id);
    $slif_2024 = $data['is_slif_2024'] = ceo_otherinfo_from_dp_slif_2024($donor_id);
    $sling_2021 = $data['is_sling_2021'] = ceo_otherinfo_from_dp_sling_2021($donor_id);
    $sling_2025 = $data['is_sling_2025'] = ceo_otherinfo_from_dp_sling_2025($donor_id);
    $member = $data['is_member'] = ceo_is_member($donor_id);
    $pathway = $data['pathway'] = ceo_is_pathway_2021($donor_id);

    $dis_name = $data['first_name'] . " " . $data['last_name'];

   // wp_update_user( array( 'ID' => $wp_user_id, 'display_name' => $dis_name ) );

    $u = new WP_User( $wp_user_id );

    $area_codes = array( 'ATL' => 'atlanta', 'CHI' => 'chicago', 'DEN' => 'denver', 'DFW' => 'dfw', 'HOU' => 'houston',
        'PHX' => 'phoenix', 'SJ' => 'san_jose', 'STL' => 'st_louis', 'DC' => 'washington_dc',
        'MEM' => 'member', 'GR' => 'grand_rapids', 'AUSTIN' => 'austin', 'BV' => 'bentonville', 'BOS' => 'boston',
        'NYC' => 'new_york_city', 'NE' => 'northeast', 'CFL' => 'orlando', 'CIN' => 'cincinnati',
        'ATL_2' => 'atlanta', 'CHI_2' => 'chicago', 'DEN_2' => 'denver', 'DFW_2' => 'dfw', 'HOU_2' => 'houston',
        'PHX_2' => 'phoenix', 'SJ_2' => 'san_jose', 'STL_2' => 'st_louis', 'DC_2' => 'washington_dc',
        'AUSTIN_2' => 'austin', 'BV_2' => 'bentonville', 'BOS_2' => 'boston', 'NYC_2' => 'new_york_city',
        'NE_2' => 'northeast', 'CFL_2' => 'orlando', 'CIN_2' => 'cincinnati', 'GR_2' => 'grand_rapids', 'CHAR' => 'charlotte',
        'CHAR_2' => 'charlotte', 'NASH' => 'nashville', 'NASH_2' => 'nashville');

    $area_list = array('atlanta','chicago','denver','dfw','houston','phoenix','san_jose','st_louis','washington_dc','member',
        'female_executive', 'austin', 'grand_rapids', 'bentonville', 'boston', 'new_york_city', 'northeast', 'orlando', 'cincinnati', 'charlotte','nashville');

    foreach ($area_list as $a) {
        $u->remove_role( $a );
    }

    if (isset($data['SLI_VERSION'] ) && $data['SLI_VERSION'] == 'SLIG') {
        $u->add_role( 'sli_graduate' );
    } else {
        $u->remove_role( 'sli_graduate' );
    }



    if ( $pathway === 1 && $data['area'] != '' && $member == 1 ) {
        $u->add_role( strtolower($area_codes[$data['area']]) );
    }

    if ( $pathway === 1 && $data['AREA_2'] != '' && $member == 1 ) {
        $u->add_role( strtolower($area_codes[$data['AREA_2']]) );
    }

    if ( $data['GENDER_MAIN'] == 'F' && $member == 1 ) {
        $u->add_role( 'female_executive' );
    }

    if ( $disciple === 1 ) {
        $u->add_role( 'sli_discipler' );
    } else {
        $u->remove_role( 'sli_discipler' );
    }

    if ( $staff === 1 ) {
        $u->add_role( 'staff' );
    } else {
        $u->remove_role( 'staff' );
    }

    if ( $spouse === 1 ) {
        $u->add_role( 'spouse' );
    } else {
        $u->remove_role( 'spouse' );
    }

    if ( $ministry_partner === 1 ) {
        $u->add_role( 'ministry_partner' );
    } else {
        $u->remove_role( 'ministry_partner' );
    }

    if ( $sli_2022 === 1 ) {
        $u->add_role( 'sli_2022' );
    } else {
        $u->remove_role( 'sli_2022' );
    }

    if ( $sli_2024 === 1 ) {
        $u->add_role( 'sli_2024' );
    } else {
        $u->remove_role( 'sli_2024' );
    }

    if ( $slif_2024 === 1 ) {
        $u->add_role( 'slif_2024' );
    } else {
        $u->remove_role( 'slif_2024' );
    }

    if ( $sling_2021 === 1 ) {
        $u->add_role( 'sling_2021' );
    } else {
        $u->remove_role( 'sling_2021' );
    }

    if ( $sling_2025 === 1 ) {
        $u->add_role( 'sling_2025' );
    } else {
        $u->remove_role( 'sling_2025' );
    }

    if ( $member === 1 ) {
        $u->add_role( 'member' );
    } else {
        $u->remove_role( 'inactive_member' );
    }

    /*if ( ! email_exists($data['email'])) {
        wp_update_user( array( 'ID' => $wp_user_id, 'user_email' => $data['email']) );
    }*/

    /*update_user_meta($wp_user_id, 'first_name', $data['first_name']);
    update_user_meta($wp_user_id, 'last_name', $data['last_name']);*/
    update_user_meta($wp_user_id, 'user_registration_business_name', $data['EMPLOYER']);
    update_user_meta($wp_user_id, 'user_registration_city', $data['city']);
    update_user_meta($wp_user_id, 'user_registration_state', $data['state']);

    if(!empty($data['OPT_IN_DIR'])){
        update_user_meta($wp_user_id, 'user_registration_optin',$data['OPT_IN_DIR']);
        $upt_id = get_user_meta($wp_user_id, '_upt_post_id', true);
        update_post_meta(intval($upt_id), 'meta-user_registration_optin', $data['OPT_IN_DIR']);
    }


    ceo_log_message('User <' . $data['email'] . '> with donor id # . ' .$donor_id . '  updated, data => ' .serialize($data));

    //update_dp_record_update ($donor_id);
}

function ceo_update_optin($donor_id, $optin_status = 'N'){
    $curl = curl_init();
    $url = "https://www.donorperfect.net/prod/xmlrequest.asp?apikey=".API_TH."&action=dp_save_udf_xml&params=@matching_id=$donor_id,@field_name=%27OPT_IN_DIR%27,@data_type=%27C%27,@char_value=%27$optin_status%27,@date_value=null,@number_value=null";
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Postman-Token: 2748c04e-7f5a-4bcd-ae05-c060e9519172",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    /*if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }*/
}




/**
 * Write a message to the plugin's custom log file.
 *
 * @param string $message  The log message.
 */
function ceo_log_message( $message ) {
    // Define the log file path relative to the plugin
    $log_dir  = CEO_PLUGIN_PATH . '/log/';
    $log_file = $log_dir . 'custom.log';

    // Create the log directory if it doesn't exist
    if ( ! file_exists( $log_dir ) ) {
        wp_mkdir_p( $log_dir );
    }

    // Format the message with a timestamp
    $timestamp = date( 'Y-m-d H:i:s' );
    $formatted_message = "[{$timestamp}] " . print_r( $message, true ) . PHP_EOL;

    // Write the message to the log file
    file_put_contents( $log_file, $formatted_message, FILE_APPEND );
}





