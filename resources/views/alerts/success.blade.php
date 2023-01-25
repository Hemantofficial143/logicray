@if(\Illuminate\Support\Facades\Session::has('success'))
    <div class="alert alert-success">
        {{ \Illuminate\Support\Facades\Session::get('success') }}
    </div>
@endif
