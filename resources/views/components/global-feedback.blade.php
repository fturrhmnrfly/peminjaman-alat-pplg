<div id="global-feedback-data" hidden>
    @if (session('success'))
        <div data-swal-type="success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div data-swal-type="error">{{ session('error') }}</div>
    @endif

    @if (session('warning'))
        <div data-swal-type="warning">{{ session('warning') }}</div>
    @endif

    @if (session('status'))
        <div data-swal-type="success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div data-swal-type="error">{{ $errors->first() }}</div>
    @endif
</div>
