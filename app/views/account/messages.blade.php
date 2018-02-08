@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
	@parent
	:: Account Signup
@stop

{{-- Content --}}
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Laravel 4 Chat</h1>
			<table class="table table-striped">
				<thead>

				</thead>
				@foreach( $messages as $message)
					<tr>
						<td>
							{{$message->fromUser->username}}
						</td>
						<td>
							{{$message->toUser->username}}
						</td>
						<td>
							{{$message->message}}
						</td>
						<td>
							<a data-id="{{$message->id}}" class="removeMessage" href="#" >Delete</a>
						</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>
<script type="text/javascript" src="{{ asset("js/jquery.1.9.1.js") }}"></script>
<script type="text/javascript" src="{{ asset("js/bootstrap.3.0.0.js") }}"></script>
<script type="text/javascript" src="{{ asset("js/message.js") }}"></script>
@stop