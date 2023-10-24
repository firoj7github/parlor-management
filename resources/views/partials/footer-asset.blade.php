<script src="{{ asset('public/frontend/js/jquery-3.6.0.min.js') }}"></script>
<!-- bootstrap js -->
<script src="{{ asset('public/frontend/js/bootstrap.bundle.min.js') }}"></script>
<!-- swipper js -->
<script src="{{ asset('public/frontend/js/swiper.min.js') }}"></script>
<!-- viewport js -->
<script src="{{ asset('public/frontend/js/viewport.jquery.js') }}"></script>
<!-- odometer js -->
<script src="{{ asset('public/frontend/js/odometer.min.js') }}"></script>
<!-- lightcase js-->
<script src="{{ asset('public/frontend/js/lightcase.js') }}"></script>
<!-- nice select js -->
<script src="{{ asset('public/frontend/js/jquery.nice-select.js') }}"></script>
<!-- select2 -->
<script src="{{ asset('public/backend/js/select2.min.js') }}"></script>
<!-- smooth scroll js -->
<script src="{{ asset('public/frontend/js/smoothscroll.min.js') }}"></script>
<!--  Popup -->
<script src="{{ asset('public/backend/library/popup/jquery.magnific-popup.js') }}"></script>
<!-- AOS js -->
<script src="{{ asset('public/frontend/js/aos.js') }}"></script>
<!-- pace js-->
<script src="{{ asset('public/frontend/js/pace.js') }}"></script>
<!-- apexcharts js -->
<script src="{{ asset('public/frontend/js/apexcharts.min.js') }}"></script>
<!-- dayjs js -->
<script src="{{ asset('public/frontend/js/dayjs.min.js') }}"></script>

<script>
    var fileHolderAfterLoad = {};
</script>

<script src="https://cdn.appdevs.net/fileholder/v1.0/js/fileholder-script.js" type="module"></script>
<script type="module">
    import { fileHolderSettings } from "https://cdn.appdevs.net/fileholder/v1.0/js/fileholder-settings.js";
    import { previewFunctions } from "https://cdn.appdevs.net/fileholder/v1.0/js/fileholder-script.js";

    var inputFields = document.querySelector(".file-holder");
    fileHolderAfterLoad.previewReInit = function(inputFields){
        previewFunctions.previewReInit(inputFields)
    };

    fileHolderSettings.urls.uploadUrl = "{{ setRoute('fileholder.upload') }}";
    fileHolderSettings.urls.removeUrl = "{{ setRoute('fileholder.remove') }}";

</script>

<script>
    function fileHolderPreviewReInit(selector) {
        var inputField = document.querySelector(selector);
        fileHolderAfterLoad.previewReInit(inputField);
    }
</script>
<!-- main -->
<script src="{{ asset('public/frontend/js/main.js') }}"></script>


