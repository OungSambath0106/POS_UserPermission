@extends('layouts.master')

@section('content')
    @push('style')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            .swal2-actions button {
                margin-right: 10px;
                /* Adjust the margin as needed */
            }

            .img-thumbnail {
                width: 50px !important;
                height: 50px !important;
            }
        </style>
    @endpush
    @if (session('status'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
                customClass: {
                    popup: 'swal-toast'
                }
            });
            Toast.fire({
                icon: "success",
                title: "{{ session('status') }}"
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
                customClass: {
                    popup: 'swal-toast'
                }
            });
            Toast.fire({
                icon: "error",
                title: "{{ session('error') }}"
            });
        </script>
    @endif

    <div class="list-group w-auto p-3 mt-1" style="border-radius: 10px">
        <div class="list-group-item" style="background-color: #3559E0" aria-current="true">
            <h4 style="color: #FFFFFF;" class="mt-2"><b>Users Hidden List</b></h4>
        </div>
        <div class="list-group-item">
            <div class="p-2 mt-1">

                <form role="search" action="{{ url()->current() }}" method="GET">
                    @csrf
                    <div class="input-group inline justify-content-between px-4">
                        <input type="text" class="form-control search-bar" name="search" style="border-radius: 10px; color: #3559E0;"
                            placeholder="Search for something..." aria-label="Search" />

                        <div>
                            <a href="{{ route('users.index') }}" class="btn btn-primary "
                                style="background-color: #3559E0; margin-left: 28vw;"><i class="fas fa-eye-slash"
                                    style="color: #ffffff;"></i> UnHide</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">

                <table class="table">

                    <thead class="sticky">
                        <tr>
                            <th class="px-3 py-2 col-1" scope="col"> ID </th>
                            {{-- <th class="px-3 py-2 col-2" scope="col"> Profile </th> --}}
                            <th class="px-3 py-2 col-2" scope="col"> User Name </th>
                            <th class="px-3 py-2 col-3" scope="col"> Email </th>
                            <th class="px-3 py-2 col-2" scope="col"> Role </th>
                            <th class="px-3 py-2 col-auto" scope="col"> Action </th>
                        </tr>
                    </thead>

                    <tbody class="tbody">
                        @foreach ($users as $user)
                            <tr>
                                @if ($user->ishidden != 0)
                                    <td class="px-3 py-1" scope="row"> {{ $user->id }} </td>
                                    {{-- <td style="padding-left: 23px;">
                                        <img src="{{ asset('storage/uploads/all_photo/' . $user->image) }}" width="30"
                                            height="30" class="img rounded-circle" alt="">
                                    </td> --}}
                                    <td class="px-3" scope="row"> 
                                        <img src="{{ asset('storage/uploads/all_photo/' . $user->image) }}" width="30"
                                            height="30" class="img rounded-circle" alt="">
                                        <span class="px-2">{{ $user->name }}</span> 
                                    </td>
                                    <td class="px-3 py-1" scope="row"> {{ $user->email }} </td>
                                    <td class="px-3 py-1" scope="row">
                                        @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $rolename)
                                                <label class="badge bg-primary mx-1"> {{ $rolename }}
                                                </label>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="px-3 py-1" scope="row">
                                        @can('update user')
                                            <a href="{{ url('users/' . $user->id . '/edit') }}" type="button" class="btn edit"
                                                style="background-color: #3559E0;border: none;"><i class="fas fa-edit"
                                                    style="color: #ffffff;"></i></a>
                                        @endcan
                                        @can('delete user')
                                            <a class="btn trash" href="#"
                                                onclick="event.preventDefault(); confirmDelete({{ $user->id }})"
                                                style="background-color: #FF0000; border: none;">
                                                <i class="fas fa-trash" style="color: #ffffff;"></i>
                                            </a>
                                        @endcan
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <script>
        function confirmDelete(userId) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You want to delete this record!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        title: "Deleted!",
                        text: "User has been Deleted Successfully.",
                        icon: "success",
                        showConfirmButton: true
                    }).then(() => {
                        // Redirect to the delete URL if confirmed
                        window.location.href = "{{ url('users') }}/" + userId + "/delete";
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Do nothing if cancelled
                    swalWithBootstrapButtons.fire("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
        }
    </script>

@endsection
