@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Rooms Management</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        All Rooms
                    </h2>
                    <div class="table-header-actions">
                        <div class="search_wrap search_wrap_1">
                            <div class="search_box">
                                <input type="text" class="input" id="search" name="search" placeholder="Search....">
                                <div class="btn btn_common">
                                    <i class="search-icon material-icons">search</i>
                                </div>
                            </div>
                        </div>
                        <select class="building" id="buildingFilter">
                            <option value="" selected>All Building</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building }}">{{ $building }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="NewRoom"><i class="material-icons icon">add</i>New Room</button>
                    </div>
                </heder>
                <div class="page-info">
                    
                </div>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr class="heading">
                                <th>Building Name</th>
                                <th>Room Name</th>
                                <th>Max Seats</th>
                                <th class="pagination">
                                    <select name="paginate" id="paginate">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="1000">1000</option>
                                    </select>
                                    <span class="previous" title="Previous"><i class="material-icons">keyboard_arrow_left</i></span>
                                    <span class="next" title="Next"><i class="material-icons">keyboard_arrow_right</i></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tableData">
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
@endsection
@section('script')
<script src="{{ asset('assets/js/rooms/rooms.js') }}"></script>
@if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast("{{ session('type') }}", "{{ session('message') }}");
        });
    </script>
@endif
<script>
    const formOldInputs = @json(old());
</script>
@endsection