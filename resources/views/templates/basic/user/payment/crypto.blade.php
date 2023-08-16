@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-sm-8 col-lg-8 col-xl-6">
                <div class="card card-deposit text-center">
                    <div class="card-header card-header-bg">
                        <h3>@lang('Payment Preview')</h3>
                    </div>
                    <div class="card-body card-body-deposit text-center">
                        <h4 class="my-2"> @lang('PLEASE SEND EXACTLY') <span class="text--success"> {{ $data->amount }}</span> {{ __($data->currency) }}</h4>
                        <h5 class="mb-2">@lang('TO') <span class="text--success"> {{ $data->sendto }}</span></h5>
                        <img src="{{ $data->img }}" alt="@lang('Image')">
                        <h4 class="bold my-4 text-white">@lang('SCAN TO SEND')</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
