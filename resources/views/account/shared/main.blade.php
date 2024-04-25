@include('account.shared.header',['data'=>$data])
<main id="col-main">
    @yield('content')
</main>
@include('account.shared.footer',['data'=>$data])
