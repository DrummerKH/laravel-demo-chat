@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
    @parent
    :: Account Signup
@stop

{{-- Content --}}
@section('content')
        <script type="text/x-handlebars" data-template-name="index">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Laravel 4 Chat</h1>
                        <table class="table table-striped">
                            @{{#each message in model}}
                                <tr>
                                    <td @{{bind-attr class="message.user_id_class"}}>
                                        @{{message.user_name}}
                                    </td>
                                    <td>
                                        @{{message.message}}
                                    </td>
                                </tr>
                            @{{/each}}
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            @{{input type="text" value=command class="form-control"}}
                            <span class="input-group-btn">
                                <button class="btn btn-default" @{{action "send"}}>Send</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </script>
        <script type="text/javascript" src="{{ asset("js/jquery.1.9.1.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/handlebars.1.0.0.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/ember.1.1.1.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/ember.data.1.0.0.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/bootstrap.3.0.0.js") }}"></script>
        {{--<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>--}}
        <script>
            /*var conn = new ab.Session('ws://localhost:8080',
                    function() {
                        conn.subscribe('toUser{{$toUser}}', function(topic, data) {
                            // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                            console.log('New article published to category "' + topic + '" : ' + data.title);
                        });

                    },
                    function() {
                        console.warn('WebSocket connection closed');
                    },
                    {'skipSubprotocolCheck': true}
            );
//            conn.emit('Hello World!');
//            conn.send('Hello World!');s*/
            var App = Ember.Application.create();

            App.Router.map(function() {

                this.resource("index", {
                    "path" : "/"
                });

            });

            App.Message = DS.Model.extend({
                "user_id"       : DS.attr("integer"),
                "user_name"     : DS.attr("string"),
                "user_id_class" : DS.attr("string"),
                "message"       : DS.attr("string")
            });

            App.Store = DS.Store.extend({
                "adapter" : DS.FixtureAdapter.extend()
            });

            App.Message.FIXTURES = [
                //{
                //     "id"   : 1,
                //     "user" : "Chris",
                //     "text" : "Hello World."
                //},
                //{
                //     "id"   : 2,
                //     "user" : "Wayne",
                //     "text" : "Don't dig it, man."
                //},
                //{
                //     "id"   : 3,
                //     "user" : "Chris",
                //     "text" : "Meh."
                //}
            ];

            var store;

            App.IndexRoute = Ember.Route.extend({
                "init" : function() {
                    store = this.store;
                    var messagesFromDb = {{$messages}};

                    $.each(messagesFromDb, function( index, value ) {
                        store.push("message", {
                            "id"            : value.id,
                            "user_id"       : value.from_user_id,
                            "from_user_id"       : value.from_user_id,
                            "to_user_id"       : value.to_user_id,
                            "user_name"     : value.user || "User",
                            "user_id_class" : value.from_user_id,
                            "message"       : value.message
                        });
                    });
                },
                "model" : function () {
                    return store.find("message");
                }
            });

            App.IndexController = Ember.ArrayController.extend({

                "command" : null,

                "actions" : {

                    "send" : function(key) {

                        if (key && key != 13) {
                            return;
                        }

                        var command = this.get("command") || "";


                        if (command.indexOf("/name") === 0) {
                            socket.send(JSON.stringify({
                                "type" : "name",
                                "data" : command.split("/name")[1],
                                "from_user_id"       : '{{$user->id}}',
                                "to_user_id"       : '{{$toUser}}'
                            }));

                        } else {
                            socket.send(JSON.stringify({
                                "type" : "message",
                                "data" : command,
                                "from_user_id"       : '{{$user->id}}',
                                "from_name"       : '{{$user->username}}',
                                "to_user_id"       : '{{$toUser}}'
                            }));

                        }

                        this.set("command", null);
                    }

                }

            });

            App.IndexView = Ember.View.extend({

                "keyDown" : function(e) {
                    this.get("controller").send("send", e.keyCode);
                }

            });


            try {

                var id = 1;

                if (!WebSocket) {

                    console.log("no websocket support");

                } else {

                    var socket = new WebSocket("ws://127.0.0.1:7778/");
                    var id     = 1;

                    socket.addEventListener("open", function (e) {
                        socket.send(JSON.stringify({
                            "type" : "message",
                            "data" : 'ping',
                            "from_user_id"       : '{{$user->id}}',
                            "from_name"       : '{{$user->username}}',
                            "to_user_id"       : '{{$toUser}}'
                        }));
                    });

                    socket.addEventListener("error", function (e) {
                        console.log("error: ", e);
                    });

                    socket.addEventListener("message", function (e) {

                        var data = JSON.parse(e.data);

                        switch (data.message.type) {

                            case "name":

                                $(".name-" + data.user.id).html(data.user.name);

                                break;

                            case "message":

                                store.push("message", {
                                    "id"            : id++,
                                    "user_id"       : data.user.id,
                                    "from_user_id"       : 1,
                                    "to_user_id"       : 2,
                                    "user_name"     : data.user.name || "User",
                                    "user_id_class" : "name-" + data.user.id,
                                    "message"       : data.message.data
                                });

                                break;

                        }

                    });

                    // console.log("socket:", socket);

                    window.socket = socket; // debug


                }

            } catch (e) {

                console.log("exception: " + e);

            }

            $( window ).ready(function() {


            });

        </script>
@stop