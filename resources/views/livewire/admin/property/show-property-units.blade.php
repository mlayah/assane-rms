<div>
    <div class="table-responsive">
        <table class="table table-borderless table-nowrap table-striped table-centered m-0">

            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th class="font-weight-bold">Title</th>
                <th class="font-weight-bold">Details</th>
                <th class="font-weight-bold">Rent</th>
                <th class="font-weight-bold">Deposit</th>
                <th class="font-weight-bold">Commission</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>

            @forelse ($units as $item)
                <tr>

                    <td>
                        {{ $loop->iteration}}
                    </td>

                    <td>
                        {{ $item->title}}
                    </td>

                    <td>
                        {{ $item->details}}
                    </td>
                    <td>
                        @setting('currency_symbol') {{ number_format($item->rent,2)}}
                    </td>
                    <td>
                        @setting('currency_symbol') {{ number_format($item->deposit,2)}}
                    </td>
                    <td>
                        {{ number_format($item->commission,1)}} %
                    </td>

                    <td>
                        @switch($item->status)
                            @case('vacant')
                            <span class="badge bg-danger text-white p-1">VACANT</span>
                            @break
                            @case('occupied')
                            <span class="badge bg-success text-white  p-1">OCCUPIED</span>
                            @break
                            @case('unavailable')
                            <span class="badge bg-secondary text-white p-1">UNAVAILABLE</span>
                            @break
                            @default

                        @endswitch

                    </td>
                </tr>
            @empty

            @endforelse


            </tbody>
        </table>
    </div>

    <div class="text-center mt-3">
        {{ $units->links() }}
    </div>
</div>
