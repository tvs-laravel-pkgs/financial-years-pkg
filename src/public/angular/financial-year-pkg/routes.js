app.config(['$routeProvider', function($routeProvider) {

    $routeProvider.
    //CUSTOMER
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