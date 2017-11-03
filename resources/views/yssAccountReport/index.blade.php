@extends('layouts.master')

@section('title')
    Account Report
@stop

@section('export')
    <li><a href="{{ url('/account_report/export_csv') }}">@lang('language.CSV')</a></li>
    <li><a href="{{ url('/account_report/export_excel') }}">@lang('language.Excel')</a></li>
@stop