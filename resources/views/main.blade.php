@extends("layouts.basic2")
@section('header')
    @include("header")
@endsection
@section('content')
    <div id="content">
        <div><img src="/images/for.png" /></div>
        <div><img src="/images/eldo.png" style="width:30%" /></div>
        <div>
            <ul>
                <li>
                    <div>서버정보</div>
                    <div>3차 오픈일 : 2022-02-20</div>
                    <div>경험치 배율 : 1000배</div>
                    <div>드랍 배율 : 10배</div>
                    <div>아데나 배율 : 80배</div>
                </li>
                <li>
                    @엘도서버
                    <br />
                    1. 장비 패키지, 측근 비리 절대 없음 <br />
                    2. 서버내 모든 장비는 아덴으로 구매 가능 <br />
                    4. 반하자 지향 놀자 서버  <br />
                    3. 인형 또는 엘릭서는 사냥터에서 드랍으로 습득가능 <br />
                    5. 최소 3개월 운영으로 융통성있게 배율 조절<br />
                    6. 남은 유저분들 의견에 따라 시즌 종료 <br />
                    7. 꾸준한 업데이트와 밸런스 조절 <br />

                </li>
            </ul>
        </div>
    </div>

@endsection