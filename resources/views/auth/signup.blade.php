@include('auth.authheader')
<div class="main">
    <!-- Sign up form -->
    <section class="signup">
        <div class="container">
            <div class="signup-content">
                <div class="signup-form">
                    <h2 class="form-title">Sign up</h2>
                    <form method="POST" action="{{url('signup')}}" class="register-form" id="register-form">
                      
                        @csrf
                        <div class="form-group">
                            <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                            <input type="text" name="name" id="name" value="{{old('name')}}" placeholder="Your Name" />
                            @error('name')
                            <span>{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="zmdi zmdi-email"></i></label>
                            <input type="email" name="email" id="email" value="{{old('email')}}" placeholder="Your Email" />
                            @error('email')
                            <span>{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="password" id="pass" value="{{old('pass')}}" placeholder="Password" />
                            @error('password')
                            <span>{{$message}}</span>
                            @enderror
                        </div>
                        <!-- <div class="form-group">
                            <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                            <input type="password" name="confirm_password" id="re_pass" value="{{old('re_pas')}}" placeholder="Repeat your password" />
                            @error('confirm_password')
                            <span>{{$message}}</span>
                            @enderror
                        </div> -->
                        <div class="form-group">
                            <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                            <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in <a href="#" class="term-service">Terms of service</a></label>
                        </div>
                        <div class="form-group form-button">
                            <input type="submit" name="signup" id="signup" class="form-submit" value="Register" />
                            <a href="{{url('admin/users')}}">
                            <input type="button" class="form-submit" value="Back To Users" />
                            </a>
                        </div>
                    </form>
                </div>
                <div class="signup-image">
                    <figure><img src="{{url('assets2/images/signup-image.jpg')}}" alt="sing up image"></figure>
                </div>
            </div>
        </div>
    </section>

</div>
@include('auth.authfooter')