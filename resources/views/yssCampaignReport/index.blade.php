@extends('layouts.master')

@section('title')
    Account Report
@stop

@section('export')
    <li><a href="{{ route('export_csv') }}">CSV</a></li>
    <li><a href="{{ route('export_excel') }}">Excel</a></li>
@stop