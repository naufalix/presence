@extends('layouts.dashboard')

@section('content')

@php
use Carbon\Carbon;
  $isSunday = Carbon::now()->isSunday();
@endphp

<!--begin::Card-->
<div class="card col-12 col-md-6">
  <div class="card-body text-center">

    @if($isSunday)
      <h3 class="text-warning mb-3">
        <i class="fa-solid fa-calendar-xmark"></i>
        Hari Minggu
      </h3>
      <p class="text-muted">
        Presensi tidak diperlukan pada hari Minggu.
      </p>

    @else
      <form id="presenceForm" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="location" id="location">
        <input type="hidden" name="image" id="image">

        {{-- BELUM ABSEN --}}
        @if(!$presence || $presence->status == 0)
          <h3 class="mb-4">Absen Masuk</h3>

          <video id="video" width="100%" autoplay></video>
          <canvas id="canvas" class="d-none"></canvas>

          <button type="button" class="btn btn-info btn-purple mt-4" value="check_in" onclick="capturePhoto(this)"> Scan Foto & Absen Masuk
          </button>

        {{-- SUDAH CHECK IN --}}
        @elseif($presence->status == 1)
          <h3 class="mb-4">Absen Pulang</h3>

          <video id="video" width="100%" autoplay></video>
          <canvas id="canvas" class="d-none"></canvas>

          <button
            type="button"
            class="btn btn-danger mt-4"
            value="check_out"
            onclick="capturePhoto(this)">
            Scan Foto & Absen Pulang
          </button>

          <small class="text-muted d-block mt-3">
            Check-in: {{ $presence->time_in }}
          </small>

        {{-- SUDAH CHECK OUT --}}
        @elseif($presence->status == 2)
          <h3 class="text-success mb-3">Anda sudah check-out hari ini</h3>
          <p>Check-in : {{ $presence->time_in }}</p>
          <p>Check-out : {{ $presence->time_out }}</p>
        @endif
      </form>
    @endif

  </div>
</div>
<!--end::Card-->

<script>
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      document.getElementById('video').srcObject = stream;
    });

  function capturePhoto(button) {
    const form = document.getElementById('presenceForm');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const imageInput = document.getElementById('image');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);

    imageInput.value = canvas.toDataURL('image/jpeg');

    // hidden submit type
    let submitInput = document.createElement('input');
    submitInput.type = 'hidden';
    submitInput.name = 'submit';
    submitInput.value = button.value;

    form.appendChild(submitInput);

    // ✅ PENTING
    form.requestSubmit();
  }

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
      document.getElementById('location').value =
        pos.coords.latitude + ',' + pos.coords.longitude;
    });
  }
</script>



@endsection