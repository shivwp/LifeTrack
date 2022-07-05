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
                                <h1 class="page-title">Edit Subscription</h1>
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
            <a href="{{ route('dashboard.subscriptions.index') }}" class="btn btn-info-light ">Back</a>
        </div>
                                <form  method="post" action="{{route('dashboard.subscriptions.edit' , $user->id)}}" enctype="multipart/form-data">
                                    @csrf
                                    @method('GET')
                                    <div class="card-body">
                                        <input type="hidden" name="id" value="{{ old('id', isset($user) ? $user->id : '') }}">
                                            <div class="row">
                                                  <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Name</label>
                                                        <input   type="text" class="form-control" name="name" placeholder="Name" value="{{$user->name }}" />
                                                        <span class="text-danger">@error('name'){{"**".'Name  Type Feild is  Required.'}}@enderror</span>
                                                    </div>
                                                    
                                                </div>  

                                                 <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label"> Type</label>
                                                         <select name="subscriptions_type" class="form-control" name="subscriptions_type">
                                                              <option value="free" {{isset($user) && ($user->subscriptions_type == "free") ? 'selected' : '' }}>Free</option>
                                                              <option value="paid" {{isset($user) && ($user->subscriptions_type == "paid") ? 'selected' : '' }}>Paid</option>
                                                          </select>
                                                    </div>
                                                    
                                                </div>  
                                              
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Add Friends</label>
                                                        
                                                        <select  class="form-control" name="addfriends" value="{{ $user->idtype }}">

                                                            <option  value="{{ $user->addfriends}}">{{ $user->addfriends}}</option>
                                                             <option value="no">No</option>
                                                             <option  value="yes">Yes</option>

                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                                  <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Access Group</label>
                                                        

                                                     <select  class="form-control" name="creategroup" value="{{ $user->idtype }}">

                                                            <option  value="{{ $user->creategroup}}">{{ $user->creategroup}}</option>
                                                            <option value="no">No</option>
                                                            <option  value="yes">Yes</option>

                                                        </select>
                                                    </div>
                                                    
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Price</label>
                                                        
                                                        <input type="text" class="form-control" name="price" placeholder="Price" value="{{ $user->price}}" >
                                                        <span class="text-danger">@error('price'){{"**".'Name Field  is  Required.'}}@enderror</span>
                                                       

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


@endsection
