@extends('layouts.master')

@section('title', 'My Account')

@section('content')
    <p class="clearfix">&nbsp;</p>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-wrapper">

                @if( $currentUser !== null && $currentUser->id == $user->id )
                    <a id="btnEditAccount" class="button-edit btn btn-primary" href="{{ route('account.edit') }}"><i class="fa fa-pencil-square-o"></i></a>
                @endif
                
                <div class="row">
                    <div class="col-sm-3 col-xs-4" style="padding-right: 0px;">
                        <img src="{{ $user->profile_image or 'http://www.gravatar.com/avatar/' . md5(strtolower(trim( $user->email ))) . '?d=monsterid&s=250' }}" alt="{{ $user->name }}" style="width: 100%; max-width: 500px; padding: 3px; border: 1px solid #ddd;"/>
                    </div>
                    <div class="col-sm-9 col-xs-8">
                        <h1 style="font-weight: normal; margin-bottom: 0px; margin-top: 0px;">{{ $user->name }} <i style="font-size: 0.8em; color: gray;" class="fa {{ $user->gender ? ( $user->gender == 'male' ? 'fa-mars' : 'fa-venus') : ('') }}"></i></h1>
                        <h3 style="margin-top: 0px;">{{ $user->location->city->name or '' }}</h3>

                        <p><i>{{ $user->about_me }}</i></p>
                        <p style="font-size: 1.7em;">
                            @if($user->facebook_url)
                                <a href="{{ url('http://facebook.com/') . $user->facebook_url }}" target="_blank"><i class="fa fa-facebook-official" style="color: #45609d;"></i></a>&nbsp;
                            @endif
                            @if($user->twitter_url)
                                <a href="{{ url('http://twitter.com/') . $user->twitter_url }}" target="_blank"><i class="fa fa-twitter" style="color: #22c7ff;"></i></a>&nbsp;
                            @endif
                            @if($user->website)
                                <a href="{{ $user->website }}" target="_blank"><i class="fa fa-external-link"></i></a>
                            @endif
                        </p>
                    </div>
                </div>
                <hr />
                @if((isset($user->status->title) && !empty($user->status->title)) ||
                (isset($user->course_work) && !empty($user->course_work)) ||
                (isset($user->sponsor->title) && !empty($user->sponsor->title)))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well">
                                <div class="row">
                                    <div class="col-sm-12 mini-info">

                                        @if(isset($user->status->title) && !empty($user->status->title))
                                            <div class="mini-info-icon"><i class="fa fa-user"></i></div>
                                            <div class="mini-info-content">
                                                {{ $user->status->title }}
                                            </div>
                                        @endif
                                        <p class="clearfix"></p>
                                        @if(isset($user->course_work) && !empty($user->course_work))
                                            <div class="mini-info-icon"><i class="fa {{ $user->status->title == 'Working' ? 'fa-briefcase' : 'fa-graduation-cap' }}"></i></div>
                                            <div class="mini-info-content">
                                                {{ $user->course_work }}
                                            </div>
                                        @endif
                                        <p class="clearfix"></p>
                                        @if(isset($user->sponsor->title) && !empty($user->sponsor->title))
                                            <div class="mini-info-icon"><i class="fa fa-building"></i></div>
                                            <div class="mini-info-content">
                                                {{ $user->sponsor->title }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.map-account')
                    </div>
                </div>
                @if( $currentUser !== null && $currentUser->id == $user->id )
                    <p class="clearfix">&nbsp;</p>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Your profile</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="progress" style="height: 25px; margin-bottom: 5px;">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $progress }}%; min-width: 2em;">
                                            {{ $progress }}%
                                        </div>
                                    </div>
                                    <a href="{{ route('account.edit') }}">Complete your Profile &gt;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
