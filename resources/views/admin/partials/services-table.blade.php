<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Icon</th>
            <th>Service</th>
            <th>Price</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($services as $service)
        <tr>
            <td>{{ $service->id }}</td>
            <td><i class="fas {{ $service->icon_fa }}"></i></td>
            <td>{{ $service->service }}</td>
            <td>{{ $service->price }}</td>
            <td>{{ $service->description }}</td>
            <td>
                <button class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#editServiceModal"
                        onclick="setEditServiceData({{ $service->id }}, '{{ $service->icon_fa }}', '{{ $service->service }}', '{{ $service->price }}', `{{ $service->description }}`)">
                    Edit
                </button>
                <button class="btn btn-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteServiceModal"
                        onclick="setDeleteServiceId({{ $service->id }})">
                    Delete
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
