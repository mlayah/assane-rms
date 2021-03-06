@extends('layout.main')


@push('header-css')
    <!-- third party css -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <!-- third party css end -->


@endpush

@section('content')

    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">{{ __('property.All Properties') }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4">

                                @permission('property-create')
                                <a href="{{ route('admin.property.create')}}" class="btn btn-danger mb-2"><i
                                        class="mdi mdi-plus-circle mr-2"></i> {{ __('property.Add New Property') }}</a>

                                @endpermission
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    {{-- <button type="button" class="btn btn-success mb-2 mr-1">Export</button> --}}

                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100"
                                   id="products-datatable">
                                <thead>
                                <tr>

                                    <th>#</th>
                                    <th>{{ __('property.Property Name') }}</th>
                                    <th>{{ __('property.Location') }}</th>
                                    <th>{{ __('property.Type') }}</th>
                                    <th>{{ __('property.Rent') }}</th>
                                    <th>{{ __('property.Status') }}</th>
                                    <th>{{ __('property.Landlord') }}</th>
                                    <th>{{ __('property.Units') }}</th>
                                    <th class="text-right" style="width: 75px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->


        <!-- end row-->

    </div>

@endsection

@push('footer-scripts')

    <!-- third party js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>


    <script>
        $(document).ready(function () {
            "use strict";
            $("#products-datatable").DataTable({

                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.property.index') !!}',
                createdRow: function (row, data, dataIndex) {
                    //      $(row).find('td:eq(0)').addClass('tx-semibold');
                    //      $(row).find('td:eq(1)').addClass('tx-semibold');
                    //      $(row).find('td:eq(2)').addClass('tx-semibold');
                    //      $(row).find('td:eq(3)').addClass('text-center');
                    $(row).find('td:eq(1)').addClass('font-weight-medium');
                    $(row).find('td:eq(4)').addClass('font-weight-medium');
                    $(row).find('td:eq(6)').addClass('font-weight-medium');
                    $(row).find('td:eq(7)').addClass('text-center font-weight-bold');
                    $(row).find('td:eq(8)').addClass('text-right');
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'title', name: 'title', searchable: false},
                    {data: 'address', name: 'address', orderable: false},
                    {data: 'property_type', name: 'property_type', orderable: false},
                    {data: 'rent', name: 'rent', orderable: false},
                    {data: 'status', name: 'status', orderable: false},
                    {data: 'landlord', name: 'landlord', orderable: false},
                    {data: 'property_units_count', name: 'property_units_count', orderable: false},
                    {data: 'actions', name: 'actions', orderable: false},


                ],


                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    },
                },

                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            })
        });
    </script> --}}
    <!-- third party js ends -->

@endpush
