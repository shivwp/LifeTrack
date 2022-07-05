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
                                <h1 class="page-title">Create Mail </h1>
                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                                </ol>
                               
                            </div>
                            
                        
                        <!-- PAGE-HEADER END -->
@endsection

@section('content')
                        <!-- ROW-1 OPEN-->
                                <div class="card">
                                    <div class="card-body">
<div class="d-flex justify-content-end">
            <a href="{{ route('dashboard.mails.index') }}" class="btn btn-info-light ">Back</a>
        </div>
                                <form  method="post" action="{{route('dashboard.mails.store')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                       
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">From Name</label>
                                                        <input type="text" class="form-control" name="form_name" placeholder="From Name" value="{{ old('form_name') }}">
                                                        <span class="text-danger discription_text">@error('form_name'){{"**".'From Name Field is Required'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>
                                                

                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Subject</label>
                                                        <input type="text" class="form-control" name="subject" placeholder="Subject" value="{{ old('form_name') }}">
                                                        <span class="text-danger discription_text">@error('name'){{"**".'Subject Field is Required'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Message Category</label>
                                                        
                                                 <select class="form-control" name="massage_category">
                                                 <option value="Select Category">Select Category</option>
                                                 <option value="Contact us">Contact us</option>
                                                 <option value="Singup">Singup</option>
                                                 <option value="Password_reset">Password Rest</option>
                                                  <option value="Forget_password">Password Rest</option>
                                                 
                                                        

                                                       </select>
                                                    </div>
                                                    
                                                </div>

                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">From Email</label>
                                                        <input type="text" class="form-control" name="form_email" placeholder="From Email" value="{{ old('form_email') }}">
                                                        <span class="text-danger discription_text">@error('form_email'){{"**".'From Email Field is Required And Type also Email'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>
                                                   

                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Reply From Email</label>
                                                        <input type="text" class="form-control" name="reply_email" placeholder="Reply From Email" value="{{ old('reply_email') }}">
                                                        <span class="text-danger discription_text">@error('form_email'){{"**".'Reply From Email Field is Required  And Type also Email'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>
                                                       
                                                    <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Mail To</label>
                                                        
                                                 <select class="form-control" name="email_to">
                                                 <option value="User">User</option>
                                                 <option value="Admin">Admin</option>

                                                 
                                                 
                                                        

                                                       </select>
                                                    </div>
                                                    
                                                </div>

                                                

                                                
                                           

                                              <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Message Content</label>
                        <textarea class="form-control discription" id="summary-ckeditor" name="massage_content" placeholder="text here.." >{{ old('massage_content')}}</textarea>
                        <small class="text-danger discription_text"></small>
                        <span class="text-danger discription_text">@error('massage_content'){{"**".'Message Content Filed is Required'}}@enderror</span>
                    </div>
                </div>



                                       
                                        <button type ="submit" class="btn btn-success-light mt-3 ">Add</button>
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
