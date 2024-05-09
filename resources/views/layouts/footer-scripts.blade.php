<!-- jquery -->
<script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<!-- plugins-jquery -->
<script src="{{ URL::asset('assets/js/plugins-jquery.js') }}"></script>
<!-- plugin_path -->
<script>
    var plugin_path = 'js/';
</script>

<!-- chart -->
<script src="{{ URL::asset('assets/js/chart-init.js') }}"></script>
<!-- calendar -->
{{-- <script src="{{ URL::asset('assets/js/calendar.init.js') }}"></script> --}}
<!-- charts sparkline -->
{{-- <script src="{{ URL::asset('assets/js/sparkline.init.js') }}"></script> --}}
<!-- charts morris -->
{{-- <script src="{{ URL::asset('assets/js/morris.init.js') }}"></script> --}}
<!-- datepicker -->
{{-- <script src="{{ URL::asset('assets/js/datepicker.js') }}"></script> --}}
<!-- sweetalert2 -->
{{-- <script src="{{ URL::asset('assets/js/sweetalert2.js') }}"></script> --}}
<!-- toastr -->
@yield('js')
<script src="{{ URL::asset('assets/js/toastr.js') }}"></script>
<!-- validation -->
{{-- <script src="{{ URL::asset('assets/js/validation.js') }}"></script> --}}
<!-- lobilist -->
{{-- <script src="{{ URL::asset('assets/js/lobilist.js') }}"></script> --}}
<!-- custom -->
<script src="{{ URL::asset('assets/js/custom.js') }}"></script>
<script>
    function openWindow(route, width = 794, height = 1123) {
        var left = (window.screen.width - width) / 2;
        var top = (window.screen.height - height) / 2;
        var windowFeatures = `top=${top},left=${left},width=${width},height=${height},resizable=no`;
        window.open(route, '_blank', windowFeatures);
    }
    </script>