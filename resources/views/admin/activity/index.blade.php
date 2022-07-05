@extends('layouts.vertical-menu.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet">
@endsection
<style type="text/css">
    .paginatesection{
        position: relative;
    }
    .paginatesection1{
        position: absolute;
        z-index: 55;
    }
</style>
@section('page-header')
                        <!-- PAGE-HEADER -->
                            <div>
                                <h1 class="page-title">Activity</h1>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">Activity</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">List</li>
                                    </ol>
                            </div>
                        
                        <!-- PAGE-HEADER END -->
@endsection
@section('content')
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                        <!-- ROW-1 OPEN-->
                            <!-- ROW-1 OPEN -->
                            <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="card">
                            <div class="row" id="search-bar">   

                            

                            <div class="col-8"> 
                            <form  method="get" action="{{route('dashboard.serech')}}" enctype="multipart/form-data">
                                <div class="row">

                                <div class="col-6">  <label class="form-label" >Select Users</label>
                                                        <select class="form-control" name="username" ></div>
                                            
                                                          <option>select Users</option>

                                                        @foreach($userdetails as $deatilst)

                                                         <option value="{{$deatilst->id}}">{{$deatilst->first_name}}{{$deatilst->email}}</option>
                                                        @endforeach
                                                        </select>
                                                         </div>
                                                         <div class="col-2" style="margin-top: 24px;">   <button type ="submit" class="btn btn-success-light mt-3 ">Show Lists</button></div>
                                </div>
                  
                         

                                                      
                                               
                                     
                                    
                                                </form>
                            </div>    
                            <div class="col-4"> 
                            <div class="addnew-ele" >
                                   
                                   <a href="{{ route('dashboard.activity.create') }}" class="btn btn-info-light " style="margin-top: 12px;">
                                      Add Actives
                                   </a>
                                    
                                     
                               </div>
                            </div>                  
                          </div>
                         
                                
                        
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example" class="table table-striped table-bordered text-nowrap w-100">
                                                <thead>
                                    

                                                    <tr>
                                                        <th class="wd-15p">S.no</th>
                                                        <th class="wd-15p">User Name</th>
                                                        <th class="wd-15p">Name</th>
                                                        
                                                        <th class="wd-15p">Start Time</th>
                                                        <th class="wd-15p">End Time</th>
                                                        <th class="wd-15p">Action</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody" >
                                                        @php
                                                        $i = 1;
                                                        @endphp
                                                        @foreach($data as $vale => $key)
                                                    <tr>
                                                        
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $key->first_name }} [{{ $key->email }}]</td>

                                                        <td>{{ $key->activity }}</td>
                                                       
                                                        <td>{{ $key->starttime }}</td>
                                                        <td>{{ $key->endtime }}</td>
                                                        

                                                        
                                                       
                                                         <td>   
                                                            <a class="btn btn-sm btn-secondary" href="{{ route('dashboard.activity.show' , $key->id ) }}"><i class="fa fa-edit"></i> </a>
                                                            
                                                       
                                                            <form action="{{ route('dashboard.activity.destroy' , $key->id ) }}" method="POST" onsubmit="return confirm('Do You Want To Delete This ?');" style="display: inline-block;">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <button type="submit" class="btn btn-sm btn-danger" value=""><i class="fa fa-trash"></i></button>
                                                            </form>
                                                            
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- TABLE WRAPPER -->
                                </div>
                                <!-- SECTION WRAPPER -->
                            </div>
                        </div>
                        <!-- ROW-1 CLOSED -->  
     
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/datatable.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>

@endsection
 