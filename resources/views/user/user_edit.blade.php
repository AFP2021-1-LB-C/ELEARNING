@include('layout.header')
@inject('logged', 'App\Http\Controllers\Controller')
{{-- Módosítás --}}
<form action="/user/edit/{{ $id }}" method="post">
    @csrf
@if ($id == $logged->auth('id') || 1 == $logged->auth('role_id'))
    <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Név</label>
        <div class="col-sm-10">
            <input type="text" name="name" class="form-control" placeholder="Név" value="{{ $name }}">
        </div>
        {!! $errors->first('name', '<small class="text-danger">A név :message</small>') !!}
    </div>

    <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Kor</label>
        <div class="col-sm-10">
            <input type="number" name="age" class="form-control" placeholder="Kor" value="{{ $age }}">
        </div>
        {!! $errors->first('age', '<small class="text-danger">A kor :message</small>') !!}
    </div>

@if (1 == $logged->auth('role_id'))
    <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Szerepkör</label>
        <div class="col-sm-10">
            <select name="role_id" class="form-select" value="{{ $role_id }}">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" <?php if ($role_id == $role->id) {    echo ' selected="selected"';} ?>>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif
    <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Felhasználónév</label>
        <div class="col-sm-10">
            <input type="text" name="username" class="form-control" placeholder="Felhasználónév" value="{{ $username }}">
        </div>
        {!! $errors->first('username', '<small class="text-danger">A felhasználónév :message</small>') !!}
    </div>

    <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">E-mail cím</label>
        <div class="col-sm-10">
            <input type="email" name="email" class="form-control" placeholder="E-mail cím" value="{{ $email }}">
        </div>
        {!! $errors->first('email', '<small class="text-danger">A e-mail cím :message</small>') !!}
    </div>

    <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Jelszó</label>
        <div class="col-sm-10">
            <input type="password" name="password" id="password" class="form-control" placeholder="Jelszó" value="">
        {!! $errors->first('password', '<small class="text-danger">A jelszó :message</small>') !!}
        </div>
        
    </div>

    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Módosítás</button>
    </div>
</form>
@else
<label>Nincs jogod szerkeszteni ezt a felhasználót!</label>
@endif
@include('layout.footer')
