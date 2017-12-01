@extends('layouts.master')

@section('title')
    Keyword Report
@stop

@section('filter-list')
    <ul class="panel">
        <li class="panel-body separator">
        <li class="panel-body grayed-out">
            @lang('language.PREFECTURES')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.BY_TIME_ZONE')
        <li class="panel-body grayed-out">
            @lang('language.BY_DAYS_OF_THE_WEEK')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.DEVICES')
        </li>
    </ul>
@stop

@section('export')
    <li><a href="{{ url('/keyword-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/keyword-report/export_excel') }}">Excel</a></li>
@stop
