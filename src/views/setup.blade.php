@if(config('amc-pkg.DEV'))
    <?php $amc_pkg_prefix = '/packages/abs/amc-pkg/src';?>
@else
    <?php $amc_pkg_prefix = '';?>
@endif


<script type='text/javascript'>
	app.config(['$routeProvider', function($routeProvider) {
	    $routeProvider.
	    //Amc Policy
	    when('/amc-pkg/amc-policy/list', {
	        template: '<amc-policy-list></amc-policy-list>',
	        title: 'Amc Policies',
	    }).
	    when('/amc-pkg/amc-policy/add', {
	        template: '<amc-policy-form></amc-policy-form>',
	        title: 'Add Amc Policy',
	    }).
	    when('/amc-pkg/amc-policy/edit/:id', {
	        template: '<amc-policy-form></amc-policy-form>',
	        title: 'Edit Amc Policy',
	    }).
	    when('/amc-pkg/amc-policy/card-list', {
	        template: '<amc-policy-card-list></amc-policy-card-list>',
	        title: 'Amc Policy Card List',
	    });
	}]);

	//Amc Policies
    var amc_policy_list_template_url = '{{asset($amc_pkg_prefix.'/public/themes/'.$theme.'/amc-pkg/amc-policy/list.html')}}';
    var amc_policy_form_template_url = '{{asset($amc_pkg_prefix.'/public/themes/'.$theme.'/amc-pkg/amc-policy/form.html')}}';
    var amc_policy_card_list_template_url = '{{asset($amc_pkg_prefix.'/public/themes/'.$theme.'/amc-pkg/amc-policy/card-list.html')}}';
    var amc_policy_modal_form_template_url = '{{asset($amc_pkg_prefix.'/public/themes/'.$theme.'/amc-pkg/partials/amc-policy-modal-form.html')}}';
</script>
<script type='text/javascript' src='{{asset($amc_pkg_prefix.'/public/themes/'.$theme.'/amc-pkg/amc-policy/controller.js')}}'></script>

