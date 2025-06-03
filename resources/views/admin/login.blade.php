<div>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.signIn') }}">
        @csrf
        <div>
            <label for="login">Login:</label>
            <input type="text" name="login" required>
        </div>

        <div">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>
</div>
