@if(count($errors) > 0)
    <ul style="list-style: none" class="alert alert-danger">
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </ul>
@endif
