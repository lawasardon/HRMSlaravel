@extends('layouts.main')
@section('content')
<div class="content container-fluid" id="myAttendance">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">My Attendance - {{ now()->format('F Y') }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        @php
            $daysInMonth = Carbon\Carbon::create(now()->year, now()->month)->daysInMonth;
        @endphp

        @foreach(range(1, $daysInMonth) as $day)
            @php
                $currentDate = Carbon\Carbon::create(now()->year, now()->month, $day);
                $attendanceRecord = $attendance->get($currentDate->format('Y-m-d'));
            @endphp
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card
                    {{ $attendanceRecord ?
                        ($attendanceRecord->status == 'Late' ? 'border-danger' : 'border-success') :
                        'border-secondary'
                    }}">
                    <div class="card-header text-center
                        {{ $attendanceRecord ?
                            ($attendanceRecord->status == 'Late' ? 'bg-danger text-white' : 'bg-success text-white') :
                            'bg-secondary text-white'
                        }}">
                        {{ $currentDate->format('F d, Y') }}
                    </div>
                    <div class="card-body">
                        @if($attendanceRecord)
                            <p class="card-text">
                                <strong>Time In:</strong> {{ $attendanceRecord->time_in }}<br>
                                <strong>Time Out:</strong> {{ $attendanceRecord->time_out }}<br>
                                <strong>Status:</strong>
                                <span class="{{ $attendanceRecord->status == 'Late' ? 'text-danger' : 'text-success' }}">
                                    {{ $attendanceRecord->status }}
                                </span>
                            </p>
                        @else
                            <p class="card-text text-muted text-center">No Attendance Record</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('js')
<script>
new Vue({
    el: '#myAttendance',
    mounted() {
        // Additional Vue.js logic if needed
    }
});
</script>
@endpush
