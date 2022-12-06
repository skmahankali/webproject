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
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    
    <style>
        .container{
            padding-bottom:80px !important
        }
    </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript">
        $(document).ready(function() {
            //
            $("#myContainer a").mouseout(function() {
                $(this).tooltip("hide");
            });

            $("#myContainer a").mouseover(function() {
                $('[data-toggle="tooltip"]').tooltip("hide");
                //console.log($(this));
                // $(this).attr("title","Chemistry is the scientific study of the properties and behavior of matter. It is a natural science that covers the elements that make up matter to the compounds made of atoms, molecules and ions: their composition, structure, properties, behavior and the changes they undergo during a reaction with other substances. Chemistry also addresses the nature of chemical bonds in chemical compounds.");
                var alink = $(this);
                if ($(alink).attr("href") != "") {
                    $.ajax({
                        url: "https://en.wikipedia.org/w/api.php",
                        data: {
                            format: "json",
                            action: "query",
                            titles: $(alink).text(),
                            prop: "extracts",
                            exintro: ""
                        },
                        dataType: 'jsonp',
                        headers: {
                            'Api-User-Agent': 'locahost/1.1 (http://locahost/MyCoolTool/; locahost@example.com) locahost/1.4'
                        },
                        success: function(data) {
                            //console.log(data)
                            var markup = data.query.pages;
                            //console.log(markup);
                            var pageId = 0;
                            if (Object.keys(markup).length > 0) {
                                pageId = Object.keys(markup)[0].replace("'", "");
                            }
                            
                            //console.log(pageid, markup);
                            var summary = data.query.pages[pageId].extract;
                            
                            //console.log(summary);
                            var i = $('<div></div>').html(summary);

                            // remove links as they will not work
                            i.find('a').each(function() { $(this).replaceWith($(this).html()); });

                            // remove any references
                            i.find('sup').remove();

                            // remove cite error
                            i.find('.mw-ext-cite-error').remove();

                            // $('#article').html($(i).find('p'));
                            //console.log("final",$(this)[0]);
                            $(alink).attr("html", true);
                            //$(this).attr("data-original-title", $(i).find('p')[1].innerHTML);
                            var paras = $(i).find('p');
                            console.log(paras);
                            if(paras.length ==1){
                                $(alink).attr("title",paras[0].innerText.substring(0,100)+"...");
                            }else{
                                $(alink).attr("title",paras[1].innerText.substring(0,100)+"...");
                            }
                           
                            // $(this).attr("title", );
                            $(alink).tooltip("show");
                        }
                    });

                    // $("#modalIframe").attr("title",$(this).attr("href"));
                    // $('#myModal').modal('show');
                }
            });

        });
    </script>

    </head>

    <style>
    .responsive-iframe {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 100%;
        }
    </style>

    <body class="antialiased">
        <h1 style="background-color:yellow" class="text-center">Digital library</h1>
    {{ csrf_field() }}
    </body>

</html>

      <?php
      function highlight_words($wd, $tt, $z)
      {

          $wd = preg_replace('#' . preg_quote($tt) . '#i', '<div class="sample"> <span style = "background-color: #90EE90;">\\0</span> <span class ="tooltipt"
          > <a href='.$z.'>'.$z.'</a></span> </div>', $wd);
          //echo ("test");
          return $wd;
      }
      require '\xampp\htdocs\webproject\vendor\autoload.php';
      $client = Elastic\Elasticsearch\ClientBuilder::create()->build();
      $params = [
        'index' =>'id_kib1',
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
      $title = (isset($source['_source']['title']) ? ($source['_source']['title']) : ""); 
      $advisor = (isset($source['_source']['advisor']) ?  ($source['_source']['advisor']) : ""); 
      $pdf = (isset($source['_source']['pdf']) ? ( $source['_source']['pdf']) : ""); 
      $wiki_terms = ((isset($source['_source']['wiki_terms']) ? ($source['_source']['wiki_terms']) : "")); 
      $abstract = (isset($source['_source']['abstract']) ?  $source['_source']['abstract'] : "");



                
      //  for( $i = 0; $i < count($ta_a); $i++)
      //  {
      //      $abstract= highlight_words($abstract,$ta_a[$i],$u_a[$i]);
      //  }
      echo "<tr>
      <td>
      
      <b>Title:</b> ".$title."<br><br>
      <b>Etd_file_id:</b> ".$etd_file_id." </a><br><br>
      <b>Year(s):</b> ".$year." <br><br>
      <b>Author:</b> ".$author." <br><br>
      <b>University:</b> ".$university." <br><br>
      <b>Degree:</b> ".$degree." <br><br>
      <b>Program:</b> ".$program."<br><br>
      <b>PDF:</b> ".$pdf."<br><br>
      <a href = '/viewp/".$pdf."' target = '_blank'><b>Pdf:</b>Click Here To View The PDF<br><br></a>
      </td>
      </form>
      </td>";
 
      $rg = json_decode($wiki_terms, true);
      foreach ($rg as $item)
      {
         //  $ta_a[]= $item['term'];
         //  $u_a[]=$item['url'];
         $abstract = str_ireplace($item["term"], "<a data-toggle=\"tooltip\" href=\"" . $item["url"] . "\" target=\"_blank\">" . $item["term"] . "</a>", $abstract);

      }

     echo "<b>Abstract: </b><div id=\"myContainer\">$abstract</div>";     

    
      echo"</tr>";
      
  }
      echo "</tbody></table>";
    ?>
    <div id="myModal" class="modal modal-md fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <iframe id="modalIframe" src="" height="240px" width="100%"></iframe>
                </div>
            </div>
        </div>
    </div>
  <br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
@endsection
