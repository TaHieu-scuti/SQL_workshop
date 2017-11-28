@extends('layouts.master')

@section('title')
    Ad Report
@stop

@section('filter-list')
<ul class="panel">
    @if (session('engine') === 'adw')
        <img src="images/adwords.png" width="15px" height="15px" class="iconMedia" >
    @else
        <img src="images/yahoo.png" width="15px" height="15px" class="iconMedia" >
    @endif
    <li class="panel-body">
        <a href="{{ route('keyword-report') }}">
            @lang('language.keywords')
        </a>
    </li>
    <li class="panel-body separator">
    </li>
    <li class="panel-body">
        <p style="color: lightgrey">
            @lang('language.PREFECTURES')
        </p>
    </li>
    <li class="panel-body">
        <p style="color: lightgrey">
            @lang('language.BY_TIME_ZONE')
        </p>
    <li class="panel-body">
        <p style="color: lightgrey">
            @lang('language.BY_DAYS_OF_THE_WEEK')
        </p>
    </li>
    <li class="panel-body">
        <p style="color: lightgrey">
            @lang('language.DEVICES')
        </p>
    </li>
</ul>
@stop

@section('export')
    <li><a href="{{ url('/ad-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/ad-report/export_excel') }}">Excel</a></li>
@stop
