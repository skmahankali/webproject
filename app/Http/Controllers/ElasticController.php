<?php

namespace App\Http\Controllers;

require_once '\xampp\htdocs\webproject\vendor\autoload.php';
use Elastic\Elasticsearch\ClientBuilder;



use Illuminate\Http\Request;
use Elastic\Elasticsearch;
use Illuminate\Support\Str;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;


class ElasticController extends Controller
{


    public function rp(Request $request)
    {   
        $query_string = $request->get('p');
        $searchWord = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $query_string);
          if ($query_string != "") {
              $searchParams = [
                'index' => 'id_kib',
                'from' => 0,
                'size' => 1000,
                'type' => '_doc',
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'query' => $searchWord,
                            'fields' => ['author','$year','university','degree','program','abstract','title','advisor','wiki_terms']

            ]
                        ]
                ]
                        ];
            

        return view('search',["query_string"=>$query_string])->withquery($searchParams);      
        
    }
    

}

public function login_ser(Request $request)
    {   
       $query_string = $request->get('p');
       $searchWord = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $query_string);
          if ($query_string     != "") {
              $searchParams = [
                'index' => 'id_kib',
                'from' => 0,
                'size' => 1000,
                'type' => '_doc',
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'query' => $searchWord,
                            'fields' => ['author','$year','university','degree','program','abstract','title','advisor','wiki_terms','etd_file_id']

            ]
                        ]
                ]
                        ];

        return view('serp',["query_string"=>$query_string])->withquery($searchParams);      
        
    }
    

}


public function upload()

{    
return view('upload');
}

public function index($id)
{
    $client = ClientBuilder::create()->build();
    // $params = [
    //     'index' => 'id_kib',
    //     'etd_file_id' => $id,
    // ];
    // $response = $client->get($params);
    return view('details',["id"=>$id]);
    } 


    public function upload_data_fields(Request $request)
    {
        $client = ClientBuilder::create()->setHosts(['localhost:9200'])->build();
        
        $title                 = $request->input("title");
        $author                = $request->input('author');
        $degree                = $request->input('degree');
        $program               = $request->input('program');
        $university            = $request->input('university');
        $year                  = $request->input('year');
        $pdf                   = rand(500,1000).".pdf";
        $etd_file_id           = rand(500,1000);
        $abstract              = $request->input('abstract');
        $advisor               = $request->input('advisor');

        $params = [
            'index' => 'id_kib',
            'type' => '_doc',
            'body'  => [
                    'title' => $title,
                    'author' => $author,
                    'degree' => $degree,
                    'program' => $program,
                    'university' => $university,
                    'year'=> $year,
                    'pdf'=> $pdf,
                    'etd_file_id'=>$etd_file_id,
                    'abstract'=> $abstract,
                    'advisor'=> $advisor
            ]
        ];
        $response = $client->index($params);
        echo "<h3>Data upload successful!!!</h3>";
        print_r($pdf);
        echo "</br>";
        echo "</br>";
        echo "<a href='/upload'>Please use the above number to name your file and upload your PDF</a>";
        // echo "<h3>Please use the above number to name your file and upload your PDF</h3>";

    }
    public function pdf($pdf_id)
    {
        $file_name = storage_path()."\app\public\PDF/"."$pdf_id";

        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $file_name . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        
        readfile($file_name);

    }

}
