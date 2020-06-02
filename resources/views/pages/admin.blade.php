@extends('layouts.admin', ['title' =>"Admin Dashboard | PearToPear"])
@section('content')

<!-- Page Content -->
<div class="container">
    <div class="row">
        {{-- {{dd($reports[1])}} --}}
        <!-- Aside -->
        <div class="col-md-3 mb-4 aside" style="padding-top: 33px;">
            @include('partials.adminMenu')
        </div>

        <!-- Reports Column -->
        <div class="col-md-9">

            <h1 id="current-title" class="mt-4 mb-4 ml-2"> All Requests</h1>

            @foreach($reports as $report)

            @if($report->reportable instanceof App\UserReport)
            @include('partials.reportUser', ['report' => $report->reportable])
            @elseif($report->reportable instanceof App\CommentReport)
            @include('partials.reportComment', ['report' => $report->reportable])
            @elseif($report->reportable instanceof App\PostReport)
            @include('partials.reportPost', ['report' => $report->reportable])
            @else
            @include('partials.reportCommunity', ['report' => $report->reportable])
            @endif

            @endforeach

            @include('partials.noReports', ['report' => $report->reportable])

        </div>
    </div>
</div>

@endsection