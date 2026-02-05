@extends('layouts.admin')

@section('content')

<div class="card mb-2">
  <!--begin::Card Body-->
  <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
    <!--begin::Section-->
    <div>
      <!--begin::Heading-->
      <div class="row">
        <div class="col-12 col-md-6">
          <h1 class="anchor fw-bolder mb-5" id="striped-rounded-bordered">Selamat datang, {{auth()->user()->name}}</h1>
        </div>
      </div>
      <!--end::Heading-->
      <!--begin::Block-->
      
      <canvas id="grafik" class="mh-400px"></canvas>

      <!--end::Block-->
    </div>
    <!--end::Section-->
  </div>
  <!--end::Card Body-->
</div>

@endsection

@section('script')

@endsection