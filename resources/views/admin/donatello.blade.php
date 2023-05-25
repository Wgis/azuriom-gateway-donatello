<div class="row g-3">
    <div class="mb-3 col-md-6">
        <label class="form-label" for="keyInput">{{ trans('donatello::messages.login') }}</label>
        <input type="text" class="form-control @error('login') is-invalid @enderror" id="keyInput" name="login" value="{{ old('login', $gateway->data['login'] ?? '') }}" required>

        @error('login')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label class="form-label" for="keyInput">{{ trans('donatello::messages.auth-key') }}</label>
        <input type="text" class="form-control @error('auth-key') is-invalid @enderror" id="keyInput" name="auth-key" value="{{ old('auth-key', $gateway->data['auth-key'] ?? '') }}" required>

        @error('auth-key')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="alert alert-info">
    <p>
        <i class="bi bi-info-circle"></i>
        @lang('donatello::messages.setup', [
            'notification' => '<code>'.route('shop.payments.notification', 'donatello').'</code>',
        ])
    </p>

    <a class="btn btn-primary mb-3" target="_blank" href="https://donatello.to/panel/settings" role="button" >
        <i class="bi bi-box-arrow-in-right"></i> {{ trans('donatello::messages.keys') }}
    </a>
</div>
