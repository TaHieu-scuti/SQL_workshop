@foreach ($columnsLiveSearch as $columnsSearch)
    <option class="selection-graph" data-column="{{ $columnsSearch }}" data-tokens="{{ __('language.' .str_slug($columnsSearch,'_')) }}" @if (isset($graphColumnName)) {{ $columnsSearch === $graphColumnName ? 'selected' : '' }} @endif >{{ __('language.' .str_slug($columnsSearch,'_')) }}</option>
@endforeach