<p>@lang('language.results_per_page')</p>
<div class="form-group">
    <input type="radio" name="resultPerPage" value="20" {{(int)$keyPagination === 20 ? 'checked' : ''}} > 20<br>
</div>

<div class="form-group">
    <input type="radio" name="resultPerPage" value="50" {{(int)$keyPagination === 50 ? 'checked' : ''}}> 50<br>
</div>

<div class="form-group">
    <input type="radio" name="resultPerPage" value="100" {{(int)$keyPagination === 100 ? 'checked' : ''}}> 100
</div>
<div class="clearfix"></div>