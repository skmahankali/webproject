@extends('layouts.app')
@section('content')


<!DOCTYPE html>
<html>


<head>
      <title>Upload details</title>
   </head>
	
   <body>
      <form action="/upload_data_fields" method="POST" role="add"> 
         @csrf
         <div class="form-group">
         Title: <input type = "text" name = "title"  />
         <br>
         <br>
         Author: <input type = "text" name = "author"  />
         <br>
         <br>
         Year: <input type = "text" name = "year" />
         <br>
         <br>
         University: <input type = "text" name = "university"  />
         <br>
         <br>
         Program: <input type = "text" name = "program"  />
         <br>
         <br>
         Degree: <input type = "text" name = "degree"  />
         <br>
         <br>
         Advisor: <input type = "text" name = "degree" />
         <br>
         <br>
         <input type = "submit" name = "submit" value = "Submit"/>
         <br>
         </div>
      </form>

      <form action="{{route('fileUpload')}}" method="post" enctype="multipart/form-data">
          <h3 class="mb-2">Upload your file below</h3>
            @csrf
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
          @endif
          @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
            <div class="custom-file">
                <input type="file" name="file" class="custom-file-input" id="chooseFile">
                <label class="custom-file-label" for="chooseFile">Select your file</label>
            </div>
            <br><br>
            <button type="submit" name="submit" class="btn btn-primary">
                Upload Files
            </button>
        </form>



   
</body>
@endsection
</html>
