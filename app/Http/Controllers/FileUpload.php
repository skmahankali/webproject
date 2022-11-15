<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
class FileUpload extends Controller
{
      public function fileUpload(Request $req){
            $req->validate([
            'file' => 'required|mimes:csv,txt,xlx,xls,pdf|max:2048'
            ]);
            $fileModel = new File;
            if($req->file()) {
                $fileName = $req->file->getClientOriginalName();
                $filePath = $req->file('file')->storeAs('PDF', $fileName, 'public');
                $fileModel->name = $req->file->getClientOriginalName();
                $fileModel->file_path = '/xampp/htdocs/webproject/PDF/' . $filePath;
                $fileModel->save();
                return back()
                ->with('success','File has been uploaded successfully.')
                ->with('file', $fileName);
            }
       }    
}
