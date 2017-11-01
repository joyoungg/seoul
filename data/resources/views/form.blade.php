@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1 class="text-center">서울시로 매물 전송</h1>
            <form action="{{ route('house.list') }}" method="GET">
                <div class="form-group">
                    <label for="start-date">시작일</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                        <input class="form-control" id="start-date" name="start_date"
                               value="{{ \Carbon\Carbon::now()->subDay(7)->format('Y-m-d') }}"
                               placeholder="시작일">
                    </div>
                </div>
                <div class="form-group">
                    <label for="end-date">마감일</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                        <input class="form-control" id="end-date" name="end_date"
                               value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                               placeholder="종료일">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <button class="btn-primary btn-block btn-lg">전송</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('footer')
    <script>
        $(function () {
            $('.date').datepicker({
                autoclose: true,
                language: 'ko',
                orientation: 'bottom',
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
