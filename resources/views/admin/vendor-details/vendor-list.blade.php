@extends('layouts.vertical-menu.master')
@section('css')
<style type="text/css">
    .paginatesection{
        position: relative !important;
    }
    .paginatesection1{
        position: absolute !important;
        z-index: 55 !important;
    }
</style>

<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet">
@endsection


@section('page-header')
                        <!-- PAGE-HEADER -->
                            <div>
                                <h1 class="page-title">Stores</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Store</a></li>
                                    
                                    <li class="breadcrumb-item active" aria-current="page">List</li>
                                </ol>
                            </div>
                        
                        <!-- PAGE-HEADER END -->
@endsection
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link   href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0-rc.4/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0-rc.4/dist/js/tom-select.complete.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

 @if(session()->has('message'))
    <script>
        $(document).ready(function(){
            $("#myModal").modal('show');
        });
         setTimeout(function(){
        $('#myModal').modal('hide')
            }, 2000);
    </script>
    <div id="myModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="text-center">{{ session()->get('message') }}</h6>
                </div>
            </div>
        </div> 
   </div>
@endif
                        <!-- ROW-1 OPEN-->
                            <!-- ROW-1 OPEN -->
                            <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="card">
                                         <div class="addnew-ele">
                                <a href="{{ route('dashboard.vendorsettings.create') }}" class="btn btn-info-light ">
                                    {{$buton_name}}
                                </a>
                            </div>
                                 <div class="card-body">
                                        <div class="table-responsive">
                                            
                                            <table id="example" class="table table-striped table-bordered text-nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-15p">Id</th>
                                                        <th class="wd-15p">Store</th>
                                                        <!-- <th class="wd-15p">Email</th> -->
                                                        <!-- <th class="wd-15p">Category</th> -->
                                                        <!-- <th class="wd-15p">Number</th> -->
                                                        <!-- <th class="wd-15p">Registered</th> -->
                                                        <!-- <th class="wd-15p">Status</th> -->
                                                        <th class="wd-15p">Location</th>
                                                        <th class="wd-15p">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($setting)>0)
                                                    @foreach($setting as $key => $item)
                                                    <tr>
                                                        <td>{{$item->id ?? '' }}</td>
                                                        <td>{{$item->first_name ?? '' }}</td>
                                                        <!-- <td>{{$item->email ?? '' }}</td> -->
                                                        <!-- <td>{{$item->cat ?? '' }}</td> -->
                                                        <!-- <td>{{$item->number ?? '' }}</td> -->
                                                         <!-- <td>{{$item->created_at ?? '' }}</td> -->
                                                         <!-- <td>
                                                         @if($item->is_approved == 0)
                                                         <span class="tag tag-blue">pending</span>
                                                         @elseif($item->is_approved == 1)
                                                         <span class="tag tag-azure">approved</span>
                                                         @else
                                                         <span class="tag tag-indigo">rejected</span>
                                                         @endif
                                                        </td> -->
                                                       <td>
                                                           {{$item->last_name ?? '' }}
                                                       </td>
                                                        <td>  

                                                        @if(Auth::user()->roles->first()->title == "Admin")
                                                        <!-- <a data-toggle="tooltip" title="approve" class="btn btn-sm btn-secondary" href="{{ route('dashboard.vendor-approve', $item->id) }}"><i class="fa fa-check"></i> </a>

                                                        <a data-toggle="tooltip" title="reject" class="btn btn-sm btn-secondary" href="{{ route('dashboard.vendor-rejected', $item->id) }}"><i class="fa fa-ban"></i> </a> -->
                                                        @endif

                                                            <a class="btn btn-sm btn-secondary" href="{{ route('dashboard.vendorsettings.edit', $item->id) }}"><i class="fa fa-edit"></i> </a>
                                                                 
                                                               
                                                                    <form action="{{ route('dashboard.vendorsettings.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure');" style="display: inline-block;">
                                                                        <input type="hidden" name="_method" value="DELETE">
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        <button type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}"><i class="fa fa-trash"></i></button>
                                                                    </form></td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                    
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
<script type="text/javascript">
$(document).ready(function() {
  $('#pagination').on('change', function() {
    var $form = $(this).closest('form');
    //$form.submit();
    $form.find('input[type=submit]').click();
    console.log( $form);
  });
    $('#filter-status').on('change', function() {
        $('#filter-submit').submit();
    });
});
</script>
@endsection
 