@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{asset('css/lib/sweetalert/sweetalert.css')}}">
@endsection

@section('module')
    CONFIGURATION
@endsection

@section('title')
    Subscription
@endsection


@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-title">
                <h4>Plan Details</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>NAME</th>
                            <th>ID</th>
                            <th>PRICE ($) </th>
                            <th>INTERVAL</th>
                            <th>FEATURES</th>
                            <th>MAXIMUM USERS</th>
                            <th>PAGES/USERS</th>
                            <th>ENABLE BROADCAST MESSAGE</th>
                            <th colspan="2"><div class="text-center">Actions</div></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($subscription_plans))
                            @forelse($subscription_plans as $subscription_plan)
                            <tr>
                                <td>
                                    {{$subscription_plan->id}}
                                </td>
                                <td>{{$subscription_plan->plan_name}}</td>
                                <td>{{$subscription_plan->plan_id}}</td>
                                <td>{{$subscription_plan->plan_price}}</td>
                                <td>{{(!is_null($subscription_plan->interval)) ? $subscription_plan->interval->name : 'Daily'}}</td>
                                <td>{{$subscription_plan->plan_features}}</td>
                                <td>{{$subscription_plan->profile_creation}}</td>
                                <td>{{$subscription_plan->page_creation_per_profile}}</td>
                                <td>
                                    @if($subscription_plan->avail_broadcast == '1')
                                        <span class="badge badge-success">Active</span>
                                        @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>
                                <td><a class="btn btn-danger delete" data-id="{{$subscription_plan->id}}" data-stripe_plan = "{{$subscription_plan->plan_id}}" title="Delete Subscription"><i class="fa fa-trash-o"></i></a></td>
                                <td><a class="btn btn-info" href="{{route('subscriptions.edit',$subscription_plan->id)}}" title="Edit Subscription"><i class="fa fa-pencil"></i></a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11"><div class="text-center">There are no results available.</div></td>
                            </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="11"><div class="text-center">There are no results available.</div></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('js/lib/sweetalert/sweetalert.min.js')}}"></script>
    <script>
        $('.delete').click(function () {
            var subscription_id =   $(this).data('id');
            var stripe_plan_id  =   $(this).data('stripe_plan');
            swal({
                    title: "Are you sure?",
                    text: "You want to delete this particular subscription ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-error",
                    confirmButtonText: "Yes!",
                    closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        url: "subscriptions/"+subscription_id,
                        type: 'post',
                        data: {
                            _method: 'delete',
                            'subscription_id': subscription_id,
                            'plan_id': stripe_plan_id,
                            _token : '{{csrf_token()}}'
                        },
                        success:function(response){
                            if(response.success) {
                                swal({
                                    title: "Success",
                                    text: response.message,
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "Ok"
                                },function () {
                                    location.reload();
                                });
                            } else {
                                swal(response.message,'Please try again after sometime' , "error");
                            }
                        },
                        error: function (error) {
                            swal(error.statusText, "Please try again after sometime", "error");
                        }
                    })
                });
        });
    </script>
@endsection