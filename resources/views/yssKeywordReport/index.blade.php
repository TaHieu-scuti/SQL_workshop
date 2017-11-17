@extends('layouts.master')

@section('title')
    Keyword Report
@stop

@section('filter-list')
    <ul class="panel">
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
    <li><a href="{{ url('/keyword-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/keyword-report/export_excel') }}">Excel</a></li>
@stop
