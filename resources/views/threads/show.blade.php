@extends('layouts.app')

@section('header')



@endsection

@section('content')

    <div class="container">

            <div class="row">

                <!-- Blog Sidebar Widgets Column -->
                <div class="col-md-4">

                    @include('parts._threadsidebar')

                </div>


                <!-- Blog Entries Column -->
                <div class="col-md-8">

                    <h1>
                        {{$thread->title}}
                        <small>Created on - {{$thread->created_at}}</small>
                    </h1>
                    <br/><br/>

                    @include('parts._messages')

                </div>

            </div>
    </div>

@endsection