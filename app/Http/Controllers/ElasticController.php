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
                'index' => 'id_kib1',
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
            // function highlightWords($query_string, $searchWord)
            // {
            //     $text = preg_replace('#' . preg_quote($searchWord) . '#i', '<span style = "background-color: #F9F902;">\\0</span>', $query_string);
            //      //echo ("test");
            //     return $text;
            // }

                            

        return view('search',["query_string"=>$query_string])->withquery($searchParams);      
        
    }
    

}

public function login_ser(Request $request)
    {   
       $query_string = $request->get('p');
       $searchWord = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $query_string);
          if ($query_string     != "") {
              $searchParams = [
                'index' => 'id_kib1',
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
            'index' => 'id_kib1',
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
        
        if (file_exists($file_name)){
            readfile($file_name);
        } else{
            echo "File not found!!";
        }

    }
    
    public function api_token()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $users = Auth::user();
            if ($users->getRememberToken() == NULL) {
                $token = Str::random(30);
                $users->setRememberToken($token);
                $users->save();
            }
            return response()->json(['key' => $users->getRememberToken()], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
        
    }
    public function sear_api()
    {
      $terms = request('query');
      $limit = request('W');
      $key = request('key');
      $client =  ClientBuilder::create()->build();
      $resultids = (array)DB::select('select remember_token from users');
      $resultstr = json_encode($resultids);
      

      if ($key != NULL && (str_contains($resultstr, $key) )) {

                  $params = [
                    'index' => 'id_kib1',
                    'from' => 0,
                    'size' => $limit,
                    'type' => '_doc',
                    'body' => [
                        'query' => [
                            'multi_match' => [
                                'query' => $terms,
                                'fields' => ['author','title','$etd_file_id','$year','university','degree','program','abstract','advisor','wiki_terms'],
    
                ]
                            ]
                    ]
                            ];

          $results = $client->search($params);
          $count = $results['hits']['total']['value'];
          $res = $results['hits']['hits'];
          $rank = 1;
         foreach( $res as $r)
          {       
              $title[$rank]['title'] = $results['hits']['hits'][$rank-1]['_source']['title'];
              $author[$rank]['author'] = $results['hits']['hits'][$rank-1]['_source']['author'];
              $etd[$rank]['etd_file_id'] = $results['hits']['hits'][$rank-1]['_source']['etd_file_id'];
              $year[$rank]['year'] = $results['hits']['hits'][$rank-1]['_source']['year'];
              $univ[$rank]['university'] = $results['hits']['hits'][$rank-1]['_source']['university'];
              $deg[$rank]['degree'] = $results['hits']['hits'][$rank-1]['_source']['degree'];
              $prog[$rank]['program'] = $results['hits']['hits'][$rank-1]['_source']['program'];
              $abs[$rank]['abstract'] = $results['hits']['hits'][$rank-1]['_source']['abstract'];
              $wiki[$rank]['wiki_terms'] = $results['hits']['hits'][$rank-1]['_source']['wiki_terms'];
              $rank+=1;
          }
          return response()->json(['response'=>$title,$author,$etd,$year,$univ,$deg,$prog,$abs,$wiki], 200);
      } else {
          return response()->json(['error' => 'You are not authorized to Access query'.$terms.',since there is no key.'], 401);
      }
  }
}
