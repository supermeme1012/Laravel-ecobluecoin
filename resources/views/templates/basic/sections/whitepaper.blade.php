@php
$img = "629458f9e93961653889264.jpg"
@endphp

<section class="about-section pt-120 pb-120 overflow-hidden">
    <div class="container">
        <div class="row gy-4 gy-sm-5">
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="section-header mb-4">
                        <h2 class="section-header__title text--base">The WhitePaper</h2>
                        <p>The real estate marketplace business plan is to create a digital platform that
                            allows real estate buyers, sellers, and agents to interact with each other in
                            a more efficient manner. The platform will be designed to provide a seamless
                            and user-friendly experience, ensuring that customers can easily navigate through
                            the website and find what they are looking for. Our target market will be spread
                            across the United States, with a focus on urban areas and
                            densely populated cities.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-thumb rtl me-xxl-5 me-lg-4 down">
                    <img src="{{ getImage('assets/images/frontend/whtepaper/'.$img,'590x390') }}" alt="thumb">
                    <a class="video-button1" href="assets/upload/Fixire_WhitePaper_v2.2.1.pdf" download>
                        <i class="las la-download"></i>
                    </a>
                    <!-- <a href="{{ @$about->data_values->video_url }}" class="video-button"><i class="las la-download"></i></a> -->

                </div>
            </div>
        </div>
    </div>
</section>

@push('style-lib')
<link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/lib/magnific-popup.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset($activeTemplateTrue.'js/lib/magnific-popup.min.js') }}"></script>
@endpush

@push('script')
<!-- <script>
    (function($){
            "use strict";
            $(".video-button").magnificPopup({
                type: "iframe",
            });
        })(jQuery)
</script> -->
@endpush