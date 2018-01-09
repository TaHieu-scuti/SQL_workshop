<?php
$arrField = [];
if (isset($fieldNames)) {
    foreach ($fieldNames as $check) {
        if ($check === 'account_id') {
            continue;
        } else {
            $arrField[$check] = $check;
        }
    }
}
?>

@foreach(array_chunk($columnsInModal, \Config::get('constants.COLUMNS_PER_ROW')) as $columnsChunked)
<div>
    @foreach($columnsChunked as $column)
    <div class="form-group">
        <input type="checkbox" name="fieldName" value="{{$column}}" {{!empty($arrField) && isset($arrField[$column]) ? "checked" : '' }} >
        @lang('language.'.$column)
    </div>
    @endforeach
</div>
@endforeach