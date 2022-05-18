@extends("layouts.basic")
@section('content')
<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header"><h5 class="text-center font-weight-light my-4">계열사 골프장 부킹 대행 서비스</h5></div>
                            <div class="card-body">
                                <form name="form" id="form" class="form-horizontal" enctype="multipart/form-data" method="POST" action="/auth/login/check">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputEmailAddress">아이디</label>
                                        <input class="form-control" name="user_id" type="text" placeholder="아이디를 입력하세요" />
                                    </div>
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputPassword">비밀번호</label>
                                        <input class="form-control" name="user_pw" type="password" placeholder="" />
                                    </div>
                                    <div class="form-group mt-4 mb-0"><span class="btn btn-primary login-submit  btn-block">로그인</span>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a href="/auth/register">회원가입</a></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    $(function(){
        $(document).on("click", ".login-submit", function () {
            var user_id = $("input[name='user_id']").val();
            var user_pw = $("input[name='user_pw']").val();

            if (user_id == "") {
                alert("아이디를 입력해주세요");
                return false;
            }

            if (user_pw == "") {
                alert("비밀번호를 입력해주세요");
                return false;
            }

            $("#form").submit();
            return false;

        });

        $(document).on("keypress", ".form-control", function (event) {
            if(event.which == 13){
                $(".login-submit").trigger('click');
            }
        });
    })
</script>
@endsection