@if(config('custom.PKG_DEV'))
    <?php $financial_years_pkg_prefix = '/packages/abs/financial-years-pkg/src';?>
@else
    <?php $financial_years_pkg_prefix = '';?>
@endif

<script type="text/javascript">
    var financial_year_list_template_url = "{{URL::asset($financial_years_pkg_prefix.'/public/angular/financial-year-pkg/pages/financial-year/list.html')}}";
    var financial_year_get_form_data_url = "{{url('financial-years-pkg/financial-year/get-form-data')}}";
    var financial_year_form_template_url = "{{URL::asset($financial_years_pkg_prefix.'/public/angular/financial-year-pkg/pages/financial-year/form.html')}}";
    var financial_year_delete_data_url = "{{url('financial-years-pkg/financial-year/delete/')}}";
</script>
<script type="text/javascript" src="{{URL::asset($financial_years_pkg_prefix.'/public/angular/financial-year-pkg/pages/financial-year/controller.js?v=2')}}"></script>
