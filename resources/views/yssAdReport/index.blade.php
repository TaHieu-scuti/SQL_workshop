@extends('layouts.master')

@section('title')
    Ad Report
@stop

@section('filter-list')
<ul class="panel">
    <li class="panel-body separator">
    </li>
    <li class="panel-body grayed-out">
        @lang('language.PREFECTURES')
    </li>
    <li class="panel-body grayed-out">
        @lang('language.BY_TIME_ZONE')
    </li>
    <li class="panel-body grayed-out">
        @lang('language.BY_DAYS_OF_THE_WEEK')
    </li>
    <li class="panel-body grayed-out">
        @lang('language.DEVICES')
    </li>
</ul>
@stop

@section('export')
    <li><a href="{{ url('/ad-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/ad-report/export_excel') }}">Excel</a></li>
@stop
