@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="/book.png">


  <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.22/datatables.min.css"/>
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.22/datatables.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Akaya+Telivigala&display=swap" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
mark{
background: white;
color: black;
}
.box{
    width:1200px;
    margin-top:10%;
    
   }

body {
    font-size: 14px;
    line-height: 1.42857143;
    color: #d9edf7;
    background-color: black;
    }
.btn-primary {
    color: black;
    background-color: #e8e6e6;
    border-color: #999;
}

  li {
    float: right;
  }
  li a {
    color: black;
    display: block;
    padding: 8px;
  }
    
.dataTables_wrapper .dataTables_length select {
    border: 1px solid #aaa;
    border-radius: 3px;
    padding: 5px;
    background-color: white;
    color: black;
    padding: 4px;
}
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
    background-color: #292c2f;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
    box-sizing: border-box;
    display: inline-block;
    min-width: 1.5em;
    padding: 0.5em 1em;
    margin-left: 2px;
    text-align: center;
    text-decoration: none !important;
    cursor: pointer;
    cursor: hand;
    color: black;
    background-color: white;
    border: 1px solid transparent;
    border-radius: 2px;
}
.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
      color: #d9edf7;
    }
    .dataTables_wrapper .dataTables_filter input {
    border: black;
    border-radius: 3px;
    padding: 5px;
    background-color: black;
    color: black
    margin-left: 3px;
}
</style>
</head>

<body>


<div class="container box">
<form action="/rp" method="POST">
    {{ csrf_field() }}
    <div class="input-group" style="margin:20px;">
        <input type="text" class="form-control" name="p"  id='speechText'
            placeholder="Search"> <span class="input-group-btn">
            <div class="form-group" style="margin-left:20px;">
                <input type="submit" name="Submit" class="btn btn-primary" value="Submit" style="font-weight:bold" />
                </form>  
                </div> 
    </div>

  </div>
  
</body>

<div class="container box">
<?php
      require '\xampp\htdocs\webproject\vendor\autoload.php';
      $p = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($query_string))))));
      $client = Elastic\Elasticsearch\ClientBuilder::create()->build();
      // $wd = strip_tags($_POST['p']);
      $params = [
        'index' =>'id_kib1',
        'body' => [
          'query' => [
            'bool' => [
              'must' => [
                'multi_match' => [
                  'query' => $p,
                  'fields' => ['author','$year','university','degree','program','abstract','title','advisor','wiki_terms']
                ]
              ]
            ]
          ],
          'size' => 1000
        ]
      ];

      $response = $client->search($params);
      $total = $response['hits']['total']['value'];
      if ($total == 0){
            echo "No Results found";
        }
        else{
        ($score = $response['hits']['hits'][0]['_score']);
 
  if ($total == 0){
    echo'<div style="text-align:center;" class="alert alert-danger success-block">';
    echo '<p class="head">No Results Found..!</p>';
  }

  else{
    $score = $response['hits']['hits'][0]['_score'];
    echo
    "<div>  
    <h3><b><i>$total search results for $p</b></i><h3>
    </div>";
    echo 
    '<table class="table table-stripped" id="dt1">
    <thead>
    <th>Title</th>
    <th></th>
    </thead>
    <tbody>';
    foreach( $response['hits']['hits'] as $source){
        $etd_file_id = (isset($source['_source']['etd_file_id'])? $source['_source']['etd_file_id'] : "");
        $year= (isset($source['_source']['year'])? $source['_source']['year'] : "");
        $author= (isset($source['_source']['author'])? $source['_source']['author'] : "");
        $university = (isset($source['_source']['university']) ? $source['_source']['university']: "");
        $degree = (isset($source['_source']['degree']) ? $source['_source']['degree'] : "");
        $program = (isset($source['_source']['program']) ? $source['_source']['program'] : ""); 
        $abstract = (isset($source['_source']['abstract']) ?  $source['_source']['abstract'] : ""); 
        $title = (isset($source['_source']['title']) ? $source['_source']['title'] : ""); 
        $advisor = (isset($source['_source']['advisor']) ? $source['_source']['advisor'] : ""); 
        $pdf = (isset($source['_source']['pdf']) ? $source['_source']['pdf'] : ""); 
        $wiki_terms = (isset($source['_source']['wiki_terms']) ? $source['_source']['wiki_terms'] : "");
    
    echo "<tr>
      <td>
      <br>
      <br>
      <a href=details/$etd_file_id><b>Title:</b> ".$title." </a><br><br>
      <b>Author(s):</b> ".$author." <br>
      <b>University:</b> ".$university." <br>
      <b>Year:</b> ".$year." <br>
      <b>id:</b> ".$etd_file_id."
      <br>
      <form method='GET' action='/download'>
        <input type='hidden' name='q' value='".$etd_file_id."' />
        <td></td>
      </form>
      </td>";
    }
    ?>
      </form>
      </td>

    

    <?php
      echo"</tr>";
      
      }
      echo "</tbody></table>";
    
    }
    ?>
       
</div>


<script src="https://cdn.jsdelivr.net/mark.js/7.0.0/jquery.mark.min.js"></script>
<script>
$(document).ready( function () {
  var table = $('#dt1').DataTable( {
    "initComplete": function( settings, json ) {
    $("body").unmark().mark("{{$query_string}}"); 
    }
  });
  table.on( 'draw.dt', function () {
    $("body").unmark().mark("{{$query_string}}");
  }); 
} );
</script>
@endsection
    
    