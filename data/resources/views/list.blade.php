@extends('layouts.app')

@section('content')
    <h1>총개수: {{ number_format($houses->total()) }}</h1>
    <form action="{{ route('house.create') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="start_date" value="{{ app('request')->input('start_date') }}">
        <input type="hidden" name="end_date" value="{{ app('request')->input('end_date') }}">
        <button>생성</button>
    </form>
    <table>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($houses as $house)
            <tr>
                <td>{{ $house->hidx }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </table>
    {{ $houses->appends(['start_date' => app('request')->input('start_date'),'end_date' => app('request')->input('end_date')])->links() }}
@endsection