<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    html, body, table{
        direction: rtl;
        font-family: Tahoma;
        text-align: right;
        line-height: 15px;
    }
</style>
<table>
    <tr>
        <td>Row</td>
        <td>Card-No</td>
        <td>Name</td>
        <td>Father</td>
        <td>Code Melli</td>
        <td>Birth Date</td>
        <td>Card Issue</td>
    </tr>
    <?php $r = 1; ?>
    @foreach(\App\Providers\EhdaServiceProvider::getExcelExport() as $row)
        @if($row->user->birth_date != '0000-00-00')
            <tr>
                <td>{{ $r++ }}</td>
                <td>{{ $row->user->card_no }}&nbsp;</td>
                <td>{{ $row->user->full_name }}</td>
                <td>{{ $row->user->name_father }}</td>
                <td>{{ $row->user->code_melli }}&nbsp;</td>
                <td>{{ $row->user->birth_date_on_card_en }}</td>
                <td>{{ $row->user->register_date_on_card_en }}</td>
            </tr>
        @endif
    @endforeach
</table>