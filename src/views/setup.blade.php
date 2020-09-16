@if(config('financial-years-pkg.DEV'))
    <?php $financial_years_pkg_prefix = '/packages/abs/financial-years-pkg/src';?>
@else
    <?php $financial_years_pkg_prefix = '';?>
@endif

<script type="text/javascript">
	app.config(['$routeProvider', function($routeProvider) {

	    $routeProvider.
	    when('/financial-year-pkg/financial-year/list', {
	        template: '<financial-year-list></financial-year-list>',
	        title: 'Financial Years',
	    }).
	    when('/financial-year-pkg/financial-year/add', {
	        template: '<financial-year-form></financial-year-form>',
	        title: 'Add Financial Year',
	    }).
	    when('/financial-year-pkg/financial-year/edit/:id', {
	        template: '<financial-year-form></financial-year-form>',
	        title: 'Edit Financial Year',
	    });
	}]);

    var financial_year_list_template_url = "{{URL::asset($financial_years_pkg_prefix.'/public/themes/'.$theme.'/financial-year-pkg/financial-year/list.html')}}";
    var financial_year_get_form_data_url = "{{url('financial-years-pkg/financial-year/get-form-data')}}";
    var financial_year_form_template_url = "{{URL::asset($financial_years_pkg_prefix.'/public/themes/'.$theme.'/financial-year-pkg/financial-year/form.html')}}";
    var financial_year_delete_data_url = "{{url('financial-years-pkg/financial-year/delete/')}}";
</script>
<!-- <script type="text/javascript" src="{{URL::asset($financial_years_pkg_prefix.'/public/themes/'.$theme.'/financial-year-pkg/financial-year/controller.js?v=2')}}"></script> -->
