@isset($filedata['currency'])
    <table class="table col-12">
        <thead>
            <tr>
                <th>Name</th>
                <th>Unit</th>
                <th>Currency Code</th>
                <th>Country</th>
                <th>Rate</th>
                <th>Change</th>
            </tr>
        </thead>
        <tbody>
        @foreach($filedata['currency'] as $currency)
            <tr>
                <td>{{$currency['name']}}</td>
                <td>{{$currency['unit']}}</td>
                <td>{{$currency['currencycode']}}</td>
                <td>{{$currency['country']}}</td>
                <td>{{$currency['rate']}}</td>
                <td>{{$currency['change']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endisset