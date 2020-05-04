@if(config('amc-pkg.DEV'))
    <?php $amc_pkg_prefix = '/packages/abs/amc-pkg/src';?>
@else
    <?php $amc_pkg_prefix = '';?>
@endif

<script type="text/javascript">
    var amc_policies_voucher_list_template_url = "{{asset($amc_pkg_prefix.'/public/themes/'.$theme.'/amc-pkg/amc-policy/amc-policy.html')}}";
</script>
<script type="text/javascript" src="{{asset($amc_pkg_prefix.'/public/themes/'.$theme.'/amc-pkg/amc-policy/controller.js')}}"></script>
