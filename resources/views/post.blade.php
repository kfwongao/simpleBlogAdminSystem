@extends('layouts.blog-post')

@section('content')

    <!-- Blog Post -->

    <!-- Title -->
    <h1>{{$post->title}}</h1>

    <!-- Author -->
    <p class="lead">
        by <a href="#">{{$post->user->name}}</a>
    </p>

    <hr>

    <!-- Date/Time -->
    <p><span class="glyphicon glyphicon-time"></span> Posted {{$post->created_at->diffForHumans()}}</p>

    <hr>

    <!-- Preview Image -->
    <img class="img-responsive" src="{{$post->photo ? $post->photo->file : 'http://placehold.it/900x300'}}" alt="">

    <hr>

    <!-- Post Content -->
    <p>{{$post->body}}</p>

    <hr>

    <!-- Blog Comments -->

    @if(Auth::check())

        <!-- Comments Form -->
        <div class="well">
            <h4>Leave a Comment:</h4>

            {!! Form::open(['method'=>'POST', 'action'=>'PostCommentsController@store']) !!}

            <input type="hidden" name="post_id" value="{{$post->id}}">

            <div class="form-group">
                {!! Form::label('body', 'Body:') !!}
                {!! Form::textarea('body', null, ['class'=>'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('Submit comment', ['class'=>'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}

        </div>

    @endif
    <hr>

    <!-- Posted Comments -->

    @if(count($comments) > 0)

        @foreach($comments as $comment)
            <!-- Comment -->
            <div class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" height="64"
                         src="{{$comment->photo ? $comment->photo :'http://placehold.it/64x64'}}" alt="">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">{{$comment->author}}
                        <small>{{$comment->created_at->diffForHumans()}}</small>
                    </h4>
                    <p>{{$comment->body}}</p>

                @if(count($comment->replies) > 0)

                    @foreach($comment->replies as $reply)

                        @if($reply->is_active == 1)

                            <!-- Nested Comment -->
                                <div id="nested-comment" class="media">
                                    <a class="pull-left" href="#">
                                        <img class="media-object" height="64"
                                             src="{{$reply->photo ? $reply->photo :'http://placehold.it/64x64'}}"
                                             alt="">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">{{$reply->author}}
                                            <small>{{$reply->created_at->diffForHumans()}}</small>
                                        </h4>
                                        <p>{{$reply->body}}</p>


                                        <div class="comment-reply-container">

                                            <button class="toggle-reply btn btn-primary pull-right">Reply</button>

                                            <div class="comment-reply col-sm-6">

                                                {!! Form::open(['method'=>'POST', 'action'=>'CommentRepliesController@createReply']) !!}

                                                <input type="hidden" name="comment_id" value="{{$comment->id}}">

                                                <div class="form-group">
                                                    {!! Form::label('body', 'Body:') !!}
                                                    {!! Form::textarea('body', null, ['class'=>'form-control', 'rows' => 2]) !!}
                                                </div>

                                                <div class="form-group">
                                                    {!! Form::submit('Submit reply', ['class'=>'btn btn-primary']) !!}
                                                </div>

                                                {!! Form::close() !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                        @else

                            <h1 class="text-center">No Replies</h1>

                        @endif

                    @endforeach

                @endif
                <!-- End Nested Comment -->

                </div>

            </div>

        @endforeach


    @endif

@endsection

@section('scripts')

    <script>
        $(".comment-reply-container .toggle-reply").click(function () {
            $(this).next().slideToggle("slow");
        });
    </script>

@endsection