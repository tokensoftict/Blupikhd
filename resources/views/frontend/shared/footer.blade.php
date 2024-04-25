<footer id="footer-pro">
    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="copyright-text-pro">{{ getFrontPageSettings('copyright') }}</div>
                <ul class="social-icons-pro">
                    @if(getFrontPageSettings('facebook'))
                        <li class="facebook-color"><a href="{{ getFrontPageSettings('facebook') }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                    @endif
                    @if(getFrontPageSettings('telegram'))
                        <li class="telegram-color"><a href="{{ getFrontPageSettings('telegram') }}" target="_blank"><i class="fab fa-telegram"></i></a></li>
                    @endif
                    @if(getFrontPageSettings('instagram'))
                        <li class="instagram-color"><a href="{{ getFrontPageSettings('instagram') }}" target="_blank"><i class="fab fa-instagram"></i></a></li>
                    @endif
                    @if(getFrontPageSettings('twitter'))
                        <li class="twitter-color"><a href="{{ getFrontPageSettings('twitter') }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
                    @endif
                </ul>
            </div><!-- close .col -->

            <div class="col-8 pt-4">
                @if(getFrontPageSettings('google'))
                    <a href="{{ getFrontPageSettings('google') }}" class="btn-header-pro"><img src="{{ asset('frontend/images/google.png') }}" width="150"/> </a>
                    <span class="btn-header-pro">
                &nbsp;&nbsp;&nbsp;&nbsp;
                </span>
                @endif
                @if(getFrontPageSettings('apple'))
                    <a href="{{ getFrontPageSettings('apple') }}" class="btn-header-pro"><img src="{{ asset('frontend/images/apple.png') }}" width="130"/> </a>
                    <span class="btn-header-pro">
                &nbsp;&nbsp;&nbsp;&nbsp;
                </span>
                @endif
                @if(getFrontPageSettings('appletv'))
                    <a href="{{ getFrontPageSettings('appletv') }}" class="btn-header-pro"><img src="{{ asset('frontend/images/appletv.jpg') }}" width="45"/> </a>
                    <span class="btn-header-pro">
                &nbsp;&nbsp;&nbsp;&nbsp;
                </span>
                @endif
                @if(getFrontPageSettings('amazontv'))
                    <a href="{{ getFrontPageSettings('amazontv') }}" class="btn-header-pro"><img src="{{ asset('frontend/images/amazon.png') }}" width="45"/> </a>
                @endif

            </div><!-- close .col -->
        </div><!-- close .row -->
    </div><!-- close .container -->
</footer>

@include('frontend.shared.login')

<a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

<script src="{{ asset('frontend') }}/js/libs/jquery-3.3.1.min.js"></script><!-- jQuery -->
<script src="{{ asset('frontend') }}/js/libs/popper.min.js" defer></script><!-- Bootstrap Popper/Extras JS -->
<script src="{{ asset('frontend') }}/js/libs/bootstrap.min.js" defer></script><!-- Bootstrap Main JS -->
<!-- All JavaScript in Footer -->

<!-- Additional Plugins and JavaScript -->
<script src="{{ asset('frontend') }}/js/navigation.js" defer></script><!-- Header Navigation JS Plugin -->
<script src="{{ asset('frontend') }}/js/jquery.flexslider-min.js" defer></script><!-- FlexSlider JS Plugin -->
<script src="{{ asset('frontend') }}/js/script.js" defer></script><!-- Custom Document Ready JS -->

@stack('extra_js_scripts_files')
@yield('extra_js_scripts_files')
</body>
</html>
