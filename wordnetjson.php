<?
$server = 'https://deb.fi.muni.cz:9001/';
$user = 'test';
$password = 'test';

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl, CURLOPT_USERPWD, $user . ":" . $password);

#initialize, what dictionaries I have access to?
$url = $server.'doc?action=connect';
curl_setopt($curl, CURLOPT_URL, $url);
$result = curl_exec($curl);

$dicts = json_decode($result, true);
foreach($dicts['dicts'] as $dict_code => $dict_info) {
        #dictionary code + dictionary url
        $dict_url = $dict_info['code'];
        echo $dict_code.':'.$dict_url."\n";

        #let's search for dogs
        $url = $server.$dict_url.'?action=queryList&word=bottle';
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        $result_hash = json_decode($result, true);

        #and let's get XML for first three
        for($i = 0; $i < 3; $i++) {
                $id = $result_hash[$i]['value'];
                echo 'Getting XML for '.$result_hash[$i]['label']." with ID=$id\n";
                $url = $server.$dict_url.'?action=runQuery&query='.$id.'&outtype=plain';
                curl_setopt($curl, CURLOPT_URL, $url);
                $result = curl_exec($curl);
                $xml = json_decode($result);
                echo $xml."\n";
        }
}
?>
