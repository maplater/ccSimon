<!-- Side Widget Well -->
                <div    >
                    <h2 class="page-header">People on this thread:</h2>
                        @foreach($data['users'] as $user)
                            {{$user->name}} - {{$user->email}}
                            <br/>

                        @endforeach

                    <h2 class="page-header">Filters:</h2>
                        @foreach($data['attachments_types'] as $type)
                            {{$type}}

                            <br/>

                        @endforeach
                    </div>