@extends('layouts.vertical-menu.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/date-picker/spectrum.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
                        <!-- PAGE-HEADER -->
                            <div>
                                <h1 class="page-title">Edit Page</h1>
                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                                </ol>
                                
                            </div>
                            
                        
                        <!-- PAGE-HEADER END -->
@endsection

@section('content')
                        <!-- ROW-1 OPEN-->
                                <div class="card">
                                     <div class="card-body">
<div class="d-flex justify-content-end">
            <a href="{{ route('dashboard.pages.index') }}" class="btn btn-info-light ">Back</a>
        </div>
                                <form  method="post" action="{{route('dashboard.pages.edit' , $user->id)}}" enctype="multipart/form-data">
                                    @csrf
                                    @method('GET')
                                    <div class="card-body">
                                        <input type="hidden" name="id" value="{{ old('id', isset($user) ? $user->id : '') }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control" name="title" placeholder="Page Title" value="{{ old('title', isset($user) ? $user->title : '') }}" >
                                                        <span class="text-danger discription_text">@error('title'){{"**".'Title  is  Required.'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>
                                               
                                               <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Meta Title</label>
                                                        <input type="text" class="form-control" name="metatitle" placeholder="Meta Title" value="{{ old('metatitle', isset($user) ? $user->metatitle : '') }}">
                                                        <span class="text-danger discription_text">@error('metatitle'){{"**".'Meta Title is  Required.'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>
                                               
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Meta Description</label>
                                                        <textareatype="text" class="form-control" name="metadescription" placeholder="Meta Description" value="{{ old('metadescription', isset($user) ? $user->metadescription : '') }}" ></textarea> 
                                                        <span class="text-danger discription_text">@error('metadescription'){{"**".'Meta Description is  Required.'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>
                                                
                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">KeyWord</label>
                                                        <input type="text" class="form-control" name="keyword" placeholder="KeyWord" value="{{ old('keyword', isset($user) ? $user->keyword : '') }}" >
                                                        <span class="text-danger discription_text">@error('keyword'){{"**".'KeyWord is  Required.'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>
                                                
                                               


                                                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-control discription" id="summary-ckeditor"  name="description" rows="6"   value="" placeholder="Description" >{{ $user->description }}</textarea>
                       <small class="text-danger discription_text"></small>
                        <span class="text-danger discription_text">@error('description'){{"**".$message}}@enderror</span>
                    </div>
                </div>
                                                
                                            </div>
                                            </div>
                                       
                                        <button type ="submit" class="btn btn-success-light mt-3 ">update</button>
                                    </div>

                                </form>
                                    
                                </div>                  
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/date-picker/spectrum.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/date-picker/jquery-ui.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/input-mask/jquery.maskedinput.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/multipleselect/multiple-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/multipleselect/multi-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/time-picker/toggles.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script>
    $(document).ready(function() {
          $('#dataTable').DataTable();
    });
</script>

<script src="//cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace( 'summary-ckeditor' );
</script>
@endsection
