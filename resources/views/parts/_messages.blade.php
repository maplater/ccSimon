<!-- Page Content -->


                <!-- First Blog Post -->


@foreach($data['messages'] as $message)

                <div class="well">
                    <p>{!! $message->body !!}</p>
                </div>
                <div class="text-right">
                    <p class="lead">
                        by {{$message->name}} - {{$message->email}}
                    </p>
                    <p>Posted on {{$message->date_sent}}</p>
                </div>
                <hr>

@endforeach