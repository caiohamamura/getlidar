<?php
include 'query.conf.php';

try {
if(!@($conexao=pg_connect ("host=apoema.esalq.usp.br dbname=modelo port=5432 user=$usr password=$pwd"))) {
        print 'Não foi possível estabelecer uma conexão com o banco de dados.';
        die;
}
$results = pg_query("SELECT geom FROM talhao_geo");
$num_fields = pg_num_fields($results);
for ($i = 0; $i < $num_fields; $i++) {
    $f = pg_field_type($results, $i);
    echo json_encode($f);
}
pg_close();
die;

$final = array();
$final['type'] = 'FeatureCollection';
$final['features'] = array();
while ($row = pg_fetch_row($results)) {
    $results2 = pg_query("SELECT ST_asGeoJson('$row[0]')");
    $row2 = pg_fetch_row($results2);
    $feat = array();
    $feat['type'] = 'Feature';
    $feat['geometry'] = json_decode($row2[0]);
    $final['features'][] = $feat;
}
header("Content-type: application/json");

$out=json_encode($final);
echo $out;
} finally {
pg_close();
}
?>