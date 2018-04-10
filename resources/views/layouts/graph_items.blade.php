
    <select class="selectpicker" id="selectpickerGraph" data-live-search="true">
        @foreach ($columnsLiveSearch as $columnsSearch)
            <option class="selection-graph" data-column="{{ $columnsSearch }}" {{$columnsSearch === $graphColumnName ? "selected" : ''}} data-tokens="{{ $columnsSearch }}">{{ __('language.' .str_slug($columnsSearch,'_')) }}</option>
        @endforeach
    </select>
