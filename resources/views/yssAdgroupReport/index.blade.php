@extends('layouts.master')

@section('title')
    Adgroup Report
@stop

@section('export')
    <li><a href="{{ url('/adgroup-report/export_csv') }}">@lang('language.CSV')</a></li>
    <li><a href="{{ url('/adgroup-report/export_excel') }}">@lang('language.Excel')</a></li>
@stop