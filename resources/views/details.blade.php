@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Digital library</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    </head>

    <body class="antialiased">
        <h1 style="background-color:yellow" class="text-center">Digital library</h1>
    {{ csrf_field() }}
    </body>

</html>

      <?php
      require '\xampp\htdocs\webproject\vendor\autoload.php';
      $client = Elastic\Elasticsearch\ClientBuilder::create()->build();
      $params = [
        'index' =>'id_kib',
        'body' => [
          'query' => [
            'bool' => [
              'must' => [
                'multi_match' => [
                  'query' => $id,
                  'fields' => ['etd_file_id']
                ]
              ]
            ]
          ],
          'size' => 1000
        ]
      ];
 
  $response = $client->search($params);


    
    foreach( $response['hits']['hits'] as $source){
      $year= (isset($source['_source']['year'])? ($source['_source']['year']) : "");  
      $etd_file_id= (isset($source['_source']['etd_file_id'])? ($source['_source']['etd_file_id']) : "");
      $author= (isset($source['_source']['author'])? ($source['_source']['author']) : "");
      $university = (isset($source['_source']['university']) ? ( $source['_source']['university']) : "");
      $degree = (isset($source['_source']['degree']) ? ($source['_source']['degree'] ) : "");
      $program = (isset($source['_source']['program']) ? ($source['_source']['program']) : ""); 
      $abstract = (isset($source['_source']['abstract']) ?  ($source['_source']['abstract']) : ""); 
      $title = (isset($source['_source']['title']) ? ($source['_source']['title']) : ""); 
      $advisor = (isset($source['_source']['advisor']) ?  ($source['_source']['advisor']) : ""); 
      $pdf = (isset($source['_source']['pdf']) ? ( $source['_source']['pdf']) : ""); 
      $wiki_terms = (isset($source['_source']['wiki_terms']) ? ($source['_source']['wiki_terms']) : ""); 
      echo "<tr>
      <td>
      <b>Etd_file_id:</b> ".$etd_file_id." </a><br><br>
      <b>Year(s):</b> ".$year." <br><br>
      <b>Author:</b> ".$author." <br><br>
      <b>University:</b> ".$university." <br><br>
      <b>Degree:</b> ".$degree." <br><br>
      <b>Program:</b> ".$program."<br><br>
      <b>Abstract:</b> ".$abstract." <br><br>
      <b>Title:</b> ".$title."<br><br>
      <b>PDF:</b> ".$pdf."<br><br>
      <a href = '/viewp/".$pdf."' target = '_blank'><b>Pdf:</b>Click Here To View The PDF<br><br></a>
      </td>
      </form>
      </td>";
    
      echo"</tr>";
  }
      echo "</tbody></table>";
    ?>
@endsection
