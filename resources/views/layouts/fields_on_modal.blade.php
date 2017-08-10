@foreach(array_chunk($columns, \Config::get('constants.COLUMNS_PER_ROW')) as $columnsChunked)
<div>
    @foreach($columnsChunked as $column)
    <div class="form-group">
        <input type="checkbox" name="fieldName" value="{{$column}}"> {{$column}}
    </div>
    @endforeach
</div>
@endforeach