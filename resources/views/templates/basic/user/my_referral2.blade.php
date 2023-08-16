@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row">
        <div class="d-flex justify-content-end ms-auto mb-3 flex-wrap">
            <form action="">
                <div class="input-group">
                    <input class="form-control form--control" name="search" type="text" value="{{ request()->search }}" placeholder="@lang('Search username')">
                    <button class="input-group-text bg--base text-white">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="mb-3">
        <table class="custom--table table--responsive--xl table">
            <thead>
                <tr>
                    <th>@lang('Name')</th>
                    <th>@lang('Email')</th>
                    <th>@lang('Joined_At')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($level2_data as $user)
                    <tr>
                        <td data-label="@lang('Name')">
                            <span class="fw-bold"> {{ __($user[0]->username) }} </span>
                        </td>

                        <td data-label="@lang('Email')">
                            <span class="fw-bold"> {{ __($user[0]->email) }} </span>
                        </td>

                        <td data-label="@lang('Joined_At')">
                            {{ showDateTime($user[0]->created_at) }}<br>{{ diffForHumans($user[0]->created_at) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($level2_data)
        {{ $level2_data[0]->links() }}
    @endif
@endsection
